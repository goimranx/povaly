@php
    EcommerceHelper::registerThemeAssets();
    $version = EcommerceHelper::getAssetVersion();
    
    // Use consistent version for all assets
    Theme::asset()->add('pv-gallery-css', 'vendor/core/plugins/ecommerce/css/pv-gallery.css', version: $version);
    Theme::asset()->container('footer')->add('pv-gallery-js', 'vendor/core/plugins/ecommerce/js/pv-gallery.js', ['jquery'], version: $version);
    
    // Keep lightgallery for zoom functionality
    Theme::asset()->add('lightgallery-css', 'vendor/core/plugins/ecommerce/libraries/lightgallery/css/lightgallery.min.css', version: $version);
    Theme::asset()->container('footer')->add('lightgallery-js', 'vendor/core/plugins/ecommerce/libraries/lightgallery/js/lightgallery.min.js', ['jquery'], version: $version);

    $galleryStyle = theme_option('ecommerce_product_gallery_image_style') ?: ($galleryStyle ?? 'vertical');
    
    // ── v2.10.15: Build ONLY selected variation's images for initial render ──────────────────────
    $allImages = collect();  // Images to render in thumbnails (selected variation only)
    $allProductImages = collect(); // ALL images for lazy-loaded lightbox
    $variationImageMap = []; // variation_id → array of indices

    // Get the configurable product
    $configurableProduct = $product;
    if ($product->is_variation || ($product->has_variation && $product->defaultVariation && $product->defaultVariation->product_id)) {
        $configurableProduct = \Botble\Ecommerce\Models\Product::query()
            ->where('id', $product->getKey())
            ->select('id', 'name', 'image', 'images', 'is_variation')
            ->first() ?? $product;
    }

    // Collect ALL product images for lightbox
    $galleryBase = !empty($configurableProduct->images) ? $configurableProduct->images : [];
    foreach ($galleryBase as $img) {
        if ($img) $allProductImages->push(['src' => $img, 'type' => 'gallery', 'variationId' => null]);
    }
    if (!empty($configurableProduct->image) && !$allProductImages->contains('src', $configurableProduct->image)) {
        $allProductImages->prepend(['src' => $configurableProduct->image, 'type' => 'featured', 'variationId' => null]);
    }

    // Load ALL variations for lightbox + mapping
    $productVariations = null;
    $selectedVariationProduct = null;
    $selectedVariationId = null;
    
    if ($product->has_variation) {
        $productVariations = \Botble\Ecommerce\Models\ProductVariation::query()
            ->where('configurable_product_id', $product->getKey())
            ->with([
                'product:id,image,images',
                'productAttributes',
                'productAttributes.productAttributeSet',
            ])
            ->select('id', 'product_id', 'configurable_product_id', 'is_default')
            ->get();

        // Collect ALL variation images for lightbox
        foreach ($productVariations as $pv) {
            if (!$pv->product) continue;
            
            $varImages = [];
            if (!empty($pv->product->image)) {
                $varImages[] = $pv->product->image;
            }
            if (!empty($pv->product->images) && is_array($pv->product->images)) {
                foreach ($pv->product->images as $vi) {
                    if ($vi && !in_array($vi, $varImages)) {
                        $varImages[] = $vi;
                    }
                }
            }
            
            // Track indices for this variation
            $varIndices = [];
            foreach ($varImages as $img) {
                if (!$allProductImages->contains('src', $img)) {
                    $idx = $allProductImages->count();
                    $allProductImages->push(['src' => $img, 'type' => 'variation', 'variationId' => $pv->product_id]);
                    $varIndices[] = $idx;
                } else {
                    $existingIndex = $allProductImages->search(fn($i) => $i['src'] === $img);
                    if ($existingIndex !== false) {
                        $varIndices[] = $existingIndex;
                    }
                }
            }
            
            // Store ALL indices for this variation (not just first)
            if (!empty($varIndices)) {
                $variationImageMap[$pv->product_id] = $varIndices;
            }
        }
    }
    
    // Determine selected variation and render ONLY its images
    $defaultJumpIndex = 0;
    $displayProduct = $product;

    if ($product->has_variation && isset($productVariation) && $productVariation) {
        $displayProduct = $productVariation;
        $selectedVariationId = $productVariation->id;
        
        // Find the variation product to get its images in correct order
        foreach ($productVariations as $pv) {
            if ($pv->product_id === $selectedVariationId) {
                $selectedVariationProduct = $pv->product;
                break;
            }
        }
        
        // Build images array from selected variation's images ONLY (in its exact order)
        if ($selectedVariationProduct) {
            // Add variation's featured image first
            if (!empty($selectedVariationProduct->image)) {
                $allImages->push([
                    'src' => $selectedVariationProduct->image,
                    'type' => 'variation',
                    'variationId' => $selectedVariationId
                ]);
            }
            
            // Add variation's gallery images in order
            if (!empty($selectedVariationProduct->images) && is_array($selectedVariationProduct->images)) {
                foreach ($selectedVariationProduct->images as $vImg) {
                    if ($vImg && !$allImages->contains('src', $vImg)) {
                        $allImages->push([
                            'src' => $vImg,
                            'type' => 'variation',
                            'variationId' => $selectedVariationId
                        ]);
                    }
                }
            }
            
            $defaultJumpIndex = 0;
        }
    } else {
        // No variation selected - show configurable product images
        $allImages = $allProductImages->values();
    }
    
    // Fallback placeholder if nothing
    if ($allImages->isEmpty()) {
        $allImages->push(['src' => RvMedia::getDefaultImage(), 'type' => 'placeholder', 'variationId' => null]);
        $allProductImages->push(['src' => RvMedia::getDefaultImage(), 'type' => 'placeholder', 'variationId' => null]);
    }
    
    // Store all product images as JSON for lazy lightbox loading
    $allProductImagesJson = json_encode($allProductImages->pluck('src')->toArray());
    $variationImageMapJson = json_encode($variationImageMap);

    // Get videos (product level, not variation-specific)
    $hasVideo = !empty($product->video) && is_array($product->video) && count(array_filter($product->video, fn($v) => !empty($v['url']))) > 0;
    $videoList = $hasVideo ? array_values(array_filter($product->video, fn($v) => !empty($v['url']))) : [];
    
    $totalImages = $allImages->count();
    $totalVideos = count($videoList);
@endphp

<div class="pv-gallery-wrapper">
    <div @class(['pv-gallery', 'pv-gallery-' . $galleryStyle]) 
         data-default-index="{{ $defaultJumpIndex }}" 
         data-variation-map="{{ $variationImageMapJson }}"
         data-all-images="{{ $allProductImagesJson }}"
         data-selected-variation-id="{{ $selectedVariationId }}">
        
        <div class="pv-gallery-main">
            {{-- Loading spinner --}}
            <div class="pv-gallery-loading" style="display:none;">
                <div class="pv-spinner"></div>
            </div>
            
            @if ($allImages->isNotEmpty())
                @php
                    $mainImageSrc = $allImages[$defaultJumpIndex]['src'];
                    $mainImageSrc = preg_replace('/-\d+x\d+(\.webp|\.jpg|\.jpeg|\.png)$/', '$1', $mainImageSrc);
                    $mainImageUrl = RvMedia::url($mainImageSrc);
                @endphp
                <img 
                    id="pv-main-image"
                    src="{{ $mainImageUrl }}" 
                    alt="{{ $product->name }}"
                    loading="eager"
                    fetchpriority="high"
                />
            @endif
            
            {{-- Action buttons overlay --}}
            <div class="pv-gallery-actions">
                <button type="button" class="pv-action-btn pv-share-btn" title="{{ __('Share') }}" data-url="{{ $product->url }}" data-title="{{ $product->name }}">
                    <i class="ph ph-export"></i>
                </button>
                
                @if (EcommerceHelper::isCompareEnabled())
                    <button type="button" class="pv-action-btn {{ EcommerceHelper::isProductInCompare($product->id) ? 'js-remove-from-compare-button active' : 'js-add-to-compare-button' }}" 
                            data-url="{{ route('public.compare.add', $product->id) }}"
                            data-remove-url="{{ route('public.compare.remove', $product->id) }}"
                            data-product-id="{{ $product->id }}"
                            title="{{ __('Compare') }}">
                        <i class="ph {{ EcommerceHelper::isProductInCompare($product->id) ? 'ph-fill' : '' }} ph-chart-bar"></i>
                    </button>
                @endif
                
                @if (EcommerceHelper::isWishlistEnabled())
                    @php
                        $inWishlist = is_added_to_wishlist($product->id);
                    @endphp
                    <button type="button" class="pv-action-btn {{ $inWishlist ? 'js-remove-from-wishlist-button active' : 'js-add-to-wishlist-button' }}" 
                            data-url="{{ $inWishlist ? route('public.wishlist.remove', $product->id) : route('public.wishlist.add', $product->id) }}"
                            data-product-id="{{ $product->id }}"
                            title="{{ $inWishlist ? __('Remove from Wishlist') : __('Add to Wishlist') }}">
                        <i class="ph {{ $inWishlist ? 'ph-fill' : '' }} ph-heart"></i>
                    </button>
                @endif
            </div>
        </div>
        
        <div class="pv-gallery-thumbnails">
            {{-- Photo thumbnails wrapper --}}
            <div class="pv-thumbs-photos">
                @foreach ($allImages as $index => $imgData)
                    @php
                        $fullSizeSrc = preg_replace('/-\d+x\d+(\.webp|\.jpg|\.jpeg|\.png)$/', '$1', $imgData['src']);
                        $fullSizeUrl = RvMedia::url($fullSizeSrc);
                    @endphp
                    <div 
                        class="pv-thumb {{ $index === $defaultJumpIndex ? 'pv-active' : '' }} {{ $index >= 3 ? 'pv-thumb-hidden' : '' }}" 
                        data-index="{{ $index }}" 
                        data-image="{{ $fullSizeUrl }}"
                        data-variation-id="{{ $imgData['variationId'] }}"
                    >
                        <img src="{{ RvMedia::getImageUrl($imgData['src'], 'thumb', false, RvMedia::url($imgData['src'])) }}" alt="{{ $product->name }}" loading="lazy" />
                        
                        {{-- Show counter on 4th item (index 3) if more exist --}}
                        @if ($index === 3 && $totalImages > 3)
                            <span class="pv-thumb-overlay">+{{ $totalImages - 3 }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
            
            {{-- Video thumbnails wrapper --}}
            <div class="pv-thumbs-videos">
                @foreach ($videoList as $vIndex => $video)
                    <div 
                        class="pv-thumb pv-thumb-video {{ $vIndex >= 2 ? 'pv-thumb-hidden' : '' }}" 
                        data-index="video-{{ $vIndex }}"
                        data-video-url="{{ $video['url'] }}"
                        data-video-provider="{{ $video['provider'] ?? 'video' }}"
                        data-video-thumbnail="{{ $video['thumbnail'] ?? '' }}"
                        @if (!empty($video['thumbnail']))
                            style="background-image:url({{ $video['thumbnail'] }});background-size:cover;background-position:center;"
                        @endif
                    >
                        <svg class="pv-play-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 4v16a1 1 0 0 0 1.524 .852l13 -8a1 1 0 0 0 0 -1.704l-13 -8a1 1 0 0 0 -1.524 .852z"/>
                        </svg>
                        
                        {{-- Show counter on 3rd video (index 2) if more exist --}}
                        @if ($vIndex === 2 && $totalVideos > 2)
                            <span class="pv-thumb-overlay">+{{ $totalVideos - 2 }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

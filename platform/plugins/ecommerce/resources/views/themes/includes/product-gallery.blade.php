@php
    EcommerceHelper::registerThemeAssets();
    $version = EcommerceHelper::getAssetVersion();
    
    // Load custom PV gallery assets with file-based cache busting
    $pvCssPath = public_path('vendor/core/plugins/ecommerce/css/pv-gallery.css');
    $pvJsPath = public_path('vendor/core/plugins/ecommerce/js/pv-gallery.js');
    $pvCssVersion = file_exists($pvCssPath) ? filemtime($pvCssPath) : $version;
    $pvJsVersion = file_exists($pvJsPath) ? filemtime($pvJsPath) : $version;
    
    Theme::asset()->add('pv-gallery-css', 'vendor/core/plugins/ecommerce/css/pv-gallery.css', version: $pvCssVersion);
    Theme::asset()->container('footer')->add('pv-gallery-js', 'vendor/core/plugins/ecommerce/js/pv-gallery.js', ['jquery'], version: $pvJsVersion);
    
    // Keep lightgallery for zoom functionality
    Theme::asset()->add('lightgallery-css', 'vendor/core/plugins/ecommerce/libraries/lightgallery/css/lightgallery.min.css', version: $version);
    Theme::asset()->container('footer')->add('lightgallery-js', 'vendor/core/plugins/ecommerce/libraries/lightgallery/js/lightgallery.min.js', ['jquery'], version: $version);

    $galleryStyle = theme_option('ecommerce_product_gallery_image_style') ?: ($galleryStyle ?? 'vertical');
    
    // ── Build the unified image list (EXACT SAME LOGIC AS QUICK-SHOP) ──────────────────────────────────
    $allImages = collect();

    // DEBUG: Log what we're starting with
    $debugInfo = [
        'product_id' => $product->id,
        'product_name' => $product->name,
        'is_variation' => $product->is_variation ?? false,
        'product_image' => $product->image ?? 'NULL',
        'product_images_count' => !empty($product->images) ? count($product->images) : 0,
        'product_images_list' => !empty($product->images) ? array_map(fn($i) => basename($i), $product->images) : [],
        'has_variation' => $product->has_variation,
    ];

    // CRITICAL FIX: Get the ORIGINAL configurable product if this is a variation
    // The controller may have modified $product with UpdateDefaultProductService
    $configurableProduct = $product;
    if ($product->is_variation || ($product->has_variation && $product->defaultVariation && $product->defaultVariation->product_id)) {
        // Load the original configurable product (without has_variation - it's computed)
        $configurableProduct = \Botble\Ecommerce\Models\Product::query()
            ->where('id', $product->getKey())
            ->select('id', 'name', 'image', 'images', 'is_variation')
            ->first() ?? $product;
        $debugInfo['loaded_configurable'] = 'YES';
        $debugInfo['configurable_images_count'] = !empty($configurableProduct->images) ? count($configurableProduct->images) : 0;
    } else {
        $debugInfo['loaded_configurable'] = 'NO - not needed';
    }

    // 1. Product gallery images (from CONFIGURABLE product, not modified $product)
    $galleryBase = !empty($configurableProduct->images) ? $configurableProduct->images : [];
    foreach ($galleryBase as $img) {
        if ($img) $allImages->push(['src' => $img, 'type' => 'gallery', 'variationId' => null]);
    }
    $debugInfo['after_gallery'] = $allImages->count();

    // 2. Featured image (CRITICAL: Use CONFIGURABLE product's featured image)
    // This is the main product's featured image
    if (!empty($configurableProduct->image) && !$allImages->contains('src', $configurableProduct->image)) {
        $allImages->prepend(['src' => $configurableProduct->image, 'type' => 'featured', 'variationId' => null]);
        $debugInfo['featured_added'] = 'YES';
    } else {
        $debugInfo['featured_added'] = 'NO - ' . (!empty($configurableProduct->image) ? 'already in gallery' : 'image is empty');
    }
    $debugInfo['after_featured'] = $allImages->count();

    // 3. All variation images (CRITICAL: Load ALL variations, not just selected one!)
    $variationImageMap = []; // product_id → first index in $allImages
    $productVariations = null;
    
    if ($product->has_variation) {
        // Load ALL variations with their products - SAME QUERY AS QUICK-SHOP
        $productVariations = \Botble\Ecommerce\Models\ProductVariation::query()
            ->where('configurable_product_id', $product->getKey())
            ->with([
                'product:id,image,images,stock_status,quantity,with_storehouse_management,allow_checkout_when_out_of_stock,price,sale_price,sale_type,start_date,end_date',
                'productAttributes',
                'productAttributes.productAttributeSet',
            ])
            ->select('id', 'product_id', 'configurable_product_id', 'is_default')
            ->get();
        $debugInfo['variations_loaded'] = $productVariations->count();
        
        foreach ($productVariations as $pv) {
            if (!$pv->product) continue;
            $varImages = [];
            
            // Variation featured image first
            if (!empty($pv->product->image)) {
                $varImages[] = $pv->product->image;
            }
            
            // Variation gallery images
            if (!empty($pv->product->images) && is_array($pv->product->images)) {
                foreach ($pv->product->images as $vi) {
                    if ($vi && !in_array($vi, $varImages)) {
                        $varImages[] = $vi;
                    }
                }
            }
            
            // Add each unique image to the collection
            foreach ($varImages as $img) {
                if (!$allImages->contains('src', $img)) {
                    $idx = $allImages->count();
                    $allImages->push(['src' => $img, 'type' => 'variation', 'variationId' => $pv->product_id]);
                    if (!isset($variationImageMap[$pv->product_id])) {
                        $variationImageMap[$pv->product_id] = $idx;
                    }
                } elseif (!isset($variationImageMap[$pv->product_id])) {
                    // Image already exists, just map this variation to it
                    $variationImageMap[$pv->product_id] = $allImages->search(fn($i) => $i['src'] === $img);
                }
            }
        }
    }
    $debugInfo['after_variations'] = $allImages->count();

    // 4. Fallback placeholder if nothing
    if ($allImages->isEmpty()) {
        $allImages->push(['src' => RvMedia::getDefaultImage(), 'type' => 'placeholder', 'variationId' => null]);
    }
    
    // DEBUG: Output to console
    $debugJson = json_encode($debugInfo);
    $allImagesList = $allImages->map(fn($i) => ['src' => basename($i['src']), 'type' => $i['type']])->toArray();
    $allImagesJson = json_encode($allImagesList);

    // ── Determine the default jump index (same logic as quick-shop) ────────────────────────────
    $defaultJumpIndex = 0;
    $displayProduct = $product;

    // Check if we have a selected variation to jump to
    if ($product->has_variation && isset($productVariation) && $productVariation) {
        $displayProduct = $productVariation;
        
        // $productVariation is the actual Product model (variation product), not ProductVariation model
        // So we use $productVariation->id to find it in the variationImageMap
        $variationProductId = $productVariation->id;
        
        // Strategy 1: use variationImageMap by product_id
        if (isset($variationImageMap[$variationProductId])) {
            $defaultJumpIndex = $variationImageMap[$variationProductId];
            $debugInfo['jump_strategy'] = '1-variationImageMap';
            $debugInfo['jump_to'] = $defaultJumpIndex;
        }
        // Strategy 2: match by image src regardless of type
        elseif (!empty($productVariation->image)) {
            $found = $allImages->search(fn($i) => $i['src'] === $productVariation->image);
            if ($found !== false) {
                $defaultJumpIndex = $found;
                $debugInfo['jump_strategy'] = '2-image-match';
                $debugInfo['jump_to'] = $defaultJumpIndex;
            }
        }
        // Strategy 3: use $productImages (controller-provided variation images)
        elseif (!empty($productImages)) {
            foreach ($productImages as $img) {
                $found = $allImages->search(fn($i) => $i['src'] === $img);
                if ($found !== false) { 
                    $defaultJumpIndex = $found; 
                    $debugInfo['jump_strategy'] = '3-productImages';
                    $debugInfo['jump_to'] = $defaultJumpIndex;
                    break; 
                }
            }
        }
    } else {
        $debugInfo['jump_strategy'] = 'none-no-variation';
    }
    
    // ── Get videos (product level, not variation-specific) ──────────────────────────────────
    $hasVideo = !empty($product->video) && is_array($product->video) && count(array_filter($product->video, fn($v) => !empty($v['url']))) > 0;
    $videoList = $hasVideo ? array_values(array_filter($product->video, fn($v) => !empty($v['url']))) : [];
    
    $totalImages = $allImages->count();
    $totalVideos = count($videoList);
@endphp

<script>
console.log('[PV Gallery Debug]', {!! $debugJson !!});
console.log('[PV Gallery] All images:', {!! $allImagesJson !!});
console.log('[PV Gallery] Total images loaded:', {{ $allImages->count() }});
</script>

<div class="pv-gallery-wrapper">
    <div @class(['pv-gallery', 'pv-gallery-' . $galleryStyle]) data-default-index="{{ $defaultJumpIndex }}" data-variation-map="{{ json_encode($variationImageMap) }}">
        <div class="pv-gallery-main">
            @if ($allImages->isNotEmpty())
                <img 
                    id="pv-main-image"
                    src="{{ RvMedia::getImageUrl($allImages[$defaultJumpIndex]['src']) }}" 
                    alt="{{ $product->name }}"
                    loading="eager"
                    fetchpriority="high"
                />
            @endif
        </div>
        
        <div class="pv-gallery-thumbnails">
            {{-- Render ALL image thumbnails (show first 3, 4th with counter) --}}
            @foreach ($allImages as $index => $imgData)
                <div 
                    class="pv-thumb {{ $index === $defaultJumpIndex ? 'pv-active' : '' }} {{ $index >= 3 ? 'pv-thumb-hidden' : '' }}" 
                    data-index="{{ $index }}" 
                    data-image="{{ RvMedia::getImageUrl($imgData['src']) }}"
                    data-variation-id="{{ $imgData['variationId'] }}"
                >
                    <img src="{{ RvMedia::getImageUrl($imgData['src'], 'thumb') }}" alt="{{ $product->name }}" loading="lazy" />
                    
                    {{-- Show counter on 4th item (index 3) if more exist --}}
                    @if ($index === 3 && $totalImages > 3)
                        <span class="pv-thumb-overlay">+{{ $totalImages - 3 }}</span>
                    @endif
                </div>
            @endforeach
            
            {{-- Render ALL video thumbnails (show first 2, 3rd with counter) --}}
            @foreach ($videoList as $vIndex => $video)
                <div 
                    class="pv-thumb pv-thumb-video {{ $vIndex >= 2 ? 'pv-thumb-hidden' : '' }}" 
                    data-index="video-{{ $vIndex }}"
                    data-video-url="{{ $video['url'] }}"
                    data-video-provider="{{ $video['provider'] ?? 'video' }}"
                >
                    @if (!empty($video['thumbnail']))
                        <img src="{{ $video['thumbnail'] }}" alt="{{ $product->name }}">
                    @else
                        <div style="width:100%;height:100%;background:#111;"></div>
                    @endif
                    
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

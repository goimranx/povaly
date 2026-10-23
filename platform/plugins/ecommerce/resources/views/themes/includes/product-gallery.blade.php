@php
    EcommerceHelper::registerThemeAssets();
    $version = EcommerceHelper::getAssetVersion();
    
    // Load custom PV gallery assets
    Theme::asset()->add('pv-gallery-css', 'vendor/core/plugins/ecommerce/css/pv-gallery.css', version: $version);
    Theme::asset()->container('footer')->add('pv-gallery-js', 'vendor/core/plugins/ecommerce/js/pv-gallery.js', ['jquery'], version: $version);
    
    // Keep lightgallery for zoom functionality
    Theme::asset()->add('lightgallery-css', 'vendor/core/plugins/ecommerce/libraries/lightgallery/css/lightgallery.min.css', version: $version);
    Theme::asset()->container('footer')->add('lightgallery-js', 'vendor/core/plugins/ecommerce/libraries/lightgallery/js/lightgallery.min.js', ['jquery'], version: $version);

    $galleryStyle = theme_option('ecommerce_product_gallery_image_style') ?: ($galleryStyle ?? 'vertical');
    
    // ── Build the unified image list (SAME AS QUICK-SHOP) ──────────────────────────────────
    $allImages = collect();

    // 1. Product gallery images
    $galleryBase = !empty($product->images) ? $product->images : [];
    foreach ($galleryBase as $img) {
        if ($img) $allImages->push(['src' => $img, 'type' => 'gallery', 'variationId' => null]);
    }

    // 2. Featured image (if not already in gallery)
    if (!empty($product->image) && !$allImages->contains('src', $product->image)) {
        $allImages->prepend(['src' => $product->image, 'type' => 'featured', 'variationId' => null]);
    }

    // 3. All variation images (featured + gallery for each variation), deduplicated
    $variationImageMap = []; // product_id → first index in $allImages
    if ($product->has_variation && isset($productVariations) && $productVariations) {
        foreach ($productVariations as $pv) {
            if (!$pv->product) continue;
            $varImages = [];
            // Featured image first
            if (!empty($pv->product->image)) $varImages[] = $pv->product->image;
            // Gallery images
            if (!empty($pv->product->images) && is_array($pv->product->images)) {
                foreach ($pv->product->images as $vi) {
                    if ($vi && !in_array($vi, $varImages)) $varImages[] = $vi;
                }
            }
            foreach ($varImages as $img) {
                if (!$allImages->contains('src', $img)) {
                    $idx = $allImages->count();
                    $allImages->push(['src' => $img, 'type' => 'variation', 'variationId' => $pv->product_id]);
                    if (!isset($variationImageMap[$pv->product_id])) {
                        $variationImageMap[$pv->product_id] = $idx;
                    }
                } elseif (!isset($variationImageMap[$pv->product_id])) {
                    $variationImageMap[$pv->product_id] = $allImages->search(fn($i) => $i['src'] === $img);
                }
            }
        }
    }

    // 4. Fallback placeholder if nothing
    if ($allImages->isEmpty()) {
        $allImages->push(['src' => RvMedia::getDefaultImage(), 'type' => 'placeholder', 'variationId' => null]);
    }

    // ── Determine the default jump index ────────────────────────────
    $defaultJumpIndex = 0;
    $displayProduct = $product;

    if ($product->has_variation && isset($productVariation) && $productVariation && $productVariation->id !== $product->id) {
        $displayProduct = $productVariation;
        // Strategy 1: use variationImageMap by product_id
        if (isset($variationImageMap[$productVariation->id])) {
            $defaultJumpIndex = $variationImageMap[$productVariation->id];
        }
        // Strategy 2: match by image src regardless of type
        elseif (!empty($productVariation->image)) {
            $found = $allImages->search(fn($i) => $i['src'] === $productVariation->image);
            if ($found !== false) $defaultJumpIndex = $found;
        }
    }
    
    // ── Get videos ──────────────────────────────────
    $hasVideo = !empty($product->video) && is_array($product->video) && count(array_filter($product->video, fn($v) => !empty($v['url']))) > 0;
    $videoList = $hasVideo ? array_values(array_filter($product->video, fn($v) => !empty($v['url']))) : [];
    
    $totalImages = $allImages->count();
    $totalVideos = count($videoList);
@endphp

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
                    @if ($index === 3 && $totalImages > 4)
                        <span class="pv-thumb-overlay">+{{ $totalImages - 4 }}</span>
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
                    @if ($vIndex === 2 && $totalVideos > 3)
                        <span class="pv-thumb-overlay">+{{ $totalVideos - 3 }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

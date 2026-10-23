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
    $videoPosition = theme_option('ecommerce_product_gallery_video_position') ?: ($videoPosition ?? 'bottom');
    
    // Collect ALL images including variations
    $allImages = collect();
    
    // 1. Product gallery images
    foreach ($productImages as $img) {
        $allImages->push(['src' => $img, 'type' => 'gallery', 'variationId' => null]);
    }
    
    // 2. Variation images if product has variations
    if ($product->variations()->exists()) {
        $variations = $product->variations()->with('product')->get();
        foreach ($variations as $variation) {
            if ($variation->product && $variation->product->image) {
                // Add variation featured image if not already in collection
                if (!$allImages->contains('src', $variation->product->image)) {
                    $allImages->push([
                        'src' => $variation->product->image,
                        'type' => 'variation',
                        'variationId' => $variation->product_id
                    ]);
                }
                
                // Add variation gallery images
                if ($variation->product->images) {
                    foreach ($variation->product->images as $vImg) {
                        if (!$allImages->contains('src', $vImg)) {
                            $allImages->push([
                                'src' => $vImg,
                                'type' => 'variation',
                                'variationId' => $variation->product_id
                            ]);
                        }
                    }
                }
            }
        }
    }
    
    // 3. Get videos
    $videos = [];
    if (!empty($product->video)) {
        $videos = is_string($product->video) ? json_decode($product->video, true) : $product->video;
        $videos = array_filter($videos ?? [], fn($v) => !empty($v['url']));
    }
    
    $totalImages = $allImages->count();
    $totalVideos = count($videos);
    $maxVisibleImages = 4;
    $maxVisibleVideos = 2;
@endphp

<div class="pv-gallery-wrapper">
    <div @class(['pv-gallery', 'pv-gallery-' . $galleryStyle])>
        <div class="pv-gallery-main">
            @if ($allImages->isNotEmpty())
                <img 
                    id="pv-main-image"
                    src="{{ RvMedia::getImageUrl($allImages->first()['src']) }}" 
                    alt="{{ $product->name }}"
                    loading="eager"
                    fetchpriority="high"
                />
            @endif
        </div>
        
        <div class="pv-gallery-thumbnails">
            {{-- Show ALL image thumbnails --}}
            @foreach ($allImages as $index => $imgData)
                <div 
                    class="pv-thumb {{ $index === 0 ? 'pv-active' : '' }}" 
                    data-index="{{ $index }}" 
                    data-image="{{ RvMedia::getImageUrl($imgData['src']) }}"
                    data-variation-id="{{ $imgData['variationId'] }}"
                >
                    {{ RvMedia::image($imgData['src'], $product->name, 'thumb') }}
                    
                    {{-- Show counter on specific thumbnails --}}
                    @if ($index === $maxVisibleImages - 1 && $totalImages > $maxVisibleImages)
                        <span class="pv-thumb-overlay">+{{ $totalImages - $maxVisibleImages }}</span>
                    @endif
                </div>
            @endforeach
            
            {{-- Divider between images and videos --}}
            @if ($totalVideos > 0)
                <div class="pv-thumb-divider"></div>
            @endif
            
            {{-- Show ALL video thumbnails --}}
            @foreach ($videos as $vIndex => $video)
                <div 
                    class="pv-thumb pv-thumb-video" 
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
                    
                    {{-- Show counter on specific video thumbnails --}}
                    @if ($vIndex === $maxVisibleVideos - 1 && $totalVideos > $maxVisibleVideos)
                        <span class="pv-thumb-overlay">+{{ $totalVideos - $maxVisibleVideos }}</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

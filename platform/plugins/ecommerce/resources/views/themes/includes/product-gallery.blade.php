@php
    EcommerceHelper::registerThemeAssets();
    $version = EcommerceHelper::getAssetVersion();
    Theme::asset()->add('lightgallery-css', 'vendor/core/plugins/ecommerce/libraries/lightgallery/css/lightgallery.min.css', version: $version);
    Theme::asset()->container('footer')->add('lightgallery-js', 'vendor/core/plugins/ecommerce/libraries/lightgallery/js/lightgallery.min.js', ['jquery'], version: $version);

    $galleryStyle = theme_option('ecommerce_product_gallery_image_style') ?: ($galleryStyle ?? 'vertical');
    $videoPosition = theme_option('ecommerce_product_gallery_video_position') ?: ($videoPosition ?? 'bottom');
    
    $imageCount = count($productImages);
    $maxVisibleImages = 4;
    $showImageCounter = $imageCount > $maxVisibleImages;
@endphp

<style>
.pv-gallery-wrapper {
    display: flex;
    gap: 10px;
}

.pv-gallery-vertical {
    display: flex;
    flex-direction: row;
    gap: 10px;
    width: 100%;
}

.pv-gallery-main {
    flex: 1;
    width: 80%;
}

.pv-gallery-main img {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 10px;
}

.pv-gallery-thumbnails {
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 50px;
    min-width: 50px;
    max-width: 50px;
}

.pv-thumb {
    position: relative;
    width: 50px;
    height: 66.67px; /* 3:4 ratio */
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.pv-thumb:hover,
.pv-thumb.active {
    border-color: var(--global-color-primary-border, #ddd);
}

.pv-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pv-thumb-video {
    display: flex;
    align-items: center;
    justify-content: center;
}

.pv-play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: rgba(255, 255, 255, 0.9);
    pointer-events: none;
    z-index: 2;
}

.pv-thumb-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(0, 0, 0, 0.75);
    color: #fff;
    font-size: 16px;
    font-weight: 700;
    z-index: 10;
    pointer-events: none;
    border-radius: 10px;
}

@media (max-width: 768px) {
    .pv-gallery-vertical {
        flex-direction: column;
    }
    
    .pv-gallery-thumbnails {
        flex-direction: row;
        width: 100%;
        max-width: 100%;
    }
}
</style>

<div class="pv-gallery-wrapper">
    <div @class(['pv-gallery', 'pv-gallery-' . $galleryStyle])>
        <div class="pv-gallery-main">
            @if (!empty($productImages))
                {{ RvMedia::image($productImages[0], $product->name, $productImageSize ?? null, attributes: ['loading' => 'eager', 'fetchpriority' => 'high', 'id' => 'pv-main-image'], lazy: false) }}
            @endif
        </div>
        <div class="pv-gallery-thumbnails">
            @foreach ($productImages as $index => $image)
                @if ($index < $maxVisibleImages)
                    <div class="pv-thumb {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}" data-image="{{ RvMedia::getImageUrl($image) }}">
                        {{ RvMedia::image($image, $product->name, 'thumb') }}
                        @if ($showImageCounter && $index === $maxVisibleImages - 1)
                            <span class="pv-thumb-overlay">+{{ $imageCount - $maxVisibleImages }}</span>
                        @endif
                    </div>
                @endif
            @endforeach
            
            @if ($videoPosition == 'bottom' && !empty($product->video))
                @php
                    $videos = is_string($product->video) ? json_decode($product->video, true) : $product->video;
                    $videos = $videos ?? [];
                    $videoCount = count($videos);
                    $maxVisibleVideos = 2;
                    $showVideoCounter = $videoCount > $maxVisibleVideos;
                @endphp
                
                @foreach ($videos as $vIndex => $video)
                    @if ($vIndex < $maxVisibleVideos)
                        <div class="pv-thumb pv-thumb-video" data-video-index="{{ $vIndex }}">
                            <img src="{{ $video['thumbnail'] ?? RvMedia::getDefaultImage() }}" alt="{{ $product->name }}">
                            <svg class="pv-play-icon" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 4v16a1 1 0 0 0 1.524 .852l13 -8a1 1 0 0 0 0 -1.704l-13 -8a1 1 0 0 0 -1.524 .852z"/>
                            </svg>
                            @if ($showVideoCounter && $vIndex === $maxVisibleVideos - 1)
                                <span class="pv-thumb-overlay">+{{ $videoCount - $maxVisibleVideos }}</span>
                            @endif
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        const gallery = document.querySelector('.pv-gallery');
        if (!gallery) return;
        
        const mainImage = document.getElementById('pv-main-image');
        const thumbnails = gallery.querySelectorAll('.pv-thumb:not(.pv-thumb-video)');
        
        console.log('✅ PV Gallery initialized');
        console.log('📸 Image thumbnails:', thumbnails.length);
        console.log('🎥 Video thumbnails:', gallery.querySelectorAll('.pv-thumb-video').length);
        
        thumbnails.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const imageUrl = this.getAttribute('data-image');
                if (imageUrl && mainImage) {
                    mainImage.src = imageUrl;
                    mainImage.srcset = '';
                    
                    thumbnails.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
    });
})();
</script>

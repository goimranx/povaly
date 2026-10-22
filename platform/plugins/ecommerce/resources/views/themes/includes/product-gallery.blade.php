@php
    EcommerceHelper::registerThemeAssets();
    $version = EcommerceHelper::getAssetVersion();
    Theme::asset()->add('lightgallery-css', 'vendor/core/plugins/ecommerce/libraries/lightgallery/css/lightgallery.min.css', version: $version);
    Theme::asset()->add('slick-css', 'vendor/core/plugins/ecommerce/libraries/slick/slick.css', version: $version);
    Theme::asset()->container('footer')->add('lightgallery-js', 'vendor/core/plugins/ecommerce/libraries/lightgallery/js/lightgallery.min.js', ['jquery'], version: $version);
    Theme::asset()->container('footer')->add('slick-js', 'vendor/core/plugins/ecommerce/libraries/slick/slick.min.js', ['jquery'], version: $version);

    $galleryStyle = theme_option('ecommerce_product_gallery_image_style') ?: ($galleryStyle ?? 'vertical');
    $videoPosition = theme_option('ecommerce_product_gallery_video_position') ?: ($videoPosition ?? 'bottom');
@endphp

<div class="bb-product-gallery-wrapper">
    <div @class(['bb-product-gallery', 'bb-product-gallery-' . $galleryStyle]) data-video-position="{{ $videoPosition }}" data-placeholder="{{ RvMedia::getDefaultImage() }}">
        <div class="bb-product-gallery-images">
            @if ($videoPosition == 'top' || ($videoPosition == 'after_first_image' && empty($productImages)))
                @include(EcommerceHelper::viewPath('includes.product-gallery-video'))
            @endif

            @foreach ($productImages as $image)
                <a href="{{ RvMedia::getImageUrl($image) }}">
                    @if ($loop->first)
                        {{-- First gallery image is the LCP element: load it eagerly at high
                             priority instead of waiting for the lazy-load script. --}}
                        {{ RvMedia::image($image, $product->name, $productImageSize ?? null, attributes: ['loading' => 'eager', 'fetchpriority' => 'high'], lazy: false) }}
                    @else
                        {{ RvMedia::image($image, $product->name, $productImageSize ?? null) }}
                    @endif
                </a>

                @if ($loop->first && $videoPosition == 'after_first_image')
                    @include(EcommerceHelper::viewPath('includes.product-gallery-video'))
                @endif

                @if ($loop->last && $videoPosition == 'before_last_image')
                    @include(EcommerceHelper::viewPath('includes.product-gallery-video'))
                @endif
            @endforeach

            @if ($videoPosition == 'bottom')
                @include(EcommerceHelper::viewPath('includes.product-gallery-video'))
            @endif
        </div>
        <div class="bb-product-gallery-thumbnails" data-vertical="{{ $galleryStyle === 'vertical' ? 1 : 0 }}">
            @if ($videoPosition == 'top')
                @include(EcommerceHelper::viewPath('includes.product-gallery-video-thumbnail'))
            @endif

            @foreach ($productImages as $image)
                <div>
                    {{ RvMedia::image($image, $product->name, 'thumb') }}
                </div>

                @if ($loop->first && $videoPosition == 'after_first_image')
                    <div>
                        @include(EcommerceHelper::viewPath('includes.product-gallery-video-thumbnail'))
                    </div>
                @endif

                @if ($loop->last && $videoPosition == 'before_last_image')
                    <div>
                        @include(EcommerceHelper::viewPath('includes.product-gallery-video-thumbnail'))
                    </div>
                @endif
            @endforeach

            @if ($videoPosition == 'bottom')
                @include(EcommerceHelper::viewPath('includes.product-gallery-video-thumbnail'))
            @endif
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    
    // Gallery thumbnail counter - show "+X" for hidden items
    function initGalleryCounter() {
        const thumbnails = document.querySelector('.bb-product-gallery-thumbnails');
        if (!thumbnails) return;
        
        // Wait for slick to initialize
        setTimeout(function() {
            const allSlides = thumbnails.querySelectorAll('.slick-slide:not(.slick-cloned)');
            const imageSlides = Array.from(allSlides).filter(slide => !slide.classList.contains('video-thumbnail'));
            const videoSlides = Array.from(allSlides).filter(slide => slide.classList.contains('video-thumbnail'));
            
            // Handle image thumbnails (show 6, 7th shows +X)
            if (imageSlides.length > 6) {
                const moreCount = imageSlides.length - 6;
                const seventhSlide = imageSlides[6];
                if (seventhSlide) {
                    seventhSlide.setAttribute('data-more-count', '+' + moreCount);
                    seventhSlide.style.display = 'block';
                }
            }
            
            // Handle video thumbnails (show 2, 3rd shows +X)
            if (videoSlides.length > 2) {
                const moreCount = videoSlides.length - 2;
                const thirdVideo = videoSlides[2];
                if (thirdVideo) {
                    thirdVideo.setAttribute('data-more-count', '+' + moreCount);
                    thirdVideo.style.display = 'block';
                }
            }
        }, 500);
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGalleryCounter);
    } else {
        initGalleryCounter();
    }
})();
</script>

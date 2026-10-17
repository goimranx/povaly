<div class="ps-deal-of-day">
    <div class="ps-container">
        <div class="ps-section__content">
            <div class="row">
                {{-- Left Side: Deal of the Day --}}
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                    <div class="ps-block--deal-of-day">
                        <div class="ps-block__header">
                            <h3>{{ $deal_title }}</h3>
                            @if($deal_products && $deal_products->isNotEmpty() && $deal_limit > 1)
                                <div class="ps-block__nav">
                                    <button type="button" class="ps-carousel-nav-btn ps-carousel-prev" data-target=".ps-carousel--deal">
                                        <i class="icon-chevron-left"></i>
                                    </button>
                                    <button type="button" class="ps-carousel-nav-btn ps-carousel-next" data-target=".ps-carousel--deal">
                                        <i class="icon-chevron-right"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="ps-block__content">
                            @if($deal_products && $deal_products->isNotEmpty())
                                @if($deal_limit == 1)
                                    {{-- Single Product Display --}}
                                    @foreach($deal_products as $product)
                                        @include('theme.martfury::partials.product-card-enhanced', [
                                            'product' => $product,
                                            'layout' => 'deal'
                                        ])
                                    @endforeach
                                @else
                                    {{-- Multiple Products Carousel --}}
                                    <div class="ps-carousel--deal owl-slider"
                                         data-owl-auto="false"
                                         data-owl-loop="true"
                                         data-owl-speed="5000"
                                         data-owl-gap="0"
                                         data-owl-nav="false"
                                         data-owl-dots="false"
                                         data-owl-item="1"
                                         data-owl-item-xs="1"
                                         data-owl-item-sm="2"
                                         data-owl-item-md="1"
                                         data-owl-item-lg="1"
                                         data-owl-item-xl="1"
                                         data-owl-duration="1000"
                                         data-owl-mousedrag="on"
                                    >
                                        @foreach($deal_products as $product)
                                            @include('theme.martfury::partials.product-card-enhanced', [
                                                'product' => $product,
                                                'layout' => 'deal'
                                            ])
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <p class="text-center text-muted">{{ __('No products available') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Right Side: Offers --}}
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                    <div class="ps-block--offers">
                        <div class="ps-block__header">
                            <h3>{{ $offers_title }}</h3>
                            @if($offers_link_url && $offers_link_url !== '#')
                                <a href="{{ $offers_link_url }}" class="ps-block__more">{{ $offers_link_text }}</a>
                            @endif
                        </div>
                        <div class="ps-block__content">
                            @if($offers_products && $offers_products->isNotEmpty())
                                <div class="ps-carousel--offers owl-slider"
                                     data-owl-auto="false"
                                     data-owl-loop="true"
                                     data-owl-speed="10000"
                                     data-owl-gap="30"
                                     data-owl-nav="true"
                                     data-owl-dots="false"
                                     data-owl-item="4"
                                     data-owl-item-xs="1"
                                     data-owl-item-sm="2"
                                     data-owl-item-md="2"
                                     data-owl-item-lg="3"
                                     data-owl-item-xl="4"
                                     data-owl-duration="1000"
                                     data-owl-mousedrag="on"
                                >
                                    @foreach($offers_products as $product)
                                        @include('theme.martfury::partials.product-card-enhanced', [
                                            'product' => $product,
                                            'layout' => 'offer'
                                        ])
                                    @endforeach
                                </div>
                            @else
                                <p class="text-center text-muted">{{ __('No offers available') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Deal and Offers Section */
    .ps-deal-of-day {
        padding: 0 !important;
        margin-bottom: 15px;
        background: var(--global-color-site-bg);
    }

    /* Left Side - Deal of the Day */
    .ps-block--deal-of-day {
        border: 1px solid var(--global-color-primary-border);
        border-radius: 4px;
        padding: 20px;
        height: 100%;
    }

    .ps-block--deal-of-day .ps-block__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--global-color-border);
    }

    .ps-block--deal-of-day .ps-block__header h3 {
        font-size: 1.6rem;
        font-weight: 600;
        color: var(--global-color-text-main);
        margin: 0;
        padding: 0;
    }

    .ps-block--deal-of-day .ps-block__header .ps-block__nav {
        display: flex;
        gap: 5px;
    }

    .ps-block--deal-of-day .ps-carousel-nav-btn {
        background: var(--global-color-site-bg) !important;
        color: var(--global-color-text-body) !important;
        width: 24px;
        height: 24px;
        border-radius: 3px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--global-color-primary-border) !important;
        transition: all 0.3s ease;
        cursor: pointer;
        padding: 0;
    }

    .ps-block--deal-of-day .ps-carousel-nav-btn:hover {
        color: var(--global-color-text-main) !important;
        border-color: var(--global-color-text-body) !important;
    }

    .ps-block--deal-of-day .ps-carousel-nav-btn.disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    .ps-block--deal-of-day .ps-block__content {
        padding: 0;
        margin: 0;
    }

    /* Hide Owl Carousel's default navigation */
    .ps-carousel--deal .owl-nav {
        display: none !important;
    }

    /* Override ps-product hover border */
    .ps-block--deal-of-day .ps-product,
    .ps-block--deal-of-day .ps-product:hover,
    .ps-block--deal-of-day .ps-product:focus,
    .ps-block--deal-of-day .ps-product:active,
    .ps-block--deal-of-day .ps-product--deal,
    .ps-block--deal-of-day .ps-product--deal:hover,
    .ps-block--deal-of-day .ps-product--deal:focus,
    .ps-block--deal-of-day .ps-product--deal:active {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }

    /* Right Side - Offers */
    .ps-block--offers {
        border: 1px solid var(--global-color-primary-border);
        border-radius: 4px;
        padding: 20px;
        height: 100%;
    }

    .ps-block--offers .ps-block__header {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 20px;
        padding: 0;
        border-bottom: 1px solid var(--global-color-border);
        flex-wrap: nowrap;
        gap: 15px;
    }

    .ps-block--offers .ps-block__header h3 {
        font-size: 1.65rem;
        font-weight: 600;
        color: var(--global-color-text-main);
        margin: 0;
        flex-shrink: 0;
    }

    .ps-block--offers .ps-block__more {
        font-size: 14px;
        color: var(--global-color-link);
        text-decoration: none;
        transition: color 0.3s ease;
        flex-shrink: 0;
        margin-right: auto;
    }

    .ps-block--offers .ps-block__more:hover {
        color: var(--global-color-link-hover);
        text-decoration: underline;
    }

    .ps-block--offers .ps-block__content {
        padding: 0;
        margin: 0;
    }

    /* Offers Carousel - Navigation arrows at edges of carousel */
    .ps-carousel--offers {
        position: relative;
    }

    .ps-carousel--offers .owl-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: -20px;
        right: -20px;
        pointer-events: none;
        z-index: 10;
        height: 100px;
    }

    .ps-carousel--offers .owl-nav button {
        position: absolute;
        top: 0;
        bottom: 0;
        background: var(--global-color-site-bg) !important;
        color: var(--global-color-text-body) !important;
        width: 35px;
        height: 100px;
        padding: 0;
        border-radius: 0;
        font-size: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        pointer-events: auto;
        border: 2px solid var(--global-color-primary-border) !important;
    }

    .ps-carousel--offers .owl-nav button.owl-prev {
        left: 0;
        border-left: none !important;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    .ps-carousel--offers .owl-nav button.owl-next {
        right: 0;
        border-right: none !important;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
    }

    .ps-carousel--offers .owl-nav button:hover {
        background: var(--global-color-site-bg) !important;
        color: var(--global-color-text-main) !important;
    }

    .ps-carousel--offers .owl-nav button.disabled {
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Responsive Design - Below 768px convert to grid (aligns with Owl's sm breakpoint) */
    @media (max-width: 767px) {
        /* Remove all padding/margin from parent containers on mobile */
        .ps-deal-of-day {
            padding: 0 !important;
            margin-bottom: 0 !important;
        }

        .ps-deal-of-day .ps-container {
            padding: 0 !important;
        }

        .ps-deal-of-day .ps-section__content {
            padding: 0 !important;
        }

        .ps-deal-of-day .row {
            margin: 0 !important;
        }

        .ps-deal-of-day .col-xl-3,
        .ps-deal-of-day .col-xl-9 {
            padding: 0 !important;
            margin-bottom: 15px !important;
        }

        /* Convert carousels to grid layout on mobile */
        .ps-carousel--offers.owl-slider,
        .ps-carousel--deal.owl-slider {
            display: grid !important;
            width: 100% !important;
            overflow: visible !important;
        }

        /* Remove border and padding from both blocks on mobile */
        .ps-deal-of-day .ps-block--offers,
        .ps-deal-of-day .ps-block--deal-of-day,
        .ps-block--offers,
        .ps-block--deal-of-day {
            border: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }

        .ps-deal-of-day .ps-block--offers .ps-block__header,
        .ps-deal-of-day .ps-block--deal-of-day .ps-block__header,
        .ps-block--offers .ps-block__header,
        .ps-block--deal-of-day .ps-block__header {
            padding: 0 10px 10px 10px !important;
            margin-bottom: 0 !important;
            border-bottom: none !important;
        }

        .ps-deal-of-day .ps-block--offers .ps-block__content,
        .ps-deal-of-day .ps-block--deal-of-day .ps-block__content,
        .ps-block--offers .ps-block__content,
        .ps-block--deal-of-day .ps-block__content {
            padding: 0 !important;
        }

        /* Hide carousel navigation on mobile */
        .ps-carousel--offers .owl-nav,
        .ps-carousel--deal .owl-nav,
        .deal-carousel-hover-nav .owl-nav,
        .ps-block--deal-of-day .ps-block__nav {
            display: none !important;
        }

        /* Deal products grid - 1 column on mobile */
        .ps-carousel--deal.owl-slider {
            grid-template-columns: repeat(1, 1fr) !important;
            gap: 10px;
            padding: 0;
            margin: 0;
        }

        .ps-carousel--deal .owl-stage-outer,
        .ps-carousel--deal .owl-stage {
            display: contents !important;
            transform: none !important;
            overflow: visible !important;
        }

        .ps-carousel--deal .owl-item {
            position: static !important;
            width: auto !important;
            display: block !important;
            padding: 0 !important;
            margin: 0 !important;
            transform: none !important;
        }

        .ps-carousel--deal .owl-item.cloned {
            display: none !important;
        }

        /* Offers products grid - 1 column on mobile */
        .ps-carousel--offers.owl-slider {
            grid-template-columns: repeat(1, 1fr) !important;
            gap: 10px;
            padding: 0;
            margin: 0;
        }

        .ps-carousel--offers .owl-stage-outer,
        .ps-carousel--offers .owl-stage {
            display: contents !important;
            transform: none !important;
            overflow: visible !important;
        }

        .ps-carousel--offers .owl-item {
            position: static !important;
            width: auto !important;
            display: block !important;
            padding: 0 !important;
            margin: 0 !important;
            transform: none !important;
        }

        .ps-carousel--offers .owl-item.cloned {
            display: none !important;
        }

        /* Adjust product items for mobile grid */
        .ps-product--offer,
        .ps-product--deal {
            width: 100% !important;
            height: auto !important;
            margin: 0 !important;
        }

        /* Remove padding and borders from blocks on mobile */
        .ps-block--deal-of-day,
        .ps-block--offers {
            padding: 0 !important;
            margin-bottom: 0 !important;
            border: none !important;
            border-radius: 0 !important;
        }

        .ps-block--deal-of-day .ps-block__header,
        .ps-block--offers .ps-block__header {
            padding: 0 10px 10px 10px !important;
            margin-bottom: 0 !important;
            border-bottom: none !important;
        }

        .ps-block--deal-of-day .ps-block__content,
        .ps-block--offers .ps-block__content {
            padding: 0 !important;
        }

        /* Stack deal and offers vertically */
        .ps-deal-of-day .row > div {
            margin-bottom: 15px;
        }

        .ps-deal-of-day .row > div:last-child {
            margin-bottom: 0;
        }
    }

    /* Very small mobile screens - still 2 columns but more compact */
    @media (max-width: 480px) {
        .ps-carousel--deal.owl-slider,
        .ps-carousel--offers.owl-slider {
            gap: 10px;
        }
        
        .ps-block--offers .ps-block__header,
        .ps-block--deal-of-day .ps-block__header {
            padding: 0 10px 10px 10px !important;
            margin-bottom: 0 !important;
        }

        .ps-block--deal-of-day .ps-block__header h3,
        .ps-block--offers .ps-block__header h3 {
            font-size: 1.55rem;
        }
    }

    @media (max-width: 767px) {
        .ps-deal-of-day {
            margin-bottom: 0 !important;
        }

        .ps-block--offers .ps-block__nav {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<script>
(function() {
    'use strict';

    function initDealOffersCarousel() {
        var $ = window.jQuery;
        if (!$) return;

        setTimeout(function() {
            var $carousel = $('.ps-carousel--deal');
            if ($carousel.length && $carousel.hasClass('owl-loaded')) {
                $('.ps-carousel-prev').off('click.deal').on('click.deal', function(e) {
                    e.preventDefault();
                    $carousel.trigger('prev.owl.carousel');
                });
                $('.ps-carousel-next').off('click.deal').on('click.deal', function(e) {
                    e.preventDefault();
                    $carousel.trigger('next.owl.carousel');
                });
            }
        }, 1000);
    }

    // Safe init - works on normal page load AND after lazy loading injection
    if (window.jQuery) {
        window.jQuery(document).ready(function() { initDealOffersCarousel(); });
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            if (window.jQuery) {
                window.jQuery(document).ready(function() { initDealOffersCarousel(); });
            }
        });
    }

    // Also re-init when shortcode is lazy loaded
    document.addEventListener('shortcode.loaded', function(e) {
        if (e.detail && e.detail.name === 'deal-and-offers') {
            initDealOffersCarousel();
        }
    });
})();
</script>

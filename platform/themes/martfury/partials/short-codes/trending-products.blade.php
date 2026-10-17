<div class="ps-trending-products">
    <div class="ps-container">
        <div class="ps-block--trending">
            <div class="ps-block__header">
                <h3>{!! BaseHelper::clean($title) !!}</h3>
                <a href="{{ route('public.products') }}" class="ps-block__more" title="{{ __('View all products') }}">{{ __('View All') }}</a>
            </div>
            <div class="ps-block__content">
                <div class="ps-carousel--trending owl-slider"
                     data-owl-auto="true"
                     data-owl-loop="false"
                     data-owl-speed="10000"
                     data-owl-gap="10"
                     data-owl-nav="true"
                     data-owl-dots="true"
                     data-owl-item="6"
                     data-owl-item-xs="1"
                     data-owl-item-sm="2"
                     data-owl-item-md="3"
                     data-owl-item-lg="4"
                     data-owl-item-xl="6"
                     data-owl-duration="1000"
                     data-owl-mousedrag="on"
                >
                    @foreach($products as $product)
                        <div class="ps-product">
                            @include('theme.martfury::partials.product-card-enhanced', [
                                'product' => $product,
                                'layout' => 'flyout'
                            ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Trending Products Section */
    .ps-trending-products {
        padding: 0 !important;
        margin: 10px 0 !important;
        background: var(--global-color-site-bg);
    }

    /* Trending Block */
    .ps-block--trending {
        border: none;
        border-radius: 0;
        padding: 0;
        height: 100%;
    }

    .ps-block--trending .ps-block__header {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 0;
        padding: 0 0 10px 0;
        flex-wrap: nowrap;
        gap: 15px;
    }

    .ps-block--trending .ps-block__header h3 {
        font-size: 1.65rem;
        font-weight: 600;
        color: var(--global-color-text-main);
        margin: 0;
        flex-shrink: 0;
    }

    .ps-block--trending .ps-block__more {
        font-size: 14px;
        color: var(--global-color-link);
        text-decoration: none;
        transition: color 0.3s ease;
        flex-shrink: 0;
        margin-right: auto;
    }

    .ps-block--trending .ps-block__more:hover {
        color: var(--global-color-link-hover);
        text-decoration: underline;
    }

    .ps-block--trending .ps-block__content {
        padding: 0;
        margin: 0;
        position: relative;
    }

    /* Remove hover border on carousel items */
    .ps-block--trending .ps-product {
        padding: 0;
        margin: 0;
        border: none !important;
    }

    .ps-block--trending .ps-product:hover {
        border: none !important;
    }

    /* Navigation Arrows - Desktop only (≥768px) */
    @media (min-width: 768px) {
        /* Add padding to carousel wrapper to leave space for arrows */
        .ps-carousel--trending {
            padding: 0 50px;
        }

        .ps-carousel--trending .owl-nav {
            display: block !important;
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            pointer-events: none;
            z-index: 10;
        }

        .ps-carousel--trending .owl-nav button {
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid var(--global-color-border-strong) !important;
            background: transparent !important;
            color: var(--global-color-text-main) !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            pointer-events: auto;
            transition: all 0.3s ease;
            font-size: 16px;
            margin: 0 !important;
            padding: 0 !important;
        }

        .ps-carousel--trending .owl-nav button:hover {
            background: var(--global-color-card-bg) !important;
            border-color: var(--global-color-border-strong) !important;
        }

        .ps-carousel--trending .owl-nav .owl-prev {
            left: 0;
        }

        .ps-carousel--trending .owl-nav .owl-next {
            right: 0;
        }

        /* Hide dots on desktop */
        .ps-carousel--trending .owl-dots {
            display: none !important;
        }
    }

    /* Navigation Dots - Mobile only (<768px) */
    @media (max-width: 767px) {
        /* Hide arrows on mobile */
        .ps-carousel--trending .owl-nav {
            display: none !important;
        }

        /* Show and style dots */
        .ps-carousel--trending .owl-dots {
            display: flex !important;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 15px;
            padding: 0;
        }

        .ps-carousel--trending .owl-dots .owl-dot {
            width: 8px !important;
            height: 8px !important;
            border-radius: 50% !important;
            background: var(--global-color-border-strong) !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
            transition: all 0.3s ease !important;
            flex-shrink: 0 !important;
        }

        .ps-carousel--trending .owl-dots .owl-dot span {
            display: none !important;
        }

        .ps-carousel--trending .owl-dots .owl-dot.active {
            background: var(--global-color-primary) !important;
            width: 24px !important;
            border-radius: 4px !important;
        }

        .ps-trending-products .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--trending {
            padding: 0 !important;
            margin-bottom: 0 !important;
        }

        .ps-block--trending .ps-block__header {
            padding: 0 10px 10px 10px !important;
            margin-bottom: 0 !important;
        }

        .ps-block--trending .ps-block__content {
            padding: 0 !important;
        }
    }
</style>

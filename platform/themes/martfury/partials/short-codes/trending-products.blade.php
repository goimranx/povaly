<div class="ps-trending-products">
    <div class="ps-container">
        <div class="ps-block--trending">
            <div class="ps-block__header">
                <h3>{!! BaseHelper::clean($title) !!}</h3>
                <a href="{{ route('public.products') }}" class="ps-block__more" title="{{ __('View all products') }}">{{ __('View All') }}</a>
            </div>
            <div class="ps-block__content">
                <div class="ps-carousel--responsive owl-slider"
                     data-owl-auto="true"
                     data-owl-loop="false"
                     data-owl-speed="10000"
                     data-owl-gap="10"
                     data-owl-nav="false"
                     data-owl-dots="true"
                     data-owl-item="7"
                     data-owl-item-xs="2"
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
    /* Trending Products Section - Matches Featured Products Design */
    .ps-trending-products {
        padding: 0 !important;
        margin: 10px 0 !important;
        background: var(--global-color-site-bg);
    }

    /* Trending Block - Same as Featured Products */
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
    }

    /* Carousel item spacing */
    .ps-block--trending .ps-product {
        padding: 0;
        margin: 0;
    }

    /* Mobile (≤768px) */
    @media (max-width: 768px) {
        .ps-trending-products .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--trending {
            padding: 0 !important;
            margin-bottom: 0 !important;
            border: none !important;
            border-radius: 0 !important;
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

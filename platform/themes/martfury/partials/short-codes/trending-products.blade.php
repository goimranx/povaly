<div class="ps-trending-products">
    <div class="ps-container">
        <div class="ps-block--trending">
            <div class="ps-block__header">
                <h3>{!! BaseHelper::clean($title) !!}</h3>
                <a href="{{ route('public.products') }}" class="ps-block__more" title="{{ __('View all products') }}">{{ __('View All') }}</a>
            </div>
            <div class="ps-block__content">
                <div class="ps-grid--trending">
                    @foreach($products as $product)
                        @include('theme.martfury::partials.product-card-enhanced', [
                            'product' => $product,
                            'layout' => 'flyout'
                        ])
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

    /* Grid Layout - Desktop: 6 items, follows current carousel breakpoints */
    .ps-grid--trending {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }

    /* Responsive Grid - Match carousel item counts */
    @media (max-width: 1680px) {
        .ps-grid--trending {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    @media (max-width: 1366px) {
        .ps-grid--trending {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    @media (max-width: 1199px) {
        .ps-grid--trending {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 991px) {
        .ps-grid--trending {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .ps-grid--trending {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {
        .ps-grid--trending {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Mobile (≤768px): Match deal-and-offers mobile pattern */
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
            padding: 0 0 10px 0 !important;
            margin-bottom: 0 !important;
        }

        .ps-block--trending .ps-block__content {
            padding: 0 !important;
        }

        .ps-grid--trending {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 10px !important;
        }
    }

    /* Mobile (≤480px): Single column + header gets horizontal padding */
    @media (max-width: 480px) {
        .ps-trending-products .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--trending .ps-block__header {
            padding: 0 10px 10px 10px !important;
        }

        .ps-grid--trending {
            grid-template-columns: repeat(1, 1fr) !important;
        }
    }
</style>

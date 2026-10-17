<div class="ps-featured-products">
    <div class="ps-container">
        <div class="ps-block--featured">
            <div class="ps-block__header">
                <h3>{!! BaseHelper::clean($title) !!}</h3>
                <a href="{{ route('public.products') }}" class="ps-block__more" title="{{ __('View all products') }}">{{ __('View All') }}</a>
            </div>
            <div class="ps-block__content">
                <div class="ps-grid--featured">
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
    /* Featured Products Section - Matches Offers Container Design */
    .ps-featured-products {
        padding: 0 !important;
        margin: 10px 0 !important;
        background: var(--global-color-site-bg);
    }

    /* Featured Block - Same as Offers but without border */
    .ps-block--featured {
        border: none;
        border-radius: 0;
        padding: 0;
        height: 100%;
    }

    .ps-block--featured .ps-block__header {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 0;
        padding: 0 0 10px 0;
        flex-wrap: nowrap;
        gap: 15px;
    }

    .ps-block--featured .ps-block__header h3 {
        font-size: 1.65rem;
        font-weight: 600;
        color: var(--global-color-text-main);
        margin: 0;
        flex-shrink: 0;
    }

    .ps-block--featured .ps-block__more {
        font-size: 14px;
        color: var(--global-color-link);
        text-decoration: none;
        transition: color 0.3s ease;
        flex-shrink: 0;
        margin-right: auto;
    }

    .ps-block--featured .ps-block__more:hover {
        color: var(--global-color-link-hover);
        text-decoration: underline;
    }

    .ps-block--featured .ps-block__content {
        padding: 0;
        margin: 0;
    }

    /* Grid Layout - Desktop: 6 items, follows current carousel breakpoints */
    .ps-grid--featured {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }

    /* Responsive Grid - Match carousel item counts */
    @media (max-width: 1680px) {
        .ps-grid--featured {
            grid-template-columns: repeat(6, 1fr);
        }
    }

    @media (max-width: 1366px) {
        .ps-grid--featured {
            grid-template-columns: repeat(5, 1fr);
        }
    }

    @media (max-width: 1199px) {
        .ps-grid--featured {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 991px) {
        .ps-grid--featured {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .ps-grid--featured {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 575px) {
        .ps-grid--featured {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Mobile (≤768px): Match deal-and-offers mobile pattern */
    @media (max-width: 768px) {
        .ps-featured-products .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--featured {
            padding: 0 !important;
            margin-bottom: 0 !important;
            border: none !important;
            border-radius: 0 !important;
        }

        .ps-block--featured .ps-block__header {
            padding: 0 0 10px 0 !important;
            margin-bottom: 0 !important;
        }

        .ps-block--featured .ps-block__content {
            padding: 0 !important;
        }

        .ps-grid--featured {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: 10px !important;
        }
    }

    /* Mobile (≤480px): Single column + header gets horizontal padding */
    @media (max-width: 480px) {
        .ps-featured-products .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--featured .ps-block__header {
            padding: 0 10px 10px 10px !important;
        }

        .ps-grid--featured {
            grid-template-columns: repeat(1, 1fr) !important;
        }
    }

    /* Tablet (481-767px): Add ps-container padding */
    @media (min-width: 481px) and (max-width: 767px) {
        .ps-featured-products .ps-container {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
    }
</style>

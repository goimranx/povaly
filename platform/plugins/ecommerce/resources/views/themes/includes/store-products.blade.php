@if(isset($product) && $product->store_id)
    @php
        // Get products from the same store, sorted by rating
        $storeProducts = \Botble\Ecommerce\Models\Product::query()
            ->where('store_id', $product->store_id)
            ->where('id', '!=', $product->id)
            ->where('status', \Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
            ->withAvg('reviews', 'star')
            ->orderByDesc('reviews_avg_star')
            ->orderByDesc('views')
            ->limit(12)
            ->get();
        
        $hasStoreProducts = $storeProducts->isNotEmpty();
        
        // If no store products, get similar products from the same category with highest ratings
        if (!$hasStoreProducts) {
            $storeProducts = \Botble\Ecommerce\Models\Product::query()
                ->whereHas('categories', function($query) use ($product) {
                    $query->whereIn('ec_product_categories.id', $product->categories->pluck('id'));
                })
                ->where('id', '!=', $product->id)
                ->where('status', \Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
                ->withAvg('reviews', 'star')
                ->orderByDesc('reviews_avg_star')
                ->orderByDesc('views')
                ->limit(12)
                ->get();
        }
    @endphp

    @if($storeProducts->isNotEmpty())
        <div class="ps-store-products">
            <div class="ps-container">
                <div class="ps-block--store-products">
                    <div class="ps-store-products__header">
                        <h2>
                            @if($hasStoreProducts)
                                {{ __('Top products from :store', ['store' => $product->store->name]) }}
                            @else
                                {{ __('Similar Products You Might Like') }}
                            @endif
                        </h2>
                        @if($hasStoreProducts)
                            <a href="{{ $product->store->url }}" class="ps-store-products__more" title="{{ __('View all products') }}">{{ __('View All') }}</a>
                        @else
                            @php
                                // Build smart search query URL for fallback products
                                // Use only first 2 main category names to avoid too specific search
                                $mainCategories = $product->categories
                                    ->filter(function($category) {
                                        // Filter out promotional/special categories
                                        $excludeNames = ['deal of the day', 'winter collection', 'summer collection', 'sale', 'hot deals', 'new arrivals', 'featured', 'trending'];
                                        return !in_array(strtolower($category->name), $excludeNames);
                                    })
                                    ->take(2)
                                    ->pluck('name')
                                    ->toArray();
                                
                                // Fallback to product name if no suitable categories
                                if (empty($mainCategories)) {
                                    $searchQuery = $product->name;
                                } else {
                                    $searchQuery = implode(' ', $mainCategories);
                                }
                                
                                $viewAllUrl = route('public.products') . '?q=' . urlencode($searchQuery);
                            @endphp
                            <a href="{{ $viewAllUrl }}" class="ps-store-products__more" title="{{ __('View all products') }}">{{ __('View All') }}</a>
                        @endif
                    </div>
                    <div class="ps-block__content">
                        <div class="ps-carousel--store-products owl-slider"
                             data-owl-auto="true"
                             data-owl-loop="false"
                             data-owl-speed="10000"
                             data-owl-gap="10"
                             data-owl-nav="true"
                             data-owl-dots="true"
                             data-owl-item="6"
                             data-owl-item-xs="1"
                             data-owl-item-sm="3"
                             data-owl-item-md="3"
                             data-owl-item-lg="4"
                             data-owl-item-xl="6"
                             data-owl-duration="1000"
                             data-owl-mousedrag="on"
                             data-owl-smartspeed="300"
                             data-owl-freeDrag="false"
                        >
                            @foreach($storeProducts as $storeProduct)
                                <div class="ps-product">
                                    @include('theme.martfury::partials.product-card-enhanced', [
                                        'product' => $storeProduct,
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
            /* Store Products Section */
            .ps-store-products {
                margin: 0;
                background: var(--global-color-site-bg);
            }

            /* Remove padding from container */
            .ps-store-products .ps-container {
                padding-left: 0;
                padding-right: 0;
                border-bottom: 1px solid var(--global-color-border-strong, #333);
                padding-bottom: 15px;
                margin-bottom: 15px;
            }

            /* Add padding to content when no carousel (all items visible) */
            .ps-block--store-products .ps-block__content:has(.owl-nav .owl-prev.disabled):has(.owl-nav .owl-next.disabled) {
                padding-left: 15px;
                padding-right: 15px;
            }

            /* Store Products Block */
            .ps-block--store-products {
                border: none;
                border-radius: 0;
                padding: 0;
                height: 100%;
            }

            /* Store Products Header */
            .ps-store-products__header {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                margin-bottom: 0;
                padding: 0 0 10px 0;
                flex-wrap: nowrap;
                gap: 15px;
            }

            .ps-store-products__header h2 {
                font-size: 22px;
                font-weight: 700;
                color: var(--global-color-text-main);
                line-height: 1.3;
                margin: 0;
                flex-shrink: 0;
            }

            @media (max-width: 767px) {
                .ps-store-products__header h2 {
                    font-size: 20px;
                }
            }

            .ps-store-products__more {
                font-size: 14px;
                color: var(--global-color-link);
                text-decoration: none;
                transition: color 0.3s ease;
                flex-shrink: 0;
                margin-right: auto;
            }

            .ps-store-products__more:hover {
                color: var(--global-color-link-hover);
                text-decoration: underline;
            }

            /* Mobile Header Layout */
            @media (max-width: 480px) {
                .ps-store-products__header {
                    flex-wrap: wrap;
                    gap: 5px;
                    padding-left: 0;
                }

                .ps-store-products__header h2 {
                    width: 100%;
                }

                .ps-store-products__more {
                    margin-left: auto;
                    margin-right: 0;
                }
            }

            .ps-block--store-products .ps-block__content {
                margin: 0;
                position: relative;
            }

            /* Remove hover border on carousel items */
            .ps-block--store-products .ps-product {
                padding: 0;
                margin: 0;
                border: none !important;
            }

            .ps-block--store-products .ps-product:hover {
                border: none !important;
            }

            /* Navigation Arrows - Desktop only (≥768px) */
            @media (min-width: 768px) {
<<<<<<< HEAD
                /* Default: NO padding */
=======
                /* Default: NO padding (for when all items fit or only 1 item) */
>>>>>>> feature/product
                .ps-carousel--store-products {
                    padding: 0;
                }
                
<<<<<<< HEAD
                /* Add padding ONLY when NOT single item carousel */
                .ps-carousel--store-products:not(.single-item-carousel) {
=======
                /* Add padding ONLY when carousel is initialized and navigation exists */
                .ps-carousel--store-products.owl-carousel:not(.owl-loaded) {
                    padding: 0;
                }
                
                .ps-carousel--store-products.owl-carousel.owl-drag {
>>>>>>> feature/product
                    padding: 0 70px;
                }
                
                /* Force center single items */
                .ps-carousel--store-products.single-item-carousel .owl-stage {
                    display: flex !important;
                    justify-content: center !important;
                }
                
                .ps-carousel--store-products.single-item-carousel .owl-item {
                    width: auto !important;
                    max-width: 200px !important;
                }

                .ps-carousel--store-products .owl-nav {
                    display: block !important;
                    position: absolute;
                    top: 50%;
                    left: 0;
                    right: 0;
                    transform: translateY(-50%);
                    pointer-events: none;
                    z-index: 10;
                }
                
                /* Hide navigation for single item */
                .ps-carousel--store-products.single-item-carousel .owl-nav {
                    display: none !important;
                }

                .ps-carousel--store-products .owl-nav button {
                    position: absolute;
                    width: 40px;
                    height: 40px;
                    border-radius: 6px;
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

                .ps-carousel--store-products .owl-nav button:hover {
                    background: var(--global-color-card-bg) !important;
                    border-color: var(--global-color-border-strong) !important;
                }

                .ps-carousel--store-products .owl-nav button.disabled {
                    opacity: 0.3 !important;
                    cursor: not-allowed !important;
                    pointer-events: auto !important;
                }

                .ps-carousel--store-products .owl-nav button.disabled:hover {
                    background: transparent !important;
                    border-color: var(--global-color-border-strong) !important;
                }

                /* Hide navigation when both arrows are disabled (all items visible) */
                .ps-carousel--store-products .owl-nav:has(.owl-prev.disabled):has(.owl-next.disabled) {
                    display: none !important;
                }

                /* Remove padding when navigation is hidden */
                .ps-carousel--store-products:has(.owl-nav .owl-prev.disabled):has(.owl-nav .owl-next.disabled) {
                    padding: 0 !important;
                }

                .ps-carousel--store-products .owl-nav .owl-prev {
                    left: 0;
                }

                .ps-carousel--store-products .owl-nav .owl-next {
                    right: 0;
                }

                /* Hide dots on desktop */
                .ps-carousel--store-products .owl-dots {
                    display: none !important;
                }
            }

            /* Navigation Dots - Mobile only (<768px) */
            @media (max-width: 767px) {
                /* Hide arrows on mobile */
                .ps-carousel--store-products .owl-nav {
                    display: none !important;
                }

                /* Show and style dots */
                .ps-carousel--store-products .owl-dots {
                    display: flex !important;
                    justify-content: center;
                    align-items: center;
                    gap: 6px;
                    margin-top: 15px;
                    padding: 0;
                }

                .ps-carousel--store-products .owl-dots .owl-dot {
                    width: 6px !important;
                    height: 6px !important;
                    min-width: 6px !important;
                    min-height: 6px !important;
                    border-radius: 50% !important;
                    background: var(--global-color-border-strong) !important;
                    border: none !important;
                    padding: 0 !important;
                    margin: 0 !important;
                    transition: all 0.3s ease !important;
                    flex-shrink: 0 !important;
                }

                .ps-carousel--store-products .owl-dots .owl-dot span {
                    display: none !important;
                }

                .ps-carousel--store-products .owl-dots .owl-dot.active {
                    background: var(--global-color-primary) !important;
                    width: 20px !important;
                    min-width: 20px !important;
                    height: 6px !important;
                    min-height: 6px !important;
                    border-radius: 3px !important;
                }

                .ps-store-products .ps-container {
                    padding-left: 0;
                    padding-right: 0;
                }

                .ps-block--store-products {
                    padding: 0;
                    margin-bottom: 0;
                }

                .ps-store-products__header {
                    padding-bottom: 10px;
                    margin-bottom: 0;
                }

                .ps-block--store-products .ps-block__content {
                    padding: 0;
                }
            }

            /* Tablet (481-767px): Header bottom padding only */
            @media (min-width: 481px) and (max-width: 767px) {
                .ps-store-products__header {
                    padding: 0 0 10px 0;
                }
            }

            /* Mobile (≤480px): Header bottom padding */
            @media (max-width: 480px) {
                .ps-store-products__header {
                    padding-bottom: 10px;
                }
            }

            /* Hide specific elements for clean product cards in store products section */
            .ps-block--store-products .ps-product {
                /* Make card content flexbox so order works for price positioning */
                .ec-card__content {
                    display: flex !important;
                    flex-direction: column !important;
                }

                /* Hide flyout action button */
                .ec-card__flyout-actions {
                    display: none !important;
                }

                /* Hide float toggle (wishlist/compare buttons) */
                .ec-card__float-toggle {
                    display: none !important;
                }

                /* Hide price row (original price with strikethrough) */
                .ec-card__price-row {
                    display: none !important;
                }

                /* Hide sale duration */
                .ec-card__sale-duration {
                    display: none !important;
                }

                /* Hide seller info */
                .ec-card__seller {
                    display: none !important;
                }

                /* Hide delivery info */
                .ec-card__delivery {
                    display: none !important;
                }

                /* Hide actions (mobile add to cart buttons) */
                .ec-card__actions {
                    display: none !important;
                }

                /* Product title styling - link color and 5 lines */
                .ec-card__title {
                    color: var(--global-color-link) !important;
                    text-decoration: none !important;
                    display: -webkit-box !important;
                    -webkit-box-orient: vertical !important;
                    -webkit-line-clamp: 5 !important;
                    overflow: hidden !important;
                    margin-bottom: 0 !important;
                }

                .ec-card__title:hover {
                    color: var(--global-color-link) !important;
                    text-decoration: underline !important;
                }

                /* Price styling - keep at end and 1.3rem size */
                .ec-card__price-sale {
                    order: 999 !important; /* Keep at the end */
                    margin-bottom: 0 !important;
                }

                .ec-card__price-amount {
                    font-size: 1.3rem !important;
                }
            }

            /* Override mobile viewport global styles to keep actions hidden */
            @media (max-width: 480px) {
                .ps-block--store-products .ps-product {
                    .ec-card--flyout .ec-card__actions,
                    .ec-card--inline .ec-card__actions {
                        display: none !important;
                    }
                }
            }
        </style>
    @endif
@endif

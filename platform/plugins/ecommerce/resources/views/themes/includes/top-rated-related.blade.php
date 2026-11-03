@if(isset($product))
    @php
        // Smart search-like query: Extract keywords from current product
        // Split product name, tags, and categories into individual words for matching
        $productName = $product->name;
        $productTags = $product->tags->pluck('name')->toArray();
        $categoryNames = $product->categories->pluck('name')->toArray();
        
        // Combine all keywords
        $allKeywords = array_merge(
            [$productName],
            $productTags,
            $categoryNames
        );
        
        // Split into individual words and filter (min 3 characters, like search)
        $searchWords = [];
        foreach ($allKeywords as $text) {
            $words = preg_split('/\s+/', trim($text), -1, PREG_SPLIT_NO_EMPTY);
            foreach ($words as $word) {
                if (strlen($word) >= 3) {
                    $searchWords[] = strtolower($word);
                }
            }
        }
        $searchWords = array_unique($searchWords);
        
        // Build query to find products matching ANY of these words
        $topRatedProducts = \Botble\Ecommerce\Models\Product::query()
            ->where('id', '!=', $product->id)
            ->where('status', \Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
            ->withAvg('reviews', 'star')
            ->withCount('reviews')
            ->where(function ($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    // Search in product name (highest priority)
                    $query->orWhere('ec_products.name', 'LIKE', "%{$word}%");
                    
                    // Search in description (medium priority)
                    $query->orWhere('ec_products.description', 'LIKE', "%{$word}%");
                    
                    // Search in tags
                    $query->orWhereHas('tags', function($tagQuery) use ($word) {
                        $tagQuery->where('name', 'LIKE', "%{$word}%");
                    });
                    
                    // Search in categories  
                    $query->orWhereHas('categories', function($catQuery) use ($word) {
                        $catQuery->where('name', 'LIKE', "%{$word}%");
                    });
                }
            })
            // Filter: Only 4+ star products with at least 1 review
            ->having('reviews_avg_star', '>=', 4.0)
            ->having('reviews_count', '>=', 1)
            ->limit(12)
            ->get();
        
        // Calculate match score in PHP and sort
        $topRatedProducts = $topRatedProducts->map(function($product) use ($searchWords) {
            $matchScore = 0;
            foreach ($searchWords as $word) {
                // Name matches (3 points each)
                if (stripos($product->name, $word) !== false) {
                    $matchScore += 3;
                }
                // Description matches (1 point each)
                if (stripos($product->description, $word) !== false) {
                    $matchScore += 1;
                }
            }
            $product->match_score = $matchScore;
            return $product;
        })->sortByDesc('match_score')
          ->sortByDesc('reviews_avg_star')
          ->sortByDesc('reviews_count')
          ->take(12)
          ->values();
    @endphp

    @if($topRatedProducts->isNotEmpty())
        <div class="ps-top-rated-related">
            <div class="ps-container">
                <div class="ps-block--top-rated-related">
                    <div class="ps-top-rated-related__header">
                        <h2>{{ __('Top Rated Related Items') }}</h2>
                        @php
                            // Build search query from product categories
                            $mainCategories = $product->categories
                                ->filter(function($category) {
                                    // Filter out promotional/special categories
                                    $excludeNames = ['deal of the day', 'winter collection', 'summer collection', 'sale', 'hot deals', 'new arrivals', 'featured', 'trending'];
                                    return !in_array(strtolower($category->name), $excludeNames);
                                })
                                ->take(2)
                                ->pluck('name')
                                ->toArray();
                            $searchQuery = !empty($mainCategories) ? implode(' ', $mainCategories) : $product->name;
                            $viewAllUrl = route('public.products') . '?q=' . urlencode($searchQuery) . '&sort_by=reviews_avg_star-desc';
                        @endphp
                        <a href="{{ $viewAllUrl }}" class="ps-top-rated-related__more" title="{{ __('View all top rated products') }}">{{ __('View All') }}</a>
                    </div>
                    <div class="ps-block__content">
                        <div class="ps-carousel--top-rated-related owl-slider"
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
                            @foreach($topRatedProducts as $ratedProduct)
                                <div class="ps-product">
                                    @include('theme.martfury::partials.product-card-enhanced', [
                                        'product' => $ratedProduct,
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
            /* Top Rated Related Section - Same styles as store products */
            .ps-top-rated-related {
                margin: 0;
                background: var(--global-color-site-bg);
            }

            /* Remove padding from container */
            .ps-top-rated-related .ps-container {
                padding-left: 0;
                padding-right: 0;
                border-bottom: 1px solid var(--global-color-border-strong, #333);
                padding-bottom: 15px;
                margin-bottom: 15px;
            }

            /* Add padding to content when no carousel (all items visible) */
            .ps-block--top-rated-related .ps-block__content:has(.owl-nav .owl-prev.disabled):has(.owl-nav .owl-next.disabled) {
                padding-left: 15px;
                padding-right: 15px;
            }

            /* Top Rated Related Block */
            .ps-block--top-rated-related {
                border: none;
                border-radius: 0;
                padding: 0;
                height: 100%;
            }

            /* Top Rated Related Header */
            .ps-top-rated-related__header {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                margin-bottom: 0;
                padding: 0 0 10px 0;
                flex-wrap: nowrap;
                gap: 15px;
            }

            .ps-top-rated-related__header h2 {
                font-size: 22px;
                font-weight: 700;
                color: var(--global-color-text-main);
                line-height: 1.3;
                margin: 0;
                flex-shrink: 0;
            }

            @media (max-width: 767px) {
                .ps-top-rated-related__header {
                    flex-wrap: wrap;
                }

                .ps-top-rated-related__header h2 {
                    font-size: 20px;
                }
            }

            .ps-top-rated-related__more {
                font-size: 14px;
                color: var(--global-color-link);
                text-decoration: none;
                transition: color 0.3s ease;
                flex-shrink: 0;
                margin-right: auto;
            }

            .ps-top-rated-related__more:hover {
                color: var(--global-color-link-hover);
                text-decoration: underline;
            }

            /* Mobile Header Layout */
            @media (max-width: 480px) {
                .ps-top-rated-related__header {
                    gap: 5px;
                    padding-left: 0;
                }

                .ps-top-rated-related__header h2 {
                    width: 100%;
                }

                .ps-top-rated-related__more {
                    margin-left: auto;
                    margin-right: 0;
                }
            }

            .ps-block--top-rated-related .ps-block__content {
                margin: 0;
                position: relative;
            }

            /* Remove hover border on carousel items */
            .ps-block--top-rated-related .ps-product {
                padding: 0;
                margin: 0;
                border: none !important;
            }

            .ps-block--top-rated-related .ps-product:hover {
                border: none !important;
            }

            /* Navigation Arrows - Desktop only (≥768px) */
            @media (min-width: 768px) {
                /* Default: NO padding */
                .ps-carousel--top-rated-related {
                    padding: 0;
                }
                
                /* Add padding ONLY when NOT single item carousel */
                .ps-carousel--top-rated-related:not(.single-item-carousel) {
                    padding: 0 70px;
                }
                
                /* Fix single item to take natural width, not full width */
                .ps-carousel--top-rated-related.single-item-carousel .owl-stage-outer {
                    width: auto !important;
                }
                
                .ps-carousel--top-rated-related.single-item-carousel .owl-stage {
                    width: auto !important;
                    transform: none !important;
                }
                
                .ps-carousel--top-rated-related.single-item-carousel .owl-item {
                    width: 200px !important;
                }

                .ps-carousel--top-rated-related .owl-nav {
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
                .ps-carousel--top-rated-related.single-item-carousel .owl-nav {
                    display: none !important;
                }

                .ps-carousel--top-rated-related .owl-nav button {
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

                .ps-carousel--top-rated-related .owl-nav button:hover {
                    background: var(--global-color-card-bg) !important;
                    border-color: var(--global-color-border-strong) !important;
                }

                .ps-carousel--top-rated-related .owl-nav button.disabled {
                    opacity: 0.3 !important;
                    cursor: not-allowed !important;
                    pointer-events: auto !important;
                }

                .ps-carousel--top-rated-related .owl-nav button.disabled:hover {
                    background: transparent !important;
                    border-color: var(--global-color-border-strong) !important;
                }

                /* Hide navigation when both arrows are disabled (all items visible) */
                .ps-carousel--top-rated-related .owl-nav:has(.owl-prev.disabled):has(.owl-next.disabled) {
                    display: none !important;
                }

                /* Remove padding when navigation is hidden */
                .ps-carousel--top-rated-related:has(.owl-nav .owl-prev.disabled):has(.owl-nav .owl-next.disabled) {
                    padding: 0 !important;
                }

                .ps-carousel--top-rated-related .owl-nav .owl-prev {
                    left: 0;
                }

                .ps-carousel--top-rated-related .owl-nav .owl-next {
                    right: 0;
                }

                /* Hide dots on desktop */
                .ps-carousel--top-rated-related .owl-dots {
                    display: none !important;
                }
            }

            /* Navigation Dots - Mobile only (<768px) */
            @media (max-width: 767px) {
                /* Hide arrows on mobile */
                .ps-carousel--top-rated-related .owl-nav {
                    display: none !important;
                }

                /* Show and style dots */
                .ps-carousel--top-rated-related .owl-dots {
                    display: flex !important;
                    justify-content: center;
                    align-items: center;
                    gap: 6px;
                    margin-top: 15px;
                    padding: 0;
                }

                .ps-carousel--top-rated-related .owl-dots .owl-dot {
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

                .ps-carousel--top-rated-related .owl-dots .owl-dot span {
                    display: none !important;
                }

                .ps-carousel--top-rated-related .owl-dots .owl-dot.active {
                    background: var(--global-color-primary) !important;
                    width: 20px !important;
                    min-width: 20px !important;
                    height: 6px !important;
                    min-height: 6px !important;
                    border-radius: 3px !important;
                }

                .ps-top-rated-related .ps-container {
                    padding-left: 0;
                    padding-right: 0;
                }

                .ps-block--top-rated-related {
                    padding: 0;
                    margin-bottom: 0;
                }

                .ps-top-rated-related__header {
                    padding-bottom: 10px;
                    margin-bottom: 0;
                }

                .ps-block--top-rated-related .ps-block__content {
                    padding: 0;
                }
            }

            /* Tablet (481-767px): Header bottom padding only */
            @media (min-width: 481px) and (max-width: 767px) {
                .ps-top-rated-related__header {
                    padding: 0 0 10px 0;
                }
            }

            /* Mobile (≤480px): Header bottom padding */
            @media (max-width: 480px) {
                .ps-top-rated-related__header {
                    padding-bottom: 10px;
                }
            }

            /* Hide specific elements for clean product cards in top rated section */
            .ps-block--top-rated-related .ps-product {
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
                .ps-block--top-rated-related .ps-product {
                    .ec-card--flyout .ec-card__actions,
                    .ec-card--inline .ec-card__actions {
                        display: none !important;
                    }
                }
            }
        </style>
    @endif
@endif

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
        
        // Build query to find NEW ARRIVAL products matching ANY of these words
        // Consider products created in the last 60 days as "new arrivals"
        $newArrivalDate = now()->subDays(60);
        
        $newArrivalProducts = \Botble\Ecommerce\Models\Product::query()
            ->where('id', '!=', $product->id)
            ->where('status', \Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
            ->where('created_at', '>=', $newArrivalDate) // NEW ARRIVALS FILTER
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
            ->orderBy('created_at', 'desc') // Newest first
            ->limit(12)
            ->get();
        
        // Calculate match score in PHP and sort by relevance, then by date
        $newArrivalProducts = $newArrivalProducts->map(function($product) use ($searchWords) {
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
          ->sortByDesc('created_at') // Then by newest
          ->take(12)
          ->values();
    @endphp

    @if($newArrivalProducts->isNotEmpty())
        <div class="ps-new-arrival-related">
            <div class="ps-container">
                <div class="ps-block--new-arrival-related">
                    <div class="ps-new-arrival-related__header">
                        <h2>{{ __('Similar items on new arrival') }}</h2>
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
                            $viewAllUrl = route('public.products') . '?q=' . urlencode($searchQuery) . '&sort_by=created_at-desc';
                        @endphp
                        <a href="{{ $viewAllUrl }}" class="ps-new-arrival-related__more" title="{{ __('View all new arrival products') }}">{{ __('View All') }}</a>
                    </div>
                    <div class="ps-block__content">
                        <div class="ps-carousel--new-arrival-related owl-slider"
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
                            @foreach($newArrivalProducts as $arrivalProduct)
                                <div class="ps-product">
                                    @include('theme.martfury::partials.product-card-enhanced', [
                                        'product' => $arrivalProduct,
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
            /* New Arrival Related Section - Same styles as top rated */
            .ps-new-arrival-related {
                margin: 0;
                background: var(--global-color-site-bg);
            }

            /* Remove padding from container */
            .ps-new-arrival-related .ps-container {
                padding-left: 0;
                padding-right: 0;
                border-bottom: 1px solid var(--global-color-border-strong, #333);
                padding-bottom: 15px;
                margin-bottom: 15px;
            }

            /* Add padding to content when no carousel (all items visible) */
            .ps-block--new-arrival-related .ps-block__content:has(.owl-nav .owl-prev.disabled):has(.owl-nav .owl-next.disabled) {
                padding-left: 15px;
                padding-right: 15px;
            }

            /* New Arrival Related Block */
            .ps-block--new-arrival-related {
                border: none;
                border-radius: 0;
                margin-bottom: 0;
                padding-top: 0;
            }

            /* New Arrival Related Header */
            .ps-new-arrival-related__header {
                display: flex;
                align-items: center;
                justify-content: start;
                padding: 20px 15px 15px 15px;
                border-top: 1px solid var(--global-color-border-strong);
                margin-bottom: 0;
                flex-wrap: wrap;
            }

            @media (max-width: 767px) {
                .ps-new-arrival-related__header {
                    padding: 15px 15px 10px 15px;
                    flex-wrap: wrap;
                }

                .ps-new-arrival-related__header h2 {
                    font-size: 20px;
                    word-break: break-word;
                    margin-bottom: 0;
                }
            }

            .ps-new-arrival-related__header h2 {
                font-size: 22px;
                font-weight: 700;
                margin: 0;
                color: var(--global-color-text-main);
                flex-grow: 0;
                line-height: 1.3;
            }

            .ps-new-arrival-related__more {
                font-size: 14px;
                color: var(--global-color-link);
                text-decoration: none;
                white-space: nowrap;
                margin-left: 15px;
                flex-shrink: 0;
            }

            .ps-new-arrival-related__more:hover {
                color: var(--global-color-link-hover);
                text-decoration: underline;
            }

            /* Mobile Header Layout */
            @media (max-width: 480px) {
                .ps-new-arrival-related__header {
                    flex-direction: column;
                    gap: 5px;
                    padding-left: 0;
                }

                .ps-new-arrival-related__header h2 {
                    width: 100%;
                }

                .ps-new-arrival-related__more {
                    margin-left: auto;
                    margin-right: 0;
                }
            }

            .ps-block--new-arrival-related .ps-block__content {
                margin: 0;
                position: relative;
            }

            /* Remove hover border on carousel items */
            .ps-block--new-arrival-related .ps-product {
                padding: 0;
                margin: 0;
            }

            .ps-block--new-arrival-related .ps-product:hover {
                border: none !important;
            }

            /* Desktop Carousel Styles */
            @media (min-width: 768px) {
                /* Default: NO padding */
                .ps-carousel--new-arrival-related {
                    padding: 0;
                }
                
                /* Add padding ONLY when NOT single item carousel */
                .ps-carousel--new-arrival-related:not(.single-item-carousel) {
                    padding: 0 70px;
                }
                
                /* Fix single item to take natural width, not full width */
                .ps-carousel--new-arrival-related.single-item-carousel .owl-stage-outer {
                    width: auto !important;
                }
                
                .ps-carousel--new-arrival-related.single-item-carousel .owl-stage {
                    width: auto !important;
                    transform: none !important;
                }
                
                .ps-carousel--new-arrival-related.single-item-carousel .owl-item {
                    width: 200px !important;
                }

                .ps-carousel--new-arrival-related .owl-nav {
                    display: block !important;
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    left: 0;
                    right: 0;
                    width: 100%;
                    height: 0;
                    pointer-events: none;
                    z-index: 10;
                }
                
                /* Hide navigation for single item */
                .ps-carousel--new-arrival-related.single-item-carousel .owl-nav {
                    display: none !important;
                }

                .ps-carousel--new-arrival-related .owl-nav button {
                    position: absolute;
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background: transparent !important;
                    border: 1px solid var(--global-color-border-strong) !important;
                    color: var(--global-color-text-main) !important;
                    font-size: 16px;
                    line-height: 1;
                    pointer-events: all;
                    transition: all 0.3s ease;
                    display: flex !important;
                    align-items: center;
                    justify-content: center;
                }

                .ps-carousel--new-arrival-related .owl-nav button:hover {
                    background: var(--global-color-card-bg) !important;
                    border-color: var(--global-color-border-strong) !important;
                }

                .ps-carousel--new-arrival-related .owl-nav button.disabled {
                    opacity: 0.3 !important;
                    cursor: not-allowed !important;
                    pointer-events: none !important;
                }

                .ps-carousel--new-arrival-related .owl-nav button.disabled:hover {
                    background: transparent !important;
                    border-color: var(--global-color-border-strong) !important;
                }

                /* Hide navigation when both arrows are disabled (all items visible) */
                .ps-carousel--new-arrival-related .owl-nav:has(.owl-prev.disabled):has(.owl-next.disabled) {
                    display: none !important;
                }

                /* Remove padding when navigation is hidden */
                .ps-carousel--new-arrival-related:has(.owl-nav .owl-prev.disabled):has(.owl-nav .owl-next.disabled) {
                    padding: 0 !important;
                }

                .ps-carousel--new-arrival-related .owl-nav .owl-prev {
                    left: 0;
                }

                .ps-carousel--new-arrival-related .owl-nav .owl-next {
                    right: 0;
                }

                /* Hide dots on desktop */
                .ps-carousel--new-arrival-related .owl-dots {
                    display: none !important;
                }
            }

            /* Mobile Carousel Styles */
            @media (max-width: 767px) {
                /* Hide arrows on mobile */
                .ps-carousel--new-arrival-related .owl-nav {
                    display: none !important;
                }

                /* Show and style dots */
                .ps-carousel--new-arrival-related .owl-dots {
                    display: flex !important;
                    justify-content: center;
                    align-items: center;
                    gap: 6px;
                    margin-top: 15px;
                    padding: 0 15px;
                }

                .ps-carousel--new-arrival-related .owl-dots .owl-dot {
                    width: 6px !important;
                    height: 6px !important;
                    background: var(--global-color-border-strong) !important;
                    border-radius: 3px !important;
                    border: none !important;
                    transition: all 0.3s ease;
                    padding: 0 !important;
                    margin: 0 !important;
                    cursor: pointer;
                    opacity: 0.5;
                }

                .ps-carousel--new-arrival-related .owl-dots .owl-dot span {
                    display: none !important;
                }

                .ps-carousel--new-arrival-related .owl-dots .owl-dot.active {
                    background: var(--global-color-primary) !important;
                    width: 20px !important;
                    opacity: 1;
                    border-radius: 10px !important;
                }

                .ps-new-arrival-related .ps-container {
                    padding-left: 0;
                    padding-right: 0;
                }

                .ps-block--new-arrival-related {
                    padding: 0;
                    margin-bottom: 0;
                }

                .ps-new-arrival-related__header {
                    padding-bottom: 15px;
                    margin-bottom: 0;
                }

                .ps-block--new-arrival-related .ps-block__content {
                    padding: 0;
                }
            }
        </style>
    @endif
@endif

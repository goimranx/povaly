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
          ->take(7)
          ->values();
    @endphp

    @if($topRatedProducts->isNotEmpty())
        <!-- TOP RATED RELATED ITEMS SECTION -->
        <div class="ps-section--default">
            <div class="ps-section__header">
                <h3>{{ __('Top Rated Related Items') }}</h3>
            </div>
            <div class="ps-section__content">
                <div class="ps-carousel--responsive owl-slider"
                     data-owl-auto="true"
                     data-owl-loop="false"
                     data-owl-speed="10000"
                     data-owl-gap="0"
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
        <!-- END: ps-section--default -->
    @endif
@endif

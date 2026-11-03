@php
    // Get current user's browsing history
    $currentUserHistory = collect();
    $isGuest = !auth('customer')->check();
    
    if (EcommerceHelper::isEnabledCustomerRecentlyViewedProducts()) {
        if (auth('customer')->check()) {
            $currentUserHistory = DB::table('ec_customer_recently_viewed_products')
                ->where('customer_id', auth('customer')->id())
                ->pluck('product_id');
        } else {
            // For guests, get from session cart
            $currentUserHistory = collect(Cart::instance('recently_viewed')->content())
                ->pluck('id');
        }
    }
    
    $recommendedProducts = collect();
    
    if ($currentUserHistory->isNotEmpty()) {
        // Find other customers who viewed similar products
        // Note: This queries the database, so it finds logged-in customers only
        $similarCustomerIds = DB::table('ec_customer_recently_viewed_products')
            ->whereIn('product_id', $currentUserHistory->toArray())
            ->where('customer_id', '!=', auth('customer')->id() ?? 0)
            ->where('customer_id', '>', 0) // Only logged-in customers (not guests with customer_id=0)
            ->groupBy('customer_id')
            ->pluck('customer_id');
        
        // Get products viewed by similar customers (excluding current user's history)
        $similarViewedProductIds = DB::table('ec_customer_recently_viewed_products')
            ->whereIn('customer_id', $similarCustomerIds)
            ->whereNotIn('product_id', $currentUserHistory->toArray())
            ->select('product_id', DB::raw('COUNT(*) as view_count'))
            ->groupBy('product_id')
            ->orderByDesc('view_count')
            ->limit(50)
            ->pluck('product_id');
        
        if ($similarViewedProductIds->isNotEmpty()) {
            $productRepository = app(\Botble\Ecommerce\Repositories\Interfaces\ProductInterface::class);
            
            // Check if we're on a product/category/brand page for contextual filtering
            $contextFilter = null;
            $currentProduct = null;
            
            // Try to get current product from route or view
            if (request()->route('slug')) {
                $currentProduct = $productRepository->getFirstBy(['slug' => request()->route('slug')]);
            } elseif (isset($product)) {
                $currentProduct = $product;
            }
            
            // Build query
            $query = \Botble\Ecommerce\Models\Product::query()
                ->whereIn('id', $similarViewedProductIds->toArray())
                ->where('status', \Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
                ->with(EcommerceHelper::withProductEagerLoadingRelations());
            
            // Apply contextual filtering if on single product page
            if ($currentProduct) {
                // Get product keywords for filtering (similar to top-rated-related logic)
                $productName = $currentProduct->name;
                $productTags = $currentProduct->tags->pluck('name')->toArray();
                $categoryNames = $currentProduct->categories->pluck('name')->toArray();
                
                $allKeywords = array_merge([$productName], $productTags, $categoryNames);
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
                
                // Apply filtering
                if (!empty($searchWords)) {
                    $query->where(function ($q) use ($searchWords) {
                        foreach ($searchWords as $word) {
                            $q->orWhere('ec_products.name', 'LIKE', "%{$word}%")
                              ->orWhere('ec_products.description', 'LIKE', "%{$word}%")
                              ->orWhereHas('tags', function($tagQuery) use ($word) {
                                  $tagQuery->where('name', 'LIKE', "%{$word}%");
                              })
                              ->orWhereHas('categories', function($catQuery) use ($word) {
                                  $catQuery->where('name', 'LIKE', "%{$word}%");
                              });
                        }
                    });
                }
            }
            
            $limit = (int) ($shortcode->limit ?? 12);
            $recommendedProducts = $query->limit($limit)->get();
        }
    }
    
    $title = $shortcode->title ?: __('Customers with similar interests also viewed');
@endphp

@if($recommendedProducts->isNotEmpty())
<div class="ps-similar-customers-recommendations">
    <div class="ps-container">
        <div class="ps-block--similar-customers">
            <div class="ps-block__header">
                <h2>{{ $title }}</h2>
            </div>
            <div class="ps-block__content">
                <div class="ps-carousel--similar-customers owl-slider"
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
                    @foreach($recommendedProducts as $product)
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
    /* Similar Customers Recommendations Section */
    .ps-similar-customers-recommendations {
        margin: 10px 0 !important;
        background: var(--global-color-site-bg);
    }

    /* Similar Customers Block */
    .ps-block--similar-customers {
        border: none;
        border-radius: 0;
        padding: 0;
        height: 100%;
    }

    .ps-block--similar-customers .ps-block__header {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 0;
        padding: 0 0 10px 0;
        flex-wrap: nowrap;
        gap: 15px;
    }

    .ps-block--similar-customers .ps-block__header h2 {
        font-size: 22px;
        font-weight: 700;
        color: var(--global-color-text-main);
        line-height: 1.3;
        margin: 0;
        padding: 0;
        flex-shrink: 0;
    }
    
    @media (max-width: 767px) {
        .ps-block--similar-customers .ps-block__header {
            flex-wrap: wrap;
        }

        .ps-block--similar-customers .ps-block__header h2 {
            font-size: 20px;
        }
    }

    .ps-block--similar-customers .ps-block__content {
        margin: 0;
        position: relative;
    }

    /* Remove hover border on carousel items */
    .ps-block--similar-customers .ps-product {
        padding: 0;
        margin: 0;
        border: none !important;
    }

    .ps-block--similar-customers .ps-product:hover {
        border: none !important;
    }

    /* Navigation Arrows - Desktop only (≥768px) */
    @media (min-width: 768px) {
        /* Add padding to carousel wrapper to leave space for arrows */
        .ps-carousel--similar-customers {
            padding: 0 70px;
        }

        .ps-carousel--similar-customers .owl-nav {
            display: block !important;
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            pointer-events: none;
            z-index: 10;
        }

        .ps-carousel--similar-customers .owl-nav button {
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

        .ps-carousel--similar-customers .owl-nav button:hover {
            background: var(--global-color-card-bg) !important;
            border-color: var(--global-color-border-strong) !important;
        }

        .ps-carousel--similar-customers .owl-nav button.disabled {
            opacity: 0.3 !important;
            cursor: not-allowed !important;
            pointer-events: auto !important;
        }

        .ps-carousel--similar-customers .owl-nav button.disabled:hover {
            background: transparent !important;
            border-color: var(--global-color-border-strong) !important;
        }

        .ps-carousel--similar-customers .owl-nav .owl-prev {
            left: 0;
        }

        .ps-carousel--similar-customers .owl-nav .owl-next {
            right: 0;
        }

        /* Hide dots on desktop */
        .ps-carousel--similar-customers .owl-dots {
            display: none !important;
        }
    }

    /* Navigation Dots - Mobile only (<768px) */
    @media (max-width: 767px) {
        /* Hide arrows on mobile */
        .ps-carousel--similar-customers .owl-nav {
            display: none !important;
        }

        /* Show and style dots */
        .ps-carousel--similar-customers .owl-dots {
            display: flex !important;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 15px;
            padding: 0;
        }

        .ps-carousel--similar-customers .owl-dots .owl-dot {
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

        .ps-carousel--similar-customers .owl-dots .owl-dot span {
            display: none !important;
        }

        .ps-carousel--similar-customers .owl-dots .owl-dot.active {
            background: var(--global-color-primary) !important;
            width: 20px !important;
            min-width: 20px !important;
            height: 6px !important;
            min-height: 6px !important;
            border-radius: 3px !important;
        }

        .ps-similar-customers-recommendations .ps-container {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .ps-block--similar-customers {
            padding: 0 !important;
            margin-bottom: 0 !important;
        }

        .ps-block--similar-customers .ps-block__header {
            padding: 0 10px 10px 10px !important;
            margin-bottom: 0 !important;
        }

        .ps-block--similar-customers .ps-block__content {
            padding: 0 !important;
        }
    }

    /* Tablet (481-767px): Add ps-container padding and header bottom padding */
    @media (min-width: 481px) and (max-width: 767px) {
        .ps-similar-customers-recommendations .ps-container {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .ps-block--similar-customers .ps-block__header {
            padding: 0 0 10px 0 !important;
        }
    }

    /* Mobile (≤480px): Header bottom padding */
    @media (max-width: 480px) {
        .ps-block--similar-customers .ps-block__header {
            padding: 0 10px 10px 10px !important;
        }
    }
</style>
@endif

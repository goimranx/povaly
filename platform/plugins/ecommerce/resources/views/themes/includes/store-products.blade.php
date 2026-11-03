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
        <!-- STORE PRODUCTS SECTION -->
        <div class="ps-section--default">
            <div class="ps-section__header">
                <h3>
                    @if($hasStoreProducts)
                        {{ __('Top products from :store', ['store' => $product->store->name]) }}
                    @else
                        {{ __('Similar Products You Might Like') }}
                    @endif
                </h3>
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
                    @foreach($storeProducts as $storeProduct)
                        <div class="ps-product">
                            {!! Theme::partial('product-item', ['product' => $storeProduct]) !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- END: ps-section--default -->
    @endif
@endif

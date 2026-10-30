@php
    Theme::set('pageId', 'product-page');
@endphp

<div class="ps-page--product">
    <div class="ps-product--detail ps-product--fullwidth bb-product-detail" id="app">
            
            <!-- PRODUCT HEADER: Gallery + Product Info + Purchase Box -->
            <div class="ps-product__header">
                <!-- Gallery Wrapper (48%) -->
                <div class="ps-product__gallery-wrapper">
                    <div class="ps-product__thumbnail">
                        @include(EcommerceHelper::viewPath('includes.product-gallery'))
                    </div>
                </div>
                
                <!-- Info Wrapper (52%) - Contains Form with Product Details + Purchase Box -->
                <form class="add-to-cart-form ps-product__info-wrapper" method="POST" action="{{ route('public.cart.add-to-cart') }}">
                    @csrf
                    
                    <!-- LEFT: Product Details -->
                    <div class="ps-product__details">
                        @php $product->loadMissing('productCollections'); @endphp

                        {{-- 1. Store link --}}
                        @if (is_plugin_active('marketplace') && $product->store_id)
                            <div class="pd-store-link">
                                <a href="{{ $product->store->url }}">
                                    {{ __('Shop the') }} {{ $product->store->name }} {{ __('store') }}
                                    {!! $product->store->badge !!}
                                </a>
                            </div>
                        @endif

                        {{-- 2. Product name --}}
                        <h1 class="pd-title">{{ $product->name }}</h1>

                        {{-- 3. Rating row --}}
                        @if (EcommerceHelper::isReviewEnabled() && $product->reviews_count > 0)
                            @php
                                // Check free shipping: site-wide setting OR a price/weight rule that covers this product
                                $hasFreeShipping = (bool) get_ecommerce_setting('free_shipping_enabled', false);
                                if (!$hasFreeShipping) {
                                    try {
                                        $productPrice  = $product->front_sale_price ?: $product->price;
                                        $productWeight = $product->weight ?? 0;
                                        $defaultCountry = \Botble\Ecommerce\Facades\EcommerceHelper::getFirstCountryId();
                                        $shippingZone = \Botble\Ecommerce\Models\Shipping::query()
                                            ->where(function ($q) use ($defaultCountry) {
                                                $q->whereNull('country')->orWhere('country', $defaultCountry);
                                            })
                                            ->first();
                                        if ($shippingZone) {
                                            $freeRule = \Botble\Ecommerce\Models\ShippingRule::query()
                                                ->where('shipping_id', $shippingZone->id)
                                                ->where('price', 0)
                                                ->where(function ($q) use ($productPrice, $productWeight) {
                                                    $q->where(function ($sub) use ($productPrice) {
                                                        $sub->where('type', 'based_on_price')
                                                            ->where('from', '<=', $productPrice)
                                                            ->where(function ($s) use ($productPrice) {
                                                                $s->whereNull('to')->orWhere('to', '>=', $productPrice);
                                                            });
                                                    })->orWhere(function ($sub) use ($productWeight) {
                                                        $sub->where('type', 'based_on_weight')
                                                            ->where('from', '<=', $productWeight)
                                                            ->where(function ($s) use ($productWeight) {
                                                                $s->whereNull('to')->orWhere('to', '>=', $productWeight);
                                                            });
                                                    });
                                                })
                                                ->exists();
                                            $hasFreeShipping = $freeRule;
                                        }
                                    } catch (\Exception $e) {}
                                }
                            @endphp
                            <div class="pd-rating-row">
                                <a href="#tab-reviews" class="pd-rating-link">
                                    <span class="pd-rating-avg">{{ number_format($product->reviews_avg, 1) }}</span>
                                    <span class="pd-rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= floor($product->reviews_avg))
                                                <i class="ph-fill ph-star"></i>
                                            @elseif ($i - $product->reviews_avg < 1)
                                                <i class="ph-fill ph-star-half"></i>
                                            @else
                                                <i class="ph ph-star"></i>
                                            @endif
                                        @endfor
                                    </span>
                                    <span class="pd-rating-count">({{ number_format($product->reviews_count) }})</span>
                                </a>
                                @if ($hasFreeShipping)
                                    <span class="pd-free-shipping">
                                        <i class="ph ph-truck"></i> {{ __('Free Shipping') }}
                                    </span>
                                @endif
                            </div>
                        @endif

                        {{-- 4. Brand --}}
                        @if ($product->brand_id && $product->brand->name)
                            <div class="pd-brand">
                                <span class="pd-brand-label">{{ __('Brand') }}:</span>
                                <a href="{{ $product->brand->url }}" class="pd-brand-value">{{ $product->brand->name }}</a>
                            </div>
                        @endif

                        {{-- 5. Bought last month --}}
                        @php
                            $boughtLastMonth = \DB::table('ec_order_product')
                                ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_product.order_id')
                                ->where('ec_order_product.product_id', $product->id)
                                ->where('ec_orders.created_at', '>=', now()->subDays(30)->startOfDay())
                                ->where('ec_orders.created_at', '<', now()->startOfDay())
                                ->sum('ec_order_product.qty');
                        @endphp
                        @if ($boughtLastMonth > 0)
                            <div class="pd-bought-count">
                                <i class="ph ph-trend-up"></i>
                                {{ number_format($boughtLastMonth) }} {{ __('bought in past month') }}
                            </div>
                        @endif

                        {{-- 6. Separator + Price --}}
                        <div class="pd-price-block">
                            <div class="pd-separator"></div>
                            @php
                                $salePercent = $product->isOnSale()
                                    ? round((1 - $product->front_sale_price / $product->price) * 100)
                                    : 0;
                            @endphp
                            <div class="pd-price-row">
                                @if ($product->isOnSale() && $salePercent > 0)
                                    <span class="pd-price-badge">-{{ $salePercent }}%</span>
                                @endif
                                {!! Theme::partial('product-price', [
                                    'product'           => $product,
                                    'priceWrapperTag'   => 'span',
                                    'priceWrapperClass' => 'pd-price-wrapper',
                                    'priceClass'        => 'pd-price-current',
                                    'priceOriginalClass'=> 'pd-price-original',
                                    'showSalePercentage'=> false,
                                ]) !!}
                            </div>
                            @if ($product->tax_description)
                                <p class="pd-tax-note"><small>{{ $product->tax_description }}</small></p>
                            @endif
                        </div>

                        {{-- 7. Variations --}}
                        @if ($product->has_variation)
                            <div class="pd-variations pr_switch_wrap">
                                {!! render_product_swatches($product, [
                                    'selected' => $selectedAttrs,
                                    'view'     => Theme::getThemeNamespace() . '::views.ecommerce.attributes.swatches-renderer'
                                ]) !!}
                            </div>
                        @endif

                        {!! render_product_options($product) !!}

                        {{-- Flash sale countdown (keep existing) --}}
                        @if (($flashSale = $product->latestFlashSales()->first()) && $flashSale->end_date->isFuture())
                            <div class="ps-product__countdown">
                                <figure>
                                    <figcaption>{{ __("Don't Miss Out! This promotion will expires in") }}</figcaption>
                                    <ul class="ps-countdown" data-time="{{ $flashSale->end_date }}">
                                        <li><span class="days"></span><p>{{ __('Days') }}</p></li>
                                        <li><span class="hours"></span><p>{{ __('Hours') }}</p></li>
                                        <li><span class="minutes"></span><p>{{ __('Minutes') }}</p></li>
                                        <li><span class="seconds"></span><p>{{ __('Seconds') }}</p></li>
                                    </ul>
                                </figure>
                                <figure>
                                    <figcaption>{{ __('Sold Items') }}</figcaption>
                                    <div class="ps-product__progress-bar ps-progress" data-value="{{ $flashSale->pivot->quantity > 0 ? ($flashSale->pivot->sold / $flashSale->pivot->quantity) * 100 : 0 }}">
                                        <div class="ps-progress__value"><span style="width: {{ $flashSale->pivot->quantity > 0 ? $flashSale->pivot->sold / $flashSale->pivot->quantity : 0 }}%;"></span></div>
                                        @if (Botble\Ecommerce\Facades\FlashSale::isShowSaleCountLeft())
                                            <p><b>{{ $flashSale->pivot->sold }}/{{ $flashSale->pivot->quantity }}</b> {{ __('Sold') }}</p>
                                        @endif
                                    </div>
                                </figure>
                            </div>
                        @endif

                        {{-- 8. Product details section --}}
                        <div class="pd-section">
                            <div class="pd-section-header">
                                <h2 class="pd-section-title">{{ __('Product Details') }}</h2>
                                <div class="pd-section-separator"></div>
                            </div>
                            
                            <div class="pd-accordion" id="pd-accordion">

                            {{-- 8a. About this item --}}
                            @if ($product->description)
                                <div class="pd-accordion-item">
                                    <button class="pd-accordion-btn" type="button" data-bs-toggle="collapse" data-bs-target="#pd-about" aria-expanded="true">
                                        <span>{{ __('About this item') }}</span>
                                        <i class="ph ph-caret-down pd-accordion-icon"></i>
                                    </button>
                                    <div id="pd-about" class="collapse show" data-bs-parent="#pd-accordion">
                                        <div class="pd-accordion-body ps-list--dot">
                                            {!! apply_filters('ecommerce_before_product_description', null, $product) !!}
                                            <div class="pd-description-content pd-description-collapsed" id="pd-description-content">
                                                {!! BaseHelper::clean($product->description) !!}
                                            </div><button type="button" class="pd-read-more-btn" id="pd-read-more-btn" onclick="toggleDescription()">{{ __('Read more') }}</button>
                                            {!! apply_filters('ecommerce_after_product_description', null, $product) !!}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- 8b. Item details --}}
                            @php
                                $hasDetails = $product->brand_id
                                    || $product->categories->isNotEmpty()
                                    || $product->tags->isNotEmpty()
                                    || $product->productCollections->isNotEmpty()
                                    || $product->sku;
                            @endphp
                            @if ($hasDetails)
                                <div class="pd-accordion-item">
                                    <button class="pd-accordion-btn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pd-details" aria-expanded="false">
                                        <span>{{ __('Item details') }}</span>
                                        <i class="ph ph-caret-down pd-accordion-icon"></i>
                                    </button>
                                    <div id="pd-details" class="collapse" data-bs-parent="#pd-accordion">
                                        <div class="pd-accordion-body pd-item-details">
                                            @if ($product->sku)
                                                <div class="pd-detail-row">
                                                    <span class="pd-detail-label">{{ __('SKU') }}</span>
                                                    <span class="pd-detail-value" data-bb-value="product-sku">{{ $product->sku }}</span>
                                                </div>
                                            @endif
                                            @if ($product->brand_id && $product->brand->name)
                                                <div class="pd-detail-row">
                                                    <span class="pd-detail-label">{{ __('Brand') }}</span>
                                                    <span class="pd-detail-value"><a href="{{ $product->brand->url }}">{{ $product->brand->name }}</a></span>
                                                </div>
                                            @endif
                                            @if ($product->categories->isNotEmpty())
                                                <div class="pd-detail-row">
                                                    <span class="pd-detail-label">{{ __('Categories') }}</span>
                                                    <span class="pd-detail-value">
                                                        @foreach($product->categories as $category)
                                                            <a href="{{ $category->url }}">{!! BaseHelper::clean($category->name) !!}</a>@if (!$loop->last), @endif
                                                        @endforeach
                                                    </span>
                                                </div>
                                            @endif
                                            @if ($product->tags->isNotEmpty())
                                                <div class="pd-detail-row">
                                                    <span class="pd-detail-label">{{ __('Tags') }}</span>
                                                    <span class="pd-detail-value">
                                                        @foreach($product->tags as $tag)
                                                            <a href="{{ $tag->url }}">{{ $tag->name }}</a>@if (!$loop->last), @endif
                                                        @endforeach
                                                    </span>
                                                </div>
                                            @endif
                                            @if ($product->productCollections->isNotEmpty())
                                                <div class="pd-detail-row">
                                                    <span class="pd-detail-label">{{ __('Collections') }}</span>
                                                    <span class="pd-detail-value">
                                                        @foreach($product->productCollections as $col)
                                                            {{ $col->name }}@if (!$loop->last), @endif
                                                        @endforeach
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- 8c. Return policy --}}
                            <div class="pd-accordion-item">
                                <button class="pd-accordion-btn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pd-return" aria-expanded="false">
                                    <span>{{ __('Return policy') }}</span>
                                    <i class="ph ph-caret-down pd-accordion-icon"></i>
                                </button>
                                <div id="pd-return" class="collapse" data-bs-parent="#pd-accordion">
                                    <div class="pd-accordion-body pd-return-policy">
                                        <div class="pd-return-text">
                                            <p><strong>{{ __('7 day returns') }}</strong></p>
                                            <p>{{ __('Return within 7 days of receiving your order. We cover full refund minus the delivery charge. Simply contact us and we\'ll handle the rest.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 8d. Review highlights --}}
                            @if (EcommerceHelper::isReviewEnabled() && $product->reviews_count > 0)
                                @php
                                    $topReviews = $product->reviews()
                                        ->where('star', 5)
                                        ->with('user')
                                        ->latest()
                                        ->limit(4)
                                        ->get();
                                @endphp
                                <div class="pd-accordion-item">
                                    <button class="pd-accordion-btn collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pd-reviews" aria-expanded="false">
                                        <span>{{ __('Review highlights') }}</span>
                                        <span class="pd-accordion-rating-inline">
                                            <span class="pd-rating-avg-sm">{{ number_format($product->reviews_avg, 1) }}</span>
                                            <span class="pd-rating-stars-sm">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($product->reviews_avg))
                                                        <i class="ph-fill ph-star"></i>
                                                    @elseif ($i - $product->reviews_avg < 1)
                                                        <i class="ph-fill ph-star-half"></i>
                                                    @else
                                                        <i class="ph ph-star"></i>
                                                    @endif
                                                @endfor
                                            </span>
                                            <span class="pd-rating-count-sm">({{ number_format($product->reviews_count) }})</span>
                                        </span>
                                        <i class="ph ph-caret-down pd-accordion-icon"></i>
                                    </button>
                                    <div id="pd-reviews" class="collapse" data-bs-parent="#pd-accordion">
                                        <div class="pd-accordion-body">
                                            @if ($topReviews->isNotEmpty())
                                                <div class="pd-review-list">
                                                    @foreach($topReviews as $review)
                                                        <div class="pd-review-item">
                                                            <div class="pd-review-header">
                                                                <span class="pd-review-stars">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i class="{{ $i <= $review->star ? 'ph-fill' : 'ph' }} ph-star"></i>
                                                                    @endfor
                                                                </span>
                                                                <span class="pd-review-author">{{ $review->user_name ?: __('Customer') }}</span>
                                                                <span class="pd-review-date">{{ $review->created_at->format('M d, Y') }}</span>
                                                            </div>
                                                            @if ($review->comment)
                                                                <p class="pd-review-comment">{{ Str::limit($review->comment, 150) }}</p>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <a href="#tab-reviews" class="pd-read-reviews-btn">{{ __('Read all reviews') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                        {{-- END: pd-accordion --}}

                        {{-- 9. FAQs --}}
                        @if (is_plugin_active('faq') && count($product->faq_items) > 0)
                            <div class="pd-faqs" id="pd-faq-accordion">
                                <div class="pd-faqs-title">{{ __('Frequently Asked Questions') }}</div>
                                @foreach($product->faq_items as $faq)
                                    <div class="pd-accordion-item">
                                        <button class="pd-accordion-btn {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#pd-faq-{{ $loop->index }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                                            <span>{!! BaseHelper::clean($faq[0]['value']) !!}</span>
                                            <i class="ph ph-caret-down pd-accordion-icon"></i>
                                        </button>
                                        <div id="pd-faq-{{ $loop->index }}" class="collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#pd-faq-accordion">
                                            <div class="pd-accordion-body">
                                                {!! BaseHelper::clean($faq[1]['value']) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        </div>
                        {{-- End of pd-section --}}

                    </div>
                    <!-- END: Product Details -->
                    
                    <!-- RIGHT: Purchase Box (35%) -->
                    <div class="ps-product__purchase">
                        <div class="ps-product__purchase-inner">
                            <div class="ps-product__purchase-price" data-bb-toggle="product-total-price">
                                {!! Theme::partial('product-price', [
                                    'product' => $product,
                                    'priceWrapperTag' => 'h3',
                                    'priceWrapperClass' => 'ps-product__total-price',
                                    'hiddenClasses' => ['ps-product__del-price'], // Hide regular price
                                ]) !!}
                            </div>
                            
                            {{-- Delivery Details --}}
                            <div class="ps-product__delivery-details">
                                <div class="delivery-details-header">
                                    <span class="delivery-label">{{ __('Deliver to') }}</span>
                                </div>
                                <div class="delivery-address-content" id="delivery-address-display">
                                    @if (auth('customer')->check())
                                        @php
                                            $defaultAddress = auth('customer')->user()->addresses()->where('is_default', 1)->first();
                                        @endphp
                                        @if ($defaultAddress)
                                            <div class="delivery-address-text">
                                                {{ $defaultAddress->name }}, {{ $defaultAddress->address }}, {{ $defaultAddress->city }}
                                                @if ($defaultAddress->state)
                                                    , {{ $defaultAddress->state }}
                                                @endif
                                                - {{ $defaultAddress->zip_code }}
                                            </div>
                                            <a href="#" class="delivery-change-btn trigger-location-modal">{{ __('Change') }}</a>
                                        @else
                                            <div class="delivery-no-address">
                                                {{ __('No delivery address set') }}
                                            </div>
                                            <a href="#" class="delivery-add-btn trigger-location-modal">{{ __('Add address') }}</a>
                                        @endif
                                    @else
                                        <div class="delivery-no-address">
                                            {{ __('No delivery address set') }}
                                        </div>
                                        <a href="#" class="delivery-add-btn trigger-location-modal">{{ __('Login to add address') }}</a>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="number-items-available mb-3">
                                @if ($product->isOutOfStock())
                                    <span class="text-danger">({{ __('Out of stock') }})</span>
                                @else
                                    @if (!$productVariation)
                                        <span class="text-danger">({{ __('Not available') }})</span>
                                    @else
                                        @if ($productVariation->isOutOfStock())
                                            <span class="text-danger">({{ __('Out of stock') }})</span>
                                        @elseif  (!$productVariation->with_storehouse_management || $productVariation->quantity < 1)
                                            <span class="text-success">({{ __('Available') }})</span>
                                        @elseif ($productVariation->quantity)
                                            @if (EcommerceHelper::showNumberOfProductsInProductSingle())
                                                <span class="text-success">
                                                    @if ($productVariation->quantity != 1)
                                                        ({{ __(':number products available', ['number' => $productVariation->quantity]) }})
                                                    @else
                                                        ({{ __(':number product available', ['number' => $productVariation->quantity]) }})
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-success">({{ __('In stock') }})</span>
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            </div>

                            {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null, $product) !!}
                            
                            <div class="ps-product__shopping">
                                @if (EcommerceHelper::isCartEnabled())
                                    <figure>
                                        <figcaption>{{ __('Quantity') }}</figcaption>
                                        <div class="form-group--number product__qty">
                                            <button class="up" type="button" aria-label="{{ __('Increase quantity') }}"><i class="icon-plus" aria-hidden="true"></i></button>
                                            <button class="down" type="button" aria-label="{{ __('Decrease quantity') }}"><i class="icon-minus" aria-hidden="true"></i></button>
                                            <input class="form-control qty-input" type="number" name="qty" value="1" placeholder="1" min="1">
                                        </div>
                                    </figure>
                                    <input type="hidden" name="id" class="hidden-product-id" value="{{ ($product->is_variation || !$product->defaultVariation->product_id) ? $product->id : $product->defaultVariation->product_id }}"/>

                                    <button class="ps-btn ps-btn--black add-to-cart-button @if ($product->isOutOfStock()) btn-disabled @endif" type="submit" name="add_to_cart" value="1" {!! EcommerceHelper::jsAttributes('add-to-cart', $product, additional: ['data-bb-toggle' => 'none']) !!} @if ($product->isOutOfStock()) disabled @endif>{{ __('Add to cart') }}</button>
                                    @if (EcommerceHelper::isQuickBuyButtonEnabled())
                                        <button class="ps-btn add-to-cart-button @if ($product->isOutOfStock()) btn-disabled @endif" type="submit" name="checkout" value="1" {!! EcommerceHelper::jsAttributes('add-to-cart', $product, additional: ['data-bb-toggle' => 'none']) !!} @if ($product->isOutOfStock()) disabled @endif>{{ __('Buy Now') }}</button>
                                    @endif
                                @endif
                                <div class="ps-product__actions">
                                    @if (EcommerceHelper::isWishlistEnabled())
                                        <a class="js-add-to-wishlist-button" href="#" data-url="{{ route('public.wishlist.add', $product->id) }}"><i class="icon-heart"></i></a>
                                    @endif
                                    @if (EcommerceHelper::isCompareEnabled())
                                        <a class="js-add-to-compare-button" href="#" data-url="{{ route('public.compare.add', $product->id) }}" title="{{ __('Compare') }}"><i class="icon-chart-bars"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END: Purchase Box (35%) -->
                </form>
            </div>
            <!-- END: ps-product__header -->

            <!-- UPSALE PRODUCTS SECTION (SIBLING OF HEADER) -->
            @if (EcommerceHelper::isEnabledUpSaleProducts())
                <div class="ec-up-sale-section">
                    @include(EcommerceHelper::viewPath('includes.up-sale-products'), ['parentProduct' => $product])
                </div>
            @endif

            <!-- CROSS-SALE PRODUCTS SECTION (SIBLING OF HEADER) -->
            @if (EcommerceHelper::isEnabledCrossSaleProducts())
                <div class="ec-cross-sale-section">
                    @include(EcommerceHelper::viewPath('includes.cross-sale-products'), ['parentProduct' => $product])
                </div>
            @endif

            <!-- TABS SECTION (SIBLING OF HEADER) -->
            <div class="ps-product__content ps-tab-root">
                <ul class="ps-tab-list">
                    <li class="active"><a href="#tab-description">{{ __('Description') }}</a></li>
                    @if (EcommerceHelper::isProductSpecificationEnabled() && $product->specificationAttributes->where('pivot.hidden', false)->isNotEmpty())
                        <li><a href="#tab-specification">{{ __('Specification') }}</a></li>
                    @endif
                    @if (EcommerceHelper::isReviewEnabled())
                        <li><a href="#tab-reviews">{{ __('Reviews') }}&nbsp;({{ $product->reviews_count }})</a></li>
                    @endif
                    @if (is_plugin_active('marketplace') && $product->store_id)
                        <li><a href="#tab-vendor">{{ __('Vendor') }}</a></li>
                    @endif
                    @if (is_plugin_active('faq'))
                        @if (count($product->faq_items) > 0)
                            <li><a href="#tab-faq">{{ __('Questions and Answers') }}</a></li>
                        @endif
                    @endif
                </ul>
                <div class="ps-tabs">
                    <div class="ps-tab active" id="tab-description">
                        <div class="ps-document">
                            <div class="ck-content">
                                {!! BaseHelper::clean($product->content) !!}
                            </div>
                            <br />
                            {!! apply_filters(BASE_FILTER_PUBLIC_COMMENT_AREA, null, $product) !!}
                        </div>
                    </div>

                    @if (EcommerceHelper::isProductSpecificationEnabled() && $product->specificationAttributes->where('pivot.hidden', false)->isNotEmpty())
                        <div class="ps-tab" id="tab-specification">
                            <div class="tp-product-details-additional-info">
                                @include(EcommerceHelper::viewPath('includes.product-specification'))
                            </div>
                        </div>
                    @endif

                    @if (EcommerceHelper::isReviewEnabled())
                        <div class="ps-tab" id="tab-reviews">
                            @include('plugins/ecommerce::themes.includes.reviews', ['reviewButtonClass' => 'ps-btn'])
                        </div>
                    @endif

                    @if (is_plugin_active('marketplace') && $product->store_id)
                        <div class="ps-tab" id="tab-vendor">
                            <h4>{{ $product->store->name }} {!! $product->store->badge !!}</h4>
                            <div>
                                {!! BaseHelper::clean($product->store->content) !!}
                            </div>

                            <a href="{{ $product->store->url }}" class="more-products">
                                {{ __('More Products from :store',  ['store' => $product->store->name]) }}
                            </a>
                        </div>
                    @endif

                    @if (is_plugin_active('faq') && count($product->faq_items) > 0)
                        <div class="ps-tab" id="tab-faq">
                            <div class="ps-faq-wrapper">
                                <div class="ps-faq-header mb-4">
                                    <h3 class="ps-faq-title">{{ __('Frequently Asked Questions') }}</h3>
                                    <p class="ps-faq-subtitle text-muted">{{ __('Find answers to common questions about this product') }}</p>
                                </div>
                                <div class="accordion ps-faq-accordion" id="faq-accordion">
                                    @foreach($product->faq_items as $faq)
                                        <div class="accordion-item ps-faq-item @if ($loop->first) first-item @endif">
                                            <h2 class="accordion-header" id="heading-faq-{{ $loop->index }}">
                                                <button class="accordion-button ps-faq-question @if (!$loop->first) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-faq-{{ $loop->index }}" aria-expanded="@if ($loop->first) true @else false @endif" aria-controls="collapse-faq-{{ $loop->index }}">
                                                    <span class="ps-faq-icon me-3">
                                                        <i class="icon-question-circle"></i>
                                                    </span>
                                                    <span class="ps-faq-question-text">{!! BaseHelper::clean($faq[0]['value']) !!}</span>
                                                    <span class="ps-faq-toggle-icon ms-auto">
                                                        <i class="icon-chevron-down"></i>
                                                    </span>
                                                </button>
                                            </h2>
                                            <div id="collapse-faq-{{ $loop->index }}" class="accordion-collapse collapse @if ($loop->first) show @endif" aria-labelledby="heading-faq-{{ $loop->index }}" data-bs-parent="#faq-accordion">
                                                <div class="accordion-body ps-faq-answer">
                                                    <div class="ps-faq-answer-content">
                                                        {!! BaseHelper::clean($faq[1]['value']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- END: ps-product__content -->

            <!-- RELATED PRODUCTS SECTION (SIBLING OF HEADER) -->
            <div class="ps-section--default">
                <div class="ps-section__header">
                    <h3>{{ __('Related products') }}</h3>
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
                        @foreach(get_related_products($product, 7) as $relatedProduct)
                            <div class="ps-product">
                                {!! Theme::partial('product-item', ['product' => $relatedProduct]) !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- END: ps-section--default -->

    </div>
    <!-- END: ps-product--detail -->
</div>
<!-- END: ps-page--product -->

<script>
// Handle scroll to reviews when clicking rating from product cards
document.addEventListener('DOMContentLoaded', function() {
    if (sessionStorage.getItem('scrollToReviews') === 'true') {
        sessionStorage.removeItem('scrollToReviews');
        
        // Wait for tab to be activated
        setTimeout(function() {
            const reviewsTab = document.getElementById('tab-reviews');
            if (reviewsTab) {
                reviewsTab.scrollIntoView({ behavior: 'smooth', block: 'start' });
                window.scrollBy(0, -100); // Offset for fixed header if any
            }
        }, 500);
    }












































































































































































    






















});
</script>


@include(Theme::getThemeNamespace('views.ecommerce.includes.location-modal'))

<script src="{{ Theme::asset()->url('js/location-modal.js') }}?v=A1B2C3D4E5F6G7H8"></script>

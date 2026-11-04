@php
    Theme::set('pageId', 'product-page');
@endphp

<div class="ps-page--product">
    <div class="ps-product--detail ps-product--fullwidth bb-product-detail" id="app">
            
            <!-- MOBILE ONLY: Header Section (Store + Title + Rating + Bought) -->
            <div class="ps-product__mobile-header">
                @php $product->loadMissing('productCollections'); @endphp
                
                {{-- Mobile Store Info --}}
                @if (is_plugin_active('marketplace') && $product->store_id)
                    <div class="pd-mobile-store-section">
                        <div class="pd-mobile-store-row">
                            <a href="{{ $product->store->url }}" class="pd-mobile-store-logo">
                                {!! RvMedia::image($product->store->logo, $product->store->name, 'thumb') !!}
                            </a>
                            <div class="pd-mobile-store-content">
                                <div class="pd-mobile-store-name">{{ $product->store->name }}</div>
                                <div class="pd-mobile-store-actions">
                                    <a href="{{ $product->store->url }}" class="pd-mobile-store-link">
                                        {{ __('Shop the store') }}
                                    </a>
                                    
                                    {{-- Rating on the same row (right side) --}}
                                    @if (EcommerceHelper::isReviewEnabled() && $product->reviews_count > 0)
                                        <div class="pd-rating-row pd-mobile-rating">
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
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                {{-- Mobile Product Title --}}
                <h1 class="pd-mobile-title">{{ $product->name }}</h1>
                
                {{-- Mobile Bought Count --}}
                @php
                    $boughtLastMonth = \DB::table('ec_order_product')
                        ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_product.order_id')
                        ->where('ec_order_product.product_id', $product->id)
                        ->where('ec_orders.created_at', '>=', now()->subDays(30)->startOfDay())
                        ->where('ec_orders.created_at', '<', now()->startOfDay())
                        ->sum('ec_order_product.qty');
                @endphp
                @if ($boughtLastMonth > 0)
                    <div class="pd-mobile-bought">
                        <i class="ph ph-trend-up"></i>
                        {{ number_format($boughtLastMonth) }} {{ __('bought in past month') }}
                    </div>
                @endif
            </div>
            
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
                        @include(Theme::getThemeNamespace('views.ecommerce.includes.product-details-section'), ['sectionId' => 'desktop'])

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
                    <!-- END: Product Details -->
                    
                    <!-- RIGHT: Purchase Box (35%) -->
                    <div class="ps-product__purchase">
                        <div class="ps-product__purchase-inner">
                            <div class="ps-product__purchase-price" data-bb-toggle="product-total-price">
                                {!! Theme::partial('product-price', [
                                    'product' => $product,
                                    'priceWrapperTag' => 'h3',
                                    'priceWrapperClass' => 'ps-product__total-price',
                                    'priceClass' => 'product-price', // Inherit from h3 parent
                                    'hiddenClasses' => ['ps-product__del-price'], // Hide regular price
                                ]) !!}
                            </div>
                            
                            {{-- Delivery Details --}}
                            <div class="ps-product__delivery-details">
                                <div class="delivery-address-content" id="delivery-address-display">
                                    @php
                                        // Get address from session (same logic as LocationSessionController)
                                        $orderHelper = app(\Botble\Ecommerce\Supports\OrderHelper::class);
                                        $token = $orderHelper->getOrderSessionToken();
                                        $sessionData = $orderHelper->getOrderSessionData($token);
                                        $sessionAddressId = $sessionData['address_id'] ?? null;
                                        
                                        $displayAddress = null;
                                        $displayType = 'default';
                                        
                                        if ($sessionAddressId && $sessionAddressId !== 'new') {
                                            // Saved address from session
                                            if (auth('customer')->check()) {
                                                $displayAddress = \Botble\Ecommerce\Models\Address::query()
                                                    ->where('id', $sessionAddressId)
                                                    ->where('customer_id', auth('customer')->id())
                                                    ->first();
                                                $displayType = 'saved';
                                            }
                                        } elseif ($sessionAddressId === 'new' && !empty($sessionData['country'])) {
                                            // Temporary address from session
                                            $displayAddress = (object) $sessionData;
                                            $displayType = 'temporary';
                                        } else {
                                            // No session address, show default saved address
                                            if (auth('customer')->check()) {
                                                $displayAddress = auth('customer')->user()->addresses()->where('is_default', 1)->first();
                                            }
                                        }
                                    @endphp
                                    
                                    @if ($displayAddress)
                                        @include(Theme::getThemeNamespace('views.ecommerce.includes.delivery-address-display'), [
                                            'address' => $displayAddress,
                                            'type' => $displayType,
                                            'product' => $product
                                        ])
                                    @else
                                        {{-- No location set - auto-detect visitor country --}}
                                        @php
                                            // Auto-detect visitor's country using geoip
                                            $visitorCountry = null;
                                            $visitorCountryCode = null;
                                            
                                            try {
                                                if (function_exists('geoip')) {
                                                    $geoip = geoip(request()->ip());
                                                    $visitorCountry = $geoip->country ?? null;
                                                    $visitorCountryCode = $geoip->iso_code ?? null;
                                                } elseif (class_exists('\Stevebauman\Location\Facades\Location')) {
                                                    $position = \Stevebauman\Location\Facades\Location::get(request()->ip());
                                                    $visitorCountry = $position ? $position->countryName : null;
                                                    $visitorCountryCode = $position ? $position->countryCode : null;
                                                }
                                            } catch (\Exception $e) {
                                                // Fallback to default country if detection fails
                                                $defaultCountryId = \Botble\Ecommerce\Facades\EcommerceHelper::getFirstCountryId();
                                                $visitorCountry = \Botble\Ecommerce\Facades\EcommerceHelper::getCountryNameById($defaultCountryId);
                                                $visitorCountryCode = \App\Helpers\DeliveryHelper::getCountryCodeById($defaultCountryId);
                                            }
                                            
                                            if (!$visitorCountry) {
                                                $defaultCountryId = \Botble\Ecommerce\Facades\EcommerceHelper::getFirstCountryId();
                                                $visitorCountry = \Botble\Ecommerce\Facades\EcommerceHelper::getCountryNameById($defaultCountryId);
                                                $visitorCountryCode = \App\Helpers\DeliveryHelper::getCountryCodeById($defaultCountryId);
                                            }
                                            
                                            // Calculate delivery date based on visitor's country
                                            $deliveryDate = \App\Helpers\DeliveryHelper::getEstimatedDeliveryDate($visitorCountryCode, $product->id);
                                            $formattedDate = \App\Helpers\DeliveryHelper::formatDeliveryDate($deliveryDate);
                                        @endphp
                                        
                                        <div class="delivery-date-info">
                                            {{ __('Delivered by around') }} <strong>{{ $formattedDate }}</strong>
                                        </div>
                                        
                                        <div class="delivery-location-row">
                                            <i class="ph ph-map-pin delivery-location-icon"></i>
                                            <div class="delivery-location-content">
                                                <div class="delivery-address-text">
                                                    <span class="delivery-location-label">{{ __('Deliver to') }}</span>
                                                    {{ $visitorCountry }}
                                                </div>
                                            </div>
                                            <a href="#" class="delivery-change-btn trigger-location-modal">
                                                @if (auth('customer')->check())
                                                    {{ __('Update location') }}
                                                @else
                                                    {{ __('Select location') }}
                                                @endif
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="number-items-available">
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
                            </div>
                        </div>
                    </div>
                    <!-- END: Purchase Box (35%) -->
                </form>
                
                {{-- MOBILE ONLY: Product Details Section (included after purchase) --}}
                @include(Theme::getThemeNamespace('views.ecommerce.includes.product-details-section'), ['sectionId' => 'mobile'])
                
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

            <!-- TOP RATED RELATED ITEMS SECTION -->
            @include(EcommerceHelper::viewPath('includes.top-rated-related'), ['product' => $product])

            <!-- STORE PRODUCTS SECTION (SIBLING OF HEADER) -->
            @if (is_plugin_active('marketplace'))
                @include(EcommerceHelper::viewPath('includes.store-products'), ['product' => $product])
            @endif

            <!-- TABS SECTION (SIBLING OF HEADER) -->
            <div class="ps-product__content ps-tab-root">
                <!-- Product Information Title -->
                <div class="ps-product-info-header">
                    <h2>{{ __('Product Information') }}</h2>
                </div>
                
                <ul class="ps-tab-list">
                    <li class="active"><a href="#tab-description">{{ __('Product details') }}</a></li>
                    @if (EcommerceHelper::isProductSpecificationEnabled() && $product->specificationAttributes->where('pivot.hidden', false)->isNotEmpty())
                        <li><a href="#tab-specification">{{ __('Specification') }}</a></li>
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

            <!-- CUSTOMER REVIEW PHOTOS GALLERY -->
            @php
                // Get review images using the same method as the reviews tab
                $reviewImages = collect();
                
                if (EcommerceHelper::isReviewEnabled() && EcommerceHelper::isCustomerReviewImageUploadEnabled()) {
                    // Get reviews with images
                    $reviewsWithImages = \Botble\Ecommerce\Models\Review::query()
                        ->where('product_id', $product->id)
                        ->where('status', \Botble\Base\Enums\BaseStatusEnum::PUBLISHED)
                        ->whereNotNull('images')
                        ->with('user')
                        ->get();
                    
                    // Build image collection with customer info
                    foreach ($reviewsWithImages as $review) {
                        if (!empty($review->images)) {
                            $images = is_array($review->images) ? $review->images : json_decode($review->images, true);
                            if ($images && is_array($images)) {
                                foreach ($images as $image) {
                                    $reviewImages->push([
                                        'url' => RvMedia::getImageUrl($image),
                                        'thumb' => RvMedia::getImageUrl($image, 'thumb'),
                                        'review_id' => $review->id,
                                        'customer_name' => $review->display_name,
                                        'rating' => $review->star,
                                        'comment' => $review->comment,
                                    ]);
                                }
                            }
                        }
                    }
                }
                
                $totalImages = $reviewImages->count();
                $imagesPerPage = 24;
                $totalPages = $totalImages > 0 ? ceil($totalImages / $imagesPerPage) : 0;
            @endphp

            @if($totalImages > 0)
            <div class="ps-customer-photos-gallery">
                <div class="ps-container">
                    <div class="ps-block--customer-photos">
                        <div class="ps-block__header">
                            <div class="ps-block__header-left">
                                <h2>Customer Photos ({{ $totalImages }})</h2>
                                <a href="#tab-reviews" class="ps-btn--read-reviews" onclick="event.preventDefault(); document.getElementById('tab-reviews').scrollIntoView({behavior: 'smooth', block: 'start'});">
                                    Read Reviews
                                </a>
                            </div>
                        </div>
                        <div class="ps-block__content">
                            <div class="ps-photo-layout">
                                <!-- Main Photo Viewer (Left) -->
                                <div class="ps-photo-viewer">
                                    <button class="ps-photo-nav ps-photo-nav--prev" id="photoNavPrev">
                                        <i class="icon-chevron-left"></i>
                                    </button>
                                    <div class="ps-photo-viewer__main">
                                        <img src="{{ $reviewImages->first()['url'] }}" alt="Customer photo" id="mainPhotoImg" />
                                    </div>
                                    <a href="#" class="ps-photo-viewer__info" id="photoInfoLink">
                                        <div class="ps-photo-rating" id="photoRating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa {{ $i <= $reviewImages->first()['rating'] ? 'fa-star' : 'fa-star-o' }}"></i>
                                            @endfor
                                        </div>
                                        <p class="ps-photo-customer" id="photoCustomer">{{ $reviewImages->first()['customer_name'] }}</p>
                                    </a>
                                    <button class="ps-photo-nav ps-photo-nav--next" id="photoNavNext">
                                        <i class="icon-chevron-right"></i>
                                    </button>
                                </div>

                                <!-- Thumbnail Sidebar (Right) -->
                                <div class="ps-photo-sidebar">
                                    <a href="#ps-product__header" class="ps-photo-product-title" onclick="event.preventDefault(); document.querySelector('.ps-product__header').scrollIntoView({behavior: 'smooth', block: 'start'});">
                                        {{ $product->name }}
                                    </a>
                                    <div class="ps-photo-thumbnails">
                                        <div class="ps-photo-thumbnails__grid" id="thumbnailGrid">
                                            <!-- Thumbnails loaded via JavaScript -->
                                        </div>
                                        @if($totalPages > 1)
                                        <div class="ps-photo-pagination">
                                            <button class="ps-pagination-btn ps-pagination-btn--prev" id="thumbnailPrevPage" disabled>
                                                <i class="icon-chevron-left"></i> Previous
                                            </button>
                                            <span class="ps-pagination-info" id="paginationInfo">Page 1 of {{ $totalPages }}</span>
                                            <button class="ps-pagination-btn ps-pagination-btn--next" id="thumbnailNextPage" {{ $totalPages <= 1 ? 'disabled' : '' }}>
                                                Next <i class="icon-chevron-right"></i>
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            (function() {
                // Review images data
                const reviewImages = @json($reviewImages->values());
                const imagesPerPage = {{ $imagesPerPage }};
                const totalPages = {{ $totalPages }};
                
                let currentImageIndex = 0;
                let currentPage = 1;
                let loadedPages = new Set([1]); // Track which pages are loaded

                // Initialize
                document.addEventListener('DOMContentLoaded', function() {
                    loadThumbnailPage(1);
                    updateMainPhoto();
                    updateNavigationButtons();
                    setupEventListeners();
                });

                function setupEventListeners() {
                    // Main photo navigation
                    document.getElementById('photoNavPrev').addEventListener('click', () => {
                        if (reviewImages.length > 1) navigatePhoto(-1);
                    });
                    document.getElementById('photoNavNext').addEventListener('click', () => {
                        if (reviewImages.length > 1) navigatePhoto(1);
                    });

                    // Thumbnail pagination
                    const prevBtn = document.getElementById('thumbnailPrevPage');
                    const nextBtn = document.getElementById('thumbnailNextPage');
                    
                    if (prevBtn) prevBtn.addEventListener('click', () => changeThumbnailPage(-1));
                    if (nextBtn) nextBtn.addEventListener('click', () => changeThumbnailPage(1));

                    // Keyboard navigation
                    document.addEventListener('keydown', function(e) {
                        if (reviewImages.length <= 1) return;
                        if (e.key === 'ArrowLeft') navigatePhoto(-1);
                        if (e.key === 'ArrowRight') navigatePhoto(1);
                    });
                }

                function navigatePhoto(direction) {
                    if (reviewImages.length <= 1) return;
                    
                    currentImageIndex += direction;
                    
                    // Wrap around
                    if (currentImageIndex < 0) currentImageIndex = reviewImages.length - 1;
                    if (currentImageIndex >= reviewImages.length) currentImageIndex = 0;

                    // Check if we need to load a different page
                    const requiredPage = Math.floor(currentImageIndex / imagesPerPage) + 1;
                    if (requiredPage !== currentPage) {
                        currentPage = requiredPage;
                        if (!loadedPages.has(currentPage)) {
                            loadThumbnailPage(currentPage);
                        }
                        updatePaginationUI();
                    }

                    updateMainPhoto();
                    updateThumbnailActive();
                    updateNavigationButtons();
                }

                function updateNavigationButtons() {
                    const prevBtn = document.getElementById('photoNavPrev');
                    const nextBtn = document.getElementById('photoNavNext');
                    
                    // If only one image, disable and hide both
                    if (reviewImages.length <= 1) {
                        prevBtn.disabled = true;
                        nextBtn.disabled = true;
                        prevBtn.style.display = 'none';
                        nextBtn.style.display = 'none';
                    } else {
                        // Show buttons
                        prevBtn.style.display = 'flex';
                        nextBtn.style.display = 'flex';
                        
                        // Disable buttons at boundaries (no wrap)
                        prevBtn.disabled = currentImageIndex === 0;
                        nextBtn.disabled = currentImageIndex === reviewImages.length - 1;
                    }
                }

                function updateMainPhoto() {
                    const photo = reviewImages[currentImageIndex];
                    const mainImg = document.getElementById('mainPhotoImg');
                    const rating = document.getElementById('photoRating');
                    const customer = document.getElementById('photoCustomer');

                    mainImg.src = photo.url;
                    customer.textContent = photo.customer_name;

                    // Update rating stars
                    let starsHtml = '';
                    for (let i = 1; i <= 5; i++) {
                        starsHtml += `<i class="fa ${i <= photo.rating ? 'fa-star' : 'fa-star-o'}"></i>`;
                    }
                    rating.innerHTML = starsHtml;
                }

                function loadThumbnailPage(page) {
                    const startIdx = (page - 1) * imagesPerPage;
                    const endIdx = Math.min(startIdx + imagesPerPage, reviewImages.length);
                    const grid = document.getElementById('thumbnailGrid');

                    // Clear grid if loading a different page
                    if (page !== currentPage || !loadedPages.has(page)) {
                        grid.innerHTML = '';
                    }

                    for (let i = startIdx; i < endIdx; i++) {
                        const photo = reviewImages[i];
                        const thumb = document.createElement('div');
                        thumb.className = 'ps-photo-thumbnail' + (i === currentImageIndex ? ' active' : '');
                        thumb.dataset.index = i;
                        thumb.innerHTML = `<img src="${photo.thumb || photo.url}" alt="Customer photo" loading="lazy" />`;
                        thumb.addEventListener('click', () => {
                            currentImageIndex = i;
                            updateMainPhoto();
                            updateThumbnailActive();
                            updateNavigationButtons();
                        });
                        grid.appendChild(thumb);
                    }

                    loadedPages.add(page);
                }

                function changeThumbnailPage(direction) {
                    const newPage = currentPage + direction;
                    if (newPage < 1 || newPage > totalPages) return;

                    currentPage = newPage;
                    if (!loadedPages.has(currentPage)) {
                        loadThumbnailPage(currentPage);
                    } else {
                        // Just update visibility if already loaded
                        updateThumbnailVisibility();
                    }
                    updatePaginationUI();
                }

                function updateThumbnailVisibility() {
                    const startIdx = (currentPage - 1) * imagesPerPage;
                    const endIdx = startIdx + imagesPerPage;
                    
                    document.querySelectorAll('.ps-photo-thumbnail').forEach((thumb, idx) => {
                        const thumbIdx = parseInt(thumb.dataset.index);
                        thumb.style.display = (thumbIdx >= startIdx && thumbIdx < endIdx) ? 'block' : 'none';
                    });
                }

                function updateThumbnailActive() {
                    document.querySelectorAll('.ps-photo-thumbnail').forEach(thumb => {
                        thumb.classList.toggle('active', parseInt(thumb.dataset.index) === currentImageIndex);
                    });
                }

                function updatePaginationUI() {
                    const prevBtn = document.getElementById('thumbnailPrevPage');
                    const nextBtn = document.getElementById('thumbnailNextPage');
                    const info = document.getElementById('paginationInfo');

                    if (prevBtn) prevBtn.disabled = currentPage === 1;
                    if (nextBtn) nextBtn.disabled = currentPage === totalPages;
                    if (info) info.textContent = `Page ${currentPage} of ${totalPages}`;
                }
            })();
            </script>

            <style>
                /* Customer Photos Gallery */
                .ps-customer-photos-gallery {
                    margin: 0 0 30px 0;
                    background: var(--global-color-site-bg);
                }

                .ps-block--customer-photos {
                    border: none;
                    border-radius: 0;
                    padding: 0;
                }

                .ps-block--customer-photos .ps-block__header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 20px;
                    padding: 0 0 10px 0;
                    flex-wrap: wrap;
                    gap: 15px;
                }

                .ps-block--customer-photos .ps-block__header-left {
                    display: flex;
                    align-items: center;
                    gap: 15px;
                }

                .ps-block--customer-photos .ps-block__header h2 {
                    font-size: 22px;
                    font-weight: 700;
                    color: var(--global-color-text-main);
                    line-height: 1.3;
                    margin: 0;
                    padding: 0;
                }

                @media (max-width: 767px) {
                    .ps-block--customer-photos .ps-block__header {
                        padding: 0 0 10px 0 !important;
                        margin: 0 !important;
                    }

                    .ps-block--customer-photos .ps-block__header-left {
                        flex-wrap: wrap;
                    }

                    .ps-block--customer-photos .ps-block__header h2 {
                        font-size: 20px;
                        word-break: break-word;
                        overflow-wrap: break-word;
                    }
                }

                .ps-btn--read-reviews {
                    font-size: 14px;
                    font-weight: 500;
                    color: var(--global-color-link) !important;
                    text-decoration: none !important;
                    padding: 0 !important;
                    border: none !important;
                    border-radius: 0 !important;
                    background: transparent !important;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .ps-btn--read-reviews:hover {
                    text-decoration: underline !important;
                    color: var(--global-color-link-hover) !important;
                    background: transparent !important;
                }

                /* Photo Layout - Two Column */
                .ps-photo-layout {
                    display: flex;
                    gap: 0;
                    align-items: stretch;
                }

                /* Main Photo Viewer - Left Side */
                .ps-photo-viewer {
                    position: relative;
                    flex: 1;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: #f8f8f8;
                    border-radius: 8px 0 0 8px;
                    overflow: hidden;
                    min-height: 400px;
                }

                .ps-photo-viewer__main {
                    position: relative;
                    width: 100%;
                    max-width: 800px;
                    aspect-ratio: 4/3;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .ps-photo-viewer__main img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                }

                .ps-photo-viewer__info {
                    position: absolute;
                    bottom: 15px;
                    left: 15px;
                    background: rgba(0, 0, 0, 0.75) !important;
                    color: var(--global-color-text-on-dark) !important;
                    padding: 10px 15px;
                    border-radius: 4px !important;
                    z-index: 5;
                    text-decoration: none;
                    display: block;
                    transition: all 0.3s ease;
                    backdrop-filter: blur(4px);
                }

                .ps-photo-viewer__info:hover {
                    opacity: 0.95;
                    background: rgba(0, 0, 0, 0.85) !important;
                }

                .ps-photo-rating {
                    margin-bottom: 5px;
                }

                .ps-photo-rating i {
                    color: var(--global-color-primary) !important;
                    font-size: 14px;
                    margin-right: 2px;
                }

                .ps-photo-customer {
                    margin: 0;
                    font-size: 13px;
                    font-weight: 500;
                    color: var(--global-color-text-on-dark) !important;
                }

                .ps-photo-nav {
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 45px;
                    height: 45px;
                    background: rgba(255, 255, 255, 0.95);
                    border: 1px solid var(--global-color-border-strong) !important;
                    border-radius: 6px !important;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    z-index: 10;
                }

                .ps-photo-nav:hover {
                    background: var(--global-color-card-bg) !important;
                    color: inherit;
                    border-color: var(--global-color-border-strong) !important;
                }

                .ps-photo-nav:disabled {
                    opacity: 0.4;
                    cursor: not-allowed;
                    pointer-events: none;
                }

                .ps-photo-nav:disabled:hover {
                    background: rgba(255, 255, 255, 0.95) !important;
                }

                .ps-photo-nav--prev {
                    left: 15px;
                }

                .ps-photo-nav--next {
                    right: 15px;
                }

                .ps-photo-nav i {
                    font-size: 20px;
                }

                /* Thumbnail Sidebar - Right Side */
                .ps-photo-sidebar {
                    width: 320px;
                    flex-shrink: 0;
                    display: flex;
                    flex-direction: column;
                    border: 1px solid var(--global-color-primary-border);
                    border-left: none;
                    border-radius: 0 8px 8px 0;
                    padding: 15px;
                    background: var(--global-color-site-bg);
                }

                /* Product Title Link */
                .ps-photo-product-title {
                    font-size: 1rem;
                    font-weight: 400;
                    color: var(--global-color-text-main);
                    text-decoration: none;
                    margin-bottom: 15px;
                    display: block;
                    line-height: 1.4;
                    transition: all 0.3s ease;
                }

                .ps-photo-product-title:hover {
                    text-decoration: underline;
                    color: var(--global-color-text-main);
                }

                /* Thumbnail Grid */
                .ps-photo-thumbnails {
                    margin: 0;
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                }

                .ps-photo-thumbnails__grid {
                    display: grid;
                    grid-template-columns: repeat(5, 1fr);
                    gap: 8px;
                    margin-bottom: 15px;
                }

                .ps-photo-thumbnail {
                    aspect-ratio: 3/4;
                    border-radius: 4px;
                    overflow: hidden;
                    cursor: pointer;
                    border: 2px solid transparent;
                    transition: border-color 0.3s ease;
                }

                .ps-photo-thumbnail:hover {
                    border-color: var(--global-color-link);
                }

                .ps-photo-thumbnail.active {
                    border-color: var(--global-color-primary);
                }

                .ps-photo-thumbnail img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                /* Responsive Layout */
                @media (max-width: 1024px) {
                    .ps-photo-layout {
                        flex-direction: column;
                    }

                    .ps-photo-viewer {
                        width: 100%;
                        border-radius: 8px 8px 0 0;
                    }

                    .ps-photo-sidebar {
                        width: 100%;
                        border-left: 1px solid var(--global-color-primary-border);
                        border-radius: 0 0 8px 8px;
                        margin-top: 0;
                    }

                    .ps-photo-thumbnails__grid {
                        display: flex;
                        flex-direction: row;
                        grid-template-columns: none;
                        overflow-x: auto;
                        overflow-y: hidden;
                        scroll-snap-type: x mandatory;
                        -webkit-overflow-scrolling: touch;
                        scrollbar-width: thin;
                        scrollbar-color: var(--global-color-primary) var(--global-color-border);
                        padding-bottom: 5px;
                    }

                    .ps-photo-thumbnails__grid::-webkit-scrollbar {
                        height: 6px;
                    }

                    .ps-photo-thumbnails__grid::-webkit-scrollbar-track {
                        background: var(--global-color-border);
                        border-radius: 3px;
                    }

                    .ps-photo-thumbnails__grid::-webkit-scrollbar-thumb {
                        background: var(--global-color-primary);
                        border-radius: 3px;
                    }

                    .ps-photo-thumbnail {
                        flex: 0 0 auto;
                        width: calc((100% - 40px) / 6);
                        scroll-snap-align: start;
                    }
                }

                @media (max-width: 767px) {
                    .ps-photo-thumbnail {
                        width: calc((100% - 32px) / 5);
                    }

                    .ps-photo-nav {
                        width: 35px !important;
                        height: 35px !important;
                    }

                    .ps-photo-nav i {
                        font-size: 16px;
                    }
                }

                @media (max-width: 480px) {
                    .ps-photo-thumbnail {
                        width: calc((100% - 16px) / 3);
                    }
                }

                /* Pagination */
                .ps-photo-pagination {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 20px;
                    margin-top: 20px;
                }

                .ps-pagination-btn {
                    padding: 8px 16px;
                    background: var(--global-color-primary);
                    color: #fff;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: 500;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 8px;
                }

                .ps-pagination-btn:hover:not(:disabled) {
                    background: var(--global-color-primary-hover);
                    transform: translateY(-2px);
                }

                .ps-pagination-btn:disabled {
                    background: #ccc;
                    cursor: not-allowed;
                    opacity: 0.6;
                }

                .ps-pagination-info {
                    font-size: 14px;
                    color: var(--global-color-text-main);
                    font-weight: 500;
                }
            </style>
            @endif
            <!-- END: Customer Photos Gallery -->

            <style>
                /* Browsing History Section - Remove all margins */
                .ps-product__browsing-history {
                    margin: 0 !important;
                }
            </style>

            <!-- BROWSING HISTORY SECTION -->
            <div class="ps-product__browsing-history">
                {!! shortcode()->compile('[recently-viewed-products title="Your browsing history" enable_caching="no" enable_lazy_loading="no"][/recently-viewed-products]') !!}
            </div>
            <!-- END: Browsing History -->

            <!-- SIMILAR CUSTOMERS RECOMMENDATIONS SECTION -->
            <div class="ps-product__similar-customers">
                {!! shortcode()->compile('[similar-customers-recommendations enable_caching="no" enable_lazy_loading="no"][/similar-customers-recommendations]') !!}
            </div>
            <!-- END: Similar Customers Recommendations -->

            <!-- REVIEWS SECTION -->
            @if (EcommerceHelper::isReviewEnabled())
                <div class="ps-product__reviews" id="tab-reviews">
                    <div class="ps-container">
                        <div class="ps-block--reviews">
                            <div class="ps-block__header">
                                <h2>{{ __('Customer Reviews') }}</h2>
                            </div>
                            <div class="ps-block__content">
                                @include('plugins/ecommerce::themes.includes.reviews', ['reviewButtonClass' => 'ps-btn'])
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- END: Reviews -->

            <style>
                /* Reviews Section */
                .ps-product__reviews {
                    margin: 0 !important;
                    background: var(--global-color-site-bg);
                }

                .ps-product__reviews .ps-container {
                    padding: 0 !important;
                    margin: 0 !important;
                    border-bottom: none !important;
                }

                .ps-block--reviews {
                    border: none;
                    border-radius: 0;
                    padding: 0;
                }

                .ps-block--reviews .ps-block__header {
                    display: flex;
                    align-items: center;
                    justify-content: flex-start;
                    margin-bottom: 20px;
                    padding: 0 0 10px 0;
                    flex-wrap: nowrap;
                    gap: 15px;
                }

                .ps-block--reviews .ps-block__header h2 {
                    font-size: 22px;
                    font-weight: 700;
                    color: var(--global-color-text-main);
                    line-height: 1.3;
                    margin: 0;
                    padding: 0;
                    flex-shrink: 0;
                }

                @media (max-width: 767px) {
                    .ps-block--reviews .ps-block__header {
                        flex-wrap: wrap;
                        padding: 0 0 10px 0 !important;
                    }

                    .ps-block--reviews .ps-block__header h2 {
                        font-size: 20px;
                        word-break: break-word;
                        overflow-wrap: break-word;
                        max-width: 100%;
                    }
                }

                .ps-block--reviews .ps-block__content {
                    margin: 0;
                }
            </style>

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
    
    // Initialize Bootstrap tooltips for delivery info
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>

<style>
    /* Product Page Layout Fixes */
    
    /* ps-container: Remove padding, add bottom spacing and border */
    .ps-page--product .ps-container {
        padding: 0 !important;
        margin-bottom: 15px;
        border-bottom: 1px solid var(--global-color-border-strong);
        padding-bottom: 15px !important;
    }
    
    /* ps-product__content: Add spacing and border */
    .ps-product__content {
        padding: 0 !important;
        margin-bottom: 15px;
        border-bottom: 1px solid var(--global-color-border-strong);
        padding-bottom: 15px !important;
    }
    
    /* Force remove margin from price-desc divs */
    .price-desc > div {
        margin: 0 !important;
    }
</style>


@include(Theme::getThemeNamespace('views.ecommerce.includes.location-modal'))

{{-- Include location.js plugin for cascading dropdowns --}}
<script src="{{ asset('vendor/core/plugins/location/js/location.js') }}?v={{ cache_bust() }}"></script>
<script src="{{ Theme::asset()->url('js/location-modal.js') }}?v={{ cache_bust() }}"></script>

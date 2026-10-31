@php
    /**
     * Enhanced Product Card Component
     * Uses ec-card prefix for all classes to avoid conflicts with theme CSS.
     *
     * @param Product $product
     * @param string  $layout   'inline' | 'flyout'
     * @param bool    $lazy
     * @param bool    $showSaleDuration
     * @param bool    $showSeller
     * @param bool    $showFloatingActions
     * @param string  $additionalClass
     */
    $layout              = $layout              ?? 'default';
    $lazy                = $lazy                ?? true;
    $showSaleDuration    = $showSaleDuration    ?? true;
    $showSeller          = $showSeller          ?? true;
    $showFloatingActions = $showFloatingActions ?? true;
    $additionalClass     = $additionalClass     ?? '';

    $layoutClass = match($layout) {
        'inline'  => 'ec-card--inline',
        default   => 'ec-card--flyout',
    };
@endphp

@if ($product && is_object($product))
    @php
        $soldCount = 0;
        if ($product->latestFlashSales && $product->latestFlashSales->first()) {
            $soldCount = $product->latestFlashSales->first()->pivot->sold ?? 0;
        }
        if ($soldCount == 0) {
            try {
                $soldCount = \Botble\Ecommerce\Models\OrderProduct::where('product_id', $product->id)
                    ->whereHas('order', fn($q) => $q->whereIn('status', [\Botble\Ecommerce\Enums\OrderStatusEnum::COMPLETED]))
                    ->sum('qty');
            } catch (\Exception $e) { $soldCount = 0; }
        }

        $discountPercentage = get_sale_percentage($product->price, $product->front_sale_price);
        $store = $product->store ?? null;

        $saleDurationText    = '';
        $saleDurationTooltip = '';
        if ($showSaleDuration && $product->front_sale_price !== $product->price) {
            if ($product->sale_type == 1 && $product->end_date) {
                $daysLeft = (int) ceil(\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($product->end_date), false));
                if ($daysLeft > 7) {
                    $saleDurationText    = 'Deal ends in ' . $daysLeft . ' days';
                    $saleDurationTooltip = 'This price won\'t last — only ' . $daysLeft . ' days left at this rate. Once it\'s gone, it\'s gone.';
                } elseif ($daysLeft > 1) {
                    $saleDurationText    = 'Only ' . $daysLeft . ' days left';
                    $saleDurationTooltip = 'Moving fast — just ' . $daysLeft . ' days remain at this price. Others are watching this too.';
                } elseif ($daysLeft == 1) {
                    $saleDurationText    = 'Ends tomorrow';
                    $saleDurationTooltip = 'Last chance — this price expires tomorrow. Order now to lock it in.';
                } elseif ($daysLeft == 0) {
                    $saleDurationText    = 'Ends today';
                    $saleDurationTooltip = 'Final hours — this deal closes today. Don\'t miss out.';
                } else {
                    $saleDurationText    = 'Offer ended';
                    $saleDurationTooltip = 'This promotion has expired.';
                }
            } else {
                $saleDurationText    = 'Limited time offer';
                $saleDurationTooltip = 'Reduced for a limited period only — this price may change without notice.';
            }
        }
    @endphp

    <div class="ec-card {{ $layoutClass }} {{ $additionalClass }}">
        {{-- Invisible full-card link overlay --}}
        <a href="{{ $product->url }}" class="ec-card__overlay-link" tabindex="-1" aria-hidden="true"></a>

        {{-- Thumbnail --}}
        <div class="ec-card__thumb">
            <a href="{{ $product->url }}" title="{{ $product->name }}">
                {!! RvMedia::image($product->image, $product->name, 'small', lazy: $lazy) !!}
            </a>

            {{-- Badges --}}
            @if ($product->isOutOfStock())
                <div class="ec-card__badges">
                    <span class="ec-card__badge ec-card__badge--out">{{ __('Out Of Stock') }}</span>
                </div>
            @elseif ($product->productLabels->isNotEmpty())
                <div class="ec-card__badges">
                    @foreach ($product->productLabels as $label)
                        <span class="ec-card__badge" {!! $label->css_styles !!}>{{ $label->name }}</span>
                    @endforeach
                </div>
            @endif

            {{-- Floating actions (flyout layout only) --}}
            @if (!$product->isOutOfStock() && $showFloatingActions && $layout !== 'inline')
                <div class="ec-card__float">
                    <div class="ec-card__float-capsule">
                        <button type="button" class="ec-card__float-btn js-quick-view-button"
                                @if($product->has_variation)
                                    data-bb-toggle="quick-shop"
                                    data-url="{{ route('public.ajax.quick-shop', $product->slug) }}"
                                    {!! EcommerceHelper::jsAttributes('quick-shop', $product) !!}
                                @else
                                    data-url="{{ route('public.ajax.quick-view', $product->id) }}"
                                @endif
                                title="{{ __('Quick View') }}">
                            <i class="ph ph-eye"></i>
                        </button>
                        @if (EcommerceHelper::isWishlistEnabled())
                            <button type="button" class="ec-card__float-btn ec-card__float-btn--wishlist js-add-to-wishlist-button"
                                    data-url="{{ route('public.wishlist.add', $product->id) }}"
                                    data-product-id="{{ $product->id }}"
                                    title="{{ __('Add to Wishlist') }}">
                                <i class="ph ph-heart"></i>
                            </button>
                        @endif
                        @if (EcommerceHelper::isCompareEnabled())
                            <button type="button" class="ec-card__float-btn ec-card__float-btn--compare js-add-to-compare-button"
                                    data-url="{{ route('public.compare.add', $product->id) }}"
                                    data-product-id="{{ $product->id }}"
                                    title="{{ __('Compare') }}">
                                <i class="ph ph-chart-bar"></i>
                            </button>
                        @endif
                        @if (EcommerceHelper::isCartEnabled())
                            @if ($product->has_variation)
                                <button type="button" class="ec-card__float-btn ec-card__float-btn--cart"
                                        data-bb-toggle="quick-shop"
                                        data-url="{{ route('public.ajax.quick-shop', $product->slug) }}"
                                        data-product-id="{{ $product->id }}"
                                        {!! EcommerceHelper::jsAttributes('quick-shop', $product) !!}
                                        title="{{ __('Select Options') }}">
                                    <i class="ph ph-shopping-cart"></i>
                                </button>
                            @else
                                <button type="button" class="ec-card__float-btn ec-card__float-btn--cart"
                                        data-id="{{ $product->id }}"
                                        data-product-id="{{ $product->id }}"
                                        data-url="{{ route('public.cart.add-to-cart') }}"
                                        data-show-toast-on-success="false"
                                        {!! EcommerceHelper::jsAttributes('add-to-cart', $product) !!}
                                        title="{{ __('Add To Cart') }}">
                                    <i class="ph ph-shopping-cart"></i>
                                </button>
                            @endif
                        @endif
                        <button type="button" class="ec-card__float-toggle">
                            <span class="ec-card__toggle-icon">
                                <span class="ec-card__toggle-line ec-card__toggle-h"></span>
                                <span class="ec-card__toggle-line ec-card__toggle-v"></span>
                            </span>
                        </button>
                    </div>
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="ec-card__body">
            <div class="ec-card__content">

                {{-- 1. Price row (discount badge + original price) --}}
                @if ($product->front_sale_price !== $product->price)
                    <div class="ec-card__price-row">
                        <span class="ec-card__badge-discount">{{ $discountPercentage }}</span>
                        <del class="ec-card__price-original">{{ format_price($product->price) }}</del>
                    </div>
                @endif

                {{-- 2. Sale duration --}}
                @if ($showSaleDuration && $product->front_sale_price !== $product->price && $saleDurationText)
                    <div class="ec-card__sale-duration">
                        <span class="ec-card__duration-text">{{ $saleDurationText }}</span>
                        <span class="ec-card__info-icon" data-tooltip="{{ $saleDurationTooltip }}">
                            <i class="icon-information"></i>
                        </span>
                    </div>
                @endif

                {{-- 3. Rating (stars + review count) --}}
                <div class="ec-card__rating-wrapper">
                    <a href="{{ $product->url }}#tab-reviews" class="ec-card__rating"
                       onclick="sessionStorage.setItem('scrollToReviews','true');">
                        <span class="ec-card__stars">
                            @php $avgRating = $product->reviews_count > 0 ? $product->reviews_avg : 0; @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($avgRating))
                                    <i class="ph-fill ph-star"></i>
                                @elseif ($i - 0.5 <= $avgRating)
                                    <i class="ph-fill ph-star-half"></i>
                                @else
                                    <i class="ph ph-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="ec-card__rating-count">{{ number_format($product->reviews_count) }}</span>
                    </a>
                </div>

                {{-- 4. View Count (separate line) --}}
                <div class="ec-card__view-count">
                    {{ number_format($product->reviews_count) }} viewed in past month
                </div>

                {{-- 5. Sale price --}}
                <div class="ec-card__price-sale">
                    <span class="ec-card__price-amount">{{ format_price($product->front_sale_price !== 0 ? $product->front_sale_price : $product->price) }}</span>
                    @if ($soldCount > 0)
                        <span class="ec-card__sold">+{{ number_format($soldCount) }} sold</span>
                    @endif
                </div>

                {{-- 6. Title --}}
                <a class="ec-card__title" href="{{ $product->url }}" title="{{ $product->name }}">
                    {!! BaseHelper::clean($product->name) !!}
                </a>

                {{-- 7. Delivery Info --}}
                @if (!$product->isOutOfStock())
                    @php
                        // EXACT SAME LOGIC AS PRODUCT DETAIL PAGE (product.blade.php)
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
                        
                        // NOW EXTRACT COUNTRY CODE FROM ADDRESS (same as delivery-address-display.blade.php)
                        $countryCode = null;
                        if ($displayAddress) {
                            if ($displayType === 'temporary') {
                                // For temporary addresses, get country code from address data
                                $countryCode = \App\Helpers\DeliveryHelper::getCountryCodeById($displayAddress->country ?? null);
                            } else {
                                // For saved addresses, get country code from address model
                                if (isset($displayAddress->country) && is_numeric($displayAddress->country)) {
                                    $countryCode = \App\Helpers\DeliveryHelper::getCountryCodeById($displayAddress->country);
                                }
                            }
                        } else {
                            // No address at all - try GeoIP then fallback
                            try {
                                if (function_exists('geoip')) {
                                    $geoip = geoip(request()->ip());
                                    $countryCode = $geoip->iso_code ?? null;
                                } elseif (class_exists('\Stevebauman\Location\Facades\Location')) {
                                    $position = \Stevebauman\Location\Facades\Location::get(request()->ip());
                                    $countryCode = $position ? $position->countryCode : null;
                                }
                            } catch (\Exception $e) {
                                // Silent fail
                            }
                            
                            // Final fallback: Default country from settings
                            if (!$countryCode) {
                                $defaultCountryId = \Botble\Ecommerce\Facades\EcommerceHelper::getFirstCountryId();
                                $countryCode = \App\Helpers\DeliveryHelper::getCountryCodeById($defaultCountryId);
                            }
                        }
                        
                        // Calculate delivery date based on country and product
                        // Pass FULL product object, not just ID
                        $deliveryDate = \App\Helpers\DeliveryHelper::getEstimatedDeliveryDate($countryCode, $product);
                        $dayName = $deliveryDate->format('l');
                        $monthDay = $deliveryDate->format('M j');
                        
                        // Check if free shipping is available globally or by weight
                        $shippingInfo = null;
                        $weight = $product->weight ?? 0;
                        
                        // Check for free shipping rules
                        $hasFreeShipping = false;
                        if (get_ecommerce_setting('free_shipping_enabled', false)) {
                            $hasFreeShipping = true;
                        } else {
                            // Check if product weight qualifies for free shipping based on default rules
                            try {
                                $defaultCountry = \Botble\Ecommerce\Facades\EcommerceHelper::getFirstCountryId();
                                $shipping = \Botble\Ecommerce\Models\Shipping::query()
                                    ->whereNull('country')
                                    ->orWhere('country', $defaultCountry)
                                    ->first();
                                
                                if ($shipping) {
                                    // Check if any rule offers free shipping
                                    $freeRule = \Botble\Ecommerce\Models\ShippingRule::query()
                                        ->where('shipping_id', $shipping->id)
                                        ->where('price', 0)
                                        ->first();
                                    
                                    if ($freeRule) {
                                        $hasFreeShipping = true;
                                    }
                                }
                            } catch (\Exception $e) {
                                // If shipping check fails, show generic shipping info
                            }
                        }
                        
                        if ($hasFreeShipping) {
                            $shippingInfo = 'Free Delivery';
                        } else {
                            // Show generic shipping info since we can't calculate exact fee without location
                            $shippingInfo = 'Shipping charges apply';
                        }
                    @endphp
                    <div class="ec-card__delivery">
                        <div class="ec-card__delivery-date">Get it as soon as <strong>{{ $dayName }}, {{ $monthDay }}</strong></div>
                        {{-- 8. Delivery Charge --}}
                        <div class="ec-card__delivery-charge">{{ $shippingInfo }}</div>
                    </div>
                @endif

                {{-- Seller --}}
                @if ($showSeller && $store && $store->name)
                    <a href="{{ $store->url }}" class="ec-card__seller">
                        {{ __('Shop on') }} {{ $store->name }}
                    </a>
                @endif

                {{-- Action button --}}
                @if (EcommerceHelper::isCartEnabled() && !$product->isOutOfStock())
                    <div class="ec-card__actions">
                        @if ($product->has_variation)
                            <button type="button" class="ec-card__btn ec-card__btn--select"
                                    data-bb-toggle="quick-shop"
                                    data-url="{{ route('public.ajax.quick-shop', $product->slug) }}"
                                    {!! EcommerceHelper::jsAttributes('quick-shop', $product) !!}>
                                {{ __('Select Options') }}
                            </button>
                        @else
                            <button type="button" class="ec-card__btn ec-card__btn--cart"
                                    data-id="{{ $product->id }}"
                                    data-slug="{{ $product->slug }}"
                                    data-url="{{ route('public.cart.add-to-cart') }}"
                                    data-show-toast-on-success="false"
                                    {!! EcommerceHelper::jsAttributes('add-to-cart', $product) !!}>
                                {{ __('Add to Cart') }}
                            </button>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
@endif

{{-- CSS loaded globally via header-meta.blade.php (public/css/product-card-enhanced.css) --}}
{{-- JS loaded globally via config.php (js/product-card-enhanced.js) --}}

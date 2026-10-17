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
                                data-url="{{ route('public.ajax.quick-view', $product->id) }}"
                                title="{{ __('Quick View') }}">
                            <i class="icon-eye"></i>
                        </button>
                        @if (EcommerceHelper::isWishlistEnabled())
                            <button type="button" class="ec-card__float-btn js-add-to-wishlist-button"
                                    data-url="{{ route('public.wishlist.add', $product->id) }}"
                                    title="{{ __('Add to Wishlist') }}">
                                <i class="icon-heart"></i>
                            </button>
                        @endif
                        @if (EcommerceHelper::isCompareEnabled())
                            <button type="button" class="ec-card__float-btn js-add-to-compare-button"
                                    data-url="{{ route('public.compare.add', $product->id) }}"
                                    title="{{ __('Compare') }}">
                                <i class="icon-chart-bars"></i>
                            </button>
                        @endif
                        @if (EcommerceHelper::isCartEnabled())
                            @if ($product->has_variation)
                                <button type="button" class="ec-card__float-btn"
                                        data-bb-toggle="quick-shop"
                                        data-url="{{ route('public.ajax.quick-shop', $product->slug) }}"
                                        {!! EcommerceHelper::jsAttributes('quick-shop', $product) !!}
                                        title="{{ __('Select Options') }}">
                                    <i class="icon-bag2"></i>
                                </button>
                            @else
                                <button type="button" class="ec-card__float-btn"
                                        data-id="{{ $product->id }}"
                                        data-url="{{ route('public.cart.add-to-cart') }}"
                                        {!! EcommerceHelper::jsAttributes('add-to-cart', $product) !!}
                                        title="{{ __('Add To Cart') }}">
                                    <i class="icon-bag2"></i>
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

                {{-- Price row --}}
                @if ($product->front_sale_price !== $product->price)
                    <div class="ec-card__price-row">
                        <span class="ec-card__badge-discount">{{ $discountPercentage }}</span>
                        <del class="ec-card__price-original">{{ format_price($product->price) }}</del>
                    </div>
                @endif

                {{-- Sale duration --}}
                @if ($showSaleDuration && $product->front_sale_price !== $product->price && $saleDurationText)
                    <div class="ec-card__sale-duration">
                        <span class="ec-card__duration-text">{{ $saleDurationText }}</span>
                        <span class="ec-card__info-icon" data-tooltip="{{ $saleDurationTooltip }}">
                            <i class="icon-information"></i>
                        </span>
                    </div>
                @endif

                {{-- Sale price --}}
                <div class="ec-card__price-sale">
                    <span class="ec-card__price-amount">{{ format_price($product->front_sale_price !== 0 ? $product->front_sale_price : $product->price) }}</span>
                    @if ($soldCount > 0)
                        <span class="ec-card__sold">+{{ number_format($soldCount) }} sold</span>
                    @endif
                </div>

                {{-- Title --}}
                <a class="ec-card__title" href="{{ $product->url }}" title="{{ $product->name }}">
                    {!! BaseHelper::clean($product->name) !!}
                </a>

                {{-- Rating --}}
                <div class="ec-card__rating-wrapper">
                    <a href="{{ $product->url }}#tab-reviews" class="ec-card__rating"
                       onclick="sessionStorage.setItem('scrollToReviews','true');">
                        <span class="ec-card__stars">
                            @php $avgRating = $product->reviews_count > 0 ? $product->reviews_avg : 0; @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($avgRating))
                                    <i class="icon-star ec-card__star--filled"></i>
                                @elseif ($i - 0.5 <= $avgRating)
                                    <i class="icon-star ec-card__star--half"></i>
                                @else
                                    <i class="icon-star"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="ec-card__rating-num">{{ $product->reviews_count > 0 ? number_format($product->reviews_avg, 1) : '0.0' }}</span>
                    </a>
                    <span class="ec-card__rating-count">{{ number_format($product->reviews_count) }}+ viewed in past month</span>
                </div>

                {{-- Sale duration info --}}
                @if ($showSaleDuration && $product->front_sale_price !== $product->price && $saleDurationText)
                    <div class="ec-card__sale-duration">
                        <span class="ec-card__duration-text">{{ $saleDurationText }}</span>
                        <span class="ec-card__info-icon" data-tooltip="{{ $saleDurationTooltip }}">
                            <i class="icon-information"></i>
                        </span>
                    </div>
                @endif

                {{-- Delivery Message with Shipping Info --}}
                @if (!$product->isOutOfStock())
                    @php
                        $minDays = 5;
                        $maxDays = 7;
                        $deliveryDate = \Carbon\Carbon::now()->addDays(rand($minDays, $maxDays));
                        $dayName = $deliveryDate->format('l');
                        $monthDay = $deliveryDate->format('M j');
                        
                        // Get shipping info
                        $shippingInfo = 'Free Delivery';
                        if ($product->shipping_cost > 0) {
                            $shippingInfo = format_price($product->shipping_cost) . ' Delivery';
                        }
                    @endphp
                    <div class="ec-card__delivery">
                        <div class="ec-card__delivery-date">Get it as soon as <strong>{{ $dayName }}, {{ $monthDay }}</strong></div>
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
                                    data-url="{{ route('public.cart.add-to-cart') }}"
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

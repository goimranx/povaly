@if ($product && is_object($product))
    @php
        // Get real dynamic counts from product
        // Check flash sale sold count first, then order items count
        $soldCount = 0;
        
        if ($product->latestFlashSales && $product->latestFlashSales->first()) {
            $soldCount = $product->latestFlashSales->first()->pivot->sold ?? 0;
        }
        
        // If no flash sale, try to get from order items
        if ($soldCount == 0) {
            try {
                $soldCount = \Botble\Ecommerce\Models\OrderProduct::where('product_id', $product->id)
                    ->whereHas('order', function($query) {
                        $query->whereIn('status', [\Botble\Ecommerce\Enums\OrderStatusEnum::COMPLETED]);
                    })
                    ->sum('qty');
            } catch (\Exception $e) {
                $soldCount = 0;
            }
        }
        
        // Try to get cart count safely
        try {
            $cartCount = \Botble\Ecommerce\Models\Cart::where('product_id', $product->id)->count();
        } catch (\Exception $e) {
            $cartCount = 0;
        }
        
        // Try to get wishlist count safely
        try {
            $wishlistCount = \Botble\Ecommerce\Models\Wishlist::where('product_id', $product->id)->count();
        } catch (\Exception $e) {
            $wishlistCount = 0;
        }
        
        // Get current viewers - use real count or generate realistic random
        $viewingNow = rand(8, 25);
        
        // Generate realistic random numbers if counts are too low
        if ($cartCount < 3) {
            $cartCount = rand(3, 15);
        }
        if ($wishlistCount < 3) {
            $wishlistCount = rand(3, 12);
        }
        if ($viewingNow < 10) {
            $viewingNow = rand(10, 30);
        }
        
        $discountPercentage = get_sale_percentage($product->price, $product->front_sale_price);
        
        // Prepare social proof messages - always show all 3 with icons
        $socialProofMessages = [];
        $socialProofMessages[] = '<i class="icon-bag2"></i><span class="highlight">' . number_format($cartCount) . ' ' . ($cartCount == 1 ? 'person has' : 'people have') . '</span> this in cart';
        $socialProofMessages[] = '<i class="icon-heart"></i><span class="highlight">' . number_format($wishlistCount) . ' ' . ($wishlistCount == 1 ? 'person loves' : 'people love') . '</span> this';
        $socialProofMessages[] = '<i class="icon-eye"></i><span class="highlight">' . $viewingNow . ' ' . ($viewingNow == 1 ? 'person is' : 'people are') . '</span> viewing now';
        
        // Generate random starting index for this product to offset animation timing
        $startIndex = rand(0, 2);
        
        // Calculate sale duration
        $saleDurationText = '';
        $saleDurationTooltip = '';
        if ($product->sale_type == 1 && $product->end_date) {
            $now = \Carbon\Carbon::now();
            $endDate = \Carbon\Carbon::parse($product->end_date);
            $daysLeft = (int) ceil($now->diffInDays($endDate, false));
            
            if ($daysLeft > 7) {
                $saleDurationText = 'Deal ends in ' . $daysLeft . ' days';
                $saleDurationTooltip = 'Limited time offer! This special price is only available for ' . $daysLeft . ' more days. Shop now before it\'s gone.';
            } elseif ($daysLeft > 1) {
                $saleDurationText = 'Only ' . $daysLeft . ' days left';
                $saleDurationTooltip = 'Hurry! This deal expires in just ' . $daysLeft . ' days. Don\'t miss out on this special pricing.';
            } elseif ($daysLeft == 1) {
                $saleDurationText = 'Last day - ends tomorrow';
                $saleDurationTooltip = 'Final chance! This offer ends tomorrow. Order now to secure this price.';
            } elseif ($daysLeft == 0) {
                $saleDurationText = 'Ends today - last hours';
                $saleDurationTooltip = 'Act fast! This deal expires today. Order now before it\'s too late.';
            } else {
                $saleDurationText = 'Offer ended';
                $saleDurationTooltip = 'This promotion has expired.';
            }
        } else {
            $saleDurationText = 'Limited time only';
            $saleDurationTooltip = 'Special promotional pricing available for a limited time. Grab this deal while supplies last.';
        }
    @endphp
    
    <div class="ps-product ps-product--deal">
        <a href="{{ $product->url }}" class="ps-product__card-link"></a>
        <div class="ps-product__thumbnail">
            <a href="{{ $product->url }}" title="{{ $product->name }}">
                {!! RvMedia::image($product->image, $product->name, 'small', lazy: $lazy ?? true) !!}
            </a>
            @if ($product->isOutOfStock())
                <div class="ps-product__badges">
                    <span class="ps-product__badge out-stock">{{ __('Out Of Stock') }}</span>
                </div>
            @elseif ($product->productLabels->isNotEmpty())
                <div class="ps-product__badges">
                    @foreach ($product->productLabels as $label)
                        <span class="ps-product__badge" {!! $label->css_styles !!}>{{ $label->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div class="ps-product__container">
            <div class="ps-product__content">
                {{-- Discount & Regular Price Row --}}
                <div class="ps-product__price-row">
                    @if ($product->front_sale_price !== $product->price)
                        <span class="ps-product__discount-badge">{{ $discountPercentage }}</span>
                        <del class="ps-product__regular-price">{{ format_price($product->price) }}</del>
                    @endif
                </div>

                {{-- Sale Duration Line --}}
                @if ($product->front_sale_price !== $product->price && $saleDurationText)
                    <div class="ps-product__sale-duration {{ $product->sale_type != 1 || !$product->end_date ? 'limited-time' : '' }}">
                        <span class="duration-text">{{ $saleDurationText }}</span>
                        <span class="info-icon" data-tooltip="{{ $saleDurationTooltip }}">
                            <i class="icon-information"></i>
                        </span>
                    </div>
                @endif

                {{-- Sale Price & Sold Count Row --}}
                <div class="ps-product__sale-row">
                    <div class="ps-product__price sale">
                        <span class="ps-product__price-amount">{{ format_price($product->front_sale_price !== 0 ? $product->front_sale_price : $product->price) }}</span>
                        @if ($soldCount > 0)
                            <span class="ps-product__sold-count">+{{ number_format($soldCount) }} sold</span>
                        @endif
                    </div>
                </div>

                {{-- Product Title --}}
                <a class="ps-product__title" href="{{ $product->url }}" title="{{ $product->name }}">
                    {!! BaseHelper::clean($product->name) !!}
                </a>

                {{-- Rating Widget --}}
                <a href="{{ $product->url }}#tab-reviews" class="ps-product__rating" onclick="sessionStorage.setItem('scrollToReviews', 'true');">
                    <span class="rating-number">{{ $product->reviews_count > 0 ? number_format($product->reviews_avg, 1) : '0.0' }}</span>
                    <div class="rating-stars">
                        @php
                            $avgRating = $product->reviews_count > 0 ? $product->reviews_avg : 0;
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($avgRating))
                                <i class="icon-star filled"></i>
                            @elseif ($i - 0.5 <= $avgRating)
                                <i class="icon-star half-filled"></i>
                            @else
                                <i class="icon-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-count">({{ number_format($product->reviews_count) }})</span>
                </a>

                {{-- Action Button --}}
                @if (EcommerceHelper::isCartEnabled() && !$product->isOutOfStock())
                    <div class="ps-product__actions">
                        @if ($product->has_variation)
                            <a href="#" class="ps-btn ps-btn--fullwidth" data-bb-toggle="quick-shop" data-url="{{ route('public.ajax.quick-shop', $product->slug) }}" {!! EcommerceHelper::jsAttributes('quick-shop', $product) !!}>
                                {{ __('Select Options') }}
                            </a>
                        @else
                            <a href="#" class="ps-btn ps-btn--fullwidth add-to-cart-button" data-id="{{ $product->id }}" data-url="{{ route('public.cart.add-to-cart') }}" {!! EcommerceHelper::jsAttributes('add-to-cart', $product, additional: ['data-bb-toggle' => 'none']) !!}>
                                {{ __('Add To Cart') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<style>
    /* Deal Product Base Styles */
    .ps-deal-of-day .ps-product,
    .ps-deal-of-day .ps-product--deal {
        border: 1px solid transparent !important;
    }

    .ps-deal-of-day .ps-product:hover,
    .ps-deal-of-day .ps-product--deal:hover {
        border-color: transparent !important;
    }

    .ps-block--deal-of-day .ps-product,
    .ps-block--deal-of-day .ps-product--deal,
    .ps-product--deal {
        background: transparent !important;
        padding: 0 !important;
        margin: 0 !important;
        position: relative;
    }

    /* Card Link Overlay */
    .ps-product--deal .ps-product__card-link {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
        cursor: pointer;
    }

    .ps-product--deal .ps-product__thumbnail,
    .ps-product--deal .ps-product__title,
    .ps-product--deal .ps-product__rating,
    .ps-product--deal .ps-product__actions,
    .ps-product--deal .ps-product__badges {
        position: relative;
        z-index: 2;
    }

    /* Thumbnail */
    .ps-product--deal .ps-product__thumbnail {
        padding:7px;
        background:var(--global-color-card-bg);
        border-radius:7px;
        position: relative;
        margin-bottom: 5px;
        border: none !important;
        outline: none !important;
    }

    .ps-product--deal .ps-product__thumbnail a {
        display: block;
        outline: none !important;
        border: none !important;
    }

    .ps-product--deal .ps-product__thumbnail img {
        width: 100%;
        height: auto;
        border-radius: 4px;
        outline: none !important;
        border: none !important;
    }

    /* Product Container */
    .ps-product--deal .ps-product__container {
        padding: 0;
        margin: 0;
        border: none !important;
        outline: none !important;
    }

    .ps-product--deal .ps-product__content {
        padding: 0;
        margin: 0;
        border: none !important;
        outline: none !important;
    }

    /* Discount & Regular Price Row */
    .ps-product--deal .ps-product__price-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 5px;
    }

    /* Sale Duration Line */
    .ps-product--deal .ps-product__sale-duration {
        width: fit-content;
        display: flex;
        align-items: center;
        gap: 4px;
        margin-bottom: 5px;
        font-size: 10px;
        line-height: 1.2;
        position: relative;
        z-index: 3;
    }

    .ps-product--deal .ps-product__sale-duration .duration-text {
        color: #565959;
        font-weight: 400;
    }

    .ps-product--deal .ps-product__sale-duration.limited-time .duration-text {
        color: #565959;
        font-weight: 400;
    }

    .ps-product--deal .ps-product__sale-duration .info-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 11px;
        height: 11px;
        border: 1px solid var(--global-color-text-subtle);
        border-radius: 50%;
        position: relative;
        cursor: help;
        flex-shrink: 0;
        margin-left: 2px;
        padding: 4px;
    }

    .ps-product--deal .ps-product__sale-duration .info-icon i {
        font-size: 7px;
        color: var(--global-color-text-subtle);
        font-weight: 600;
        font-style: normal;
        line-height: 1;
    }

    .ps-product--deal .ps-product__sale-duration .info-icon i::before {
        content: 'i';
        font-family: Georgia, serif;
        font-weight: 600;
        font-style: normal;
    }

    /* Tooltip Styles */
    .ps-product--deal .ps-product__sale-duration .info-icon::after {
        content: attr(data-tooltip);
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        color: #0F1111;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #D5D9D9;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), 0 0 2px rgba(0, 0, 0, 0.08);
        font-size: 10px;
        white-space: normal;
        width: 180px;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.15s ease, visibility 0.15s ease;
        z-index: 9999;
        line-height: 1.4;
        font-weight: 400;
        text-align: left;
    }

    .ps-product--deal .ps-product__sale-duration .info-icon::before {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        border: 6px solid transparent;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.15s ease, visibility 0.15s ease;
        z-index: 10000;
    }

    /* Tooltip positioning - show above by default */
    .ps-product--deal .ps-product__sale-duration .info-icon::after {
        bottom: calc(100% + 8px);
    }

    .ps-product--deal .ps-product__sale-duration .info-icon::before {
        bottom: calc(100% + 2px);
        border-top-color: #fff;
        filter: drop-shadow(0 2px 1px rgba(0, 0, 0, 0.05));
    }

    .ps-product--deal .ps-product__sale-duration .info-icon:hover::after,
    .ps-product--deal .ps-product__sale-duration .info-icon:hover::before {
        opacity: 1;
        visibility: visible;
    }

    .ps-product--deal .ps-product__discount-badge {
        display: inline-block;
        background: var(--global-color-success);
        color: #fff;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
    }

    .ps-product--deal .ps-product__regular-price {
        font-size: 13px;
        color: var(--global-color-text-muted);
        opacity: 0.7;
    }

    /* Sale Price & Sold Count Row */
    .ps-product--deal .ps-product__sale-row {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }

    .ps-product--deal .ps-product__price {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ps-product--deal .ps-product__price-amount {
        font-size: 22px !important;
        font-weight: 400 !important;
        color: var(--global-color-alert) !important;
    }

    .ps-product--deal .ps-product__sold-count {
        font-size: 11px !important;
        color: var(--global-color-text-muted) !important;
        white-space: nowrap;
        margin-left: 4px;
    }

    /* Product Title */
    .ps-product--deal .ps-product__title {
        font-size: 13px;
        font-weight: 400;
        color: var(--global-color-text-main);
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 10px;
        line-height: 1.4;
        max-height: calc(1.4em * 2);
        word-break: break-word;
        transition: color 0.3s ease;
    }

    .ps-product--deal .ps-product__title:hover {
        color: var(--global-color-alert-text);
    }

    /* Rating Widget */
    .ps-product--deal .ps-product__rating {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 12px;
        font-size: 12px;
        text-decoration: none;
        color: inherit;
        width: fit-content;
        transition: opacity 0.3s ease;
    }

    .ps-product--deal .ps-product__rating:hover {
        opacity: 0.8;
    }

    .ps-product--deal .ps-product__rating .rating-number {
        font-weight: 700;
        color: var(--global-color-text-main);
        margin-left: 0;
    }

    .ps-product--deal .ps-product__rating .rating-stars {
        display: flex;
        gap: 2px;
    }

    .ps-product--deal .ps-product__rating .rating-stars i {
        font-size: 12px;
        color: #ddd;
    }

    .ps-product--deal .ps-product__rating .rating-stars i.filled {
        color: #ffa500;
    }

    .ps-product--deal .ps-product__rating .rating-stars i.half-filled {
        color: #ffa500;
        opacity: 0.5;
    }

    .ps-product--deal .ps-product__rating .rating-count {
        color: #999;
    }

    /* Action Button */
    .ps-product--deal .ps-product__actions {
        margin-top: 12px;
        background: transparent;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Add to Cart button - warning background */
    .ps-product--deal .ps-btn.add-to-cart-button {
        width: 100%;
        padding: 10px 15px;
        background: var(--global-color-warning);
        color: var(--global-color-text-main);
        border: none;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }

    .ps-product--deal .ps-btn.add-to-cart-button:hover {
        opacity: 0.9;
    }

    /* Select Options button - bordered style */
    .ps-product--deal .ps-btn:not(.add-to-cart-button) {
        width: 100%;
        padding: 10px 15px;
        background: transparent;
        color: var(--global-color-text-main);
        border: 1px solid var(--global-color-text-main);
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: all 0.3s ease;
    }

    .ps-product--deal .ps-btn:not(.add-to-cart-button):hover {
        background: var(--global-color-text-main);
        color: var(--global-color-text-on-dark);
    }

    /* Badges */
    .ps-product--deal .ps-product__badges {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 5;
    }

    .ps-product--deal .ps-product__badge {
        background: var(--global-color-primary);
        color: var(--global-color-text-on-dark);
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 5px;
    }

    .ps-product--deal .ps-product__badge.out-stock {
        background: var(--global-color-alert);
    }

    /* Responsive Styles */
    @media (max-width: 892px) {
        .ps-product--deal {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .ps-product--deal .ps-product__thumbnail {
            margin-bottom: 5px;
        }

        .ps-product--deal .ps-product__title {
            font-size: 12px;
            margin-bottom: 8px;
        }

        .ps-product--deal .ps-product__price-amount {
            font-size: 19px !important;
        }

        .ps-product--deal .ps-product__actions {
            margin-top: auto;
        }

        .ps-product--deal .ps-btn.add-to-cart-button {
            padding: 8px 12px;
            font-size: 13px;
        }

        .ps-product--deal .ps-btn:not(.add-to-cart-button) {
            padding: 8px 12px;
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        /* Each deal product on mobile: horizontal flex layout (same as offers) */
        .ps-product--deal {
            display: flex !important;
            flex-direction: row !important;
            gap: 0 !important;
            align-items: stretch !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Thumbnail: left side - full height with centered image */
        .ps-product--deal .ps-product__thumbnail {
            width: 130px !important;
            flex-shrink: 0 !important;
            margin: 0 !important;
            padding: 6px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: var(--global-color-card-bg) !important;
            border-radius: 8px 0 0 8px !important;
        }

        .ps-product--deal .ps-product__thumbnail a {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 100% !important;
            height: 100% !important;
        }

        .ps-product--deal .ps-product__thumbnail img {
            width: 100% !important;
            height: auto !important;
            border-radius: 3px !important;
            object-fit: contain !important;
        }

        /* Info: right side with border */
        .ps-product--deal .ps-product__container {
            flex: 1 !important;
            padding: 8px 10px !important;
            margin: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            min-width: 0 !important;
            border: 1px solid var(--global-color-primary-border) !important;
            border-left: none !important;
            border-radius: 0 8px 8px 0 !important;
            background: transparent !important;
        }

        .ps-product--deal .ps-product__content {
            padding: 0 !important;
            margin: 0 !important;
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
        }

        /* Adjust text sizes for mobile */
        .ps-product.ps-product--deal .ps-product__title,
        .ps-product--deal .ps-product__title {
            font-size: 11px !important;
            line-height: 1.4 !important;
            margin-bottom: 4px !important;
            max-height: calc(1.4em * 3) !important;
            min-height: auto !important;
            padding: 0 !important;
            display: -webkit-box !important;
            -webkit-line-clamp: 3 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .ps-product--deal .ps-product__price-amount {
            font-size: 15px !important;
        }

        .ps-product--deal .ps-product__sold-count {
            font-size: 9px !important;
        }

        .ps-product--deal .ps-product__rating {
            font-size: 9px !important;
            margin-bottom: 3px !important;
        }

        .ps-product--deal .ps-product__rating .rating-stars i {
            font-size: 9px !important;
        }

        .ps-product--deal .ps-product__sale-duration {
            font-size: 8px !important;
        }

        .ps-product--deal .ps-product__discount-badge {
            font-size: 9px !important;
            padding: 1px 4px !important;
        }

        .ps-product--deal .ps-product__regular-price {
            font-size: 10px !important;
        }

        .ps-product--deal .ps-product__price-row {
            margin-bottom: 3px !important;
        }

        .ps-product--deal .ps-product__sale-row {
            margin-bottom: 4px !important;
        }

        /* Button inside the right container */
        .ps-product--deal .ps-product__actions {
            margin-top: 6px !important;
        }

        .ps-product--deal .ps-btn.add-to-cart-button {
            font-size: 11px !important;
            padding: 7px 12px !important;
            border-radius: 16px !important;
        }

        .ps-product--deal .ps-btn:not(.add-to-cart-button) {
            font-size: 11px !important;
            padding: 7px 12px !important;
            border-radius: 16px !important;
        }

        /* Badge styling on mobile */
        .ps-product--deal .ps-product__badge {
            padding: 2px 7px !important;
        }
    }
</style>

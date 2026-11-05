{{-- Cart Summary --}}
@php
    // Use the cart instance and discount amounts passed from parent context
    $instanceToUse = $currentCartInstance ?? 'cart';
    $productsToUse = $currentProducts ?? $products;
    $promotionToUse = $currentPromotionDiscount ?? $promotionDiscountAmount;
    $couponToUse = $currentCouponDiscount ?? $couponDiscountAmount;
@endphp
<div class="ps-block--shopping-total">
    <div class="ps-block__header">
        <p>
            {{ __('Subtotal') }} (<span id="cart-summary-item-count" data-total="{{ count($productsToUse) }}">{{ count($productsToUse) }}</span> {{ __('items') }}): 
            <span id="cart-summary-subtotal" data-total="{{ Cart::instance($instanceToUse)->rawSubTotal() }}">{{ format_price(Cart::instance($instanceToUse)->rawSubTotal()) }}</span>
        </p>
    </div>
    @if (EcommerceHelper::isTaxEnabled())
        <div class="ps-block__header">
            <p>{{ __('Tax') }}: <span id="cart-summary-tax" data-total="{{ Cart::instance($instanceToUse)->rawTax() }}">{{ format_price(Cart::instance($instanceToUse)->rawTax()) }}</span></p>
        </div>
    @endif
    @if ($couponToUse > 0 && session('applied_coupon_code'))
        <div class="ps-block__header">
            <p>{{ __('Coupon code: :code', ['code' => session('applied_coupon_code')]) }} 
                (<small><a class="btn-remove-coupon-code text-danger" data-url="{{ route('public.coupon.remove') }}" href="javascript:void(0)" data-processing-text="{{ __('Removing...') }}">{{ __('Remove') }}</a></small>):
                <span> {{ format_price($couponToUse) }}</span>
            </p>
        </div>
    @endif
    @if ($promotionToUse)
        <div class="ps-block__header">
            <p>{{ __('Discount promotion') }}: <span> {{ format_price($promotionToUse) }}</span></p>
        </div>
    @endif
    <div class="ps-block__content">
        <h3>{{ __('Total') }}: <span id="cart-summary-total" data-total="{{ Cart::instance($instanceToUse)->rawTotal() - $promotionToUse - $couponToUse }}">{{ ($promotionToUse + $couponToUse) > Cart::instance($instanceToUse)->rawTotal() ? format_price(0) : format_price(Cart::instance($instanceToUse)->rawTotal() - $promotionToUse - $couponToUse) }}</span></h3>
        <p><small>({{ __('Shipping fees not included') }})</small></p>
    </div>
</div>

{{-- Coupon Code --}}
<div class="ps-cart-coupon-section">
    <h3>{{ __('Coupon Discount') }}</h3>
    <div class="form-group">
        <input class="form-control coupon-code" type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="{{ __('Enter coupon code') }}">
    </div>
    <button class="ps-btn ps-btn--outline btn-apply-coupon-code" type="button" data-url="{{ route('public.coupon.apply') }}">{{ __('Apply') }}</button>
</div>

{{-- Action Buttons --}}
<div class="ps-cart-actions">
    <a class="ps-btn ps-btn--secondary btn-cart-button-action" href="{{ route('public.products') }}">
        <i class="icon-arrow-left"></i> {{ __('Shop more') }}
    </a>
    <a class="ps-btn ps-btn--primary btn-cart-button-action" href="{{ route('public.checkout.information', OrderHelper::getOrderSessionToken()) }}">
        {{ __('Proceed to checkout') }} <i class="icon-arrow-right"></i>
    </a>
</div>

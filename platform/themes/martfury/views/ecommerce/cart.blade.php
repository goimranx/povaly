{{-- Shopping Cart Enhanced --}}
@php
    $cacheVersion = \App\Helpers\VersionHelper::getCacheBustVersion();
    Theme::asset()->usePath()->add('cart-enhanced-css', 'css/cart-enhanced.css', [], [], $cacheVersion);
    Theme::asset()->container('footer')->usePath()->add('cart-enhanced-js', 'js/cart-enhanced.js', ['jquery'], [], $cacheVersion);
@endphp

<div class="ps-section--shopping ps-shopping-cart-enhanced">
    <div class="ps-cart-container">
        <form class="form--shopping-cart" method="post" action="{{ route('public.cart.update') }}">
            @csrf
            @if (count($products) > 0)
                <div class="ps-cart-layout">
                    {{-- Left Section: Products --}}
                    <div class="ps-cart-products-section">
                        <div class="ps-cart-products-card">
                            {{-- Header with Shopping Cart title --}}
                            <div class="ps-cart-header">
                                <h1>{{ __('Shopping Cart') }}</h1>
                            </div>

                            {{-- Selection Header --}}
                            <div class="ps-cart-selection-header">
                                <div class="ps-cart-selection-left">
                                    <label class="ps-cart-checkbox-wrapper">
                                        <input type="checkbox" class="ps-cart-checkbox-main" id="selectAllCheckbox">
                                        <span class="ps-cart-checkmark"></span>
                                        <span class="ps-cart-select-text" id="selectAllText"
                                              data-text-all="{{ __('Select all') }} ({count})"
                                              data-text-all-selected="{{ __('All selected') }} ({count})"
                                              data-text-partial="{{ __('Select all') }} ({selected} {{ __('of') }} {total})">
                                            {{ __('Select all') }} ({{ count($products) }})
                                        </span>
                                    </label>
                                </div>
                                <div class="ps-cart-selection-right">
                                    <button type="button" class="ps-cart-delete-btn" id="deleteSelectedBtn" 
                                            data-confirm-message="{{ __('Are you sure you want to remove the selected items?') }}" disabled>
                                        <i class="icon-trash"></i> {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>

                            @include(Theme::getThemeNamespace('views.ecommerce.includes.cart-items-enhanced'))
                        </div>
                    </div>

                    {{-- Right Section: Summary --}}
                    <div class="ps-cart-summary-section">
                        <div class="ps-cart-summary-card">
                            @include(Theme::getThemeNamespace('views.ecommerce.includes.cart-summary'))
                        </div>
                    </div>
                </div>
            @else
                <div class="ps-cart-empty-section">
                    <div class="ps-cart-empty">
                        <div class="ps-cart-empty__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
                                <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                            </svg>
                        </div>
                        <h3>{{ __('Your cart is empty!') }}</h3>
                        <p>{{ __('Looks like you haven\'t added any products to your cart yet.') }}</p>
                        <a class="ps-btn" href="{{ route('public.products') }}">{{ __('Continue Shopping') }}</a>
                    </div>
                </div>
            @endif
        </form>
    </div>

    @if (count($products) > 0)
        <div class="ps-cart-crosssell-section">
            {!! Theme::partial('cross-sell-products', compact('crossSellProducts')) !!}
        </div>
    @endif
</div>

{{-- Shopping Cart Enhanced with Tabs --}}
@php
    $cacheVersion = \App\Helpers\VersionHelper::getCacheBustVersion();
    Theme::asset()->usePath()->add('cart-enhanced-css', 'css/cart-enhanced.css', [], [], $cacheVersion);
    Theme::asset()->container('footer')->usePath()->add('cart-enhanced-js', 'js/cart-enhanced.js', ['jquery'], [], $cacheVersion);
    
    // Check if we have any items in either cart
    $hasMainCartItems = count($products) > 0;
    $hasConfirmPaymentItems = isset($confirmPaymentProducts) && count($confirmPaymentProducts) > 0;
    $hasAnyItems = $hasMainCartItems || $hasConfirmPaymentItems;
    
    // Determine which tab should be active (check session or default)
    $activeTab = session('cart_active_tab', 'shopping'); // Remember last active tab
    if (!$hasMainCartItems && $hasConfirmPaymentItems) {
        $activeTab = 'confirm-payment'; // Auto-switch if main cart empty
    }
@endphp

<div class="ps-section--shopping ps-shopping-cart-enhanced">
    <div class="ps-cart-container">
        @if ($hasAnyItems)
            {{-- Cart Tabs --}}
            <div class="ps-cart-tabs">
                <div class="ps-cart-tabs-header">
                    <button class="ps-cart-tab-button {{ $activeTab === 'shopping' ? 'active' : '' }}" 
                            data-tab="shopping" 
                            type="button">
                        <span class="tab-title">{{ __('Shopping Cart') }}</span>
                        <span class="tab-count">({{ count($products) }})</span>
                    </button>
                    <button class="ps-cart-tab-button {{ $activeTab === 'confirm-payment' ? 'active' : '' }}" 
                            data-tab="confirm-payment" 
                            type="button">
                        <span class="tab-title">{{ __('Confirm Payment') }}</span>
                        <span class="tab-count">({{ $hasConfirmPaymentItems ? count($confirmPaymentProducts) : 0 }})</span>
                    </button>
                </div>
                
                {{-- Shopping Cart Tab Content --}}
                <div class="ps-cart-tab-content {{ $activeTab === 'shopping' ? 'active' : '' }}" data-tab-content="shopping">
                    @if ($hasMainCartItems)
                        @php
                            // Set context for shopping cart
                            $currentCartInstance = 'cart';
                            $currentProducts = $products;
                            $currentPromotionDiscount = $promotionDiscountAmount;
                            $currentCouponDiscount = $couponDiscountAmount;
                            $tabId = 'shopping';
                            
                            \Log::info('[Blade Debug] Rendering shopping tab');
                            \Log::info('[Blade Debug] Shopping - currentCartInstance: ' . $currentCartInstance);
                            \Log::info('[Blade Debug] Shopping - currentProducts count: ' . count($currentProducts));
                            \Log::info('[Blade Debug] Shopping - product names: ' . $currentProducts->pluck('name')->join(', '));
                        @endphp
                        <form class="form--shopping-cart" method="post" action="{{ route('public.cart.update') }}">
                            @csrf
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
                                            <div class="ps-cart-selection-header-inner">
                                                <div class="ps-cart-selection-left">
                                                    <label class="ps-cart-checkbox-wrapper">
                                                        <input type="checkbox" class="ps-cart-checkbox-main" id="selectAllCheckbox-shopping">
                                                        <span class="ps-cart-checkmark"></span>
                                                        <span class="ps-cart-select-text" id="selectAllText-shopping"
                                                              data-text-all="{{ __('Select all') }} ({count})"
                                                              data-text-all-selected="{{ __('All selected') }} ({count})"
                                                              data-text-partial="{{ __('Select all') }} ({selected} {{ __('of') }} {total})">
                                                            {{ __('Select all') }} ({{ count($currentProducts) }})
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="ps-cart-selection-right">
                                                    <button type="button" class="ps-cart-delete-btn" id="deleteSelectedBtn-shopping" 
                                                            data-confirm-message="{{ __('Are you sure you want to remove the selected items?') }}" disabled>
                                                        <i class="icon-trash"></i> {{ __('Delete') }}
                                                    </button>
                                                </div>
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
                        </form>
                    @else
                        <div class="ps-cart-empty-section">
                            <div class="ps-cart-empty">
                                <div class="ps-cart-empty__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/>
                                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>
                                    </svg>
                                </div>
                                <h3>{{ __('Your shopping cart is empty!') }}</h3>
                                <p>{{ __('Your main shopping cart is empty. Items in checkout are shown in the Confirm Payment tab.') }}</p>
                                <a class="ps-btn" href="{{ route('public.products') }}">{{ __('Continue Shopping') }}</a>
                            </div>
                        </div>
                    @endif
                </div>
                
                {{-- Confirm Payment Tab Content --}}
                <div class="ps-cart-tab-content {{ $activeTab === 'confirm-payment' ? 'active' : '' }}" data-tab-content="confirm-payment">
                    @if ($hasConfirmPaymentItems)
                        @php
                            // Set context for confirm payment cart
                            $currentCartInstance = 'confirm_payment';
                            $currentProducts = $confirmPaymentProducts;
                            $currentPromotionDiscount = $confirmPaymentPromotionDiscount ?? 0;
                            $currentCouponDiscount = $confirmPaymentCouponDiscount ?? 0;
                            $tabId = 'confirm-payment';
                            
                            \Log::info('[Blade Debug] Rendering confirm-payment tab');
                            \Log::info('[Blade Debug] Confirm - currentCartInstance: ' . $currentCartInstance);
                            \Log::info('[Blade Debug] Confirm - currentProducts count: ' . count($currentProducts));
                            \Log::info('[Blade Debug] Confirm - product names: ' . $currentProducts->pluck('name')->join(', '));
                        @endphp
                        
                        <form class="form--shopping-cart form--confirm-payment" method="post" action="{{ route('public.cart.update') }}">
                            @csrf
                            <div class="ps-cart-layout">
                                {{-- Left Section: Products --}}
                                <div class="ps-cart-products-section">
                                    <div class="ps-cart-products-card">
                                        {{-- Header --}}
                                        <div class="ps-cart-header">
                                            <h1>{{ __('Confirm Payment') }}</h1>
                                            <p class="ps-cart-subtitle">{{ __('Items currently in checkout process - All items selected by default') }}</p>
                                        </div>

                                        {{-- Selection Header --}}
                                        <div class="ps-cart-selection-header">
                                            <div class="ps-cart-selection-header-inner">
                                                <div class="ps-cart-selection-left">
                                                    <label class="ps-cart-checkbox-wrapper">
                                                        <input type="checkbox" class="ps-cart-checkbox-main" id="selectAllCheckbox-confirm-payment">
                                                        <span class="ps-cart-checkmark"></span>
                                                        <span class="ps-cart-select-text" id="selectAllText-confirm-payment"
                                                              data-text-all="{{ __('Select all') }} ({count})"
                                                              data-text-all-selected="{{ __('All selected') }} ({count})"
                                                              data-text-partial="{{ __('Select all') }} ({selected} {{ __('of') }} {total})">
                                                            {{ __('Select all') }} ({{ count($currentProducts) }})
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="ps-cart-selection-right">
                                                    <button type="button" class="ps-cart-delete-btn" id="deleteSelectedBtn-confirm-payment" 
                                                            data-confirm-message="{{ __('Are you sure you want to remove the selected items from checkout?') }}" disabled>
                                                        <i class="icon-trash"></i> {{ __('Remove') }}
                                                    </button>
                                                </div>
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
                        </form>
                    @else
                        <div class="ps-cart-empty-section">
                            <div class="ps-cart-empty">
                                <div class="ps-cart-empty__icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M9 9h.01M15 9h.01M9.5 13a3.5 3.5 0 0 0 5 0"/>
                                    </svg>
                                </div>
                                <h3>{{ __('No items in checkout') }}</h3>
                                <p>{{ __('You don\'t have any items in the checkout process yet.') }}</p>
                                <a class="ps-btn" href="{{ route('public.products') }}">{{ __('Continue Shopping') }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            @if ($hasMainCartItems && count($crossSellProducts) > 0)
                <div class="ps-cart-crosssell-section">
                    {!! Theme::partial('cross-sell-products', compact('crossSellProducts')) !!}
                </div>
            @endif
        @else
            {{-- Both carts empty --}}
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
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching functionality with session storage
    const tabButtons = document.querySelectorAll('.ps-cart-tab-button');
    const tabContents = document.querySelectorAll('.ps-cart-tab-content');
    
    // Remember active tab for page reloads
    function saveActiveTab(tabName) {
        // Save to session via AJAX
        fetch('{{ route("public.cart") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ active_tab: tabName })
        }).catch(() => {});
    }
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.dataset.tab;
            
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked button and corresponding content
            this.classList.add('active');
            const targetContent = document.querySelector(`[data-tab-content="${tabName}"]`);
            if (targetContent) {
                targetContent.classList.add('active');
            }
            
            // Save active tab
            saveActiveTab(tabName);
            
            // Reinitialize cart enhanced for the active tab
            setTimeout(() => {
                if (typeof window.initCartEnhanced === 'function') {
                    window.initCartEnhanced();
                }
            }, 100);
        });
    });
    
    // Auto-select all items in confirm payment tab on load
    const confirmPaymentTab = document.querySelector('[data-tab-content="confirm-payment"]');
    if (confirmPaymentTab && confirmPaymentTab.classList.contains('active')) {
        setTimeout(() => {
            const confirmPaymentCheckboxes = confirmPaymentTab.querySelectorAll('.ps-cart-checkbox-item');
            confirmPaymentCheckboxes.forEach(cb => {
                if (!cb.checked) {
                    cb.checked = true;
                }
            });
            
            // Trigger update
            if (typeof window.initCartEnhanced === 'function') {
                window.initCartEnhanced();
            }
        }, 200);
    }
});
</script>

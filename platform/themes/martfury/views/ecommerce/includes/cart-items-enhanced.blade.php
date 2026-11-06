{{-- Group products by store --}}
@php
    // Use the cart instance and products passed from parent context
    // $currentCartInstance and $currentProducts are set in cart.blade.php for each tab
    $instanceToUse = $currentCartInstance ?? 'cart';
    $productsToUse = $currentProducts ?? $products;
    
    \Log::info('[Cart Items Debug] Instance: ' . $instanceToUse . ', Products count: ' . count($productsToUse));
    \Log::info('[Cart Items Debug] Product IDs: ' . $productsToUse->pluck('id')->join(', '));
    
    $groupedProducts = collect();
    
    // Group products directly from $productsToUse (already filtered by cart instance)
    foreach($productsToUse as $product) {
        $storeId = $product->original_product->store_id ?? 0;
        $storeName = $storeId && $product->original_product->store 
            ? $product->original_product->store->name 
            : __('Direct Sale');
        $storeLogo = $storeId && $product->original_product->store 
            ? $product->original_product->store->logo_url 
            : null;
        $storeUrl = $storeId && $product->original_product->store 
            ? $product->original_product->store->url 
            : null;
        
        if (!$groupedProducts->has($storeId)) {
            $groupedProducts->put($storeId, [
                'name' => $storeName,
                'logo' => $storeLogo,
                'url' => $storeUrl,
                'badge' => $storeId && $product->original_product->store ? $product->original_product->store->badge : null,
                'items' => collect(),
            ]);
        }
        
        // Find the cart item for this product
        $cartItem = Cart::instance($instanceToUse)->content()->first(function($item) use ($product) {
            return $item->id == $product->id;
        });
        
        if ($cartItem) {
            $groupedProducts[$storeId]['items']->push([
                'key' => $cartItem->rowId,
                'cartItem' => $cartItem,
                'product' => $product,
            ]);
        }
    }
    
    \Log::info('[Cart Items Debug] Grouped stores count: ' . $groupedProducts->count());
@endphp

{{-- Render store groups --}}
@foreach($groupedProducts as $storeId => $storeData)
    <div class="ps-cart-store-group" data-store-id="{{ $storeId }}">
        {{-- Store Header --}}
        <div class="ps-cart-store-header">
            <label class="ps-cart-checkbox-wrapper">
                <input type="checkbox" class="ps-cart-checkbox-store" data-store-id="{{ $storeId }}">
                <span class="ps-cart-checkmark"></span>
            </label>
            <div class="ps-cart-store-info">
                @if($storeId == 0)
                    {{-- Direct Sale - Show Povaly logo and text --}}
                    <div class="ps-cart-povaly-logo">
                        <img src="{{ theme_option('logo') }}" alt="{{ theme_option('site_title') }}" class="ps-cart-store-logo">
                    </div>
                    <span class="ps-cart-store-name">{{ __('Sold and shipped by') }} {{ theme_option('site_title') }}</span>
                @else
                    {{-- Store with logo or icon --}}
                    @if($storeData['logo'])
                        <img src="{{ $storeData['logo'] }}" alt="{{ $storeData['name'] }}" class="ps-cart-store-logo">
                    @else
                        <div class="ps-cart-store-icon">
                            <i class="icon-store"></i>
                        </div>
                    @endif
                    @if($storeData['url'])
                        <a href="{{ $storeData['url'] }}" class="ps-cart-store-name">
                            {{ $storeData['name'] }} {!! $storeData['badge'] ?? '' !!}
                        </a>
                    @else
                        <span class="ps-cart-store-name">{{ $storeData['name'] }}</span>
                    @endif
                @endif
            </div>
        </div>

        {{-- Store Products --}}
        <div class="ps-cart-store-products">
            @foreach($storeData['items'] as $itemData)
                @php
                    $key = $itemData['key'];
                    $cartItem = $itemData['cartItem'];
                    $product = $itemData['product'];
                @endphp
                <div class="ps-cart-product-item" data-row-id="{{ $cartItem->rowId }}" data-store-id="{{ $storeId }}">
                    <input type="hidden" name="items[{{ $key }}][rowId]" value="{{ $cartItem->rowId }}">
                    
                    {{-- Checkbox --}}
                    <label class="ps-cart-checkbox-wrapper">
                        <input type="checkbox" class="ps-cart-checkbox-item" data-row-id="{{ $cartItem->rowId }}" data-store-id="{{ $storeId }}">
                        <span class="ps-cart-checkmark"></span>
                    </label>

                    {{-- Product Image --}}
                    <div class="ps-cart-product-image">
                        <a href="{{ $product->original_product->url }}">
                            <img src="{{ RvMedia::getImageUrl(Arr::get($cartItem->options, 'image', $product->original_product->image), 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->original_product->name }}">
                        </a>
                    </div>

                    {{-- Product Details --}}
                    <div class="ps-cart-product-details">
                        {{-- Product Name and Price --}}
                        <div class="ps-cart-product-name-price">
                            <a href="{{ $product->original_product->url }}" class="ps-cart-product-name">
                                {{ $product->original_product->name }}
                                @if ($product->isOutOfStock())
                                    <span class="stock-status-label">({!! $product->stock_status_html !!})</span>
                                @endif
                                <i class="icon-chevron-right"></i>
                            </a>
                            
                            {{-- Price --}}
                            <div class="ps-cart-product-price">
                                <div class="product__price @if ($product->front_sale_price != $product->price) sale @endif">
                                    <span>{{ format_price($cartItem->price) }}</span>
                                    @if ($product->front_sale_price != $product->price)
                                        <small><del>{{ format_price($product->price) }}</del></small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Variations --}}
                        @if (!empty($cartItem->options['attributes']))
                            <p class="ps-cart-product-attributes"><small>{{ $cartItem->options['attributes'] }}</small></p>
                        @endif

                        @if (!empty($cartItem->options['options']))
                            <div class="ps-cart-product-options">
                                {!! render_product_options_info($cartItem->options['options'], $product, true) !!}
                            </div>
                        @endif

                        @if (!empty($cartItem->options['extras']) && is_array($cartItem->options['extras']))
                            @foreach($cartItem->options['extras'] as $option)
                                @if (!empty($option['key']) && !empty($option['value']))
                                    <p class="ps-cart-product-extra"><small>{{ $option['key'] }}: <strong>{{ $option['value'] }}</strong></small></p>
                                @endif
                            @endforeach
                        @endif

                        @include(
                            EcommerceHelper::viewPath('includes.cart-item-options-extras'),
                            ['options' => $cartItem->options]
                        )

                        {!! apply_filters('ecommerce_cart_after_item_content', null, $cartItem) !!}

                        {{-- Quantity and Remove --}}
                        <div class="ps-cart-product-actions">
                            <div class="ps-cart-product-remove">
                                <a href="#" data-url="{{ route('public.cart.remove', $cartItem->rowId) }}" data-cart-instance="{{ $instanceToUse }}" class="remove-cart-button" title="{{ __('Remove') }}">
                                    {{ __('Remove') }}
                                </a>
                            </div>

                            <div class="ps-cart-product-quantity">
                                <div class="form-group--number product__qty">
                                    <button class="up" type="button">+</button>
                                    <button class="down" type="button">-</button>
                                    <input type="number" class="form-control qty-input" min="1" value="{{ $cartItem->qty }}" title="{{ __('Qty') }}" name="items[{{ $key }}][values][qty]" data-cart-instance="{{ $instanceToUse }}" data-row-id="{{ $cartItem->rowId }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach

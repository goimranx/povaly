@if (isset($products) && $products)
    @php
        \Log::info('=== CHECKOUT PRODUCTS PARTIAL DEBUG ===');
        \Log::info('Products being rendered count: ' . $products->count());
        \Log::info('Products details:', $products->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => $p->price,
            'cartItem_qty' => $p->cartItem->qty ?? 'N/A'
        ])->toArray());
        \Log::info('=== END PRODUCTS PARTIAL DEBUG ===');
    @endphp
    
    <p>{{ __('Product(s)') }}:</p>
    @foreach ($products as $key => $product)
        @php
            $cartItem = $product->cartItem;
        @endphp

        @if (!empty($product))
            @include('plugins/ecommerce::orders.checkout.product', [
                'product' => $product,
                'cartItem' => $cartItem,
                'key' => $cartItem->rowId,
            ])
        @endif
    @endforeach

    <hr class="border-dark-subtle" />
@endif

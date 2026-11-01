{{-- Reusable Product Bundle Modal for Upsell & Cross-sale --}}
<div class="ec-bundle-modal" id="{{ $modalId ?? 'bundle-modal' }}" style="display: none;" data-bundle-modal>
    {{-- Overlay with transparency --}}
    <div class="ec-bundle-modal-overlay" data-bundle-modal-close></div>
    
    {{-- Close icon outside modal top right --}}
    <button type="button" class="ec-bundle-modal-close-btn" data-bundle-modal-close>
        <i class="ph ph-x"></i>
    </button>
    
    {{-- Modal content --}}
    <div class="ec-bundle-modal-content">
        {{-- Sticky Header --}}
        <div class="ec-bundle-modal-header">
            <h4>{{ $modalTitle ?? __('Buy Together') }}</h4>
        </div>
        
        {{-- Scrollable Product List --}}
        <div class="ec-bundle-modal-body">
            <div class="ec-bundle-modal-items" data-bundle-modal-items>
                {{-- Products will be rendered here --}}
                @if(isset($products) && $products->isNotEmpty())
                    @foreach($products as $product)
                        @php
                            $productVariation = $product;
                            $selectedAttrs = collect();
                            $productImage = $product->image;

                            if ($product->variations->isNotEmpty()) {
                                [$productImages, $productVariation, $selectedAttrs] = \Botble\Ecommerce\Facades\EcommerceHelper::getProductVariationInfo($product);
                                if ($productImages && count($productImages) > 0) {
                                    $productImage = $productImages[0];
                                }
                            }

                            $salePrice = $productVariation->price()->getPrice();
                            $bundleDiscountPrice = $product->pivot->price ?? 0;
                            $bundleDiscountType = $product->pivot->price_type ?? 'fixed';
                            $isPercentDiscount = $bundleDiscountType === 'percent' || $bundleDiscountType === \Botble\Ecommerce\Enums\UpSellPriceType::PERCENT;

                            $bundlePrice = $salePrice;
                            if ($bundleDiscountPrice > 0) {
                                if ($isPercentDiscount) {
                                    $bundlePrice = $salePrice - ($salePrice * $bundleDiscountPrice / 100);
                                } else {
                                    $bundlePrice = max(0, $salePrice - $bundleDiscountPrice);
                                }
                            }

                            $currency = get_application_currency();
                            $displayPrice = $bundlePrice;
                            if (!$currency->is_default && $currency->exchange_rate > 0) {
                                $displayPrice = $bundlePrice * $currency->exchange_rate;
                            }

                            $cartId = $productVariation->is_variation ? $productVariation->id : $product->id;
                            $showOriginalPrice = $salePrice > $bundlePrice ? $salePrice : null;
                        @endphp
                        
                        <div class="ec-bundle-modal-item">
                            {{-- Inline checkbox on left --}}
                            <div class="ec-bundle-modal-checkbox">
                                <input 
                                    type="checkbox" 
                                    id="modal-product-{{ $cartId }}"
                                    data-product-id="{{ $cartId }}"
                                    data-price="{{ $displayPrice }}"
                                    data-name="{{ $product->name }}"
                                    checked
                                >
                                <label for="modal-product-{{ $cartId }}"></label>
                            </div>
                            
                            {{-- Product card content --}}
                            <div class="ec-bundle-modal-card">
                                <div class="ec-bundle-modal-thumb">
                                    <a href="{{ $product->url }}">
                                        {{ RvMedia::image($productImage, $product->name, 'thumb', true) }}
                                    </a>
                                </div>
                                
                                <div class="ec-bundle-modal-info">
                                    <h5 class="ec-bundle-modal-name">
                                        <a href="{{ $product->url }}">{{ $product->name }}</a>
                                    </h5>
                                    
                                    <div class="ec-bundle-modal-price">
                                        @if($showOriginalPrice)
                                            <span class="price-original">{{ format_price($showOriginalPrice) }}</span>
                                        @endif
                                        <span class="price-sale">{{ format_price($bundlePrice) }}</span>
                                        @if($bundleDiscountPrice > 0)
                                            <span class="discount-badge">
                                                @if($isPercentDiscount)
                                                    -{{ (int) $bundleDiscountPrice }}%
                                                @else
                                                    -{{ format_price($bundleDiscountPrice) }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($product->variations->isNotEmpty())
                                        <div class="ec-bundle-modal-attributes">
                                            {!! render_product_swatches($product, [
                                                'selected' => $selectedAttrs,
                                                'view' => 'plugins/ecommerce::themes.attributes.swatches-renderer-upsale',
                                            ]) !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        
        {{-- Sticky Footer --}}
        <div class="ec-bundle-modal-footer">
            <div class="ec-bundle-modal-total">
                <span class="total-label">{{ __('Total price:') }}</span>
                <span class="total-price" data-bundle-total-price>{{ format_price(0) }}</span>
            </div>
            
            <button 
                type="button" 
                class="ec-bundle-modal-add-btn"
                data-bundle-add-all
                data-url="{{ route('public.cart.add-to-cart') }}"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span>{{ __('Add to Cart') }}</span>
            </button>
        </div>
    </div>
</div>

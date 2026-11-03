@if(request()->ajax() && isset($products) && isset($parentProduct))
    @if($products->isNotEmpty())
        @php
            $currency = get_application_currency();
            $currencyConfig = [
                'symbol' => $currency->symbol,
                'is_prefix' => $currency->is_prefix_symbol,
                'decimals' => $currency->decimals,
                'thousands_separator' => $currency->thousands_separator,
                'decimal_separator' => $currency->decimal_separator,
            ];
        @endphp
        
        {{-- Wrapper for both desktop and mobile sections so they refresh together --}}
        <div data-crosssale-bundle data-currency-config="{{ json_encode($currencyConfig) }}">
        <section class="ec-crosssale-bundle">
            <div class="container">
                <div class="ec-crosssale-bundle-wrapper">
                    <div class="ec-crosssale-bundle-header">
                        <div class="ec-crosssale-bundle-title">
                            <h2>{{ __('Frequently Bought Together') }}</h2>
                        </div>
                    </div>

                    <div class="ec-crosssale-bundle-content">
                        <div class="ec-crosssale-bundle-carousel-wrapper">
                            <div class="ec-crosssale-bundle-list ec-crosssale-carousel" data-crosssale-carousel>
                        @foreach ($products as $product)
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

                                // Get the base price WITHOUT cross-sell discount applied
                                // Use the stored attributes directly, not the calculated methods
                                $originalPrice = $productVariation->price_with_taxes;
                                $basePrice = $productVariation->isOnSale() ? $productVariation->front_sale_price_with_taxes : $productVariation->price_with_taxes;

                                $bundleDiscountPrice = $product->pivot->price ?? 0;
                                $bundleDiscountType = $product->pivot->price_type ?? 'fixed';
                                $isPercentDiscount = $bundleDiscountType === 'percent' || $bundleDiscountType === \Botble\Ecommerce\Enums\CrossSellPriceType::PERCENT;

                                // Calculate bundle price from base price (not already-discounted price)
                                $bundlePrice = $basePrice;
                                if ($bundleDiscountPrice > 0) {
                                    if ($isPercentDiscount) {
                                        $bundlePrice = $basePrice - ($basePrice * $bundleDiscountPrice / 100);
                                    } else {
                                        $bundlePrice = max(0, $basePrice - $bundleDiscountPrice);
                                    }
                                }

                                $hasDiscount = $bundlePrice < $basePrice || $basePrice < $originalPrice;
                                $showOriginalPrice = $bundlePrice < $basePrice ? $basePrice : ($basePrice < $originalPrice ? $originalPrice : null);

                                $displayPrice = $bundlePrice;
                                if (!$currency->is_default && $currency->exchange_rate > 0) {
                                    $displayPrice = $bundlePrice * $currency->exchange_rate;
                                }

                                $cartId = $productVariation->is_variation ? $productVariation->id : $product->id;
                            @endphp
                            <div
                                @class([
                                    'ec-crosssale-bundle-item',
                                    'has-variations' => $product->variations->isNotEmpty(),
                                ])
                                data-product-id="{{ $product->id }}"
                                data-crosssale-bundle-item
                            >
                                <div class="ec-crosssale-bundle-item-inner">
                                    <div class="ec-crosssale-bundle-checkbox">
                                        <input
                                            type="checkbox"
                                            class="ec-crosssale-checkbox"
                                            data-crosssale-checkbox
                                            aria-label="{{ __('Select :name', ['name' => $product->name]) }}"
                                            data-id="{{ $cartId }}"
                                            data-price="{{ $displayPrice }}"
                                            data-name="{{ $product->name }}"
                                            data-bundle-discount="{{ $bundleDiscountPrice }}"
                                            data-bundle-discount-type="{{ $isPercentDiscount ? 'percent' : 'fixed' }}"
                                            data-original-price="{{ $basePrice * ($currency->is_default ? 1 : $currency->exchange_rate) }}"
                                            data-saved-amount="{{ ($basePrice - $bundlePrice) * ($currency->is_default ? 1 : $currency->exchange_rate) }}"
                                            @checked(true)
                                        >
                                        <span class="ec-crosssale-checkmark"></span>
                                    </div>

                                    <div class="ec-crosssale-bundle-thumb">
                                        {{ RvMedia::image($productImage, $product->name, 'thumb', true) }}
                                        
                                        @if(!$loop->last)
                                            <div class="ec-crosssale-plus-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ec-crosssale-bundle-info">
                                        <h5 class="ec-crosssale-bundle-name">
                                            <a href="{{ $product->url }}" title="{{ $product->name }}">
                                                {{ $product->name }}
                                            </a>
                                        </h5>
                                        <div class="ec-crosssale-bundle-price">
                                            <span class="ec-crosssale-price-sale">{{ format_price($bundlePrice) }}</span>
                                        </div>

                                        @if($product->variations->isNotEmpty())
                                            <div class="ec-crosssale-attributes-wrapper" data-product-id="{{ $product->id }}">
                                                <input type="hidden" name="id" class="ec-crosssale-variation-id" value="{{ $cartId }}" />
                                                {!! render_product_swatches($product, [
                                                    'selected' => $selectedAttrs,
                                                    'view' => 'plugins/ecommerce::themes.attributes.swatches-renderer-crosssale',
                                                ]) !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- Arrows will be appended here by Slick --}}
                </div>

                @if(EcommerceHelper::isCartEnabled())
                    <div class="ec-crosssale-bundle-footer">
                        <div class="ec-crosssale-bundle-total">
                            <span class="ec-crosssale-total-label">Total price:</span>
                            <span class="ec-crosssale-total-price" data-crosssale-total-price data-base-price="0">
                                {{ format_price(0) }}
                            </span>
                        </div>
                        <div class="ec-crosssale-bundle-saved" style="display: none;" data-crosssale-saved>
                            <span class="ec-crosssale-saved-label">Saved:</span>
                            <span class="ec-crosssale-saved-amount" data-crosssale-saved-amount>
                                {{ format_price(0) }}
                            </span>
                        </div>
                        <button
                            type="button"
                            class="ec-crosssale-bundle-add-all"
                            data-crosssale-add-all
                            data-url="{{ route('public.cart.add-to-cart') }}"
                            data-parent-product="{{ $parentProduct->slug }}"
                            disabled
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span data-crosssale-button-text>{{ __('Add Selected to Cart') }}</span>
                        </button>
                    </div>
                @endif
                    </div>
                </div>
            </div>
        </section>
        
        {{-- MOBILE ONLY: Simple crosssale with images + buy all button --}}
        <section class="ec-crosssale-mobile-simple" data-crosssale-mobile-simple>
            <div class="container">
                @php
                    $totalBundlePrice = 0;
                    $productCount = 0;
                    $bundleProductData = [];
                @endphp
                
                <div class="ec-crosssale-mobile-header">
                    <h2>{{ __('Frequently Bought Together') }}</h2>
                </div>

                <div class="ec-crosssale-mobile-images">
                    @foreach ($products as $product)
                        @php
                            $productVariation = $product;
                            $productImage = $product->image;

                            if ($product->variations->isNotEmpty()) {
                                [$productImages, $productVariation, $selectedAttrs] = \Botble\Ecommerce\Facades\EcommerceHelper::getProductVariationInfo($product);
                                if ($productImages && count($productImages) > 0) {
                                    $productImage = $productImages[0];
                                }
                            }

                            // Get the base price WITHOUT cross-sell discount applied
                            $basePrice = $productVariation->isOnSale() ? $productVariation->front_sale_price_with_taxes : $productVariation->price_with_taxes;
                            $bundleDiscountPrice = $product->pivot->price ?? 0;
                            $bundleDiscountType = $product->pivot->price_type ?? 'fixed';
                            $isPercentDiscount = $bundleDiscountType === 'percent' || $bundleDiscountType === \Botble\Ecommerce\Enums\CrossSellPriceType::PERCENT;

                            // Calculate bundle price from base price (not already-discounted price)
                            $bundlePrice = $basePrice;
                            if ($bundleDiscountPrice > 0) {
                                if ($isPercentDiscount) {
                                    $bundlePrice = $basePrice - ($basePrice * $bundleDiscountPrice / 100);
                                } else {
                                    $bundlePrice = max(0, $basePrice - $bundleDiscountPrice);
                                }
                            }

                            $displayPrice = $bundlePrice;
                            if (!$currency->is_default && $currency->exchange_rate > 0) {
                                $displayPrice = $bundlePrice * $currency->exchange_rate;
                            }

                            $totalBundlePrice += $displayPrice;
                            $productCount++;
                            
                            $cartId = $productVariation->is_variation ? $productVariation->id : $product->id;
                            $bundleProductData[] = [
                                'id' => $cartId,
                                'name' => $product->name,
                                'price' => $displayPrice,
                                'image' => $productImage,
                                'url' => $product->url,
                            ];
                        @endphp
                        
                        <a href="{{ $product->url }}" class="ec-crosssale-mobile-image-item">
                            {{ RvMedia::image($productImage, $product->name, 'thumb', true) }}
                        </a>
                        
                        @if(!$loop->last)
                            <div class="ec-crosssale-mobile-plus-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="ec-crosssale-mobile-btn-wrapper">
                    <button 
                        type="button" 
                        class="ec-crosssale-mobile-buy-btn"
                        data-crosssale-mobile-modal-open
                        data-bundle-products="{{ json_encode($bundleProductData) }}"
                        data-total-price="{{ $totalBundlePrice }}"
                        data-parent-product="{{ $parentProduct->slug }}"
                    >
                        <span>Buy all ({{ $productCount }}): {{ format_price($totalBundlePrice) }}</span>
                        <i class="ph ph-caret-right"></i>
                    </button>
                </div>
            </div>
        </section>

        {{-- Mobile Modal --}}
        @include('plugins/ecommerce::themes.includes.product-bundle-modal', [
            'modalId' => 'crosssale-mobile-modal',
            'modalTitle' => __('Frequently Bought Together'),
            'products' => $products
        ])
        </div>
        {{-- End wrapper for refresh --}}
    @endif
@elseif(isset($parentProduct))
    <div data-bb-toggle="block-lazy-loading" data-url="{{ route('public.ajax.cross-sale-products', $parentProduct) }}">
        <section class="ec-crosssale-skeleton">
            <div class="container">
                <div class="ec-crosssale-skeleton-wrapper">
                    <div class="ec-crosssale-skeleton-header">
                        <div class="skeleton skeleton-icon"></div>
                        <div class="skeleton-title-group">
                            <div class="skeleton skeleton-title"></div>
                            <div class="skeleton skeleton-subtitle"></div>
                        </div>
                    </div>
                    <div class="ec-crosssale-skeleton-items">
                        @for ($i = 0; $i < 2; $i++)
                            <div class="ec-crosssale-skeleton-item">
                                <div class="skeleton skeleton-checkbox"></div>
                                <div class="skeleton skeleton-thumb"></div>
                                <div class="skeleton-info">
                                    <div class="skeleton skeleton-name"></div>
                                    <div class="skeleton skeleton-price"></div>
                                </div>
                                <div class="skeleton skeleton-action"></div>
                            </div>
                        @endfor
                    </div>
                    <div class="ec-crosssale-skeleton-footer">
                        <div class="skeleton-total">
                            <div class="skeleton skeleton-label"></div>
                            <div class="skeleton skeleton-price"></div>
                        </div>
                        <div class="skeleton skeleton-btn"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endif

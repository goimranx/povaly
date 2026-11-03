@if(request()->ajax() && isset($products))
    @if($products->isNotEmpty())
        @php
            $cartContent = Cart::instance('cart')->content();
            $cartProductIds = $cartContent->pluck('id')->filter()->unique()->toArray();
            $parentProductInCart = false;

            if (!empty($cartProductIds)) {
                $cartProducts = \Botble\Ecommerce\Models\Product::query()
                    ->whereIn('id', $cartProductIds)
                    ->with('variationInfo.configurableProduct')
                    ->get();

                $parentProductInCart = $cartProducts->contains(function ($product) use ($parentProduct) {
                    if ($product->id == $parentProduct->id) {
                        return true;
                    }
                    if ($product->is_variation && $product->variationInfo && $product->variationInfo->configurable_product_id) {
                        return $product->variationInfo->configurable_product_id == $parentProduct->id;
                    }
                    return false;
                });
            }

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
        <div data-upsale-bundle data-currency-config="{{ json_encode($currencyConfig) }}">
        <section class="ec-upsell-bundle">
            <div class="container">
                <div class="ec-upsell-bundle-wrapper">
                    <div class="ec-upsell-bundle-header">
                        <div class="ec-upsell-bundle-title">
                            <h2>{{ trans('plugins/ecommerce::products.up_sale.title') }}</h2>
                        </div>
                    </div>

                    <div class="ec-upsell-bundle-content">
                        <div class="ec-upsell-bundle-carousel-wrapper">
                            <div class="ec-upsell-bundle-list ec-upsell-carousel" data-upsale-carousel>
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

                                $originalPrice = $productVariation->price()->getPriceOriginal();
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

                                $hasDiscount = $bundlePrice < $salePrice || $salePrice < $originalPrice;
                                $showOriginalPrice = $bundlePrice < $salePrice ? $salePrice : ($salePrice < $originalPrice ? $originalPrice : null);

                                $displayPrice = $bundlePrice;
                                if (!$currency->is_default && $currency->exchange_rate > 0) {
                                    $displayPrice = $bundlePrice * $currency->exchange_rate;
                                }

                                $cartId = $productVariation->is_variation ? $productVariation->id : $product->id;
                            @endphp
                            <div
                                @class([
                                    'ec-upsell-bundle-item',
                                    'has-variations' => $product->variations->isNotEmpty(),
                                    'is-locked' => ! $parentProductInCart,
                                ])
                                data-product-id="{{ $product->id }}"
                                data-upsale-bundle-item
                                @unless($parentProductInCart)
                                    title="{{ trans('plugins/ecommerce::products.up_sale.unlock_discount', ['product' => $parentProduct->name]) }}"
                                @endunless
                            >
                                <div class="ec-upsell-bundle-item-inner">
                                    <div class="ec-upsell-bundle-checkbox">
                                        <input
                                            type="checkbox"
                                            class="ec-upsell-checkbox"
                                            data-upsale-checkbox
                                            aria-label="{{ __('Select :name', ['name' => $product->name]) }}"
                                            data-id="{{ $cartId }}"
                                            data-price="{{ $displayPrice }}"
                                            data-name="{{ $product->name }}"
                                            data-bundle-discount="{{ $bundleDiscountPrice }}"
                                            data-bundle-discount-type="{{ $isPercentDiscount ? 'percent' : 'fixed' }}"
                                            data-original-price="{{ $salePrice * ($currency->is_default ? 1 : $currency->exchange_rate) }}"
                                            data-saved-amount="{{ ($salePrice - $bundlePrice) * ($currency->is_default ? 1 : $currency->exchange_rate) }}"
                                            @checked($parentProductInCart)
                                            @disabled(! $parentProductInCart)
                                        >
                                        <span class="ec-upsell-checkmark">
                                            @unless($parentProductInCart)
                                                <svg class="ec-upsell-lock-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                </svg>
                                            @endunless
                                        </span>
                                    </div>

                                    <div class="ec-upsell-bundle-thumb">
                                        <a href="{{ $product->url }}">
                                            {{ RvMedia::image($productImage, $product->name, 'thumb', true) }}
                                        </a>
                                    </div>

                                    <div class="ec-upsell-bundle-info">
                                        <h5 class="ec-upsell-bundle-name">
                                            <a href="{{ $product->url }}" title="{{ $product->name }}">
                                                {{ Str::limit($product->name, 50) }}
                                            </a>
                                        </h5>
                                        <div class="ec-upsell-bundle-price">
                                            @if($showOriginalPrice)
                                                <span class="ec-upsell-price-original">{{ format_price($showOriginalPrice) }}</span>
                                            @endif
                                            <span class="ec-upsell-price-sale">{{ format_price($bundlePrice) }}</span>
                                            @if($bundleDiscountPrice > 0)
                                                {{-- Badge sits inline next to the sale price (was absolute on
                                                     the thumb and clipped product images). --}}
                                                <span class="ec-upsell-discount-badge ec-upsell-discount-badge--inline">
                                                    @if($isPercentDiscount)
                                                        -{{ (int) $bundleDiscountPrice }}%
                                                    @else
                                                        -{{ format_price($bundleDiscountPrice) }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>

                                        @if($product->variations->isNotEmpty())
                                            <div class="ec-upsell-attributes-wrapper" data-product-id="{{ $product->id }}">
                                                <input type="hidden" name="id" class="ec-upsell-variation-id" value="{{ $cartId }}" />
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
                    </div>
                    <div class="ec-upsell-bundle-arrows"></div>
                </div>

                @if(EcommerceHelper::isCartEnabled())
                    <div class="ec-upsell-bundle-footer">
                            @if(!$parentProductInCart)
                                <div class="ec-upsell-bundle-notice">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="8" x2="12" y2="12"></line>
                                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                    </svg>
                                    <span>{{ trans('plugins/ecommerce::products.up_sale.unlock_discount', ['product' => $parentProduct->name]) }}</span>
                                </div>
                            @else
                                <div>
                                    <div class="ec-upsell-bundle-total">
                                        <span class="ec-upsell-total-label">Total price:</span>
                                        <span class="ec-upsell-total-price" data-upsale-total-price data-base-price="0">
                                            {{ format_price(0) }}
                                        </span>
                                    </div>
                                    <div class="ec-upsell-bundle-saved" style="display: none;" data-upsale-saved>
                                        <span class="ec-upsell-saved-label">Saved:</span>
                                        <span class="ec-upsell-saved-amount" data-upsale-saved-amount>
                                            {{ format_price(0) }}
                                        </span>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="ec-upsell-bundle-add-all"
                                    data-upsale-add-all
                                    data-url="{{ route('public.cart.add-to-cart') }}"
                                    data-parent-product="{{ $parentProduct->slug }}"
                                    disabled
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span data-upsale-button-text>{{ trans('plugins/ecommerce::products.up_sale.add_selected_to_cart') }}</span>
                                </button>
                            @endif
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </section>
        
        {{-- MOBILE ONLY: Simple upsell with images + buy all button --}}
        <section class="ec-upsell-mobile-simple" data-upsale-mobile-simple @class(['is-locked' => !$parentProductInCart])>
            <div class="container">
                @php
                    $totalBundlePrice = 0;
                    $productCount = 0;
                    $bundleProductData = [];
                @endphp
                
                <div class="ec-upsell-mobile-header">
                    <h2>{{ trans('plugins/ecommerce::products.up_sale.title') }}</h2>
                </div>

                <div class="ec-upsell-mobile-images">
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
                        
                        <a href="{{ $product->url }}" class="ec-upsell-mobile-image-item" @unless($parentProductInCart) onclick="return false;" @endunless>
                            {{ RvMedia::image($productImage, $product->name, 'thumb', true) }}
                        </a>
                        
                        @if(!$loop->last)
                            <div class="ec-upsell-mobile-plus-icon">
                                <i class="ph ph-plus"></i>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="ec-upsell-mobile-btn-wrapper">
                    @if($parentProductInCart)
                        <button 
                            type="button" 
                            class="ec-upsell-mobile-buy-btn"
                            data-upsale-mobile-modal-open
                            data-bundle-products="{{ json_encode($bundleProductData) }}"
                            data-total-price="{{ $totalBundlePrice }}"
                            data-parent-product="{{ $parentProduct->slug }}"
                        >
                            <span>Buy all ({{ $productCount }}): {{ format_price($totalBundlePrice) }}</span>
                            <i class="ph ph-caret-right"></i>
                        </button>
                    @else
                        <button 
                            type="button" 
                            class="ec-upsell-mobile-buy-btn ec-upsell-mobile-buy-btn--locked"
                            disabled
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span class="ec-upsell-mobile-unlock-text">{{ trans('plugins/ecommerce::products.up_sale.unlock_discount', ['product' => $parentProduct->name]) }}</span>
                        </button>
                    @endif
                </div>
            </div>
        </section>

        {{-- Mobile Modal --}}
        @include('plugins/ecommerce::themes.includes.product-bundle-modal', [
            'modalId' => 'upsale-mobile-modal',
            'modalTitle' => trans('plugins/ecommerce::products.up_sale.title'),
            'products' => $products
        ])
        </div>
        {{-- End wrapper for refresh --}}
        
        <style>
            /* Fix for single-item carousel display */
            .ec-upsell-carousel.slick-initialized {
                /* When there's only 1 item, make it display at proper width */
                .slick-slide:only-child {
                    width: 381px !important;
                    max-width: 100%;
                }
                
                /* Center single item */
                .slick-track:has(.slick-slide:only-child) {
                    justify-content: center;
                }
            }
        </style>
    @endif
@else
    <div data-bb-toggle="block-lazy-loading" data-url="{{ route('public.ajax.up-sale-products', $parentProduct) }}">
        <section class="ec-upsell-skeleton">
            <div class="container">
                <div class="ec-upsell-skeleton-wrapper">
                    <div class="ec-upsell-skeleton-header">
                        <div class="skeleton skeleton-icon"></div>
                        <div class="skeleton-title-group">
                            <div class="skeleton skeleton-title"></div>
                            <div class="skeleton skeleton-subtitle"></div>
                        </div>
                    </div>
                    <div class="ec-upsell-skeleton-items">
                        @for ($i = 0; $i < 2; $i++)
                            <div class="ec-upsell-skeleton-item">
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
                    <div class="ec-upsell-skeleton-footer">
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

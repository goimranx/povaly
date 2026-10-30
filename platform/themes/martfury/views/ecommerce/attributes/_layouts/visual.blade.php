@php
    $displayAttributes = $attributes->where('attribute_set_id', $set->id);
@endphp

@if ($displayAttributes && $displayAttributes->isNotEmpty())
    @php
        $selectedAttr  = $selected->where('attribute_set_id', $set->id)->first();
        $selectedTitle = $selectedAttr ? $selectedAttr->title : '';
        $setId         = 'swatch-set-' . $set->id . '-' . $product->id;
        $key           = $key ?? mt_rand();
    @endphp

    <div class="bb-product-attribute-swatch attribute-swatches-wrapper qs-swatch-wrap"
         data-type="visual"
         data-slug="{{ $set->slug }}">

        {{-- Label: "Color: Black" --}}
        <div class="qs-swatch__label">
            <span class="qs-swatch__set-name">{{ $set->title }}:</span>
            <span class="qs-swatch__selected-val" data-default="{{ $selectedTitle }}">{{ $selectedTitle }}</span>
        </div>

        {{-- Grid wrapper with prev/next pagination --}}
        <div class="qs-var-grid-wrap" id="{{ $setId }}">

            <button type="button" class="qs-var-grid__arrow qs-var-grid__arrow--prev" style="display:none;" aria-label="{{ __('Previous') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>

            <div class="qs-var-grid__viewport">
                <ul class="qs-var-grid bb-product-attribute-swatch-list visual-swatch color-swatch attribute-swatch">
                    @foreach ($displayAttributes as $attribute)
                        @php
                            $isDisabled  = $variationInfo->where('id', $attribute->id)->isEmpty();
                            $isSelected  = $selected->where('id', $attribute->id)->isNotEmpty();
                            
                            // Get image: first try variation product image directly (regardless of use_image_from_product_variation flag),
                            // then fall back to attribute's own image
                            $imgUrl = null;
                            foreach ($productVariations as $pv) {
                                $pvAttr = $pv->productAttributes->where('attribute_set_id', $set->id)->first();
                                if ($pvAttr && $pvAttr->id == $attribute->id) {
                                    if ($pv->product && $pv->product->image) {
                                        $imgUrl = RvMedia::getImageUrl($pv->product->image, 'thumb');
                                        break;
                                    }
                                }
                            }
                            if (!$imgUrl && $attribute->image) {
                                $imgUrl = RvMedia::getImageUrl($attribute->image, 'thumb');
                            }

                            // Get prices for this variation - store all matching variation IDs
                            $varPrice = null;
                            $varOriginalPrice = null;
                            $varStock = null;
                            $matchingVariationIds = [];
                            
                            foreach ($productVariations as $pv) {
                                $pvAttr = $pv->productAttributes->where('attribute_set_id', $set->id)->first();
                                if ($pvAttr && $pvAttr->id == $attribute->id && $pv->product) {
                                    // Store all variation IDs that have this attribute
                                    $matchingVariationIds[] = $pv->product_id;
                                    
                                    // Use first match for initial price display
                                    if ($varPrice === null) {
                                        $pvProduct = $pv->product;
                                        // Use front_sale_price if set, else price, else fall back to parent product price
                                        $pvSalePrice = $pvProduct->front_sale_price ?? $pvProduct->price ?? null;
                                        $pvBasePrice = $pvProduct->price ?? null;
                                        
                                        if ($pvSalePrice && $pvSalePrice > 0) {
                                            $varPrice = $pvSalePrice;
                                        } elseif ($pvBasePrice && $pvBasePrice > 0) {
                                            $varPrice = $pvBasePrice;
                                        } else {
                                            // Fall back to parent product price
                                            $varPrice = $product->front_sale_price ?? $product->price;
                                        }
                                        
                                        $varOriginalPrice = ($pvBasePrice && $pvSalePrice && $pvSalePrice < $pvBasePrice)
                                            ? $pvBasePrice
                                            : null;
                                        
                                        // Get stock for this variation
                                        if ($pvProduct->with_storehouse_management) {
                                            $varStock = $pvProduct->quantity;
                                        }
                                    }
                                }
                            }
                            
                            // Final fallback: use parent product price
                            if (!$varPrice || $varPrice <= 0) {
                                $varPrice = $product->front_sale_price ?? $product->price;
                            }
                        @endphp
                        <li class="qs-var-item bb-product-attribute-swatch-item attribute-swatch-item {{ $isDisabled ? 'disabled' : '' }} {{ $isSelected ? 'selected' : '' }}"
                            data-slug="{{ $attribute->slug }}"
                            data-id="{{ $attribute->id }}"
                            data-title="{{ $attribute->title }}"
                            data-stock="{{ $varStock !== null ? $varStock : '' }}"
                            data-variation-image="{{ $imgUrl }}"
                            data-variation-ids="{{ json_encode($matchingVariationIds) }}"
                            @if($isDisabled) data-bs-toggle="tooltip" data-bs-title="{{ __('Not available') }}" @endif>
                            <label>
                                <input type="radio"
                                       name="attribute_{{ $set->slug }}_{{ $key }}"
                                       data-slug="{{ $attribute->slug }}"
                                       @if(!empty($referenceProduct)) data-reference-product="{{ $referenceProduct->slug }}" @endif
                                       value="{{ $attribute->id }}"
                                       @checked($isSelected)
                                       class="product-filter-item">

                                <div class="qs-var-item__thumb">
                                    @if($imgUrl)
                                        <img src="{{ $imgUrl }}" alt="{{ $attribute->title }}" loading="lazy" />
                                    @else
                                        {{-- No image: show color swatch filling the thumbnail --}}
                                        <span class="qs-var-item__color-fill"
                                              style="background-color: {{ $attribute->color ?: '#cccccc' }};"></span>
                                    @endif
                                </div>

                                @if($varPrice !== null)
                                    <div class="qs-var-item__price" data-variation-price="{{ round($varPrice) }}" data-variation-original-price="{{ $varOriginalPrice ? round($varOriginalPrice) : '' }}">
                                        <span class="qs-var-item__price-sale">{{ format_price(round($varPrice)) }}</span>
                                        @if($varOriginalPrice)
                                            <del class="qs-var-item__price-orig">{{ format_price(round($varOriginalPrice)) }}</del>
                                        @endif
                                    </div>
                                @endif
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>

            <button type="button" class="qs-var-grid__arrow qs-var-grid__arrow--next" style="display:none;" aria-label="{{ __('Next') }}">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>

        </div>
    </div>
@endif

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

                            // Get price for this variation
                            $varPrice = null;
                            foreach ($productVariations as $pv) {
                                $pvAttr = $pv->productAttributes->where('attribute_set_id', $set->id)->first();
                                if ($pvAttr && $pvAttr->id == $attribute->id && $pv->product) {
                                    $varPrice = $pv->product->front_sale_price ?? $pv->product->price;
                                    break;
                                }
                            }
                        @endphp
                        <li class="qs-var-item bb-product-attribute-swatch-item attribute-swatch-item {{ $isDisabled ? 'disabled' : '' }} {{ $isSelected ? 'selected' : '' }}"
                            data-slug="{{ $attribute->slug }}"
                            data-id="{{ $attribute->id }}"
                            data-title="{{ $attribute->title }}"
                            @if($isDisabled) data-bs-toggle="tooltip" data-bs-title="{{ __('Not available') }}" @endif>
                            <label>
                                <input type="radio"
                                       name="attribute_{{ $set->slug }}_{{ $key }}"
                                       data-slug="{{ $attribute->slug }}"
                                       @if(!empty($referenceProduct)) data-reference-product="{{ $referenceProduct->slug }}" @endif
                                       value="{{ $attribute->id }}"
                                       @checked($isSelected)
                                       class="product-filter-item"
                                       @if($isDisabled) disabled @endif>

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
                                    <div class="qs-var-item__price">{{ format_price($varPrice) }}</div>
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

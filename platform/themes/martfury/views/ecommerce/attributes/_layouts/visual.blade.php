@php
    $displayAttributes = $attributes->where('attribute_set_id', $set->id);
@endphp

@if ($displayAttributes && $displayAttributes->isNotEmpty())
    @php
        // Find the currently selected attribute title for this set
        $selectedAttr = $selected->where('attribute_set_id', $set->id)->first();
        $selectedTitle = $selectedAttr ? $selectedAttr->title : '';
        $hasImages = false;
        foreach ($displayAttributes as $attr) {
            $imgUrl = $attr->getAttributeImageUrl($set, $productVariations);
            if ($imgUrl) { $hasImages = true; break; }
        }
        $setId = 'swatch-' . $set->id . '-' . $product->id;
    @endphp

    <div class="bb-product-attribute-swatch attribute-swatches-wrapper qs-swatch-wrap {{ $hasImages ? 'qs-swatch--has-images' : 'qs-swatch--colors' }}"
         data-type="visual"
         data-slug="{{ $set->slug }}">

        {{-- Attribute label + selected value --}}
        <div class="qs-swatch__label">
            <span class="qs-swatch__set-name">{{ $set->title }}:</span>
            <span class="qs-swatch__selected-val" data-default="{{ $selectedTitle }}">{{ $selectedTitle }}</span>
        </div>

        @if ($hasImages)
            {{-- Image-based variation grid with pagination --}}
            <div class="qs-var-grid-wrap" id="{{ $setId }}">
                <button type="button" class="qs-var-grid__arrow qs-var-grid__arrow--prev" aria-label="Previous" style="display:none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                </button>

                <div class="qs-var-grid__viewport">
                    <div class="qs-var-grid bb-product-attribute-swatch-list visual-swatch color-swatch attribute-swatch">
                        @foreach ($displayAttributes as $attribute)
                            @php
                                $isDisabled = $variationInfo->where('id', $attribute->id)->isEmpty();
                                $imgUrl = $attribute->getAttributeImageUrl($set, $productVariations);
                                $isSelected = $selected->where('id', $attribute->id)->isNotEmpty();

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
                                            <span class="qs-var-item__color" style="{{ $attribute->getAttributeStyle($set, $productVariations) }}"></span>
                                        @endif
                                    </div>
                                    @if($varPrice !== null)
                                        <div class="qs-var-item__price">{{ format_price($varPrice) }}</div>
                                    @endif
                                </label>
                            </li>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="qs-var-grid__arrow qs-var-grid__arrow--next" aria-label="Next">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                </button>
            </div>

        @else
            {{-- Color/text swatches (no images) --}}
            <ul class="bb-product-attribute-swatch-list visual-swatch color-swatch attribute-swatch qs-color-list">
                @foreach ($displayAttributes as $attribute)
                    @php
                        $isDisabled = $variationInfo->where('id', $attribute->id)->isEmpty();
                        $style = $attribute->getAttributeStyle($set, $productVariations);
                        $isSelected = $selected->where('id', $attribute->id)->isNotEmpty();
                    @endphp
                    <li class="bb-product-attribute-swatch-item attribute-swatch-item qs-color-item {{ $isDisabled ? 'disabled' : '' }} {{ $isSelected ? 'selected' : '' }}"
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
                            <span class="bb-product-attribute-swatch-display qs-color-swatch" @if($style) style="{{ $style }}" @endif></span>
                            <span class="bb-product-attribute-swatch-item-tooltip">{{ $attribute->title }}</span>
                        </label>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endif

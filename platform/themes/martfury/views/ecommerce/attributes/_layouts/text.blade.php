@php
    $displayAttributes = $attributes->where('attribute_set_id', $set->id);
@endphp

@if ($displayAttributes && $displayAttributes->isNotEmpty())
    @php
        $selectedAttr  = $selected->where('attribute_set_id', $set->id)->first();
        $selectedTitle = $selectedAttr ? $selectedAttr->title : '';
        $key           = $key ?? mt_rand();
    @endphp

    <div class="bb-product-attribute-swatch text-swatches-wrapper attribute-swatches-wrapper qs-swatch-wrap"
         data-type="text"
         data-slug="{{ $set->slug }}">

        {{-- Label: "Size: M" --}}
        <div class="qs-swatch__label">
            <span class="qs-swatch__set-name">{{ $set->title }}:</span>
            <span class="qs-swatch__selected-val" data-default="{{ $selectedTitle }}">{{ $selectedTitle }}</span>
        </div>

        <ul class="qs-color-list bb-product-attribute-swatch-list text-swatch attribute-swatch">
            @foreach ($displayAttributes as $attribute)
                @php
                    $isDisabled = !$variationInfo->where('id', $attribute->id)->isNotEmpty();
                    $isSelected = $selected->where('id', $attribute->id)->isNotEmpty();
                @endphp
                <li class="qs-color-item bb-product-attribute-swatch-item attribute-swatch-item {{ $isDisabled ? 'disabled' : '' }} {{ $isSelected ? 'selected' : '' }}"
                    data-slug="{{ $attribute->slug }}"
                    data-id="{{ $attribute->id }}"
                    data-title="{{ $attribute->title }}"
                    @if($isDisabled) data-bs-toggle="tooltip" data-bs-title="{{ __('Not available') }}" @endif>
                    <label>
                        <input
                            type="radio"
                            name="attribute_{{ $set->slug }}_{{ $key }}"
                            data-slug="{{ $attribute->slug }}"
                            @if(!empty($referenceProduct)) data-reference-product="{{ $referenceProduct->slug }}" @endif
                            value="{{ $attribute->id }}"
                            @checked($isSelected)
                            class="product-filter-item"
                        >{{ $attribute->title }}
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
@endif

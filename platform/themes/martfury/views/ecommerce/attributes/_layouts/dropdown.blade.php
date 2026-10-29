@php
    $displayAttributes = $attributes->where('attribute_set_id', $set->id);
@endphp

@if ($displayAttributes && $displayAttributes->isNotEmpty())
    @php
        $selectedAttr  = $selected->where('attribute_set_id', $set->id)->first();
        $selectedTitle = $selectedAttr ? $selectedAttr->title : '';
        $key           = $key ?? mt_rand();
    @endphp

    <div class="bb-product-attribute-swatch dropdown-swatches-wrapper attribute-swatches-wrapper qs-swatch-wrap"
         data-type="dropdown"
         data-slug="{{ $set->slug }}">

        {{-- Label: "Size: M" --}}
        <div class="qs-swatch__label">
            <span class="qs-swatch__set-name">{{ $set->title }}:</span>
            <span class="qs-swatch__selected-val" data-default="{{ $selectedTitle }}">{{ $selectedTitle }}</span>
        </div>

        <div class="qs-dropdown-wrap">
            <select class="qs-dropdown product-filter-item" name="attribute_{{ $set->slug }}_{{ $key }}">
                <option value="">{{ trans('plugins/ecommerce::products.select_attribute', ['name' => strtolower($set->title)]) }}</option>
                @foreach ($displayAttributes as $attribute)
                    @php
                        $isDisabled = !$variationInfo->where('id', $attribute->id)->isNotEmpty();
                        $isSelected = $selected->where('id', $attribute->id)->isNotEmpty();
                    @endphp
                    <option
                        data-id="{{ $attribute->id }}"
                        data-slug="{{ $attribute->slug }}"
                        data-title="{{ $attribute->title }}"
                        @if(!empty($referenceProduct)) data-reference-product="{{ $referenceProduct->slug }}" @endif
                        value="{{ $attribute->id }}"
                        @selected($isSelected)
                        @disabled($isDisabled)
                    >
                        {{ $attribute->title }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endif

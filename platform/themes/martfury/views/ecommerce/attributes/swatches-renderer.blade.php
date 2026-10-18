@php
    $key = mt_rand();
@endphp

<div
    class="product-attributes product-attribute-swatches"
    id="product-attributes-{{ $product->id }}"
    data-target="{{ route('public.web.get-variation-by-attributes', $product->getKey()) }}"
>
    @php
        $variationInfo = $productVariationsInfo;
        $variationNextIds = [];
    @endphp

    @foreach ($attributeSets as $set)
        @if (! $loop->first)
            @php
                $variationInfo = $productVariationsInfo->where('attribute_set_id', $set->id)->whereIn('variation_id', $variationNextIds);
            @endphp
        @endif

        @php
            // Try theme override first, fall back to plugin
            $themeLayout = Theme::getThemeNamespace() . '::views.ecommerce.attributes._layouts.' . $set->display_layout;
            $pluginLayout = 'plugins/ecommerce::themes.attributes._layouts.' . $set->display_layout;
            $layout = view()->exists($themeLayout) ? $themeLayout : $pluginLayout;
            if (!view()->exists($layout)) {
                $layout = view()->exists($themeLayout . 'dropdown') ? $themeLayout . 'dropdown' : 'plugins/ecommerce::themes.attributes._layouts.dropdown';
            }
        @endphp

        @include($layout)

        @php
            [$variationNextIds] = handle_next_attributes_in_product($attributes->where('attribute_set_id', $set->id), $productVariationsInfo, $set->id, $selected->pluck('id')->toArray(), $loop->index, $variationNextIds);
        @endphp
    @endforeach
</div>

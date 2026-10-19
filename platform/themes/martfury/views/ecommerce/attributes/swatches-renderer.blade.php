@php
    $key = mt_rand();

    // Build a variation price map: variation_id → {attrs: [id,...], price, original_price, available}
    // Used by JS to update swatch prices when another attribute is selected
    $variationPriceMap = [];
    if (isset($productVariations) && $productVariations) {
        foreach ($productVariations as $pv) {
            if (!$pv->product) continue;
            $attrIds = $pv->productAttributes->pluck('id')->toArray();
            $salePrice = $pv->product->front_sale_price ?? $pv->product->price ?? 0;
            $basePrice = $pv->product->price ?? 0;
            $variationPriceMap[] = [
                'attrs'          => $attrIds,
                'price'          => (float)$salePrice,
                'original_price' => ($basePrice > $salePrice) ? (float)$basePrice : null,
                'price_fmt'      => format_price($salePrice),
                'orig_fmt'       => ($basePrice > $salePrice) ? format_price($basePrice) : null,
                'available'      => !$pv->product->isOutOfStock(),
            ];
        }
    }
@endphp

<div
    class="product-attributes product-attribute-swatches"
    id="product-attributes-{{ $product->id }}"
    data-target="{{ route('public.web.get-variation-by-attributes', $product->getKey()) }}"
    data-update-url="false"
    data-variation-prices="{{ htmlspecialchars(json_encode($variationPriceMap), ENT_QUOTES) }}"
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

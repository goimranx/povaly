<div class="ps-shopping ps-tab-root">
    <div class="ps-tabs ps-products-listing bb-product-items-wrapper">
        @include(Theme::getThemeNamespace('views.ecommerce.includes.product-items'))
        {!! apply_filters('ecommerce_after_product_listing') !!}
    </div>
</div>

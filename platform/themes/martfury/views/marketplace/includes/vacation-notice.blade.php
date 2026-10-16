@php
    /**
     * Martfury-styled vendor vacation notice.
     *
     * Rendered in two places:
     *   - The single-store banner (views/marketplace/store.blade.php)
     *   - The product detail page, injected via the ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML
     *     filter. The plugin renders MarketplaceHelper::viewPath('includes.vacation-notice')
     *     which resolves to THIS theme override when present, so the notice picks up the
     *     theme's look instead of the plugin's generic Bootstrap alert.
     *
     * Inputs:
     *   $store  \Botble\Marketplace\Models\Store  (required)
     */
@endphp

@if (! empty($store) && method_exists($store, 'isOnVacation') && $store->isOnVacation())
    <div class="ps-store-vacation" role="status">
        <span class="ps-store-vacation__icon" aria-hidden="true">
            <i class="icon-clock"></i>
        </span>
        <span class="ps-store-vacation__body">
            <strong class="ps-store-vacation__title">
                {{ trans('plugins/marketplace::store.forms.vacation_badge') }}
            </strong>
            <span class="ps-store-vacation__text">
                @if (! empty($store->vacation_message))
                    {{ $store->vacation_message }}
                @else
                    {{ trans('plugins/marketplace::store.forms.vacation_default_notice', ['store' => $store->name]) }}
                @endif
            </span>
        </span>
    </div>
@endif

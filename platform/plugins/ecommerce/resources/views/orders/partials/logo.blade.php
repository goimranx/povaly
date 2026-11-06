@if ($logo = theme_option('logo_in_the_checkout_page') ?: Theme::getLogo())
    <div class="checkout-logo text-center text-sm-start">
        <a
            href="{{ route('public.cart') }}"
            title="{{ __('Go back to cart') }}"
            style="display: inline-flex; align-items: center; gap: 8px; color: var(--global-color-text-main); text-decoration: none;"
        >
            <i class="icon-chevron-left" style="font-size: 20px;"></i>
            <img
                src="{{ RvMedia::getImageUrl($logo) }}"
                alt="{{ $siteTitle = Theme::getSiteTitle() }}"
            />
        </a>
    </div>
    <hr class="border-dark-subtle" />
@endif

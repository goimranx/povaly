@php $mobileSticky = theme_option('header_mobile_sticky_element', 'topbar'); @endphp
<header class="header header--mobile {{ $mobileSticky === 'searchbar' ? 'header--mobile-sticky-search' : '' }}" data-sticky="{{ theme_option('sticky_header_mobile_enabled', 'yes') == 'yes' ? 'true' : 'false' }}">
    <div class="navigation--mobile">
        <div class="navigation__left">
            <a class="ps-logo" href="{{ BaseHelper::getHomepageUrl() }}">
                {!! Theme::getLogoImage(['style' => 'max-height: var(--mobile-logo-max-height, 40px)']) !!}
            </a>
        </div>
        @if (is_plugin_active('ecommerce'))
            <div class="navigation__right">
                <div class="header__actions">
                    {!! apply_filters('before_theme_header_mobile_actions', null) !!}
                    <div class="ps-cart--mini">
                        <a class="header__extra btn-shopping-cart" href="{{ route('public.cart') }}">
                            <i class="icon-bag2"></i><span><i>{{ Cart::instance('cart')->count() }}</i></span>
                        </a>
                        <div class="ps-cart--mobile">
                            {!! Theme::partial('cart') !!}
                        </div>
                    </div>
                    {!! apply_filters('after_theme_header_mobile_actions', null) !!}
                    <a class="header__extra header__extra--menu ps-toggle--sidebar" href="#menu-mobile" aria-label="{{ __('Menu') }}">
                        <svg class="mobile-menu-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="4" y1="6" x2="20" y2="6"/>
                            <line x1="4" y1="12" x2="16" y2="12"/>
                            <line x1="4" y1="18" x2="12" y2="18"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endif
    </div>
    @if (is_plugin_active('ecommerce'))
        <div class="ps-search--mobile">
            <form class="ps-form--search-mobile" action="{{ route('public.products') }}" data-ajax-url="{{ route('public.ajax.search-products') }}" method="get">
                <div class="form-group--nest position-relative">
                    <input class="form-control input-search-product" name="q" value="{{ BaseHelper::stringify(request()->query('q')) }}" type="text" autocomplete="off" placeholder="{{ __('Search something...') }}">
                    <div class="spinner-icon">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                    <button type="submit" title="{{ __('Search') }}"><i class="icon-magnifier"></i></button>
                    <div class="ps-panel--search-result"></div>
                </div>
            </form>
        </div>
    @endif
</header>

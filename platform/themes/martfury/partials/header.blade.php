{!! Theme::partial('header-meta') !!}
    <body {!! Theme::bodyAttributes() !!} @if (Theme::get('pageId')) id="{{ Theme::get('pageId') }}" @endif>
        {!! apply_filters(THEME_FRONT_BODY, null) !!}
        <div id="alert-container"></div>

        @if (theme_option('preloader_enabled', 'no') == 'yes')
            <div id="loader-wrapper">
                <div class="preloader-circle">
                    <svg class="preloader-spinner" viewBox="0 0 100 100">
                        <circle class="preloader-spinner-path" cx="50" cy="50" r="42" fill="none" stroke-width="4"/>
                    </svg>
                    @php
                        $preloaderImage = theme_option('preloader_image');
                        $defaultLogo = theme_option('logo');
                    @endphp
                    @if ($preloaderImage)
                        <img src="{{ RvMedia::getImageUrl($preloaderImage) }}" alt="Loading..." class="preloader-logo">
                    @elseif ($defaultLogo)
                        <img src="{{ RvMedia::getImageUrl($defaultLogo) }}" alt="Loading..." class="preloader-logo">
                    @endif
                </div>
            </div>
        @endif

        {!! Theme::get('topHeader') !!}

        <header class="header header--1" data-sticky="{{ Theme::get('stickyHeader', theme_option('sticky_header_enabled', 'yes') == 'yes' ? 'true' : 'false') }}">
            <div class="header__top">
                <div class="ps-container align-items-center">
                    @if (is_plugin_active('ecommerce'))
                        <div class="header__left">
                            <div class="menu--product-categories">
                                <div class="menu__toggle"><i class="icon-menu"></i><span> {{ __('Shop by Department') }}</span></div>
                                <div class="menu__content" style="display: none">
                                    <ul class="menu--dropdown">
                                        @php
                                            $categoriesDropdown = Theme::partial('product-categories-dropdown', ['categories' => ProductCategoryHelper::getProductCategoriesWithUrl()]);
                                        @endphp
                                        {!! $categoriesDropdown ?? null !!}
                                    </ul>
                                </div>
                            </div>
                            <a class="ps-logo" href="{{ BaseHelper::getHomepageUrl() }}">
                                {!! Theme::getLogoImage(['style' => 'max-height: var(--logo-max-height, 40px)']) !!}
                            </a>
                        </div>
                        <div class="header__center">
                            <form class="ps-form--quick-search" action="{{ route('public.products') }}" data-ajax-url="{{ route('public.ajax.search-products') }}" method="get">
                                <div class="form-group--icon">
                                    <div class="product-cat-label">{{ __('All') }}</div>
                                    <select class="form-control product-category-select" name="categories[]" aria-label="{{ __('Product categories') }}">
                                        <option value="0">{{ __('All') }}</option>
                                        {!! ProductCategoryHelper::renderProductCategoriesSelect() !!}
                                    </select>
                                </div>
                                <input class="form-control input-search-product" name="q" type="text" placeholder="{{ __("I'm shopping for...") }}" autocomplete="off">
                                <div class="spinner-icon">
                                    <i class="fa fa-spin fa-spinner"></i>
                                </div>
                                <button type="submit" class="btn-search-submit" data-style="{{ theme_option('search_button_style', 'text') }}">
                                    @if(theme_option('search_button_style', 'text') === 'icon')
                                        <i class="icon-magnifier"></i>
                                    @elseif(theme_option('search_button_style', 'text') === 'icon_text')
                                        <i class="icon-magnifier"></i> <span>{{ __('Search') }}</span>
                                    @else
                                        {{ __('Search') }}
                                    @endif
                                </button>
                                <div class="ps-panel--search-result"></div>
                            </form>
                        </div>
                        <div class="header__right">
                            <div class="header__actions">
                                {!! apply_filters('before_theme_header_actions', null) !!}
                                @if (EcommerceHelper::isCompareEnabled())
                                    <a class="header__extra btn-compare" href="{{ route('public.compare') }}" title="{{ __('Compare products') }}"><i class="icon-chart-bars"></i><span><i>{{ Cart::instance('compare')->count() }}</i></span></a>
                                @endif
                                @if (EcommerceHelper::isWishlistEnabled())
                                    <a class="header__extra btn-wishlist" href="{{ route('public.wishlist') }}" title="{{ __('Wishlist') }}"><i class="icon-heart"></i><span><i>{{ !auth('customer')->check() ? Cart::instance('wishlist')->count() : auth('customer')->user()->wishlist()->count() }}</i></span></a>
                                @endif
                                @if (EcommerceHelper::isCartEnabled())
                                    <div class="ps-cart--mini">
                                        <a class="header__extra btn-shopping-cart" href="{{ route('public.cart') }}" title="{{ __('Shopping cart') }}"><i class="icon-bag2"></i><span><i>{{ Cart::instance('cart')->count() }}</i></span></a>
                                        <div class="ps-cart--mobile">
                                            {!! Theme::partial('cart') !!}
                                        </div>
                                    </div>
                                @endif
                                {!! apply_filters('after_theme_header_actions', null) !!}
                                <div class="ps-block--user-header">
                                    <div class="ps-block__left"><i class="icon-user"></i></div>
                                    <div class="ps-block__right">
                                        @if (auth('customer')->check())
                                            <a href="{{ route('customer.overview') }}" class="customer-name">{{ auth('customer')->user()->name }}</a>
                                            <a href="{{ route('customer.logout') }}">{{ __('Logout') }}</a>
                                        @else
                                            <a href="{{ route('customer.login') }}">{{ __('Login') }}</a><a href="{{ route('customer.register') }}">{{ __('Register') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <nav class="navigation">
                <div class="ps-container">
                    <div class="navigation__left">
                        <div class="menu--product-categories">
                            <div class="menu__toggle"><i class="icon-menu"></i><span> {{ __('Shop by Department') }}</span></div>
                            <div class="menu__content" style="display: none">
                                <ul class="menu--dropdown">
                                    {!! $categoriesDropdown ?? null !!}
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="navigation__right">
                        {!! Menu::renderMenuLocation('main-menu', [
                            'view'    => 'menu',
                            'options' => ['class' => 'menu'],
                        ]) !!}
                        @if (is_plugin_active('ecommerce'))
                            <ul class="navigation__extra">
                                @if (is_plugin_active('marketplace') && theme_option('show_sell_on_marketplace_link', 'yes') == 'yes')
                                    <li class="sell-on-marketplace-link"><a href="{{ !auth('customer')->check() ? route('customer.register') : (auth('customer')->user()->is_vendor ? route('marketplace.vendor.dashboard') : route('marketplace.vendor.become-vendor')) }}">{{ theme_option('sell_on_site_text') ?: __('Sell On Martfury') }}</a></li>
                                @endif
                                @if (EcommerceHelper::isOrderTrackingEnabled())
                                    <li><a href="{{ route('public.orders.tracking') }}">{{ __('Track your order') }}</a></li>
                                @endif
                                @if (is_plugin_active('language'))
                                    {!! Theme::partial('language-switcher') !!}
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </nav>
        </header>
        @if (Theme::get('headerMobile'))
            {!! Theme::get('headerMobile') !!}
        @else
            {!! Theme::partial('header-mobile') !!}
        @endif
        @if (is_plugin_active('ecommerce'))
            <div class="ps-panel--sidebar" id="cart-mobile" style="display: none">
                <div class="ps-panel__header">
                    <div class="ps-panel__user-greeting">
                        @auth('customer')
                            <a href="{{ route('customer.overview') }}" class="user-greeting-link">
                                <div class="user-avatar">
                                    <img src="{{ auth('customer')->user()->avatar_url }}" alt="{{ auth('customer')->user()->name }}" onerror="this.src='{{ asset('vendor/core/core/base/images/default-user.png') }}'">
                                </div>
                                <div class="user-info">
                                    <span class="greeting">{{ __('Hello') }},</span>
                                    <span class="user-name">{{ auth('customer')->user()->name ? Str::limit(explode(' ', auth('customer')->user()->name)[0], 15) : __('User') }}</span>
                                </div>
                            </a>
                        @else
                            <div class="user-greeting-guest">
                                <div class="user-avatar user-avatar-guest">
                                    <i class="icon-bag2"></i>
                                </div>
                                <div class="user-info">
                                    <span class="greeting">{{ __('Welcome') }}!</span>
                                    <div class="guest-actions">
                                        <a href="{{ route('customer.login') }}" class="btn-guest-action btn-login">{{ __('Login') }}</a>
                                        <a href="{{ route('customer.register') }}" class="btn-guest-action btn-register">{{ __('Register') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                    <h3 class="ps-panel__title">{{ __('Shopping Cart') }}</h3>
                </div>
                <div class="navigation__content">
                    <div class="ps-cart--mobile">
                        {!! Theme::partial('cart') !!}
                    </div>
                </div>
            </div>
            <div class="ps-panel--sidebar" id="navigation-mobile" style="display: none">
                <div class="ps-panel__header">
                    <div class="ps-panel__user-greeting">
                        @auth('customer')
                            <a href="{{ route('customer.overview') }}" class="user-greeting-link">
                                <div class="user-avatar">
                                    <img src="{{ auth('customer')->user()->avatar_url }}" alt="{{ auth('customer')->user()->name }}" onerror="this.src='{{ asset('vendor/core/core/base/images/default-user.png') }}'">
                                </div>
                                <div class="user-info">
                                    <span class="greeting">{{ __('Hello') }},</span>
                                    <span class="user-name">{{ auth('customer')->user()->name ? Str::limit(explode(' ', auth('customer')->user()->name)[0], 15) : __('User') }}</span>
                                </div>
                            </a>
                        @else
                            <div class="user-greeting-guest">
                                <div class="user-avatar user-avatar-guest">
                                    <i class="icon-list4"></i>
                                </div>
                                <div class="user-info">
                                    <span class="greeting">{{ __('Welcome') }}!</span>
                                    <div class="guest-actions">
                                        <a href="{{ route('customer.login') }}" class="btn-guest-action btn-login">{{ __('Login') }}</a>
                                        <a href="{{ route('customer.register') }}" class="btn-guest-action btn-register">{{ __('Register') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                    <h3 class="ps-panel__title">{{ __('Categories') }}</h3>
                </div>
                <div class="ps-panel__content">
                    <ul class="menu--mobile" id="categories-mobile-menu">
                        {!! $categoriesDropdown ?? null !!}
                    </ul>
                </div>
            </div>
        @endif

        @if (Theme::get('showBottomBarMenu', true))
            <div class="navigation--list" @if(theme_option('bottom_bar_menu_show_text', 'yes') != 'yes')data-hide-text="true"@endif style="--bottom-bar-menu-text-font-size: {{ theme_option('bottom_bar_menu_text_font_size', 14) }}px;">
                <div class="navigation__content">
                    {{-- Smart first item: Logo on homepage, Home icon on other pages, Back to top when scrolled --}}
                    @php
                        $isHomePage = request()->is('/') || request()->is(app()->getLocale());
                    @endphp
                    <a class="navigation__item navigation__item--smart" 
                       href="{{ route('public.index') }}" 
                       data-home-page="{{ $isHomePage ? 'true' : 'false' }}"
                       data-homepage-url="{{ route('public.index') }}"
                       data-logo-img="{{ theme_option('bottom_bar_logo') ? RvMedia::getImageUrl(theme_option('bottom_bar_logo')) : (theme_option('logo') ? RvMedia::getImageUrl(theme_option('logo')) : '') }}"
                       id="bb-smart-nav">
                        @if($isHomePage)
                            <span class="bb-logo-wrap">
                                @if(theme_option('bottom_bar_logo'))
                                    <img src="{{ RvMedia::getImageUrl(theme_option('bottom_bar_logo')) }}" alt="{{ theme_option('site_title') }}" class="bb-logo-img">
                                @elseif(theme_option('logo'))
                                    <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="{{ theme_option('site_title') }}" class="bb-logo-img">
                                @else
                                    <i class="icon-home"></i>
                                @endif
                            </span>
                        @else
                            <i class="icon-home"></i><span>{{ __('Home') }}</span>
                        @endif
                    </a>
                    
                    {{-- Search --}}
                    <a class="navigation__item bb-nav-toggle ps-toggle--sidebar" href="#search-sidebar" data-open-icon="icon-magnifier" data-open-text="{{ __('Search') }}" data-close-text="{{ __('Close') }}"><i class="icon-magnifier"></i><span> {{ __('Search') }}</span></a>
                    
                    {{-- Cart --}}
                    <a class="navigation__item bb-nav-toggle ps-toggle--sidebar" href="#cart-mobile" data-open-icon="icon-bag2" data-open-text="{{ __('Cart') }}" data-close-text="{{ __('Close') }}"><i class="icon-bag2"></i><span> {{ __('Cart') }}</span></a>
                    
                    {{-- Account (Me) --}}
                    <a class="navigation__item" href="{{ auth('customer')->check() ? route('customer.overview') : route('customer.login') }}"><i class="icon-user"></i><span> {{ __('Me') }}</span></a>
                </div>
            </div>
        @endif

        @if (is_plugin_active('ecommerce'))
            <div class="ps-panel--sidebar" id="search-sidebar" style="display: none">
                <div class="ps-panel__header ps-panel__header--search">
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
                <div class="navigation__content">
                    <div class="search__empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                        <span class="search-empty-message">{{ __('Start typing to search for products...') }}</span>
                    </div>
                </div>
            </div>
        @endif
        <div class="ps-panel--sidebar" id="menu-mobile" style="display: none">
            <div class="ps-panel__header">
                <div class="ps-panel__user-greeting">
                    @auth('customer')
                        <a href="{{ route('customer.overview') }}" class="user-greeting-link">
                            <div class="user-avatar">
                                <img src="{{ auth('customer')->user()->avatar_url }}" alt="{{ auth('customer')->user()->name }}" onerror="this.src='{{ asset('vendor/core/core/base/images/default-user.png') }}'">
                            </div>
                            <div class="user-info">
                                <span class="greeting">{{ __('Hello') }},</span>
                                <span class="user-name">{{ auth('customer')->user()->name ? Str::limit(explode(' ', auth('customer')->user()->name)[0], 15) : __('User') }}</span>
                            </div>
                        </a>
                    @else
                        <div class="user-greeting-guest">
                            <div class="user-avatar user-avatar-guest">
                                <i class="icon-user"></i>
                            </div>
                            <div class="user-info">
                                <span class="greeting">{{ __('Welcome') }}!</span>
                                <div class="guest-actions">
                                    <a href="{{ route('customer.login') }}" class="btn-guest-action btn-login">{{ __('Login') }}</a>
                                    <a href="{{ route('customer.register') }}" class="btn-guest-action btn-register">{{ __('Register') }}</a>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
            <div class="ps-panel__content">
                {!! Menu::renderMenuLocation('main-menu', [
                    'view'    => 'menu',
                    'options' => ['class' => 'menu--mobile'],
                ]) !!}

                <ul class="menu--mobile menu--mobile-extra">
                    @if (is_plugin_active('ecommerce'))
                        @if (EcommerceHelper::isOrderTrackingEnabled())
                            <li><a href="{{ route('public.orders.tracking') }}"><i class="icon-check-square"></i> <span>{{ __('Track your order') }}</span></a></li>
                        @endif
                        @if (EcommerceHelper::isCompareEnabled())
                            <li><a href="{{ route('public.compare') }}"><i class="icon-chart-bars"></i> <span>{{ __('Compare') }}</span></a></li>
                        @endif
                        @if (EcommerceHelper::isWishlistEnabled())
                            <li><a href="{{ route('public.wishlist') }}"><i class="icon-heart"></i> <span>{{ __('Wishlist') }}</span></a></li>
                        @endif
                    @endif

                    @if (is_plugin_active('language'))
                        @php
                            $supportedLocales = Language::getSupportedLocales();
                        @endphp

                        @if ($supportedLocales && count($supportedLocales) > 1)
                            @php
                                $languageDisplay = setting('language_display', 'all');
                            @endphp
                            <li class="menu-item-has-children menu-item-language-switcher" data-language-switcher="true">
                                <a href="#">
                                    @if ($languageDisplay == 'all' || $languageDisplay == 'flag')
                                        {!! language_flag(Language::getCurrentLocaleFlag(), Language::getCurrentLocaleName()) !!}
                                    @endif
                                    @if ($languageDisplay == 'all' || $languageDisplay == 'name')
                                        {{ Language::getCurrentLocaleName() }}
                                    @endif
                                </a>
                                <span class="sub-toggle"></span>
                                <ul class="sub-menu" data-current-locale="{{ Language::getCurrentLocale() }}" data-current-locale-name="{{ Language::getCurrentLocaleName() }}" data-current-locale-flag="{{ Language::getCurrentLocaleFlag() }}">
                                    @foreach ($supportedLocales as $localeCode => $properties)
                                        @if ($localeCode != Language::getCurrentLocale())
                                            <li data-locale-code="{{ $localeCode }}" data-locale-name="{{ $properties['lang_name'] }}" data-locale-flag="{{ $properties['lang_flag'] }}">
                                                <a href="{{ Language::getSwitcherUrl($localeCode, $properties['lang_code']) }}">
                                                    @if ($languageDisplay == 'all' || $languageDisplay == 'flag'){!! language_flag($properties['lang_flag'], $properties['lang_name']) !!}@endif
                                                    @if ($languageDisplay == 'all' || $languageDisplay == 'name')<span>{{ $properties['lang_name'] }}</span>@endif
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>
        </div>

{{-- Product Lightbox Modal --}}
<div id="product-lightbox-modal" class="product-lightbox-overlay" style="display: none;">
    <div class="product-lightbox-container">
        {{-- Close button - positioned at container level --}}
        <button class="product-lightbox-close" aria-label="Close">
            <i class="icon-cross"></i>
        </button>
        
        {{-- Header --}}
        <div class="product-lightbox-header">
            <div class="product-lightbox-tabs">
                <button class="product-lightbox-tab active" data-tab="images">IMAGES</button>
                <button class="product-lightbox-tab" data-tab="videos">VIDEOS</button>
            </div>
        </div>
        
        {{-- Body --}}
        <div class="product-lightbox-body">
            {{-- Left: Main Display (64%) --}}
            <div class="product-lightbox-main">
                <div class="product-lightbox-display" id="lightbox-images-content">
                    <div class="product-lightbox-image-wrapper">
                        <img src="" alt="" class="product-lightbox-main-image" id="lightbox-main-image">
                    </div>
                </div>
                <div class="product-lightbox-display" id="lightbox-videos-content" style="display: none;">
                    <div class="product-lightbox-video-wrapper">
                        <p>Video content will be displayed here</p>
                    </div>
                </div>
            </div>
            
            {{-- Right: Info & Thumbnails (35%) --}}
            <div class="product-lightbox-sidebar">
                <div class="product-lightbox-info">
                    <h3 class="product-lightbox-title" id="lightbox-product-title">Product Title</h3>
                    <div class="product-lightbox-variations" id="lightbox-product-variations">
                        {{-- Variations will be inserted here --}}
                    </div>
                </div>
                
                <div class="product-lightbox-thumbnails" id="lightbox-thumbnails">
                    {{-- Thumbnails will be inserted here --}}
                </div>
            </div>
        </div>
    </div>
</div>

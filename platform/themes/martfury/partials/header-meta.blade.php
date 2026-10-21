<!DOCTYPE html>
<html {!! Theme::htmlAttributes() !!}>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! BaseHelper::googleFonts('https://fonts.googleapis.com/css2?family=' . urlencode(theme_option('primary_font', 'Work Sans')) . ':wght@300;400;500;600;700&display=swap') !!}

    <style>
        :root {
            /* ═══════════════════════════════════════════════════════════════ */
            /*              POVALY GLOBAL DESIGN SYSTEM                        */
            /* ═══════════════════════════════════════════════════════════════ */
            
            /* ══ BOOTSTRAP FONT OVERRIDE ══ */
            --bs-font-sans-serif: '{{theme_option('primary_font', 'Work Sans')}}', sans-serif;
            --bs-body-font-family: var(--bs-font-sans-serif);
            
            /* ══ SURFACES (60%) ══ */
            --global-color-site-bg:        {{theme_option('global_color_site_bg', '#F4F9FD')}};
            --global-color-card-bg:        {{theme_option('global_color_card_bg', '#FFFFFF')}};
            --global-color-card-bg-raised: {{theme_option('global_color_card_bg_raised', '#FFF3EC')}};
            --global-color-card-bg-dark:   {{theme_option('global_color_card_bg_dark', '#0D1B2A')}};
            --global-color-border-light:   {{theme_option('global_color_border_light', '#F0D8CB')}};
            --global-color-border-strong:  {{theme_option('global_color_border_strong', '#E8B49B')}};

            /* ══ STRUCTURE (30%) ══ */
            --global-color-secondary:      {{theme_option('secondary_color', '#0D1B2A')}};
            --global-color-secondary-soft: {{theme_option('global_color_secondary_soft', '#182A3E')}};
            --global-color-secondary-mid:  {{theme_option('global_color_secondary_mid', '#243B53')}};
            --global-color-text-main:      {{theme_option('global_color_text_main', '#0D1B2A')}};
            --global-color-text-body:      {{theme_option('global_color_text_body', '#2E3D52')}};
            --global-color-text-muted:     {{theme_option('global_color_text_muted', '#7A8FA6')}};
            --global-color-text-subtle:    {{theme_option('global_color_text_subtle', '#A8BED4')}};
            --global-color-text-on-dark:   {{theme_option('global_color_text_on_dark', '#F4F9FD')}};

            /* ══ ACCENT (10% - Primary) ══ */
            --global-color-primary:        {{theme_option('primary_color', '#FF642C')}};
            --global-color-primary-hover:  {{theme_option('global_color_primary_hover', '#E54E18')}};
            --global-color-primary-active: {{theme_option('global_color_primary_active', '#C83C0A')}};
            --global-color-primary-subtle: {{theme_option('global_color_primary_subtle', '#FFF0EB')}};
            --global-color-primary-border: {{theme_option('global_color_primary_border', '#FFB399')}};

            /* ══ STATUS COLORS ══ */
            --global-color-success:        {{theme_option('global_color_success', '#2E7D32')}};
            --global-color-success-subtle: {{theme_option('global_color_success_subtle', '#E8F5E9')}};
            --global-color-success-text:   {{theme_option('global_color_success_text', '#1B5E20')}};
            --global-color-alert:          {{theme_option('global_color_alert', '#FF5C5C')}};
            --global-color-alert-hover:    {{theme_option('global_color_alert_hover', '#D94040')}};
            --global-color-alert-subtle:   {{theme_option('global_color_alert_subtle', '#FDECEA')}};
            --global-color-alert-text:     {{theme_option('global_color_alert_text', '#B71C1C')}};
            --global-color-warning:        {{theme_option('global_color_warning', '#FBB13C')}};
            --global-color-warning-subtle: {{theme_option('global_color_warning_subtle', '#FFFBEB')}};

            /* ══ LINK COLORS ══ */
            --global-color-link:           {{theme_option('global_color_link', '#13acdf')}};
            --global-color-link-hover:     {{theme_option('global_color_link_hover', '#1dbff5')}};
            --global-color-link-subtle:    {{theme_option('global_color_link_subtle', '#FFF3EC')}};

            /* ══ ECOMMERCE BUTTONS ══ */
            --ec-btn-cart-bg:       {{theme_option('ec_btn_cart_bg', '#FFD814')}};
            --ec-btn-cart-bg-hover: {{theme_option('ec_btn_cart_bg_hover', '#f0c800')}};
            --ec-btn-cart-text:     {{theme_option('ec_btn_cart_text', '#111111')}};
            --ec-btn-buy-bg:        {{theme_option('ec_btn_buy_bg', '#FFA41C')}};
            --ec-btn-buy-bg-hover:  {{theme_option('ec_btn_buy_bg_hover', '#f09600')}};
            --ec-btn-buy-text:      {{theme_option('ec_btn_buy_text', '#111111')}};

            /* ═══════════════════════════════════════════════════════════════ */
            /*              EXISTING THEME OPTIONS                             */
            /* ═══════════════════════════════════════════════════════════════ */
            
            --primary-font: '{{theme_option('primary_font', 'Work Sans')}}', sans-serif;
            /* Top Bar */
            --header-topbar-padding: {{ (function() {
                $raw = theme_option('header_topbar_padding', '{"top":"7","right":"0","bottom":"7","left":"0","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??0).$u.' '.($d['right']??0).$u.' '.($d['bottom']??0).$u.' '.($d['left']??0).$u;
                }
                return $raw;
            })() }};
            --header-topbar-margin: {{ (function() {
                $raw = theme_option('header_topbar_margin', '{"top":"0","right":"0","bottom":"0","left":"0","unit":"px","linked":true}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??0).$u.' '.($d['right']??0).$u.' '.($d['bottom']??0).$u.' '.($d['left']??0).$u;
                }
                return $raw;
            })() }};
            /* Nav Bar */
            --header-nav-link-padding-top: {{ (function() {
                $raw = theme_option('header_nav_link_padding_y', '{"top":"7","right":"0","bottom":"7","left":"0","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) { return ($d['top']??7).($d['unit']??'px'); }
                return is_numeric(trim($raw)) ? trim($raw).'px' : $raw;
            })() }};
            --header-nav-link-padding-bottom: {{ (function() {
                $raw = theme_option('header_nav_link_padding_y', '{"top":"7","right":"0","bottom":"7","left":"0","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) { return ($d['bottom']??7).($d['unit']??'px'); }
                return is_numeric(trim($raw)) ? trim($raw).'px' : $raw;
            })() }};
            --header-nav-padding: {{ (function() {
                $raw = theme_option('header_nav_padding', '{"top":"0","right":"0","bottom":"0","left":"0","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??0).$u.' '.($d['right']??0).$u.' '.($d['bottom']??0).$u.' '.($d['left']??0).$u;
                }
                return $raw;
            })() }};
            --header-nav-border-color: {{theme_option('header_nav_border_color', 'transparent')}};
            --header-menu-link-font-size: {{theme_option('header_menu_link_font_size', 14)}}px;
            /* Logo & Search */
            --logo-max-height: {{theme_option('logo_max_height', 40)}}px;
            /* Category Menu Padding/Spacing */
            --cat-dropdown-padding: {{ (function() {
                $raw = theme_option('header_cat_dropdown_padding', '{"top":"10","right":"20","bottom":"10","left":"20","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??10).$u.' '.($d['right']??20).$u.' '.($d['bottom']??10).$u.' '.($d['left']??20).$u;
                }
                return $raw;
            })() }};
            --cat-submenu-padding: {{ (function() {
                $raw = theme_option('header_cat_submenu_padding', '{"top":"10","right":"15","bottom":"10","left":"15","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??10).$u.' '.($d['right']??15).$u.' '.($d['bottom']??10).$u.' '.($d['left']??15).$u;
                }
                return $raw;
            })() }};
            --cat-submenu-item-padding: {{ (function() {
                $raw = theme_option('header_cat_submenu_item_padding', '{"top":"8","right":"15","bottom":"8","left":"15","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??8).$u.' '.($d['right']??15).$u.' '.($d['bottom']??8).$u.' '.($d['left']??15).$u;
                }
                return $raw;
            })() }};
            /* Mobile Header */
            /* Mobile Header Spacing */
            --header-mobile-logo-max-height: {{theme_option('header_mobile_logo_max_height', 40)}}px;
            --header-mobile-padding: {{ (function() {
                $raw = theme_option('header_mobile_padding', '{"top":"8","right":"15","bottom":"8","left":"15","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??0).$u.' '.($d['right']??0).$u.' '.($d['bottom']??0).$u.' '.($d['left']??0).$u;
                }
                return $raw;
            })() }};
            /* Bottom Bar */
            --bottom-bar-font-size: {{theme_option('bottom_bar_menu_text_font_size', 14)}}px;
            /* Footer */
            --footer-padding: {{ (function() {
                $raw = theme_option('footer_padding', '{"top":"40","right":"0","bottom":"40","left":"0","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??0).$u.' '.($d['right']??0).$u.' '.($d['bottom']??0).$u.' '.($d['left']??0).$u;
                }
                return $raw;
            })() }};
            --footer-widget-title-font-size: {{theme_option('footer_widget_title_font_size', 16)}}px;
            /* Footer Bottom */
            --footer-links-bar-padding: {{ (function() {
                $raw = theme_option('footer_links_bar_padding', '{"top":"15","right":"0","bottom":"15","left":"0","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??0).$u.' '.($d['right']??0).$u.' '.($d['bottom']??0).$u.' '.($d['left']??0).$u;
                }
                return $raw;
            })() }};
            --footer-copyright-padding: {{ (function() {
                $raw = theme_option('footer_copyright_padding', '{"top":"15","right":"0","bottom":"15","left":"0","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??0).$u.' '.($d['right']??0).$u.' '.($d['bottom']??0).$u.' '.($d['left']??0).$u;
                }
                return $raw;
            })() }};
            /* Page Elements */
            --breadcrumb-bg: {{theme_option('breadcrumb_background_color', '#f5f5f5')}};
            --breadcrumb-text-color: {{theme_option('breadcrumb_text_color', '#666666')}};
            --breadcrumb-link-color: {{theme_option('breadcrumb_link_color', '#333333')}};
            --breadcrumb-link-hover-color: {{theme_option('breadcrumb_link_hover_color', '#fcb800')}};
            --breadcrumb-padding: {{ (function() {
                $raw = theme_option('breadcrumb_padding', '{"top":"10","right":"0","bottom":"10","left":"0","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                if (is_array($d)) {
                    $u = $d['unit'] ?? 'px';
                    return ($d['top']??0).$u.' '.($d['right']??0).$u.' '.($d['bottom']??0).$u.' '.($d['left']??0).$u;
                }
                return $raw;
            })() }};
            --newsletter-popup-heading-color: {{theme_option('newsletter_popup_heading_color', '#333333')}};
            --newsletter-popup-text-color: {{theme_option('newsletter_popup_text_color', '#666666')}};
            --newsletter-popup-btn-bg: {{theme_option('newsletter_popup_btn_bg', '#fcb800')}};
            --newsletter-popup-btn-color: {{theme_option('newsletter_popup_btn_color', '#000000')}};
            /* Go to Top Button - Other settings */
            --backtotop-border-color:  {{theme_option('backtotop_border_color', '#e0a000')}};
            --backtotop-size:          {{theme_option('backtotop_size', 40)}}px;
            --backtotop-icon-size:     {{theme_option('backtotop_icon_size', 14)}}px;
            --backtotop-border-radius: {{theme_option('backtotop_border_radius', 4)}}px;
            --backtotop-bottom:        {{theme_option('backtotop_bottom', 30)}}px;
            --backtotop-right:         {{theme_option('backtotop_right', 30)}}px;
            --backtotop-shadow: {{ (function() {
                $s = theme_option('backtotop_shadow', 'soft');
                if ($s === 'soft')   return '0 2px 8px rgba(0,0,0,.15)';
                if ($s === 'strong') return '0 4px 16px rgba(0,0,0,.35)';
                return 'none';
            })() }};
        }

        /* Header — driven by theme options */
        .header .header__top {
            background-color: var(--global-color-secondary, #0D1B2A) !important;
            border: none !important;
            padding: var(--header-topbar-padding) !important;
            margin: var(--header-topbar-margin) !important;
        }
        .header--mobile,
        .header .ps-search--mobile {
            background-color: var(--global-color-secondary-mid, #243B53) !important;
        }
        /* Mobile header padding — override the hardcoded padding:10px 30px */
        .header--mobile .navigation--mobile {
            padding: var(--header-mobile-padding) !important;
        }
        /* ps-search--mobile follows same padding as the mobile header */
        .header--mobile .ps-search--mobile {
            padding-left: {{ (function() {
                $raw = theme_option('header_mobile_padding', '{"top":"8","right":"15","bottom":"8","left":"15","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                $u = isset($d['unit']) ? $d['unit'] : 'px';
                return isset($d['left']) ? $d['left'].$u : '15px';
            })() }} !important;
            padding-right: {{ (function() {
                $raw = theme_option('header_mobile_padding', '{"top":"8","right":"15","bottom":"8","left":"15","unit":"px","linked":false}');
                $d = @json_decode($raw, true);
                $u = isset($d['unit']) ? $d['unit'] : 'px';
                return isset($d['right']) ? $d['right'].$u : '15px';
            })() }} !important;
        }
        /* Mobile search bar input, button — apply theme option styles */
        .header--mobile .ps-form--search-mobile input.form-control,
        .header--mobile .ps-form--search-mobile .input-search-product {
            background-color: var(--global-color-card-bg, #FFFFFF) !important;
            color: var(--global-color-text-body, #2E3D52) !important;
            border-color: var(--global-color-primary-border, #FFB399) !important;
        }
        .header--mobile .ps-form--search-mobile button[type=submit] {
            background-color: var(--global-color-warning, #FBB13C) !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
        }
        .header--mobile .ps-form--search-mobile button[type=submit]:hover {
            background-color: var(--global-color-primary, #FF642C) !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        .header--mobile .ps-form--search-mobile button[type=submit] i {
            color: inherit !important;
            font-size: 20px !important;
            font-weight: 600 !important;
            display: inline-block !important;
        }
        
        /* Sidebar/Offcanvas search icon (opened from bottom navigation) */
        .ps-panel--sidebar .ps-form--search-mobile button[type=submit] i,
        #search-sidebar .ps-form--search-mobile button[type=submit] i {
            font-size: 20px !important;
            font-weight: 600 !important;
            display: inline-block !important;
            color: inherit !important;
        }
        
        /* Search sidebar submit button - match mobile search box button exactly */
        .ps-panel--sidebar .ps-form--search-mobile button[type=submit],
        #search-sidebar .ps-form--search-mobile button[type=submit] {
            background-color: var(--global-color-warning, #FBB13C) !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
        }
        
        .ps-panel--sidebar .ps-form--search-mobile button[type=submit]:hover,
        #search-sidebar .ps-form--search-mobile button[type=submit]:hover {
            background-color: var(--global-color-primary, #FF642C) !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        
        /* Search sidebar empty state - match cart empty state design */
        #search-sidebar .navigation__content {
            background: var(--global-color-site-bg) !important;
            padding: 60px 30px !important;
        }
        
        .search__empty-state {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            gap: 16px !important;
        }
        
        .search__empty-state svg {
            width: 64px !important;
            height: 64px !important;
            color: var(--global-color-text-body) !important;
            opacity: 0.5 !important;
        }
        
        .search__empty-state .search-empty-message {
            font-size: 14px !important;
            text-align: center !important;
            color: var(--global-color-text-body) !important;
            line-height: 1.5 !important;
        }
        
        /* Sticky search bar mode — when searchbar is chosen, make ps-search--mobile sticky
           and let navigation--mobile scroll away */
        .header--mobile.header--mobile-sticky-search.header--sticky .navigation--mobile {
            position: static !important;
            box-shadow: none !important;
        }
        .header--mobile.header--mobile-sticky-search.header--sticky .ps-search--mobile {
            position: fixed !important;
            top: {{ theme_option('header_mobile_sticky_top', 0) }}px !important;
            left: 0 !important;
            width: 100% !important;
            z-index: 1000 !important;
            box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
        }
        /* Remove extra margin from the hamburger menu icon added to header actions */
        .header--mobile .header__actions .header__extra--menu {
            margin: 0 !important;
            margin-left: 10px !important;
        }
        /* Make user icon same size as cart icon on mobile */
        .header--mobile .header__extra > i,
        .header--mobile .ps-block--user-header i,
        .header--mobile .header__extra--menu .mobile-menu-icon {
            font-size: 24px !important;
            width: 24px !important;
            height: 24px !important;
        }
        /* Badge inner count — must NOT inherit the 24px icon size */
        .header--mobile .header__actions .header__extra > span i {
            font-size: 12px !important;
            width: auto !important;
            height: auto !important;
        }
        /* Menu icon in mobile header — color follows topbar action icon options */
        .header--mobile .header__extra--menu {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            display: inline-flex !important;
            align-items: center !important;
            width: auto !important;
            height: auto !important;
            margin-left: auto !important;
        }
        .header--mobile .header__extra--menu:hover {
            color: var(--global-color-warning, #FBB13C) !important;
        }
        .header--mobile .header__extra--menu .mobile-menu-icon {
            stroke: currentColor !important;
            width: 26px !important;
            height: 26px !important;
        }
        /* User icon color on mobile */
        .header--mobile .ps-block--user-header .ps-block__left i,
        .header--mobile .ps-block--user-header a i {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        .header--mobile .ps-block--user-header a:hover i {
            color: var(--global-color-warning, #FBB13C) !important;
        }
        /* Mobile header cart and other icons */
        .header--mobile .header__extra > a,
        .header--mobile .ps-cart--mini > a {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        .header--mobile .header__extra > a:hover,
        .header--mobile .ps-cart--mini > a:hover {
            color: var(--global-color-warning, #FBB13C) !important;
        }
        /* Mobile header badges */
        .header--mobile .header__extra .header__badge,
        .header--mobile .ps-cart--mini .header__badge {
            background-color: var(--global-color-warning, #FBB13C) !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
        }
        .header--sticky :is(.header__top, .navigation--mobile) {
            background-color: var(--global-color-secondary, #0D1B2A) !important;
        }
        .header .navigation {
            background-color: var(--global-color-secondary-soft, #182A3E) !important;
            border-bottom: 1px solid var(--header-nav-border-color) !important;
            padding: var(--header-nav-padding) !important;
        }

        /* Nav menu links — theme uses --header-text-color but its stylesheet
           also defines that var at :root. Use !important rules to guarantee
           our theme-option values win regardless of load order. */
        .header .navigation .menu > li > a,
        .header .header__extra,
        .header .menu--product-categories .menu__toggle i,
        .header .menu--product-categories .menu__toggle span {
            color: var(--global-color-text-on-dark) !important;
            font-size: var(--header-menu-link-font-size) !important;
        }
        /* User icon — keep at 30px (theme default), don't inherit nav font-size */
        .header .ps-block__left i {
            color: var(--global-color-text-on-dark) !important;
            font-size: 30px !important;
        }

        /* Active / current menu item */
        .header .navigation .menu > .current-menu-item > a,
        .header .navigation .menu > .current-menu-item > a:hover,
        .header .navigation .menu > li > a:hover {
            color: var(--global-color-text-on-dark) !important;
        }

        /* Secondary nav — currency, language, extra links */
        .header .navigation__extra > li > a,
        .header .navigation__extra > li > div > a,
        .navigation__extra > li a {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        .header .navigation__extra > li > a:hover,
        .header .navigation__extra > li > div > a:hover,
        .navigation__extra > li a:hover {
            color: var(--global-color-warning, #FBB13C) !important;
        }
        
        /* Professional inline layout for extra navigation items */
        .header .navigation__extra {
            display: flex !important;
            align-items: center !important;
            gap: 0 !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .header .navigation__extra > li {
            display: flex !important;
            align-items: center !important;
            position: relative !important;
            margin: 0 !important;
            padding: 0 16px !important;
            white-space: nowrap !important;
        }
        
        /* Remove padding from first item */
        .header .navigation__extra > li:first-child {
            padding-left: 0 !important;
        }
        
        /* Remove padding from last item */
        .header .navigation__extra > li:last-child {
            padding-right: 0 !important;
        }
        
        /* Links inside navigation extra - consistent styling */
        .header .navigation__extra > li > a {
            font-size: var(--header-menu-link-font-size) !important;
            font-weight: 400 !important;
            line-height: 1.4 !important;
            text-decoration: none !important;
            transition: color 0.3s ease !important;
        }
        
        /* Divider line between extra nav items */
        .header .navigation__extra > li::after {
            content: '' !important;
            position: absolute !important;
            right: 0 !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 1px !important;
            height: 14px !important;
            background-color: var(--global-color-text-on-dark, #F4F9FD) !important;
            opacity: 0.3 !important;
        }
        
        /* Remove divider from last item */
        .header .navigation__extra > li:last-child::after {
            display: none !important;
        }
        
        /* Legacy fallback for non-.header contexts */
        .navigation__extra > li::after {
            background-color: var(--global-color-text-on-dark, #F4F9FD) !important;
            opacity: 0.4;
        }

        /* ── Category Toggle (Shop by Department) ───────────────────────── */

        /* Toggle button background */
        .header .menu--product-categories .menu__toggle,
        .header .navigation .menu--product-categories .menu__toggle {
            background-color: transparent !important;
            padding-left: 0 !important;
            padding-right: 15px !important;
            position: relative !important;
        }

        /* Toggle icon + text color — overrides the broad --header-text-color rule */
        .header .menu--product-categories .menu__toggle i {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            position: absolute !important;
            top: calc(50% + 1px) !important;
            transform: translateY(-50%) !important;
        }
        
        .header .menu--product-categories .menu__toggle span {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            padding-left: 22px !important;
        }
        .header .menu--product-categories .menu__toggle:hover i,
        .header .menu--product-categories .menu__toggle:hover span {
            color: var(--global-color-warning, #FBB13C) !important;
        }
        
        /* ═══════════════════════════════════════════════════════════════════
         * SIDEBAR OVERLAY & BODY SCROLL LOCK
         * ═══════════════════════════════════════════════════════════════════ */
        
        /* Overlay backdrop - appears above header */
        .ps-sidebar-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(0, 0, 0, 0.5) !important;
            z-index: 10000 !important; /* Above header (1050) but below sidebar (10001) */
            opacity: 0 !important;
            visibility: hidden !important;
            transition: opacity 0.3s ease !important;
            pointer-events: none !important;
        }
        
        /* Overlay visible state */
        .ps-sidebar-overlay.active {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }
        
        /* Body scroll lock */
        body.sidebar-open {
            overflow: hidden !important;
            height: 100vh !important;
        }

        /* Hover underline animation line */
        .header .menu--product-categories .menu__toggle::before,
        .menu--product-categories .menu__toggle::before {
            background-color: var(--global-color-warning, #FBB13C) !important;
        }

        /* ── Category Dropdown list ─────────────────────────────────────── */

        /* Dropdown box */
        .menu--product-categories .menu--dropdown {
            background-color: var(--global-color-site-bg, #F4F9FD) !important;
            border: 1px solid var(--global-color-primary-border, #FFB399) !important;
        }

        /* Category items */
        .menu--product-categories .menu--dropdown > li {
            padding: 0 !important;
        }
        .menu--product-categories .menu--dropdown > li > a {
            color: var(--global-color-text-body, #2E3D52) !important;
            display: flex !important;
            align-items: center !important;
            padding: var(--cat-dropdown-padding) !important;
            position: relative !important;
        }
        /* Arrow indicator for items that have a submenu — pull it inside the row */
        .menu--product-categories .menu--dropdown > li.menu-item-has-children > a::after {
            right: 8px !important;
        }
        .menu--product-categories .menu--dropdown > li > a i,
        .menu--product-categories .menu--dropdown > li > a img + span,
        .menu--product-categories .menu--dropdown > li > a .ms-1 {
            color: inherit !important;
        }
        /* Category icon color */
        .menu--product-categories .menu--dropdown > li > a i {
            color: var(--global-color-text-body, #2E3D52) !important;
        }

        /* Hover row — theme uses background-color: var(--color-1st) on li:hover */
        .menu--product-categories .menu--dropdown > li:hover {
            background-color: var(--global-color-card-bg, #FFFFFF) !important;
        }
        .menu--product-categories .menu--dropdown > li:hover > a,
        .menu--product-categories .menu--dropdown > li:hover > a span {
            color: var(--global-color-text-main, #0D1B2A) !important;
        }
        .menu--product-categories .menu--dropdown > li:hover > a i {
            color: var(--global-color-text-main, #0D1B2A) !important;
        }
        /* Chevron arrow on hover */
        .menu--product-categories .menu--dropdown > li:hover > a::after {
            color: var(--global-color-text-main, #0D1B2A) !important;
            border-left-color: var(--global-color-text-main, #0D1B2A) !important;
        }

        /* ── Mega-menu (submenu panel) ──────────────────────────────────── */

        .menu--product-categories .mega-menu {
            background-color: var(--global-color-site-bg, #F4F9FD) !important;
            border: 1px solid var(--global-color-primary-border, #FFB399) !important;
            padding: 0 !important;
        }

        /* Box padding goes on the wrapper, nowhere else */
        .menu--product-categories .mega-menu-wrapper {
            background-color: var(--global-color-site-bg, #F4F9FD) !important;
            padding: var(--cat-submenu-padding) !important;
        }

        /* Zero out any theme padding on columns/lists so wrapper controls spacing */
        .menu--product-categories .mega-menu .mega-menu__column,
        .menu--product-categories .mega-menu .mega-menu__list {
            padding: 0 !important;
        }

        /* Submenu column titles (h4 inside <a>) */
        .menu--product-categories .mega-menu .mega-menu__column > a h4,
        .menu--product-categories .mega-menu h4 {
            color: var(--global-color-text-main, #0D1B2A) !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Submenu list links — padding controlled per-item */
        .menu--product-categories .mega-menu .mega-menu__list li a {
            color: var(--global-color-text-body, #2E3D52) !important;
            padding: var(--cat-submenu-item-padding) !important;
            display: block !important;
            text-decoration: none !important;
        }
        .menu--product-categories .mega-menu .mega-menu__list li a:hover {
            color: var(--global-color-text-main, #0D1B2A) !important;
            text-decoration: underline !important;
        }
        .header .ps-dropdown-menu a {
            color: var(--global-color-text-body, #2E3D52) !important;
        }
        .header .ps-dropdown-menu a:hover {
            color: var(--global-color-primary, #FF642C) !important;
        }
        
        /* ── Main Navigation Menu Dropdowns/Mega-Menus ────────────────────── */
        /* Mega-menu panels for main navigation */
        .header .menu > li .mega-menu,
        .header .navigation .menu > li .mega-menu,
        .header .menu .sub-menu,
        .header .navigation .menu .sub-menu,
        .navigation .menu .sub-menu {
            background: var(--global-color-site-bg, #F4F9FD) !important;
            border: 1px solid var(--global-color-primary-border, #FFB399) !important;
        }
        
        /* Mega-menu and submenu links */
        .header .menu > li .mega-menu a,
        .header .navigation .menu > li .mega-menu a,
        .header .menu .sub-menu a,
        .header .navigation .menu .sub-menu a,
        .navigation .menu .sub-menu a {
            color: var(--global-color-text-body, #2E3D52) !important;
        }
        
        .header .menu > li .mega-menu a:hover,
        .header .navigation .menu > li .mega-menu a:hover,
        .header .menu .sub-menu a:hover,
        .header .navigation .menu .sub-menu a:hover,
        .navigation .menu .sub-menu a:hover {
            color: var(--global-color-primary, #FF642C) !important;
        }
        
        /* Dropdown menu (simple dropdown, not mega-menu) */
        .header .navigation__extra .ps-dropdown ul,
        .header .ps-dropdown ul,
        .navigation__extra .ps-dropdown ul {
            background: var(--global-color-site-bg, #F4F9FD) !important;
            border: 1px solid var(--global-color-primary-border, #FFB399) !important;
        }
        
        /* Submenu indicator styles for main menu items with children */
        
        /* The chevron/triangle next to menu text - use CSS border trick for filled triangle */
        .header .navigation .menu > li.menu-item-has-children > a::after,
        .header .menu > li.menu-item-has-children > a::after,
        .navigation .menu > li.menu-item-has-children > a::after {
            content: '' !important; /* Remove icon font */
            font-family: inherit !important;
            width: 0 !important;
            height: 0 !important;
            border-left: 4px solid transparent !important;
            border-right: 4px solid transparent !important;
            border-top: 5px solid currentColor !important; /* Filled triangle pointing down */
            position: absolute !important;
            top: 50% !important;
            right: 0 !important;
            transform: translateY(-50%) !important;
            font-size: 0 !important;
        }
        
        /* The flyout box indicator (white diamond arrow on top of dropdown) - use global colors */
        .header .navigation .menu > li.menu-item-has-children > a::before,
        .header .menu > li.menu-item-has-children > a::before,
        .navigation .menu > li.menu-item-has-children > a::before {
            background-color: var(--global-color-site-bg, #F4F9FD) !important;
            border-left-color: var(--global-color-primary-border, #FFB399) !important;
            border-top-color: var(--global-color-primary-border, #FFB399) !important;
        }
        
.ps-search--mobile button {
            background: var(--header-mobile-search-btn);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .navigation__extra > li > a,
        .navigation__extra > li > div > a,
        .menu > li > a,
        .menu__toggle {
            padding-top: var(--header-nav-link-padding-top) !important;
            padding-bottom: var(--header-nav-link-padding-bottom) !important;
        }

        /* Top bar text colors */
        .header .header__top,
        .header .header__top a { color: var(--global-color-text-on-dark, #F4F9FD) !important; }
        .header .header__top a:hover { color: var(--global-color-primary, #FF642C) !important; }

        /* Search box — exact selectors from header.blade.php HTML */
        .header .ps-form--quick-search input.form-control.input-search-product {
            background: var(--global-color-site-bg) !important;
            border-color: var(--global-color-primary-border) !important;
            color: var(--global-color-text-main) !important;
        }
        /* Search box focus state - apply warning color to entire search box border with proper radius */
        .header .ps-form--quick-search:focus-within {
            border-color: var(--global-color-warning) !important;
            outline: 1px solid var(--global-color-warning) !important;
            border-radius: 4px !important;
            outline-offset: 0px !important;
        }
        /* Remove bottom border-radius when results are shown */
        .header .ps-form--quick-search.has-results:focus-within {
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
        /* Also remove bottom radius from category dropdown and search button */
        .header .ps-form--quick-search.has-results .product-cat-label,
        .header .ps-form--quick-search.has-results .product-category-select,
        .header .ps-form--quick-search.has-results select.form-control.product-category-select {
            border-bottom-left-radius: 0 !important;
        }
        .header .ps-form--quick-search.has-results button[type=submit],
        .header .ps-form--quick-search.has-results .btn-search-submit {
            border-bottom-right-radius: 0 !important;
        }
        /* Expand search results panel width to match focus border outline */
        .header .ps-form--quick-search:focus-within .ps-panel--search-result {
            width: calc(100% + 3px) !important;
            margin-left: -1px !important;
            border-top: 1px solid var(--global-color-warning) !important;
        }
        .header .ps-form--quick-search input.form-control.input-search-product:focus {
            border-color: var(--global-color-warning) !important;
            outline: none !important;
        }
        .header .ps-form--quick-search button[type=submit] {
            background: var(--global-color-warning) !important;
            color: var(--global-color-text-main) !important;
            transition: all 0.3s ease;
        }
        .header .ps-form--quick-search button[type=submit]:hover {
            background: var(--global-color-primary) !important;
            color: var(--global-color-text-on-dark) !important;
        }
        /* Search button styles */
        .header .ps-form--quick-search button.btn-search-submit[data-style="icon"] {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
        }
        .header .ps-form--quick-search button.btn-search-submit[data-style="icon"] i {
            font-size: 18px;
            font-weight: 600;
        }
        .header .ps-form--quick-search button.btn-search-submit[data-style="icon_text"] {
            display: flex !important;
            align-items: center;
            gap: 6px;
            padding: 0 15px;
        }
        .header .ps-form--quick-search button.btn-search-submit[data-style="icon_text"] i {
            font-size: 18px !important;
            font-weight: 600;
            display: inline-block !important;
        }
        .header .ps-form--quick-search button.btn-search-submit[data-style="icon_text"] span {
            font-size: 14px !important;
            font-weight: 500 !important;
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            color: inherit !important;
            line-height: 1.5 !important;
            white-space: nowrap !important;
        }
        .header .ps-form--quick-search button.btn-search-submit[data-style="text"] {
            padding: 0 20px;
        }
        .header .ps-form--quick-search .product-cat-label {
            background: var(--global-color-card-bg) !important;
            color: var(--global-color-text-main) !important;
        }
        .header .ps-form--quick-search .product-category-select,
        .header .ps-form--quick-search select.form-control.product-category-select {
            background: var(--global-color-card-bg) !important;
            color: var(--global-color-text-main) !important;
        }

        /* Header action icons — exact HTML: <a class="header__extra"><i></i><span><i>count</i></span></a> */
        .header .header__actions .header__extra > i { 
            color: var(--global-color-text-on-dark) !important;
        }
        .header .header__actions .header__extra:hover > i { 
            color: var(--global-color-warning) !important;
        }
        
        /* Badge: <span><i>N</i></span> — NO .number class */
        .header .header__actions .header__extra > span {
            background-color: var(--global-color-warning) !important;
        }
        .header .header__actions .header__extra > span > i {
            color: var(--global-color-text-main) !important;
        }

        /* User block — exact HTML: .ps-block--user-header > .ps-block__left > i.icon-user */
        .header .ps-block--user-header .ps-block__left i { color: var(--global-color-text-on-dark) !important; }
        .header .ps-block--user-header .ps-block__right a { color: var(--global-color-text-on-dark) !important; }
        .header .ps-block--user-header .ps-block__right a:hover { color: var(--global-color-warning) !important; }

        /* Bottom bar - ABSOLUTE NUCLEAR OPTION - prevent ANY movement */
        nav.navigation--mobile-product,
        .navigation--mobile-product,
        nav.navigation--mobile-product[style],
        .navigation--mobile-product[style] {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            top: auto !important;
            width: 100% !important;
            transform: translate3d(0, 0, 0) !important;
            -webkit-transform: translate3d(0, 0, 0) !important;
            transition: none !important;
            -webkit-transition: none !important;
            animation: none !important;
            -webkit-animation: none !important;
            will-change: auto !important;
            z-index: 10002 !important;
            backface-visibility: hidden !important;
            -webkit-backface-visibility: hidden !important;
            /* Prevent interaction with viewport height changes */
            contain: layout style paint !important;
            /* Override any inline styles */
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* CRITICAL: Fix for real mobile devices - use fixed viewport units */
        @media (max-width: 1199px) {
            nav.navigation--mobile-product,
            .navigation--mobile-product {
                /* Use fixed viewport height that doesn't change with address bar */
                bottom: 0 !important;
                position: fixed !important;
                /* Prevent browser from repositioning on scroll */
                -webkit-overflow-scrolling: auto !important;
            }
            
            /* iOS specific fix */
            @supports (-webkit-touch-callout: none) {
                nav.navigation--mobile-product,
                .navigation--mobile-product {
                    /* Use safe area inset for iOS notch */
                    bottom: env(safe-area-inset-bottom, 0) !important;
                    /* Disable elastic scrolling effect */
                    -webkit-overflow-scrolling: auto !important;
                }
            }
            
            /* Android Chrome specific fix */
            @supports not (-webkit-touch-callout: none) {
                nav.navigation--mobile-product,
                .navigation--mobile-product {
                    /* Force GPU rendering to prevent reflow */
                    transform: translateZ(0) !important;
                    -webkit-transform: translateZ(0) !important;
                }
            }
        }
        
        /* Prevent ANY transform classes from moving the bar */
        nav.navigation--mobile-product.navigation--pin,
        nav.navigation--mobile-product.navigation--unpin,
        nav.navigation--mobile-product.navigation--sticky,
        .navigation--mobile-product.navigation--pin,
        .navigation--mobile-product.navigation--unpin,
        .navigation--mobile-product.navigation--sticky,
        nav.navigation--mobile-product[class*="navigation--"],
        .navigation--mobile-product[class*="navigation--"] {
            transform: translate3d(0, 0, 0) !important;
            -webkit-transform: translate3d(0, 0, 0) !important;
            position: fixed !important;
            bottom: 0 !important;
        }
        
        .navigation--list { 
            background: var(--global-color-site-bg, #F4F9FD) !important;
            border-top: 1px solid var(--global-color-primary-border, #FFB399) !important;
            padding: 5px 20px !important;
            display: flex;
            align-items: center !important;
            justify-content: space-between !important;
        }
        .navigation--list .navigation__content {
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }
        .navigation--list .navigation__item { 
            flex: 0 !important;
            min-width: fit-content !important; 
            color: var(--global-color-text-body, #2E3D52) !important; 
            transition: color 0.3s ease !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 4px 8px !important;
            gap: 2px !important;
        }
        .navigation--list .navigation__item span { 
            font-size: 11px !important; 
            color: var(--global-color-text-body, #2E3D52) !important; 
            transition: color 0.3s ease !important;
            line-height: 1.2 !important;
        }
        .navigation--list .navigation__item i { 
            color: var(--global-color-text-body, #2E3D52) !important; 
            transition: color 0.3s ease !important;
            font-size: 22px !important;
            line-height: 1 !important;
        }
        
        /* Smart navigation item - Logo with circular gradient background */
        .navigation__item--smart {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .navigation__item--smart .bb-logo-wrap {
            width: 40px !important;
            height: 40px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: linear-gradient(135deg, 
                var(--global-color-primary, #FF642C) 0%, 
                var(--global-color-warning, #FBB13C) 100%) !important;
            background: linear-gradient(135deg, 
                var(--global-color-primary, #FF642C) 85%, 
                var(--global-color-warning, #FBB13C) 100%) !important;
            border-radius: 50% !important;
            padding: 7px !important;
            overflow: hidden !important;
            transition: all 0.3s ease !important;
        }
        .navigation__item--smart .bb-logo-img {
            max-width: 26px !important;
            max-height: 26px !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
            transition: all 0.3s ease !important;
        }
        .navigation__item--smart .bb-logo-wrap i {
            font-size: 22px !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            transition: all 0.3s ease !important;
        }
        
        /* Arrow icon animation when it appears */
        .navigation__item--smart.show-arrow .bb-logo-wrap {
            background: linear-gradient(135deg, 
                var(--global-color-primary, #FF642C) 85%, 
                var(--global-color-warning, #FBB13C) 100%) !important;
        }
        .navigation__item--smart.show-arrow .bb-logo-wrap i {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            animation: arrowBounceIn 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
        }
        
        /* Ensure icon-arrow-up specifically gets the right color */
        .navigation__item--smart .bb-logo-wrap i.icon-arrow-up {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        
        /* Smooth bounce animation for arrow */
        @keyframes arrowBounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3) translateY(10px);
            }
            50% {
                opacity: 1;
                transform: scale(1.1) translateY(-2px);
            }
            70% {
                transform: scale(0.95) translateY(1px);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        /* Logo fade out animation when switching to arrow */
        .navigation__item--smart .bb-logo-img.fade-out {
            animation: logoFadeOut 0.3s ease-out !important;
        }
        
        @keyframes logoFadeOut {
            0% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform: scale(0.8);
            }
        }
        
        /* Bottom bar hover state */
        .navigation--list .navigation__item:hover,
        .navigation--list .navigation__item:hover i,
        .navigation--list .navigation__item:hover span { color: var(--global-color-warning, #FBB13C) !important; }
        
        /* Bottom bar active state (close icon and text) */
        .navigation--list .navigation__item.active,
        .navigation--list .navigation__item.active i,
        .navigation--list .navigation__item.active span { color: var(--global-color-primary, #FF642C) !important; }
        .navigation__item.active { color: var(--global-color-primary, #FF642C); }

        /* Sidebar close button — clean ✕, no circle, white lines always visible */
        .ps-panel--sidebar .ps-panel__header {
            position: relative !important;
        }
        .ps-panel--sidebar .ps-panel__header .ps-btn--close {
            position: absolute !important;
            top: 50% !important;
            right: 14px !important;
            transform: translateY(-50%) !important;
            width: 28px !important;
            height: 28px !important;
            background: none !important;
            border: none !important;
            border-radius: 0 !important;
        }
        .ps-panel--sidebar .ps-panel__header .ps-btn--close::before,
        .ps-panel--sidebar .ps-panel__header .ps-btn--close::after {
            background-color: rgba(255,255,255,.8) !important;
            height: 60% !important;
            width: 2px !important;
        }
        .ps-panel--sidebar .ps-panel__header .ps-btn--close:hover {
            transform: translateY(-50%) rotate(90deg) !important;
        }
        .ps-panel--sidebar .ps-panel__header .ps-btn--close:hover::before,
        .ps-panel--sidebar .ps-panel__header .ps-btn--close:hover::after {
            background-color: #fff !important;
        }
        
        /* Sidebar menu header */
        .ps-panel--sidebar .ps-panel__header {
            background-color: var(--global-color-secondary-mid, #243B53) !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: var(--header-mobile-padding) !important;
            border-right: 1px solid var(--global-color-secondary-soft, #415A77) !important;
        }
        
        .ps-panel--sidebar .ps-panel__header h3 {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        
        /* Hide h3 title in menu-mobile, show close button instead */
        #menu-mobile .ps-panel__header .ps-panel__title,
        #menu-mobile .ps-panel__header h3 {
            display: none !important;
        }
        
        /* Close button for menu-mobile ONLY (added via JS) */
        .ps-sidebar-close-btn {
            position: absolute !important;
            right: 14px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 28px !important;
            height: 28px !important;
            background: none !important;
            border: none !important;
            cursor: pointer !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 10 !important;
        }
        
        .ps-sidebar-close-btn span {
            position: absolute !important;
            width: 2px !important;
            height: 18px !important;
            background-color: rgba(255,255,255,.9) !important;
            transition: background-color 0.2s ease !important;
        }
        
        .ps-sidebar-close-btn span:first-child {
            transform: rotate(45deg) !important;
        }
        
        .ps-sidebar-close-btn span:last-child {
            transform: rotate(-45deg) !important;
        }
        
        .ps-sidebar-close-btn:hover span {
            background-color: #fff !important;
        }
        
        #menu-mobile .ps-panel__header .ps-btn--close {
            display: none !important;
        }
        
        /* User greeting in sidebar header */
        .ps-panel--sidebar .ps-panel__user-greeting {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            flex: 1 !important;
        }
        
        .ps-panel--sidebar .user-avatar {
            width: 32px !important;
            height: 32px !important;
            border-radius: 50% !important;
            overflow: hidden !important;
            background-color: var(--global-color-card-bg, #FFFFFF) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
        }
        
        .ps-panel--sidebar .user-avatar img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }
        
        .ps-panel--sidebar .user-avatar-guest {
            background-color: var(--global-color-secondary-soft, #182A3E) !important;
        }
        
        .ps-panel--sidebar .user-avatar-guest i {
            font-size: 16px !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        
        /* Panel title on the right side */
        .ps-panel--sidebar .ps-panel__title {
            font-size: 15px !important;
            font-weight: 600 !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            margin: 0 !important;
            white-space: nowrap !important;
        }
        
        .ps-panel--sidebar .user-info {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            line-height: 1.3 !important;
        }
        
        .ps-panel--sidebar .user-info .greeting {
            font-size: 13px !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            opacity: 0.8 !important;
            font-weight: 400 !important;
        }
        
        .ps-panel--sidebar .user-info .user-name {
            font-size: 16px !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            font-weight: 600 !important;
        }
        
        /* Logged-in user: clickable link */
        .ps-panel--sidebar .user-greeting-link {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            text-decoration: none !important;
            transition: opacity 0.2s ease !important;
        }
        
        .ps-panel--sidebar .user-greeting-link:hover {
            opacity: 0.85 !important;
        }
        
        /* Guest user: wrapper for avatar + buttons */
        .ps-panel--sidebar .user-greeting-guest {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            width: 100% !important;
        }
        
        .ps-panel--sidebar .user-greeting-guest .user-info {
            flex: 1 !important;
        }
        
        /* Guest actions buttons container */
        .ps-panel--sidebar .guest-actions {
            display: flex !important;
            gap: 8px !important;
            margin-top: 4px !important;
        }
        
        .ps-panel--sidebar .btn-guest-action {
            display: inline-block !important;
            padding: 4px 12px !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            border-radius: 4px !important;
            transition: all 0.2s ease !important;
        }
        
        .ps-panel--sidebar .btn-login {
            background-color: transparent !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            border: 1px solid var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        
        .ps-panel--sidebar .btn-login:hover {
            background-color: var(--global-color-text-on-dark, #F4F9FD) !important;
            color: var(--global-color-secondary, #182A3E) !important;
        }
        
        .ps-panel--sidebar .btn-register {
            background-color: var(--global-color-primary, #FF642C) !important;
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
            border: 1px solid var(--global-color-primary, #FF642C) !important;
        }
        
        .ps-panel--sidebar .btn-register:hover {
            background-color: var(--global-color-primary-hover, #FF4500) !important;
            border-color: var(--global-color-primary-hover, #FF4500) !important;
        }
        
        /* Search sidebar header special layout - full width */
        .ps-panel__header--search {
            display: block !important;
        }
        
        .ps-panel__header--search .ps-form--search-mobile {
            width: 100% !important;
        }
        
        /* Sidebar panel content - remove padding but add bottom space for bottom bar */
        .ps-panel--sidebar .ps-panel__content {
            padding: 0 0 100px 0 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            max-height: calc(100vh - 60px) !important; /* 60px for header */
            -webkit-overflow-scrolling: touch !important;
            position: relative !important;
            background-color: var(--global-color-site-bg, #F4F9FD) !important;
        }
        
        /* Cart sidebar uses .navigation__content instead of .ps-panel__content */
        .ps-panel--sidebar .navigation__content {
            padding: 0 0 100px 0 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            max-height: calc(100vh - 60px) !important; /* 60px for header */
            -webkit-overflow-scrolling: touch !important;
        }
        
        /* When submenu panel is active, hide parent scrolling */
        .ps-panel--sidebar .ps-panel__content.submenu-open {
            overflow-y: hidden !important;
        }
        
        /* Hide default submenus */
        .ps-panel--sidebar .menu--mobile .sub-menu {
            display: none !important;
        }
        
        /* Submenu slide panel */
        .ps-submenu-panel {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: var(--global-color-site-bg, #F4F9FD) !important;
            transform: translateX(100%) !important;
            transition: transform 0.3s ease !important;
            z-index: 10 !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch !important;
            display: flex !important;
            flex-direction: column !important;
        }
        
        .ps-submenu-panel.active {
            transform: translateX(0) !important;
        }
        
        /* Hide main menu items when submenu panel is active */
        .ps-panel--sidebar:has(.ps-submenu-panel.active) > .ps-panel__content > .menu--mobile {
            visibility: hidden !important;
        }
        
        /* Submenu panel header */
        .ps-submenu-panel__header {
            background: transparent !important;
            padding: 0 !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 20 !important;
        }
        
        /* Back button - full width, left aligned with arrow */
        .ps-submenu-panel__back {
            display: flex !important;
            align-items: center !important;
            color: var(--global-color-text-body, #2E3D52) !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            cursor: pointer !important;
            background: var(--global-color-site-bg, #F4F9FD) !important;
            border: none !important;
            padding: 15px !important;
            width: 100% !important;
            transition: background-color 0.2s ease !important;
        }
        
        .ps-submenu-panel__back:hover {
            background-color: var(--global-color-card-bg, #FFFFFF) !important;
        }
        
        .ps-submenu-panel__back i {
            font-size: 16px !important;
            margin-right: 8px !important;
            color: var(--global-color-text-body, #2E3D52) !important;
        }
        
        /* Divider after back button */
        .ps-submenu-panel__divider {
            height: 1px !important;
            background-color: var(--global-color-primary-border, #FFB399) !important;
            margin: 0 !important;
        }
        
        /* Parent menu title */
        .ps-submenu-panel__title {
            color: var(--global-color-text-main, #0D1B2A) !important;
            font-size: 16px !important;
            font-weight: 600 !important;
            margin: 0 !important;
            padding: 15px !important;
            background-color: var(--global-color-site-bg, #F4F9FD) !important;
            border-bottom: 1px solid var(--global-color-primary-border, #FFB399) !important;
        }
        
        /* Submenu panel items */
        .ps-submenu-panel__items {
            padding: 0 !important;
            padding-bottom: 100px !important;
            margin: 0 !important;
            list-style: none !important;
            background-color: var(--global-color-site-bg, #F4F9FD) !important;
        }
        
        .ps-submenu-panel__items li {
            border-bottom: 1px solid var(--global-color-primary-border, #FFB399) !important;
        }
        
        .ps-submenu-panel__items li:last-child {
            border-bottom: none !important;
        }
        
        .ps-submenu-panel__items li a {
            display: block !important;
            padding: 10px 15px !important;
            color: var(--global-color-text-body, #2E3D52) !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            background-color: transparent !important;
        }
        
        .ps-submenu-panel__items li a:hover {
            background-color: var(--global-color-card-bg, #FFFFFF) !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
        }
        
        /* Active submenu items */
        .ps-submenu-panel__items li.current-menu-item > a,
        .ps-submenu-panel__items li.active > a {
            background-color: transparent !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
            font-weight: 600 !important;
        }
        
        .ps-submenu-panel__items li.current-menu-item > a:hover,
        .ps-submenu-panel__items li.active > a:hover {
            background-color: var(--global-color-card-bg, #FFFFFF) !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
        }
        
        /* Language switcher in submenu panel - exact desktop flyout style */
        .ps-submenu-panel__lang-switcher {
            background-color: var(--global-color-site-bg, #F4F9FD) !important;
            padding: 0 !important;
        }
        
        /* Section title - exactly like desktop */
        .ps-submenu-panel__lang-switcher .bb-lf-section-title {
            font-size: 11px !important;
            font-weight: 500 !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
            text-transform: none !important;
            letter-spacing: .07em !important;
            padding: 10px 16px 6px !important;
            margin: 0 !important;
        }
        
        /* Language list - exactly like desktop */
        .ps-submenu-panel__lang-switcher .bb-lf-list {
            list-style: none !important;
            margin: 0 !important;
            padding: 0 16px !important;
        }
        
        .ps-submenu-panel__lang-switcher .bb-lf-item {
            margin: 0 !important;
        }
        
        /* Active item - no extra margin */
        .ps-submenu-panel__lang-switcher .bb-lf-item--active {
            margin-bottom: 0 !important;
        }
        
        /* Divider between current and other languages - exactly like desktop */
        .ps-submenu-panel__lang-switcher .bb-lf-divider {
            height: 1px !important;
            background: var(--global-color-primary-border, #FFB399) !important;
            margin: 8px 0 !important;
            list-style: none !important;
            border: none !important;
            padding: 0 !important;
        }
        
        /* Language row base styles - exactly like desktop */
        .ps-submenu-panel__lang-switcher .bb-lf-row,
        .ps-submenu-panel__lang-switcher a.bb-lf-row {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            gap: 8px !important;
            padding: 7px 0 !important;
            text-decoration: none !important;
            color: var(--global-color-text-body, #2E3D52) !important;
            font-size: 13px !important;
            line-height: 1.4 !important;
            cursor: pointer !important;
            background: none !important;
            transition: color .15s ease, text-decoration .15s ease !important;
            border: none !important;
            border-radius: 0 !important;
        }
        
        /* Ensure text spans inherit the link color */
        .ps-submenu-panel__lang-switcher .bb-lf-row .bb-lf-text,
        .ps-submenu-panel__lang-switcher a.bb-lf-row .bb-lf-text {
            color: inherit !important;
        }
        
        /* Non-active items: hover state - use primary color with underline */
        .ps-submenu-panel__lang-switcher .bb-lf-item:not(.bb-lf-item--active) .bb-lf-row:hover,
        .ps-submenu-panel__lang-switcher .bb-lf-item:not(.bb-lf-item--active) a.bb-lf-row:hover {
            color: var(--global-color-primary, #FF642C) !important;
            text-decoration: underline !important;
            background: none !important;
        }
        
        /* Active/selected item: base state */
        .ps-submenu-panel__lang-switcher .bb-lf-item--active .bb-lf-row,
        .ps-submenu-panel__lang-switcher .bb-lf-item--active a.bb-lf-row {
            font-weight: 600 !important;
            color: var(--global-color-text-body, #2E3D52) !important;
        }
        
        /* Active/selected item: hover state */
        .ps-submenu-panel__lang-switcher .bb-lf-item--active .bb-lf-row:hover,
        .ps-submenu-panel__lang-switcher .bb-lf-item--active a.bb-lf-row:hover {
            color: var(--global-color-primary, #FF642C) !important;
            text-decoration: underline !important;
        }
        
        /* Radio indicator base - exactly like desktop */
        .ps-submenu-panel__lang-switcher .bb-lf-radio {
            flex-shrink: 0 !important;
            width: 14px !important;
            height: 14px !important;
            border: 2px solid #ccc !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: border-color .15s ease !important;
        }
        
        /* Radio border on hover for non-active items */
        .ps-submenu-panel__lang-switcher .bb-lf-item:not(.bb-lf-item--active) .bb-lf-row:hover .bb-lf-radio {
            border-color: var(--global-color-primary, #FF642C) !important;
        }
        
        /* Active item radio border */
        .ps-submenu-panel__lang-switcher .bb-lf-item--active .bb-lf-radio {
            border-color: var(--global-color-primary, #FF642C) !important;
        }
        
        /* Radio dot base */
        .ps-submenu-panel__lang-switcher .bb-lf-radio__dot {
            width: 6px !important;
            height: 6px !important;
            border-radius: 50% !important;
            background: transparent !important;
            transition: background .15s ease !important;
        }
        
        /* Show dot on hover for non-active items */
        .ps-submenu-panel__lang-switcher .bb-lf-item:not(.bb-lf-item--active) .bb-lf-row:hover .bb-lf-radio__dot {
            background: var(--global-color-primary, #FF642C) !important;
        }
        
        /* Active item dot - always visible */
        .ps-submenu-panel__lang-switcher .bb-lf-item--active .bb-lf-radio__dot {
            background: var(--global-color-primary, #FF642C) !important;
        }
        
        /* Text and flag containers - exactly like desktop */
        .ps-submenu-panel__lang-switcher .bb-lf-text {
            flex: 1 !important;
            min-width: 0 !important;
        }
        
        .ps-submenu-panel__lang-switcher .bb-lf-flag {
            flex-shrink: 0 !important;
            line-height: 0 !important;
        }
        
        .ps-submenu-panel__lang-switcher .bb-lf-flag img {
            width: 20px !important;
            height: 13px !important;
            object-fit: cover !important;
            border-radius: 2px !important;
            display: block !important;
        }
        
        /* Currency section divider and styles - exactly like desktop */
        .ps-submenu-panel__lang-switcher .bb-lf-section-divider {
            height: 1px !important;
            background: var(--global-color-primary-border, #FFB399) !important;
            margin: 12px 0 !important;
        }
        
        .ps-submenu-panel__lang-switcher .bb-lf-section-divider + .bb-lf-section-title {
            padding-top: 0 !important;
        }
        
        .ps-submenu-panel__lang-switcher .bb-lf-currency-row {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 10px 16px !important;
            font-size: 13px !important;
            color: var(--global-color-text-body, #2E3D52) !important;
        }
        
        .ps-submenu-panel__lang-switcher .bb-lf-curr-info {
            flex: 1 !important;
            font-weight: 500 !important;
        }
        
        .ps-submenu-panel__lang-switcher .bb-lf-change-link {
            flex-shrink: 0 !important;
            font-size: 13px !important;
            color: var(--global-color-link, #13acdf) !important;
            text-decoration: underline !important;
            transition: color .15s ease !important;
        }
        
        .ps-submenu-panel__lang-switcher .bb-lf-change-link:hover {
            color: var(--global-color-link-hover, #1dbff5) !important;
        }
        
        /* Hide sub-toggle arrows since we're replacing behavior */
        .ps-panel--sidebar .sub-toggle {
            display: none !important;
        }
        
        /* Sidebar menu lists - remove padding */
        .ps-panel--sidebar .menu--mobile,
        .ps-panel--sidebar .menu--mobile-extra,
        .ps-panel--sidebar .menu--mobile .sub-menu {
            padding: 0 !important;
            background-color: var(--global-color-site-bg, #F4F9FD) !important;
        }
        
        /* Add borders between menu items */
        .ps-panel--sidebar .menu--mobile > li,
        .ps-panel--sidebar .menu--mobile-extra > li {
            border-bottom: 1px solid var(--global-color-primary-border, #FFB399) !important;
        }
        
        .ps-panel--sidebar .menu--mobile > li:last-child,
        .ps-panel--sidebar .menu--mobile-extra > li:last-child {
            border-bottom: none !important;
        }
        
        /* Sidebar menu items */
        .ps-panel--sidebar .menu--mobile > li > a,
        .ps-panel--sidebar .menu--mobile-extra > li > a {
            color: var(--global-color-text-body, #2E3D52) !important;
            padding: 10px 15px !important;
            transition: background-color 0.2s ease, color 0.2s ease, transform 0s !important;
            transform: none !important;
            position: relative !important;
            display: block !important;
        }
        
        /* Menu items with children - add padding for chevron area and adjust positioning */
        .ps-panel--sidebar .menu--mobile > li.menu-item-has-children {
            position: relative !important;
            display: flex !important;
        }
        
        .ps-panel--sidebar .menu--mobile > li.menu-item-has-children > a {
            padding-right: 15px !important;
            flex: 1 !important;
            /* Don't let the link's background extend into chevron area */
            margin-right: 0 !important;
        }
        
        /* Chevron click area for items with submenus */
        .ps-panel--sidebar .menu--mobile > li.menu-item-has-children > .submenu-chevron {
            position: relative !important;
            width: 50px !important;
            min-width: 50px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-left: 1px solid var(--global-color-primary-border, #FFB399) !important;
            cursor: pointer !important;
            background: transparent !important;
            transition: background-color 0.2s ease !important;
            flex-shrink: 0 !important;
        }
        
        .ps-panel--sidebar .menu--mobile > li.menu-item-has-children > .submenu-chevron:hover {
            background-color: var(--global-color-card-bg-raised, #FFF3EC) !important;
        }
        
        .ps-panel--sidebar .menu--mobile > li.menu-item-has-children > .submenu-chevron i {
            font-size: 18px !important;
            color: var(--global-color-text-muted, #7A8FA6) !important;
            transition: color 0.2s ease !important;
        }
        
        .ps-panel--sidebar .menu--mobile > li.menu-item-has-children > .submenu-chevron:hover i {
            color: var(--global-color-primary, #FF642C) !important;
        }
        
        /* Hover effects - link area only (not covering chevron) */
        .ps-panel--sidebar .menu--mobile > li > a:hover,
        .ps-panel--sidebar .menu--mobile-extra > li > a:hover {
            background-color: var(--global-color-card-bg, #FFFFFF) !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
            transform: none !important;
        }
        
        /* Active menu items - default state */
        .ps-panel--sidebar .menu--mobile > li.current-menu-item > a,
        .ps-panel--sidebar .menu--mobile > li.active > a {
            background-color: transparent !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
            font-weight: 600 !important;
            transform: none !important;
        }
        
        /* Active menu items - hover state should still work */
        .ps-panel--sidebar .menu--mobile > li.current-menu-item > a:hover,
        .ps-panel--sidebar .menu--mobile > li.active > a:hover {
            background-color: var(--global-color-card-bg, #FFFFFF) !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
        }
        
        /* Sidebar submenu items - not needed anymore with slide panels */

        /* Content-area overlay — sits above page content but below header and sidebars */
        .ps-site-overlay {
            z-index: 999 !important;   /* below header (1000+) and sidebars (10001) */
            height: auto !important;   /* JS sets top/bottom to clip to content area */
        }
        /* Keep header above the overlay at all times */
        .header,
        .header--mobile,
        .navigation--sticky {
            z-index: 1050 !important;
            position: relative !important;
        }
        /* Cart mini flyout, search panel — above overlay but below sidebar panels */
        .ps-cart--mini .ps-cart__content,
        .ps-panel--search-result {
            z-index: 1060 !important;
        }

        /* ── Desktop content-area overlay ─────────────────────────────────
           Shown when a desktop flyout/dropdown is hovered.
           Covers only the body content area (below header, above bottom bar).
           The ps-site-overlay stays full-viewport for mobile sidebars (unchanged). */
        #bb-content-overlay {
            position: fixed;
            top: var(--bb-header-h, 0px);
            bottom: var(--bb-bottombar-h, 0px);
            left: 0;
            right: 0;
            background: rgba(0,0,0,.45);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: opacity .25s ease, visibility .25s ease;
            pointer-events: none;
        }
        /* Overlay triggers - managed by JavaScript */
        .ps-cart--mini:hover #bb-content-overlay,
        body.bb-flyout-open #bb-content-overlay {
            opacity: 1;
            visibility: visible;
        }
        /* Keep header, flyouts, sidebars above content overlay */
        .header,
        .header--mobile,
        .navigation--sticky { z-index: 1050 !important; position: relative !important; }
        .header .navigation { z-index: 1050 !important; position: relative !important; }
        .ps-cart--mini .ps-cart__content,
        .ps-panel--search-result,
        .menu--product-categories .menu__content,
        .ps-dropdown ul,
        .ps-dropdown-menu,
        .menu > li .mega-menu { z-index: 1060 !important; }
        /* ps-site-overlay stays full-viewport at 10000 for mobile sidebars */
        .ps-site-overlay { z-index: 10000 !important; }
        .header--mobile .ps-cart--mini {
            margin: 0 6px !important;
        }

        /* Breadcrumb */
        .ps-breadcrumb { background: var(--breadcrumb-bg); padding: var(--breadcrumb-padding); }
        .ps-breadcrumb a { color: var(--breadcrumb-link-color); }
        .ps-breadcrumb a:hover { color: var(--breadcrumb-link-hover-color); }
        .ps-breadcrumb span { color: var(--breadcrumb-text-color); }

        /* Go to Top Button — selector: #back2top */
        #back2top {
            width: var(--backtotop-size) !important;
            height: var(--backtotop-size) !important;
            bottom: var(--backtotop-bottom) !important;
            right: var(--backtotop-right) !important;
            background-color: var(--global-color-warning, #FBB13C) !important;
            border-color: var(--backtotop-border-color) !important;
            border-radius: var(--backtotop-border-radius) !important;
            box-shadow: var(--backtotop-shadow) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
        }
        #back2top svg {
            width: 20px !important;
            height: 20px !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
            transition: color 0.3s ease !important;
        }
        #back2top i {
            font-size: var(--backtotop-icon-size) !important;
            color: var(--global-color-text-main, #0D1B2A) !important;
            transition: color 0.3s ease !important;
        }
        #back2top:hover {
            background-color: var(--global-color-primary, #FF642C) !important;
            border-color: var(--global-color-primary, #FF642C) !important;
        }
        #back2top:hover svg,
        #back2top:hover i {
            color: var(--global-color-text-on-dark, #F4F9FD) !important;
        }
        /* Go to Top Button — Hide on mobile (we have bottom bar arrow) */
        @media (max-width: 1199px) {
            #back2top,
            .ps-back-top {
                display: none !important;
                visibility: hidden !important;
            }
        }
        
        /* ═══════════════════════════════════════════════════════════════ */
        /*            PRODUCT LIGHTBOX MODAL                               */
        /* ═══════════════════════════════════════════════════════════════ */
        
        .product-lightbox-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(13, 27, 42, 0.4); /* Opacity 0.4 */
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        
        .product-lightbox-container {
            width: 95%;
            height: 85vh;
            max-width: 1600px;
            background: var(--global-color-site-bg, #F4F9FD);
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            padding: 20px;
            position: relative; /* For absolute positioning of close button */
        }
        
        /* Close button - positioned at container level, not inside header */
        .product-lightbox-close {
            background: none;
            border: none;
            color: var(--global-color-text-body, #2E3D52);
            cursor: pointer;
            padding: 0;
            line-height: 1;
            position: absolute;
            top: 4px;
            right: 4px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s ease;
            z-index: 10;
        }
        
        .product-lightbox-close i,
        .product-lightbox-close .icon-cross {
            font-size: 1.25rem;
            font-weight: 700;
        }
        
        .product-lightbox-close:hover {
            color: var(--global-color-link-hover, #1dbff5);
        }
        
        /* Header */
        .product-lightbox-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            position: relative;
            border-bottom: 2px solid var(--global-color-primary-border, #FFB399);
        }
        
        .product-lightbox-tabs {
            display: flex;
            gap: 0;
            padding: 0;
            width: 100%;
        }
        
        .product-lightbox-tab {
            background: none;
            border: none;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: var(--global-color-text-body, #2E3D52);
            cursor: pointer;
            position: relative;
            transition: color 0.3s ease;
        }
        
        .product-lightbox-tab:hover {
            color: var(--global-color-text-main, #0D1B2A);
        }
        
        .product-lightbox-tab.active {
            color: var(--global-color-text-main, #0D1B2A);
        }
        
        .product-lightbox-tab.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--global-color-link, #13acdf);
            z-index: 1;
        }
        
        /* Body */
        .product-lightbox-body {
            flex: 1;
            display: flex;
            overflow: hidden;
            padding: 0;
        }
        
        /* Main Display - 64% */
        .product-lightbox-main {
            width: 64%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            background: transparent;
            overflow: hidden;
        }
        
        .product-lightbox-display {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            overflow: hidden;
            position: relative;
        }
        
        /* Hide video content by default - show only on VIDEOS tab */
        #lightbox-videos-content {
            display: none;
        }
        
        .product-lightbox-image-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
            cursor: zoom-in;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-lightbox-image-wrapper.zoomed {
            cursor: zoom-out;
        }
        
        .product-lightbox-main-image {
            display: block;
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            margin: auto; /* Center in wrapper */
        }
        
        .product-lightbox-image-wrapper.zoomed .product-lightbox-main-image {
            position: absolute; /* Only absolute when zoomed */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: none;
            max-height: none;
            min-width: 200%;
            min-height: 200%;
            width: auto;
            height: auto;
            margin: 0;
        }
        
        /* Sidebar - 35% */
        .product-lightbox-sidebar {
            width: 35%;
            display: flex;
            flex-direction: column;
            background: var(--global-color-site-bg, #F4F9FD);
            border: none; /* No border */
        }
        
        .product-lightbox-info {
            padding: 20px 30px;
            border-bottom: 1px solid var(--global-color-primary-border, #FFB399);
        }
        
        .product-lightbox-title {
            font-size: 1.05rem;
            font-weight: 400;
            color: var(--global-color-text-main, #0D1B2A);
            margin: 0 0 15px 0;
            line-height: 1.4;
        }
        
        .product-lightbox-variations {
            font-size: 14px;
            color: var(--global-color-text-body, #2E3D52);
        }
        
        /* Thumbnails */
        .product-lightbox-thumbnails {
            flex: 1;
            padding: 0 20px;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            align-content: start;
        }
        
        .product-lightbox-thumbnail {
            aspect-ratio: 1;
            border: 2px solid var(--global-color-border-light, #F0D8CB);
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--global-color-card-bg, #FFFFFF);
        }
        
        .product-lightbox-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-lightbox-thumbnail:hover {
            border-color: var(--global-color-link-hover, #1dbff5);
        }
        
        .product-lightbox-thumbnail.active {
            border-color: var(--global-color-link, #13acdf);
            box-shadow: 0 0 0 2px rgba(19, 172, 223, 0.2);
        }
        
        .product-lightbox-thumbnail.active img {
            transform: scale(1.02);
        }
        
        /* ══════════ TABLET LAYOUT (768px - 1199px) ══════════ */
        @media (max-width: 1199px) and (min-width: 768px) {
            .product-lightbox-container {
                width: 95%;
                height: 90%;
            }
            
            .product-lightbox-body {
                flex-direction: column;
            }
            
            .product-lightbox-main {
                width: 100%;
                height: 50%;
            }
            
            .product-lightbox-sidebar {
                width: 100%;
                height: 50%;
                border-left: none;
                border-top: 1px solid var(--global-color-primary-border, #FFB399);
            }
            
            .product-lightbox-thumbnails {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        /* ══════════ MOBILE LAYOUT (below 768px) ══════════ */
        @media (max-width: 767px) {
            /* Full screen overlay - no spacing */
            .product-lightbox-overlay {
                padding: 0 !important;
            }
            
            .product-lightbox-container {
                width: 100% !important;
                height: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                border-radius: 0 !important;
            }
            
            /* Hide header completely on mobile */
            .product-lightbox-header {
                display: none !important;
            }
            
            /* Close button becomes back button - overlay on top left */
            .product-lightbox-close {
                position: fixed !important;
                top: 20px !important;
                left: 20px !important;
                right: auto !important;
                width: auto !important;
                height: auto !important;
                background: rgba(255, 255, 255, 0.6) !important;
                border-radius: 20px !important;
                padding: 8px 16px !important;
                display: flex !important;
                align-items: center !important;
                z-index: 1000 !important;
            }
            
            /* Hide icon, show only text */
            .product-lightbox-close i {
                display: none !important;
            }
            
            .product-lightbox-close::after {
                content: 'Back' !important;
                font-size: 14px !important;
                font-weight: 500 !important;
                color: var(--global-color-text-main, #0D1B2A) !important;
            }
            
            /* Full screen layout */
            .product-lightbox-body {
                flex-direction: column !important;
                padding: 0 !important;
                height: 100% !important;
            }
            
            /* Main image takes full screen */
            .product-lightbox-main {
                width: 100% !important;
                height: 100% !important;
                padding: 0 !important;
                position: relative !important;
            }
            
            /* Double tap hint message - above thumbnails */
            .product-lightbox-hint {
                display: none !important;
                position: fixed !important;
                bottom: 90px !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
                background: rgba(255, 255, 255, 0.9) !important;
                color: var(--global-color-link, #0D6EFD) !important;
                padding: 8px 16px !important;
                border-radius: 20px !important;
                font-size: 13px !important;
                font-weight: 500 !important;
                z-index: 1000 !important;
                pointer-events: none !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
                animation: fadeInOut 3s ease-in-out !important;
            }
            
            @keyframes fadeInOut {
                0% { opacity: 0; }
                10% { opacity: 1; }
                90% { opacity: 1; }
                100% { opacity: 0; }
            }
            
            /* Keep sidebar for thumbnails but hide info */
            .product-lightbox-sidebar {
                display: block !important;
                position: fixed !important;
                bottom: 0 !important;
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                height: 80px !important;
                padding: 0 !important;
                border: none !important;
                background: transparent !important;
                z-index: 999 !important;
            }
            
            .product-lightbox-info {
                display: none !important;
            }
            
            /* Thumbnails as bottom strip overlay */
            .product-lightbox-thumbnails {
                display: flex !important;
                position: relative !important;
                width: 100% !important;
                height: 100% !important;
                padding: 10px 15px !important;
                background: var(--global-color-site-bg, #FFFFFF) !important;
                flex-direction: row !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                gap: 2px !important;
                align-items: center !important;
            }
            
            /* Each thumbnail - calculated to show 7.5 images in view */
            .product-lightbox-thumbnail {
                flex: 0 0 calc((100vw - 30px - 13px) / 7.5) !important;
                width: calc((100vw - 30px - 13px) / 7.5) !important;
                height: 60px !important;
                display: block !important;
            }
        }
        
        /* Footer — driven by theme options */
        .ps-footer {
            background: var(--global-color-secondary-soft) !important;
        }
        .ps-footer__widgets {
            background: var(--global-color-secondary-mid) !important;
        }
        /* Footer widget titles */
        .ps-footer .widget-title,
        .ps-footer h4.widget-title {
            color: var(--global-color-text-on-dark) !important;
        }
        /* Footer widget content elements */
        .ps-footer .widget_content,
        .ps-footer .widget_content p,
        .ps-footer .widget_content span,
        .ps-footer .widget_content a,
        .ps-footer .widget_content li {
            color: var(--global-color-text-on-dark) !important;
        }
        /* Footer widget content links hover - add underline */
        .ps-footer .widget_content p a:hover {
            text-decoration: underline !important;
        }
        /* Footer widget content h3 - warning color */
        .ps-footer .widget_content h3 {
            color: var(--global-color-warning) !important;
        }
        /* Footer ps-list--link */
        .ps-footer .ps-list--link a {
            color: var(--global-color-text-on-dark) !important;
        }
        .ps-footer .ps-list--link a:hover {
            color: var(--global-color-text-on-dark) !important;
        }
        .ps-footer__links,
        .ps-footer__copyright {
            background: var(--global-color-secondary) !important;
            border-top: 1px solid var(--global-color-secondary-soft) !important;
            color: var(--global-color-text-on-dark) !important;
        }
        /* Footer bottom text elements */
        .ps-footer__links p,
        .ps-footer__links span,
        .ps-footer__links a,
        .ps-footer__links li,
        .ps-footer__links strong,
        .ps-footer__copyright p,
        .ps-footer__copyright span,
        .ps-footer__copyright a,
        .ps-footer__copyright strong {
            color: var(--global-color-text-on-dark) !important;
        }
        /* Footer bottom links hover - keep same color, animation border same color */
        .ps-footer__links a:hover,
        .ps-footer__copyright a:hover {
            color: var(--global-color-text-on-dark) !important;
            border-color: var(--global-color-text-on-dark) !important;
        }
        /* Footer bottom link underline (a:before) */
        .ps-footer__links a:before,
        .ps-footer__copyright a:before {
            background-color: var(--global-color-text-on-dark) !important;
            border-color: var(--global-color-text-on-dark) !important;
        }

        /* Mobile footer padding */
        @media (max-width: 767px) {
            .ps-footer {
                padding-top: 35px !important;
            }
            .ps-footer__widgets {
                padding-bottom: 0 !important;
            }
            .ps-footer__links,
            .ps-footer__copyright {
                padding-top: 35px !important;
                padding-bottom: 35px !important;
            }
        }
        .widget_footer a:before {
            background: var(--global-color-card-bg) !important;
        }

        /* Search results dropdown */
        .ps-panel--search-result { 
            background: var(--global-color-site-bg) !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        /* Fix text alignment for ALL search results (desktop + mobile) */
        .ps-panel--search-result .ps-product,
        .ps-panel--search-result .ps-product *,
        .ps-panel--search-result .ps-product__content,
        .ps-panel--search-result .ps-product__title,
        .ps-panel--search-result .ps-product__title a,
        .ps-panel--search-result .ps-product__price,
        .ps-panel--search-result .ps-product__price span,
        .ps-panel--search-result .ps-product__price del,
        .ps-panel--search-result .ps-product__meta {
            text-align: left !important;
        }
        
        /* Target exact structure for search results */
        .ps-panel--search-result .ps-panel__content .ps-product--wide.ps-product--search-result,
        .ps-panel--search-result .ps-panel__content .ps-product--wide.ps-product--search-result *,
        .ps-panel--search-result .ps-panel__content .ps-product--wide.ps-product--search-result .ps-product__content,
        .ps-panel--search-result .ps-panel__content .ps-product--wide.ps-product--search-result .ps-product__content *,
        .ps-panel--search-result .ps-panel__content .ps-product--wide.ps-product--search-result .ps-product__content a,
        .ps-panel--search-result .ps-panel__content .ps-product--wide.ps-product--search-result .ps-product__content p,
        .ps-panel--search-result .ps-panel__content .ps-product--wide.ps-product--search-result .ps-product__content .ps-product__title,
        .ps-panel--search-result .ps-panel__content .ps-product--wide.ps-product--search-result .ps-product__content .ps-product__price,
        .ps-panel--search-result .ps-panel__content .ps-product--wide.ps-product--search-result .ps-product__content .ps-product__price *,
        .ps-panel--search-result .ps-product--wide .ps-product__content,
        .ps-panel--search-result .ps-product--wide .ps-product__content *,
        .ps-panel--search-result .ps-product__content,
        .ps-panel--search-result .ps-product__content *,
        .ps-panel--search-result .ps-product__content a,
        .ps-panel--search-result .ps-product__content p,
        .ps-panel--search-result .ps-product--search-result .ps-product__content,
        .ps-panel--search-result .ps-product--search-result .ps-product__content * {
            text-align: left !important;
            justify-content: flex-start !important;
        }
        .ps-panel--search-result .ps-product--search-result .ps-product__title { 
            color: var(--global-color-link) !important; 
            text-decoration: none !important;
        }
        .ps-panel--search-result .ps-product--search-result .ps-product__title:hover { 
            color: var(--global-color-link-hover) !important;
            @if(theme_option('search_product_title_hover_decoration', 'underline') === 'underline')
            text-decoration: underline !important;
            @else
            text-decoration: none !important;
            @endif
        }
        .ps-panel--search-result .ps-product--search-result .ps-product__price { color: var(--global-color-primary) !important; }
        .ps-panel--search-result .ps-product--search-result .ps-product__price del { color: var(--global-color-text-body) !important; }
        .ps-panel--search-result .ps-panel__footer { border-top: 1px solid var(--global-color-primary-border) !important; }
        .ps-panel--search-result .ps-panel__footer a {
            background: var(--global-color-site-bg) !important;
            color: var(--global-color-text-main) !important;
            border: 1px solid var(--global-color-site-bg) !important;
        }
        .ps-panel--search-result .ps-panel__footer a:hover {
            color: var(--global-color-primary) !important;
        }
        /* No results */
        .ps-panel--search-result .text-center {
            background: var(--global-color-site-bg) !important;
            color: var(--global-color-text-body) !important;
        }

        /* Cart flyout — background on items area and footer, not the wrapper */
        .ps-cart__items,
        .ps-cart--mini .ps-cart__items,
        .ps-cart--mobile .ps-cart__items {
            background: var(--global-color-site-bg) !important;
        }

        /* Cart item row */
        .ps-product--cart-mobile {
            background: var(--global-color-site-bg) !important;
            border-bottom: 1px solid var(--global-color-primary-border) !important;
            margin-bottom: 0 !important;
            padding-bottom: 15px !important;
        }
        /* Remove border and padding from last item when there's only one */
        .ps-product--cart-mobile:only-child {
            border-bottom: none !important;
            padding-bottom: 10px !important;
        }
        /* Keep spacing between multiple items */
        .ps-product--cart-mobile:not(:last-child) {
            margin-bottom: 15px !important;
        }

        /*
         * Product name link — exclude the remove button (.ps-product__remove)
         * which is also a direct-child <a>. The theme styles ALL <a> inside
         * .ps-product__content so we must be equally broad but exclude the remove.
         */
        .ps-product--cart-mobile .ps-product__content a:not(.ps-product__remove) {
            color: var(--global-color-link) !important;
        }
        .ps-product--cart-mobile .ps-product__content a:not(.ps-product__remove):hover {
            color: var(--global-color-link-hover) !important;
            text-decoration: underline !important;
        }

        /*
         * Qty "N x" — lives inside <small> which the theme colors #000.
         * Target small directly so we win the specificity race.
         */
        .ps-product--cart-mobile .ps-product__content small {
            color: var(--global-color-text-body) !important;
        }
        /* Variations/attributes — nested <small><small> */
        .ps-product--cart-mobile .ps-product__content small small {
            color: var(--global-color-text-main) !important;
        }
        /* But don't let that bleed onto the price span — re-assert price color */
        .ps-product--cart-mobile .cart-price {
            color: var(--global-color-text-body) !important;
        }
        .ps-product--cart-mobile .cart-price del,
        .ps-product--cart-mobile .cart-price small del {
            color: var(--global-color-text-muted) !important;
        }

        /*
         * Remove button — theme sets color on the <a> itself, not on <i>.
         * Target the <a> directly (and the <i> as a fallback).
         */
        .ps-product--cart-mobile .ps-product__remove,
        .ps-product--cart-mobile .ps-product__remove i {
            color: var(--global-color-alert) !important;
        }
        .ps-product--cart-mobile .ps-product__remove:hover,
        .ps-product--cart-mobile .ps-product__remove:hover i {
            color: var(--global-color-alert-hover) !important;
        }

        /* Cart footer — mini + mobile dropdowns both use .ps-cart--mini/.ps-cart--mobile
           which raise specificity; we must match or exceed them */
        .ps-cart__footer,
        .ps-cart--mini .ps-cart__footer,
        .ps-cart--mobile .ps-cart__footer {
            background: var(--global-color-site-bg) !important;
            border-top: 1px solid var(--global-color-primary-border) !important;
        }

        /* Total / subtotal label */
        .ps-cart__footer h3,
        .ps-cart__footer h5,
        .ps-cart--mini .ps-cart__footer h3,
        .ps-cart--mini .ps-cart__footer h5,
        .ps-cart--mobile .ps-cart__footer h3,
        .ps-cart--mobile .ps-cart__footer h5 {
            color: var(--global-color-text-main) !important;
        }

        /* Subtotal & Tax values (h5 strong) — use text body color */
        .ps-cart__footer h5 strong,
        .ps-cart--mini .ps-cart__footer h5 strong,
        .ps-cart--mobile .ps-cart__footer h5 strong {
            color: var(--global-color-text-body) !important;
        }

        /* Total value (h3 strong) — use primary color */
        .ps-cart__footer h3 strong,
        .ps-cart--mini .ps-cart__footer h3 strong,
        .ps-cart--mobile .ps-cart__footer h3 strong {
            color: var(--global-color-primary) !important;
        }

        /* Cart buttons — they are <a> tags, so add a.ps-btn alongside .ps-btn */
        .ps-cart__footer .ps-btn,
        .ps-cart__footer a.ps-btn,
        .ps-cart--mini .ps-cart__footer .ps-btn,
        .ps-cart--mini .ps-cart__footer a.ps-btn,
        .ps-cart--mobile .ps-cart__footer .ps-btn,
        .ps-cart--mobile .ps-cart__footer a.ps-btn {
            background-color: var(--global-color-warning) !important;
            color: var(--global-color-text-main) !important;
            border: 1px solid var(--global-color-warning) !important;
        }
        .ps-cart__footer .ps-btn:hover,
        .ps-cart__footer a.ps-btn:hover,
        .ps-cart--mini .ps-cart__footer .ps-btn:hover,
        .ps-cart--mini .ps-cart__footer a.ps-btn:hover,
        .ps-cart--mobile .ps-cart__footer .ps-btn:hover,
        .ps-cart--mobile .ps-cart__footer a.ps-btn:hover {
            background-color: var(--global-color-primary) !important;
            color: var(--global-color-text-on-dark) !important;
            border-color: var(--global-color-primary) !important;
        }

        /* Cart empty state */
        .ps-cart__items.ps-cart_no_items {
            background: var(--global-color-site-bg) !important;
        }
        .ps-cart__items.ps-cart_no_items .cart-empty-message {
            color: var(--global-color-text-body) !important;
        }
        .ps-cart__empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            gap: 16px;
        }
        .ps-cart__empty-state svg {
            width: 64px;
            height: 64px;
            color: var(--global-color-text-body);
            opacity: 0.5;
        }
        .ps-cart__empty-state .cart-empty-message {
            font-size: 14px;
            text-align: center;
        }

        @media all and (max-width: 431px) {
            /* Only make search sticky when that mode is explicitly selected */
            .header.header--mobile-sticky-search.header--sticky .navigation--mobile {
                position: static !important;
                box-shadow: none !important;
            }
            .header.header--mobile-sticky-search.header--sticky .ps-search--mobile {
                position: fixed;
                top: 40px;
                width: 100%;
            }
            /* Default (topbar) mode — navigation bar stays sticky, search bar stays static */
            .header.header--sticky:not(.header--mobile-sticky-search) .navigation--mobile {
                position: fixed;
                top: {{ theme_option('header_mobile_sticky_top', 0) }}px;
                width: 100%;
            }
            .header.header--sticky:not(.header--mobile-sticky-search) .ps-search--mobile {
                position: static !important;
            }
            
            /* Fix text alignment for mobile cart items */
            .ps-product--cart-mobile .ps-product__content,
            .ps-product--cart-mobile .ps-product__title,
            .ps-product--cart-mobile .ps-product__content a,
            .ps-product--cart-mobile .ps-product__content small,
            .ps-product--cart-mobile .cart-price {
                text-align: left !important;
            }
            
            /* Fix text alignment for mobile cart footer (subtotal, tax, total) */
            .ps-cart__footer,
            .ps-cart__footer p,
            .ps-cart__footer h3,
            .ps-cart__footer .ps-block__total,
            .ps-cart__footer * {
                text-align: left !important;
            }
        }

    /* ── Language/Currency Flyout (matches cart flyout style exactly) ── */
    /* Language switcher in navigation extra menu */
    .navigation__extra > li.bb-locale-switcher {
        display: flex !important;
        align-items: center !important;
    }
    
    .bb-lang-trigger {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        background: none !important;
        border: none !important;
        cursor: pointer !important;
        color: var(--global-color-text-on-dark, #F4F9FD) !important;
        font-size: var(--header-menu-link-font-size) !important;
        font-weight: 400 !important;
        padding: 0 !important;
        line-height: 1.4 !important;
        transition: color 0.3s ease !important;
        vertical-align: middle !important;
        margin: 0 !important;
    }
    .bb-lang-trigger:hover {
        color: var(--global-color-warning, #FBB13C) !important;
    }
    .bb-lang-trigger img { 
        width: 18px !important; 
        height: 12px !important; 
        object-fit: cover !important; 
        border-radius: 2px !important;
        display: block !important;
        flex-shrink: 0 !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
    .bb-lang-iso { 
        font-size: inherit !important; 
        font-weight: inherit !important; 
        letter-spacing: 0.02em !important; 
        text-transform: uppercase !important;
        line-height: inherit !important;
        display: inline-block !important;
        vertical-align: baseline !important;
    }

    .bb-lang-flyout-wrap { 
        position: relative !important; 
        display: inline-flex !important;
        align-items: center !important;
        vertical-align: middle !important;
    }
    .bb-lang-flyout {
        position: absolute !important;
        top: calc(100% + 10px) !important;
        right: 0 !important;
        min-width: 245px !important;
        background: var(--global-color-site-bg, #F4F9FD) !important;
        border-radius: 0 !important;
        border: 1px solid var(--global-color-primary-border, #FFB399) !important;
        box-shadow: 0 5px 20px rgba(0,0,0,.1) !important;
        padding: 0 !important;
        z-index: 1003 !important;
        opacity: 0 !important;
        visibility: hidden !important;
        transform: translateY(6px) !important;
        transition: opacity .2s ease, transform .2s ease, visibility .2s ease !important;
    }
    /* Upward triangle arrow (cart flyout style) */
    .bb-lang-flyout::before {
        content: '' !important;
        position: absolute !important;
        top: -6px !important;
        right: var(--arrow-right, 16px) !important;
        left: var(--arrow-left, auto) !important;
        width: 0 !important;
        height: 0 !important;
        border-left: 6px solid transparent !important;
        border-right: 6px solid transparent !important;
        border-bottom: 6px solid var(--global-color-primary-border, #FFB399) !important;
    }
    .bb-lang-flyout::after {
        content: '' !important;
        position: absolute !important;
        top: -5px !important;
        right: var(--arrow-right, 17px) !important;
        left: var(--arrow-left, auto) !important;
        width: 0 !important;
        height: 0 !important;
        border-left: 5px solid transparent !important;
        border-right: 5px solid transparent !important;
        border-bottom: 5px solid var(--global-color-site-bg, #F4F9FD) !important;
    }
    .bb-locale-switcher:hover .bb-lang-flyout { opacity: 1 !important; visibility: visible !important; transform: translateY(0) !important; }

    /* Section title */
    .bb-lf-section-title {
        font-size: 11px !important;
        font-weight: 500 !important;
        color: var(--global-color-text-main, #0D1B2A) !important;
        text-transform: none !important;
        letter-spacing: .07em !important;
        padding: 10px 16px 6px !important;
        margin: 0 !important;
    }

    /* Language list */
    .bb-lf-list { list-style: none !important; margin: 0 !important; padding: 0 16px !important; }
    
    /* Language row base styles - use global text colors */
    .bb-lang-flyout .bb-lf-row,
    a.bb-lf-row {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 7px 0 !important;
        text-decoration: none !important;
        color: var(--global-color-text-body, #2E3D52) !important;
        font-size: 13px !important;
        line-height: 1.4 !important;
        cursor: pointer !important;
        background: none !important;
        transition: color .15s ease, text-decoration .15s ease !important;
    }
    
    /* Ensure text spans inherit the link color - force with specificity */
    .bb-lang-flyout .bb-lf-row .bb-lf-text,
    a.bb-lf-row .bb-lf-text {
        color: inherit !important;
    }
    
    /* Non-active items: default hover state - use primary color */
    .bb-lf-item:not(.bb-lf-item--active) .bb-lf-row:hover,
    .bb-lf-item:not(.bb-lf-item--active) a.bb-lf-row:hover {
        color: var(--global-color-primary, #FF642C) !important;
        text-decoration: underline !important;
        background: none !important;
    }
    
    /* Active/selected item: base state */
    .bb-lf-item--active .bb-lf-row,
    .bb-lf-item--active a.bb-lf-row {
        font-weight: 600 !important;
        color: var(--global-color-text-body, #2E3D52) !important;
    }
    
    /* Active/selected item: hover state - use primary color */
    .bb-lf-item--active .bb-lf-row:hover,
    .bb-lf-item--active a.bb-lf-row:hover {
        color: var(--global-color-primary, #FF642C) !important;
        text-decoration: underline !important;
    }

    /* Active item - removed border, now using separate divider element */
    .bb-lf-item--active { 
        margin-bottom: 0 !important; 
    }
    
    /* Language list divider between current and other languages */
    .bb-lf-divider {
        height: 1px !important;
        background: var(--global-color-primary-border, #FFB399) !important;
        margin: 8px 0 !important;
        list-style: none !important;
        border: none !important;
        padding: 0 !important;
    }

    /* Radio indicator base */
    .bb-lf-radio {
        flex-shrink: 0 !important;
        width: 14px !important;
        height: 14px !important;
        border: 2px solid #ccc !important;
        border-radius: 50% !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: border-color .15s ease !important;
    }
    
    /* Radio border on hover for non-active items - use primary color */
    .bb-lf-item:not(.bb-lf-item--active) .bb-lf-row:hover .bb-lf-radio {
        border-color: var(--global-color-primary, #FF642C) !important;
    }
    
    /* Active item radio border - use primary color */
    .bb-lf-item--active .bb-lf-radio { 
        border-color: var(--global-color-primary, #FF642C) !important; 
    }
    
    /* Radio dot base */
    .bb-lf-radio__dot {
        width: 6px !important;
        height: 6px !important;
        border-radius: 50% !important;
        background: transparent !important;
        transition: background .15s ease !important;
    }
    
    /* Show dot on hover for non-active items - use primary color */
    .bb-lf-item:not(.bb-lf-item--active) .bb-lf-row:hover .bb-lf-radio__dot {
        background: var(--global-color-primary, #FF642C) !important;
    }
    
    /* Active item dot - always visible with primary color */
    .bb-lf-item--active .bb-lf-radio__dot { 
        background: var(--global-color-primary, #FF642C) !important; 
    }

    /* Text and flag containers */
    .bb-lf-text { 
        flex: 1 !important; 
        min-width: 0 !important; 
    }
    .bb-lf-flag { 
        flex-shrink: 0 !important; 
        line-height: 0 !important; 
    }
    .bb-lf-flag img { 
        width: 20px !important; 
        height: 13px !important; 
        object-fit: cover !important; 
        border-radius: 2px !important; 
        display: block !important; 
    }

    /* Full-width divider with spacing */
    .bb-lf-section-divider { 
        height: 1px !important; 
        background: var(--global-color-primary-border, #FFB399) !important; 
        margin: 12px 0 !important; 
    }

    /* Currency section title */
    .bb-lf-section-divider + .bb-lf-section-title {
        padding-top: 0 !important;
    }

    /* Currency row */
    .bb-lf-currency-row {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 10px 16px !important;
        font-size: 13px !important;
        color: var(--global-color-text-body, #2E3D52) !important;
    }
    .bb-lf-curr-info { 
        flex: 1 !important;
        color: inherit !important;
    }
    
    /* Change link - use link colors */
    .bb-lang-flyout .bb-lf-currency-row .bb-lf-change-link,
    .bb-lang-flyout .bb-lf-currency-row a.bb-lf-change-link,
    .navigation__extra .bb-lang-flyout .bb-lf-change-link,
    .navigation__extra .bb-lang-flyout a.bb-lf-change-link,
    .bb-lf-change-link,
    a.bb-lf-change-link {
        font-size: 13px !important;
        font-weight: 400 !important;
        color: var(--global-color-link, #13acdf) !important;
        text-decoration: none !important;
        background: none !important;
        border: none !important;
        padding: 0 !important;
        cursor: pointer !important;
        white-space: nowrap !important;
        margin-left: 10px !important;
        transition: color .15s ease, text-decoration .15s ease !important;
    }
    
    .bb-lang-flyout .bb-lf-currency-row .bb-lf-change-link:hover,
    .bb-lang-flyout .bb-lf-currency-row a.bb-lf-change-link:hover,
    .navigation__extra .bb-lang-flyout .bb-lf-change-link:hover,
    .navigation__extra .bb-lang-flyout a.bb-lf-change-link:hover,
    .bb-lf-change-link:hover,
    a.bb-lf-change-link:hover { 
        text-decoration: underline !important; 
        color: var(--global-color-link-hover, #1dbff5) !important; 
    }

    @media (max-width: 767px) { .bb-locale-switcher { display: none !important; } }

    /* ── [locale-settings] Shortcode Panel ──────────────────────────── */
    /* COMPLETELY INDEPENDENT STYLES - NOT DEPENDENT ON FLYOUT */
    
    .bb-locale-settings {
        max-width: 600px;
        margin: 40px 70px;
        padding: 0 0 40px;
        text-align: left;
    }
    
    .bb-ls-section {
        margin-bottom: 40px;
    }
    
    .bb-ls-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--global-color-text-main, #0D1B2A);
    }
    
    .bb-ls-subtitle {
        font-size: 14px;
        color: var(--global-color-text-body, #2E3D52);
        margin-bottom: 24px;
        line-height: 1.5;
    }
    
    /* Language wrapper */
    .bb-ls-lang-wrapper {
        background: transparent;
        border: none;
        padding: 0;
    }
    
    /* Language list */
    .bb-ls-lang-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    /* Language item */
    .bb-ls-lang-item {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    
    /* Language link */
    .bb-ls-lang-link {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 8px;
        padding: 7px 0;
        text-decoration: none;
        color: var(--global-color-text-body, #2E3D52);
        font-size: 13px;
        line-height: 1.4;
        cursor: pointer;
        background: none;
        transition: color .15s ease, text-decoration .15s ease;
    }
    
    .bb-ls-lang-link:hover {
        color: var(--global-color-primary, #FF642C);
        text-decoration: underline;
    }
    
    .bb-ls-lang-item--active .bb-ls-lang-link {
        font-weight: 600;
    }
    
    /* Radio button */
    .bb-ls-radio {
        flex-shrink: 0;
        width: 14px;
        height: 14px;
        border: 2px solid #ccc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color .15s ease;
    }
    
    .bb-ls-lang-link:hover .bb-ls-radio {
        border-color: var(--global-color-primary, #FF642C);
    }
    
    .bb-ls-lang-item--active .bb-ls-radio {
        border-color: var(--global-color-primary, #FF642C);
    }
    
    .bb-ls-radio-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: transparent;
        transition: background .15s ease;
    }
    
    .bb-ls-lang-link:hover .bb-ls-radio-dot {
        background: var(--global-color-primary, #FF642C);
    }
    
    .bb-ls-lang-item--active .bb-ls-radio-dot {
        background: var(--global-color-primary, #FF642C);
    }
    
    /* Language name */
    .bb-ls-lang-name {
        flex: 1;
        min-width: 0;
        color: inherit;
    }
    
    /* Flag container */
    .bb-ls-flag {
        flex-shrink: 0;
        line-height: 0;
        display: inline-flex;
        align-items: center;
        justify-content: flex-start;
        width: 23px;
        height: 16px;
    }
    
    .bb-ls-flag svg,
    .bb-ls-flag img {
        display: block !important;
        border-radius: 2px;
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    .bb-ls-flag img {
        width: 23px !important;
        height: 16px !important;
        min-width: 23px !important;
        min-height: 16px !important;
        max-width: 23px !important;
        max-height: 16px !important;
        object-fit: contain !important;
        object-position: center !important;
    }
    
    /* Ensure SVG paths keep their colors */
    .bb-ls-flag svg path,
    .bb-ls-flag svg rect,
    .bb-ls-flag svg g,
    .bb-ls-flag svg * {
        opacity: 1 !important;
        visibility: visible !important;
    }
    
    /* Divider between current and other languages */
    .bb-ls-divider-item {
        height: 1px;
        background: var(--global-color-primary-border, #FFB399);
        margin: 8px 0;
        list-style: none;
        border: none;
        padding: 0;
    }
    
    /* Divider between current and other languages */
    .bb-ls-divider-item {
        height: 1px;
        background: var(--global-color-primary-border, #FFB399);
        margin: 8px 0;
        list-style: none;
        border: none;
        padding: 0;
    }
    
    /* Section divider */
    .bb-ls-section-divider {
        height: 1px;
        background: var(--global-color-primary-border, #FFB399);
        margin: 0 0 24px 0;
    }
    
    /* Currency section */
    .bb-ls-currency-section {
        padding: 0;
        margin-bottom: 0;
    }
    
    .bb-ls-currency-title {
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 6px;
        color: var(--global-color-text-main, #0D1B2A);
    }
    
    .bb-ls-currency-subtitle {
        font-size: 14px;
        color: var(--global-color-text-body, #2E3D52);
        margin-bottom: 24px;
        line-height: 1.5;
    }
    
    /* Currency wrapper */
    .bb-ls-currency-wrap {
        padding: 0;
    }
    
    .bb-ls-currency-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--global-color-primary-border, #FFB399);
        border-radius: 4px;
        font-size: 13px;
        color: var(--global-color-text-body, #2E3D52);
        background: transparent;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%232E3D52'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 30px;
        cursor: pointer;
    }
    
    .bb-ls-currency-select:focus {
        outline: 2px solid var(--global-color-text-body, #2E3D52);
        outline-offset: 0;
    }

    /* Main divider before buttons */
    .bb-ls-main-divider {
        height: 1px;
        background: var(--global-color-primary-border, #FFB399);
        margin: 40px 0 24px 0;
    }

    /* Action buttons */
    .bb-ls-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 4px;
    }
    
    .bb-ls-btn {
        padding: 10px 28px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        border: 2px solid transparent;
        transition: background .15s, color .15s, border-color .15s;
    }
    
    .bb-ls-btn--cancel {
        background: var(--global-color-card-bg, #FFFFFF);
        color: var(--global-color-text-body, #2E3D52);
        border-color: var(--global-color-primary-border, #FFB399);
    }
    
    .bb-ls-btn--cancel:hover {
        border-color: var(--global-color-primary, #FF642C);
        color: var(--global-color-primary, #FF642C);
    }
    
    .bb-ls-btn--save {
        background: var(--global-color-warning, #FBB13C);
        color: var(--global-color-text-main, #0D1B2A);
        border-color: var(--global-color-warning, #FBB13C);
    }
    
    .bb-ls-btn--save:hover {
        background: var(--global-color-primary, #FF642C);
        color: var(--global-color-text-on-dark, #F4F9FD);
        border-color: var(--global-color-primary, #FF642C);
    }
    .bb-ls-btn--save {
        background: var(--global-color-warning, #FBB13C);
        color: var(--global-color-text-main, #0D1B2A);
        border-color: var(--global-color-warning, #FBB13C);
    }
    .bb-ls-btn--save:hover {
        background: var(--global-color-primary, #FF642C);
        border-color: var(--global-color-primary, #FF642C);
    }
    
    /* ═══════════════════════════════════════════════════════════════════
       CUSTOM PRELOADER - Circular Logo with Spinning Ring
       ═══════════════════════════════════════════════════════════════════ */
    #loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--global-color-site-bg, #F4F9FD);
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        visibility: visible;
        transition: opacity 0.5s ease, visibility 0.5s ease;
    }
    
    #loader-wrapper.loaded {
        opacity: 0;
        visibility: hidden;
    }
    
    /* Circular container with primary background - SMALLER SIZE */
    .preloader-circle {
        position: relative;
        width: 90px;
        height: 90px;
        background: var(--global-color-primary, #FF642C);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: none;
    }
    
    /* SVG spinner container - INSIDE the circle, same size */
    .preloader-spinner {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        animation: preloader-spin 1s linear infinite;
    }
    
    /* Spinning white arc - sharp pointed ends, no background ring */
    .preloader-spinner-path {
        stroke: #ffffff;
        stroke-linecap: butt; /* Sharp edges instead of round */
        stroke-dasharray: 80 200; /* Short arc (80px visible, 200px gap) - no full ring */
        transform-origin: center;
    }
    
    /* Logo inside the circle - centered with equal spacing */
    .preloader-logo {
        width: 50px;
        height: 50px;
        object-fit: contain;
        position: relative;
        z-index: 2;
    }
    
    /* Continuous smooth spinning animation */
    @keyframes preloader-spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 767px) {
        .preloader-circle {
            width: 75px;
            height: 75px;
        }
        
        .preloader-logo {
            width: 42px;
            height: 42px;
        }
    }
    </style>

    @php
        Theme::asset()->remove('language-css');
        Theme::asset()->container('footer')->remove('language-public-js');
        Theme::asset()->container('footer')->remove('simple-slider-owl-carousel-css');
        Theme::asset()->container('footer')->remove('simple-slider-owl-carousel-js');
        Theme::asset()->container('footer')->remove('simple-slider-css');
        Theme::asset()->container('footer')->remove('simple-slider-js');
    @endphp

    {!! Theme::header() !!}
<script>
// ═══════════════════════════════════════════════════════════════════
// Preloader - Hide when page fully loads
// ═══════════════════════════════════════════════════════════════════
window.addEventListener('load', function() {
    var loaderWrapper = document.getElementById('loader-wrapper');
    if (loaderWrapper) {
        loaderWrapper.classList.add('loaded');
        // Remove from DOM after fade out animation completes
        setTimeout(function() {
            loaderWrapper.style.display = 'none';
        }, 500);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // ── FORCE LOCK BOTTOM BAR POSITION (prevent any JS from moving it) ─────
    var bottomBar = document.querySelector('.navigation--mobile-product');
    if (bottomBar) {
        // Calculate the actual bottom position once
        var calculateBottom = function() {
            // Always stick to viewport bottom, not document bottom
            return 0;
        };
        
        // Force remove any transforms or animations
        var lockPosition = function() {
            var bottomValue = calculateBottom();
            bottomBar.style.setProperty('position', 'fixed', 'important');
            bottomBar.style.setProperty('bottom', bottomValue + 'px', 'important');
            bottomBar.style.setProperty('left', '0', 'important');
            bottomBar.style.setProperty('right', '0', 'important');
            bottomBar.style.setProperty('transform', 'translate3d(0, 0, 0)', 'important');
            bottomBar.style.setProperty('-webkit-transform', 'translate3d(0, 0, 0)', 'important');
            bottomBar.style.setProperty('transition', 'none', 'important');
            bottomBar.style.setProperty('-webkit-transition', 'none', 'important');
        };
        
        // Lock immediately
        lockPosition();
        
        // Re-lock on scroll (mobile address bar hide/show)
        var scrollLockTimeout;
        var lastScrollY = window.scrollY;
        window.addEventListener('scroll', function() {
            // Only re-lock if scroll position actually changed
            if (Math.abs(window.scrollY - lastScrollY) > 5) {
                lastScrollY = window.scrollY;
                lockPosition();
            }
            
            if (scrollLockTimeout) clearTimeout(scrollLockTimeout);
            scrollLockTimeout = setTimeout(lockPosition, 50);
        }, { passive: true });
        
        // Re-lock on resize (orientation change, keyboard open/close)
        var resizeLockTimeout;
        window.addEventListener('resize', function() {
            if (resizeLockTimeout) clearTimeout(resizeLockTimeout);
            resizeLockTimeout = setTimeout(lockPosition, 50);
        }, { passive: true });
        
        // Watch for any attribute/style changes and force lock
        new MutationObserver(function() {
            lockPosition();
        }).observe(bottomBar, {
            attributes: true,
            attributeFilter: ['style', 'class']
        });
        
        // Force lock on page visibility change (tab switch)
        document.addEventListener('visibilitychange', lockPosition);
    }
    
    // ── Bottom bar toggle swap (Search / Cart / Categories) ─────────────
    var toggles = document.querySelectorAll('.navigation__item.bb-nav-toggle');

    function resetToggle(el) {
        var icon = el.querySelector('i');
        var text = el.querySelector('span');
        var openIcon = el.getAttribute('data-open-icon');
        var openText = el.getAttribute('data-open-text');
        if (icon && openIcon) icon.className = openIcon;
        if (text && openText) text.textContent = ' ' + openText;
    }

    function activateToggle(el) {
        var icon = el.querySelector('i');
        var text = el.querySelector('span');
        var closeText = el.getAttribute('data-close-text');
        if (icon) icon.className = 'icon-cross';
        if (text && closeText) text.textContent = ' ' + closeText;
    }
    
    // ── Smart navigation: Logo/Home/Back to top ─────────────────────────
    var smartNav = document.getElementById('bb-smart-nav');
    if (smartNav) {
        var isHomePage = smartNav.getAttribute('data-home-page') === 'true';
        var scrollThreshold = 500;
        
        if (isHomePage) {
            // On homepage: show logo initially, switch to arrow when scrolled
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > scrollThreshold) {
                    if (!smartNav.classList.contains('show-arrow')) {
                        smartNav.classList.add('show-arrow');
                        smartNav.href = 'javascript:void(0)';
                        // Add fade-out animation to logo, then replace with arrow
                        var logoWrap = smartNav.querySelector('.bb-logo-wrap');
                        if (logoWrap) {
                            var logoImg = logoWrap.querySelector('.bb-logo-img');
                            // Get the text-on-dark color from CSS variable
                            var textOnDarkColor = getComputedStyle(document.documentElement)
                                .getPropertyValue('--global-color-text-on-dark').trim() || '#F4F9FD';
                            
                            if (logoImg) {
                                logoImg.classList.add('fade-out');
                                setTimeout(function() {
                                    var arrowIcon = document.createElement('i');
                                    arrowIcon.className = 'icon-arrow-up';
                                    arrowIcon.style.color = textOnDarkColor;
                                    logoWrap.innerHTML = '';
                                    logoWrap.appendChild(arrowIcon);
                                }, 300); // Wait for fade-out animation
                            } else {
                                // No logo image, replace immediately
                                var arrowIcon = document.createElement('i');
                                arrowIcon.className = 'icon-arrow-up';
                                arrowIcon.style.color = textOnDarkColor;
                                logoWrap.innerHTML = '';
                                logoWrap.appendChild(arrowIcon);
                            }
                        }
                    }
                } else {
                    if (smartNav.classList.contains('show-arrow')) {
                        smartNav.classList.remove('show-arrow');
                        // Update href to homepage
                        smartNav.href = smartNav.getAttribute('data-homepage-url') || '/';
                        // Restore logo without reloading
                        var logoWrap = smartNav.querySelector('.bb-logo-wrap');
                        if (logoWrap) {
                            // Restore logo image
                            var logoImg = smartNav.getAttribute('data-logo-img');
                            if (logoImg) {
                                logoWrap.innerHTML = '<img src="' + logoImg + '" alt="Logo" class="bb-logo-img" />';
                            } else {
                                logoWrap.innerHTML = '<i class="icon-home"></i>';
                            }
                        }
                    }
                }
            });
            
            smartNav.addEventListener('click', function(e) {
                e.preventDefault();
                if (smartNav.classList.contains('show-arrow')) {
                    // Scroll to top
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    // Hide existing back to top button
                    var backToTop = document.querySelector('.ps-back-top');
                    if (backToTop) backToTop.style.display = 'none';
                } else {
                    // Go to homepage
                    window.location.href = smartNav.getAttribute('data-homepage-url') || '/';
                }
            });
            
            // Hide the default back-to-top button on mobile
            var backToTop = document.querySelector('.ps-back-top');
            if (backToTop && window.innerWidth < 768) {
                backToTop.style.display = 'none';
            }
        }
    }

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            var self = this;
            var isCurrentlyOpen = self.classList.contains('active');

            // Reset all other toggles first
            toggles.forEach(function (other) {
                if (other !== self) resetToggle(other);
            });

            // After theme JS runs toggleClass('active'), state will flip.
            // isCurrentlyOpen = true means it's about to close → reset.
            // isCurrentlyOpen = false means it's about to open → activate.
            if (isCurrentlyOpen) {
                resetToggle(self);
            } else {
                activateToggle(self);
            }
        });
    });

    // ── Reset bottom bar toggles when hamburger menu is opened ─────────────
    var hamburgerMenu = document.querySelector('.ps-toggle--sidebar[href="#menu-mobile"]');
    if (hamburgerMenu) {
        hamburgerMenu.addEventListener('click', function() {
            // Find and click any active bottom bar toggle to properly close it
            toggles.forEach(function(toggle) {
                if (toggle.classList.contains('active')) {
                    // Trigger click to properly close the sidebar through the theme's mechanism
                    toggle.click();
                }
            });
        });
    }

    // ── Content-area overlay: clips to between header and footer ─────────
    (function () {
        var overlay   = document.getElementById('bb-content-overlay');
        var header    = document.querySelector('header.header:not(.header--mobile)') || document.querySelector('.header:not(.header--mobile)');
        var bottomBar = document.querySelector('.navigation--list');

        if (!overlay) return;

        // Measure and set CSS vars for top/bottom offsets
        function updateBounds() {
            var h = header    ? header.getBoundingClientRect().bottom : 0;
            var b = bottomBar ? bottomBar.getBoundingClientRect().height : 0;
            document.documentElement.style.setProperty('--bb-header-h', Math.max(0, h) + 'px');
            document.documentElement.style.setProperty('--bb-bottombar-h', b + 'px');
        }
        updateBounds();
        window.addEventListener('resize', updateBounds);
        if (header) new MutationObserver(updateBounds).observe(header, { attributes: true, subtree: false });

        // State management: track which flyouts are currently open
        var openFlyouts = new Set();

        function showOverlay(source) {
            openFlyouts.add(source);
            overlay.style.opacity = '1';
            overlay.style.visibility = 'visible';
        }

        function hideOverlay(source) {
            openFlyouts.delete(source);
            // Only hide if no other flyouts are open
            if (openFlyouts.size === 0) {
                overlay.style.opacity = '0';
                overlay.style.visibility = 'hidden';
            }
        }

        // Elements that trigger desktop flyouts on hover
        var flyoutTriggers = [
            '.ps-cart--mini',
            '.menu--product-categories',
            '.header .menu > li.has-mega-menu',
            '.header .menu > li.menu-item-has-children',
            '.header .navigation__extra .ps-dropdown',
            '.navigation__extra .bb-locale-switcher',
        ];

        flyoutTriggers.forEach(function (sel) {
            document.querySelectorAll(sel).forEach(function (el) {
                var flyoutId = sel + '-' + Array.from(document.querySelectorAll(sel)).indexOf(el);
                el.addEventListener('mouseenter', function () { showOverlay(flyoutId); });
                el.addEventListener('mouseleave', function () { hideOverlay(flyoutId); });
            });
        });

        // Search results overlay - watch for results appearing/disappearing
        var searchResultPanels = document.querySelectorAll('.ps-panel--search-result');
        searchResultPanels.forEach(function (panel, index) {
            var searchForm = panel.closest('form');
            var searchId = 'search-' + index;
            
            var observer = new MutationObserver(function () {
                // Check if panel has active class AND content
                if (panel.classList.contains('active') && panel.textContent.trim() !== '') {
                    showOverlay(searchId);
                    if (searchForm) searchForm.classList.add('has-results');
                } else {
                    hideOverlay(searchId);
                    if (searchForm) searchForm.classList.remove('has-results');
                }
            });
            observer.observe(panel, { childList: true, subtree: true, attributes: true, attributeFilter: ['class'] });
        });

        // Also handle search input focus - show overlay if results exist
        var searchInputs = document.querySelectorAll('.input-search-product');
        searchInputs.forEach(function (input, index) {
            var searchId = 'search-' + index;
            
            // When input is focused and has value, trigger search to show results
            input.addEventListener('focus', function () {
                var panel = input.closest('form').querySelector('.ps-panel--search-result');
                
                // If panel has content and active class, show overlay
                if (panel && panel.classList.contains('active') && panel.textContent.trim() !== '') {
                    showOverlay(searchId);
                    var form = input.closest('form');
                    if (form) form.classList.add('has-results');
                }
            });
        });
    })();

    // ── Sidebar submenu slide panels ─────────────────────────────────────
    (function () {
        var panelsCreated = false;
        var navigationPanelsCreated = false;
        
        function initSubmenuPanels(sidebarId) {
            // Target the specific sidebar
            var sidebar = document.getElementById(sidebarId);
            if (!sidebar) {
                return false; // Return false to keep trying
            }
            
            // Check if panels already created for this sidebar
            var isPanelsCreated = sidebarId === 'menu-mobile' ? panelsCreated : navigationPanelsCreated;
            if (isPanelsCreated) {
                return true; // Return true to stop trying
            }
            
            var sidebarContent = sidebar.querySelector('.ps-panel__content');
            if (!sidebarContent) {
                return false; // Keep trying
            }
            
            // Find all menu items with children BEFORE we modify the DOM
            var menuItems = sidebar.querySelectorAll('.menu--mobile > li.menu-item-has-children');
            
            if (menuItems.length === 0) {
                return;
            }
            
            menuItems.forEach(function (menuItem, index) {
                // Get direct children only using querySelectorAll with :scope
                var links = menuItem.querySelectorAll(':scope > a');
                var link = links.length > 0 ? links[0] : null;
                
                // Check for both .sub-menu (menu) and .mega-menu (categories)
                var submenus = menuItem.querySelectorAll(':scope > .sub-menu');
                var submenu = submenus.length > 0 ? submenus[0] : null;
                
                if (!submenu) {
                    // Try .mega-menu for categories
                    var megaMenus = menuItem.querySelectorAll(':scope > .mega-menu');
                    submenu = megaMenus.length > 0 ? megaMenus[0] : null;
                }
                
                if (!link || !submenu) {
                    return;
                }
                
                // Get menu title and href
                var menuTitle = link.textContent.trim();
                var linkHref = link.getAttribute('href');
                var hasRealLink = linkHref && linkHref !== '#' && linkHref !== '';
                
                // Check if this is the language switcher menu - multiple detection methods
                // Method 1: Check data attributes
                var isLanguageSwitcher = menuItem.hasAttribute('data-language-switcher') || 
                                        menuItem.classList.contains('menu-item-language-switcher') ||
                                        submenu.hasAttribute('data-current-locale') ||
                                        (submenu.querySelector('li[data-locale-code]') !== null);
                
                // Method 2: Check if submenu items have language flags (SVG masks with lang codes)
                if (!isLanguageSwitcher) {
                    var firstSubmenuLink = submenu.querySelector('li a');
                    if (firstSubmenuLink) {
                        var hasLangFlag = firstSubmenuLink.querySelector('img[alt*="flag"]') !== null ||
                                         firstSubmenuLink.querySelector('svg') !== null ||
                                         firstSubmenuLink.textContent.match(/English|Español|Français|Deutsch|Italiano|Português|العربية|বাংলা|Tiếng Việt|Bahasa Indonesia|Türkçe/i);
                        if (hasLangFlag) {
                            isLanguageSwitcher = true;
                        }
                    }
                }
                
                // Make the li position relative for absolute positioning of chevron
                menuItem.style.position = 'relative';
                
                // Create chevron button
                var chevronBtn = document.createElement('span');
                chevronBtn.className = 'submenu-chevron';
                chevronBtn.innerHTML = '<i class="icon-chevron-right"></i>';
                chevronBtn.setAttribute('aria-label', 'Open ' + menuTitle + ' submenu');
                
                // Insert chevron after the link
                link.parentNode.insertBefore(chevronBtn, link.nextSibling);
                
                // Create submenu panel
                var panel = document.createElement('div');
                panel.className = 'ps-submenu-panel';
                panel.setAttribute('data-parent', menuTitle);
                
                // Create panel header
                var panelHeader = document.createElement('div');
                panelHeader.className = 'ps-submenu-panel__header';
                
                var backButton = document.createElement('button');
                backButton.className = 'ps-submenu-panel__back';
                backButton.type = 'button';
                backButton.innerHTML = '<i class="icon-arrow-left"></i><span>Main Menu</span>';
                
                var divider = document.createElement('div');
                divider.className = 'ps-submenu-panel__divider';
                
                panelHeader.appendChild(backButton);
                panelHeader.appendChild(divider);
                
                var titleElement = document.createElement('div');
                titleElement.className = 'ps-submenu-panel__title';
                
                // For language switcher, hide the title (current language already shown in main menu)
                if (isLanguageSwitcher) {
                    titleElement.style.display = 'none';
                } else {
                    titleElement.textContent = menuTitle;
                }
                
                // Create items list or language switcher UI
                var itemsList;
                
                if (isLanguageSwitcher) {
                    // Create language switcher layout (desktop flyout style)
                    itemsList = document.createElement('div');
                    itemsList.className = 'ps-submenu-panel__lang-switcher';
                    
                    // Section title
                    var sectionTitle = document.createElement('p');
                    sectionTitle.className = 'bb-lf-section-title';
                    sectionTitle.textContent = 'Change language';
                    itemsList.appendChild(sectionTitle);
                    
                    // Language list
                    var langList = document.createElement('ul');
                    langList.className = 'bb-lf-list';
                    
                    // Get current locale data from submenu data attributes
                    var currentLocale = submenu.getAttribute('data-current-locale');
                    var currentLocaleName = submenu.getAttribute('data-current-locale-name');
                    var currentLocaleFlag = submenu.getAttribute('data-current-locale-flag');
                    
                    // Add current language first (active/selected)
                    if (currentLocale && currentLocaleName) {
                        var currentItem = document.createElement('li');
                        currentItem.className = 'bb-lf-item bb-lf-item--active';
                        
                        var currentRow = document.createElement('a');
                        currentRow.href = '#';
                        currentRow.className = 'bb-lf-row bb-lf-row--current';
                        currentRow.addEventListener('click', function(e) { e.preventDefault(); });
                        
                        // Radio button (filled)
                        var radio = document.createElement('span');
                        radio.className = 'bb-lf-radio bb-lf-radio--checked';
                        radio.innerHTML = '<span class="bb-lf-radio__dot"></span>';
                        
                        // Text
                        var text = document.createElement('span');
                        text.className = 'bb-lf-text';
                        text.textContent = currentLocaleName + ' – ' + currentLocale.toUpperCase();
                        
                        currentRow.appendChild(radio);
                        currentRow.appendChild(text);
                        
                        // Flag - get from main menu link
                        var mainMenuFlag = link.querySelector('img, svg');
                        if (mainMenuFlag) {
                            var flagSpan = document.createElement('span');
                            flagSpan.className = 'bb-lf-flag';
                            flagSpan.appendChild(mainMenuFlag.cloneNode(true));
                            currentRow.appendChild(flagSpan);
                        }
                        
                        currentItem.appendChild(currentRow);
                        langList.appendChild(currentItem);
                        
                        // Add divider after current language
                        var divider = document.createElement('li');
                        divider.className = 'bb-lf-divider';
                        langList.appendChild(divider);
                    }
                    
                    // Get all other language items
                    var langItems = submenu.querySelectorAll('li[data-locale-code]');
                    
                    langItems.forEach(function(langItem) {
                        var localeCode = langItem.getAttribute('data-locale-code');
                        var localeName = langItem.getAttribute('data-locale-name');
                        var localeFlag = langItem.getAttribute('data-locale-flag');
                        var langLink = langItem.querySelector('a');
                        
                        if (!langLink) return;
                        
                        var item = document.createElement('li');
                        item.className = 'bb-lf-item';
                        
                        var row = document.createElement('a');
                        row.href = langLink.href;
                        row.className = 'bb-lf-row';
                        
                        // Radio button (empty)
                        var radio = document.createElement('span');
                        radio.className = 'bb-lf-radio';
                        radio.innerHTML = '<span class="bb-lf-radio__dot"></span>';
                        
                        // Text
                        var text = document.createElement('span');
                        text.className = 'bb-lf-text';
                        text.textContent = localeName + ' – ' + localeCode.toUpperCase();
                        
                        row.appendChild(radio);
                        row.appendChild(text);
                        
                        // Flag - get the actual flag HTML from the link
                        var flagElement = langLink.querySelector('img, svg');
                        if (flagElement) {
                            var flagSpan = document.createElement('span');
                            flagSpan.className = 'bb-lf-flag';
                            flagSpan.appendChild(flagElement.cloneNode(true));
                            row.appendChild(flagSpan);
                        }
                        
                        item.appendChild(row);
                        langList.appendChild(item);
                    });
                    
                    itemsList.appendChild(langList);
                    
                    // Add Currency Section - Get actual currency from desktop flyout
                    var desktopFlyout = document.querySelector('.bb-lang-flyout .bb-lf-currency-row');
                    var currencyInfo = null;
                    var currencyChangeUrl = null;
                    
                    if (desktopFlyout) {
                        var currInfoSpan = desktopFlyout.querySelector('.bb-lf-curr-info');
                        var currLink = desktopFlyout.querySelector('.bb-lf-change-link');
                        
                        if (currInfoSpan) {
                            currencyInfo = currInfoSpan.textContent.trim();
                        }
                        if (currLink) {
                            currencyChangeUrl = currLink.href;
                        }
                    }
                    
                    // Always show currency section if we have data or ecommerce indicators
                    if (currencyInfo || document.querySelector('.ps-cart--mini')) {
                        // Add section divider
                        var sectionDivider = document.createElement('div');
                        sectionDivider.className = 'bb-lf-section-divider';
                        itemsList.appendChild(sectionDivider);
                        
                        // Currency section title
                        var currencyTitle = document.createElement('p');
                        currencyTitle.className = 'bb-lf-section-title';
                        currencyTitle.textContent = 'Change currency';
                        itemsList.appendChild(currencyTitle);
                        
                        // Currency row
                        var currencyRow = document.createElement('div');
                        currencyRow.className = 'bb-lf-currency-row';
                        
                        var currInfo = document.createElement('span');
                        currInfo.className = 'bb-lf-curr-info';
                        currInfo.textContent = currencyInfo || '$ – USD – US Dollar';
                        
                        var changeLink = document.createElement('a');
                        changeLink.className = 'bb-lf-change-link';
                        changeLink.textContent = 'Change';
                        
                        // Use the URL from desktop flyout or build localized URL
                        if (currencyChangeUrl) {
                            changeLink.href = currencyChangeUrl;
                        } else {
                            var currentLocaleCode = currentLocale || 'en';
                            var baseUrl = window.location.origin;
                            var localizedPath = currentLocaleCode !== 'en' ? '/' + currentLocaleCode : '';
                            changeLink.href = baseUrl + localizedPath + '/customer-experience';
                        }
                        
                        currencyRow.appendChild(currInfo);
                        currencyRow.appendChild(changeLink);
                        itemsList.appendChild(currencyRow);
                    }
                } else {
                    // Regular submenu items
                    itemsList = document.createElement('ul');
                    itemsList.className = 'ps-submenu-panel__items';
                    
                    // Clone submenu items
                    var submenuItems = submenu.querySelectorAll('li');
                    
                    submenuItems.forEach(function (item) {
                        var clonedItem = item.cloneNode(true);
                        // Remove any nested sub-toggles and sub-menus from cloned items
                        var nestedToggle = clonedItem.querySelector('.sub-toggle');
                        var nestedSubmenu = clonedItem.querySelector('.sub-menu');
                        var nestedChevron = clonedItem.querySelector('.submenu-chevron');
                        if (nestedToggle) nestedToggle.remove();
                        if (nestedSubmenu) nestedSubmenu.remove();
                        if (nestedChevron) nestedChevron.remove();
                        itemsList.appendChild(clonedItem);
                    });
                }
                
                panel.appendChild(panelHeader);
                panel.appendChild(titleElement);
                panel.appendChild(itemsList);
                
                // Add panel directly to sidebar content
                sidebarContent.appendChild(panel);
                
                // Function to open panel
                function openPanel(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    panel.classList.add('active');
                    panel.scrollTop = 0;
                    
                    // Add class to parent content to hide its scrollbar
                    sidebarContent.classList.add('submenu-open');
                }
                
                // Handle chevron click - always opens submenu
                chevronBtn.addEventListener('click', openPanel);
                
                // Handle link click
                if (hasRealLink) {
                    // If has real link, link works normally, only chevron opens submenu
                } else {
                    // If no real link (href="#"), clicking anywhere opens submenu
                    link.addEventListener('click', openPanel);
                    link.style.cursor = 'pointer';
                }
                
                // Handle back button click
                backButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    panel.classList.remove('active');
                    
                    // Remove class from parent content to restore its scrollbar
                    sidebarContent.classList.remove('submenu-open');
                });
            });
            
            // Set the correct flag based on sidebar
            if (sidebarId === 'menu-mobile') {
                panelsCreated = true;
                
                // Add close icon ONLY to menu-mobile sidebar
                var menuHeader = sidebar.querySelector('.ps-panel__header');
                if (menuHeader && !menuHeader.querySelector('.ps-sidebar-close-btn')) {
                    var closeBtn = document.createElement('button');
                    closeBtn.className = 'ps-sidebar-close-btn';
                    closeBtn.setAttribute('type', 'button');
                    closeBtn.setAttribute('aria-label', 'Close Menu');
                    closeBtn.innerHTML = '<span></span><span></span>'; // Two spans for X icon
                    
                    // Add click handler to close the sidebar
                    closeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Find and trigger the actual sidebar toggle
                        var toggleBtn = document.querySelector('.ps-toggle--sidebar[href="#menu-mobile"]');
                        if (toggleBtn) {
                            toggleBtn.click();
                        } else {
                            // Fallback: hide the sidebar directly
                            sidebar.style.display = 'none';
                        }
                    });
                    
                    menuHeader.appendChild(closeBtn);
                }
            } else if (sidebarId === 'navigation-mobile') {
                navigationPanelsCreated = true;
            }
            return true; // Stop trying
        }
        
        // Try multiple times since sidebar loads dynamically
        var attempts = 0;
        var maxAttempts = 20;
        var checkInterval = setInterval(function() {
            attempts++;
            var menuSuccess = initSubmenuPanels('menu-mobile');
            var navSuccess = initSubmenuPanels('navigation-mobile');
            if ((menuSuccess && navSuccess) || attempts >= maxAttempts) {
                clearInterval(checkInterval);
            }
        }, 500);
        
        // Also watch for sidebar panel opening (when display changes from none to block)
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    var menuSidebar = document.getElementById('menu-mobile');
                    if (menuSidebar && window.getComputedStyle(menuSidebar).display !== 'none') {
                        setTimeout(function() { initSubmenuPanels('menu-mobile'); }, 100);
                    }
                    
                    var navSidebar = document.getElementById('navigation-mobile');
                    if (navSidebar && window.getComputedStyle(navSidebar).display !== 'none') {
                        setTimeout(function() { initSubmenuPanels('navigation-mobile'); }, 100);
                    }
                }
            });
        });
        
        // Watch both sidebar elements for style changes
        var menuMobile = document.getElementById('menu-mobile');
        if (menuMobile) {
            observer.observe(menuMobile, { attributes: true, attributeFilter: ['style', 'class'] });
        }
        
        var navigationMobile = document.getElementById('navigation-mobile');
        if (navigationMobile) {
            observer.observe(navigationMobile, { attributes: true, attributeFilter: ['style', 'class'] });
        }
    })();

    // ── Language Flyout Positioning (Keep Inside Viewport) ───────────────
    (function () {
        function positionFlyout(trigger) {
            var flyout = trigger.querySelector('.bb-lang-flyout');
            if (!flyout) return;
            
            // Reset positioning
            flyout.style.setProperty('left', '', 'important');
            flyout.style.setProperty('right', '', 'important');
            flyout.style.removeProperty('transform');
            flyout.style.removeProperty('margin-left');
            
            // Wait a tick for flyout to render with default positioning
            requestAnimationFrame(function() {
                // Get positions
                var triggerRect = trigger.getBoundingClientRect();
                var flyoutRect = flyout.getBoundingClientRect();
                var viewportWidth = window.innerWidth;
                
                // Calculate if current positioning goes off viewport
                var isOffLeft = flyoutRect.left < 10;
                var isOffRight = flyoutRect.right > (viewportWidth - 10);
                
                if (isOffLeft && !isOffRight) {
                    // Flyout is going off left, position it 10px from viewport left edge
                    var offsetFromViewport = 10 - triggerRect.left; // Calculate distance needed to be 10px from viewport left
                    flyout.style.setProperty('right', 'auto', 'important');
                    flyout.style.setProperty('left', offsetFromViewport + 'px', 'important');
                    
                    // Update arrow to point back to center of trigger (now it's offset)
                    var arrowLeft = triggerRect.left + (triggerRect.width / 2) - 10 - 6; // trigger center from viewport left - flyout left - half arrow width
                    flyout.style.setProperty('--arrow-right', 'auto');
                    flyout.style.setProperty('--arrow-left', arrowLeft + 'px');
                    
                    // Verify the fix worked
                    requestAnimationFrame(function() {
                        var newRect = flyout.getBoundingClientRect();
                        });
                    
                } else if (isOffRight && !isOffLeft) {
                    // Flyout is going off right, keep right-aligned (default)
                    flyout.style.setProperty('right', '0', 'important');
                    flyout.style.setProperty('left', 'auto', 'important');
                    
                    flyout.style.setProperty('--arrow-right', '16px');
                    flyout.style.setProperty('--arrow-left', 'auto');
                    
                } else if (isOffLeft && isOffRight) {
                    // Flyout is too wide, position it to fit viewport
                    var spaceNeeded = flyoutRect.width + 20; // 10px buffer each side
                    if (spaceNeeded > viewportWidth) {
                        // Even full width won't fit, align to left edge of viewport
                        flyout.style.setProperty('left', (10 - triggerRect.left) + 'px', 'important');
                        flyout.style.setProperty('right', 'auto', 'important');
                    } else {
                        // Center in viewport
                        var centerPosition = (viewportWidth - flyoutRect.width) / 2 - triggerRect.left;
                        flyout.style.setProperty('left', centerPosition + 'px', 'important');
                        flyout.style.setProperty('right', 'auto', 'important');
                    }
                } else {
                    // Default - fits fine
                    flyout.style.setProperty('--arrow-right', '16px');
                    flyout.style.setProperty('--arrow-left', 'auto');
                }
            });
        }
        
        // Apply to all locale switchers
        var localeSwitchers = document.querySelectorAll('.bb-locale-switcher');
        localeSwitchers.forEach(function(switcher) {
            // Position on hover
            switcher.addEventListener('mouseenter', function() {
                positionFlyout(this);
            });
            
            // Reposition on window resize
            window.addEventListener('resize', function() {
                if (switcher.matches(':hover')) {
                    positionFlyout(switcher);
                }
            });
        });
    })();
    
    // ── Sidebar Overlay & Body Scroll Lock ───────────────────────────────
    (function() {
        // Create overlay element
        var overlay = document.createElement('div');
        overlay.className = 'ps-sidebar-overlay';
        document.body.appendChild(overlay);
        
        // Function to check if any sidebar is open
        function isSidebarOpen() {
            var sidebars = document.querySelectorAll('.ps-panel--sidebar, #navigation-mobile, #search-sidebar, #cart-mobile');
            
            for (var i = 0; i < sidebars.length; i++) {
                var sidebar = sidebars[i];
                var computedStyle = window.getComputedStyle(sidebar);
                
                // Only check if display is block
                if (computedStyle.display === 'block') {
                    // Check transform - if sidebar is translated off-screen, it's closed
                    var transform = computedStyle.transform;
                    
                    if (transform && transform !== 'none') {
                        var matrix = new DOMMatrix(transform);
                        var translateX = matrix.m41; // Horizontal translation
                        
                        // When translateX is close to 0 (within ±50px), sidebar is ON-SCREEN
                        // When translateX is large (beyond ±50px), sidebar is OFF-SCREEN
                        if (Math.abs(translateX) <= 50) {
                            return true;
                        } else {
                            continue; // Try next sidebar
                        }
                    } else {
                        // No transform, sidebar is at default position (visible)
                        return true;
                    }
                }
            }
            
            return false;
        }
        
        // Function to update overlay and body scroll
        function updateOverlay() {
            var isOpen = isSidebarOpen();
            
            if (isOpen) {
                overlay.classList.add('active');
                document.body.classList.add('sidebar-open');
            } else {
                overlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
        }
        
        // Click overlay to close sidebar
        overlay.addEventListener('click', function() {
            // First, check if any bottom bar toggle is active and click it to properly close
            var bottomBarToggles = document.querySelectorAll('.navigation__item.bb-nav-toggle');
            var foundActiveToggle = false;
            
            bottomBarToggles.forEach(function(toggle) {
                if (toggle.classList.contains('active')) {
                    toggle.click(); // Properly close through the toggle mechanism
                    foundActiveToggle = true;
                }
            });
            
            // If no bottom bar toggle was active, check for hamburger menu
            if (!foundActiveToggle) {
                var hamburgerMenu = document.querySelector('.ps-toggle--sidebar[href="#menu-mobile"]');
                if (hamburgerMenu) {
                    var menuSidebar = document.getElementById('menu-mobile');
                    if (menuSidebar && window.getComputedStyle(menuSidebar).display === 'block') {
                        hamburgerMenu.click();
                    }
                }
            }
        });
        
        // Listen for transition end events on all sidebars (proper way)
        var sidebars = document.querySelectorAll('.ps-panel--sidebar, #navigation-mobile, #search-sidebar, #cart-mobile');
        sidebars.forEach(function(sidebar) {
            if (sidebar) {
                // When transition ends, check overlay state
                sidebar.addEventListener('transitionend', function(e) {
                    // Only check on transform transitions (not opacity, etc)
                    if (e.propertyName === 'transform') {
                        updateOverlay();
                    }
                });
            }
        });
        
        // Initial check
        setTimeout(function() {
            updateOverlay();
        }, 100);
        
        // Also check when toggle buttons are clicked (immediate feedback)
        document.addEventListener('click', function(e) {
            var clickedToggle = e.target.closest('.ps-toggle--sidebar, .bb-nav-toggle');
            if (clickedToggle) {
                // Check if the toggle is about to open (not currently active) or close
                var isCurrentlyActive = clickedToggle.classList.contains('active');
                
                if (!isCurrentlyActive) {
                    // About to open - show overlay immediately
                    overlay.classList.add('active');
                    document.body.classList.add('sidebar-open');
                } else {
                    // About to close - hide overlay immediately
                    overlay.classList.remove('active');
                    document.body.classList.remove('sidebar-open');
                }
                
                // Verify state after animation completes
                setTimeout(updateOverlay, 350);
            }
        });
    })();
});

// ═══════════════════════════════════════════════════════════════════
// PRODUCT LIGHTBOX MODAL
// ═══════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
    var lightbox = document.getElementById('product-lightbox-modal');
    if (!lightbox) {
        console.warn('Lightbox: #product-lightbox-modal not found in DOM');
        return;
    }
    
    console.log('Lightbox: Initializing lightbox modal...');
    
    var mainImage = document.getElementById('lightbox-main-image');
    var imageWrapper = mainImage.parentElement;
    var thumbnailsContainer = document.getElementById('lightbox-thumbnails');
    var productTitle = document.getElementById('lightbox-product-title');
    var closeBtn = lightbox.querySelector('.product-lightbox-close');
    var tabs = lightbox.querySelectorAll('.product-lightbox-tab');
    var imagesContent = document.getElementById('lightbox-images-content');
    var videosContent = document.getElementById('lightbox-videos-content');
    
    var currentProduct = null;
    var isZoomed = false;
    var zoomLevel = 2;
    var hintShown = false; // Track if hint has been shown
    
    // Open lightbox
    window.openProductLightbox = function(productData) {
        currentProduct = productData;
        productTitle.textContent = productData.name || 'Product';
        
        // Set main image (use currentIndex if provided, otherwise 0)
        var startIndex = productData.currentIndex || 0;
        if (productData.images && productData.images.length > 0) {
            mainImage.src = productData.images[startIndex];
            loadThumbnails(productData.images, startIndex);
        }
        
        // Set variations if provided
        var variationsContainer = document.getElementById('lightbox-product-variations');
        if (productData.variations && variationsContainer) {
            variationsContainer.innerHTML = productData.variations;
        } else if (variationsContainer) {
            variationsContainer.innerHTML = '';
        }
        
        // Show lightbox
        lightbox.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        
        // Show hint message on mobile (only once per session)
        if (!hintShown && window.innerWidth <= 767) {
            showHintMessage();
            hintShown = true;
        }
    };
    
    // Show hint message
    function showHintMessage() {
        var hintMessage = document.getElementById('lightbox-hint-message');
        if (hintMessage) {
            hintMessage.style.display = 'block';
            // Hide after 3 seconds (animation handles fade)
            setTimeout(function() {
                hintMessage.style.display = 'none';
            }, 3000);
        }
    }
    
    // Close lightbox
    function closeLightbox() {
        lightbox.style.display = 'none';
        document.body.style.overflow = '';
        resetZoom();
    }
    
    closeBtn.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightbox();
        }
    });
    
    // Tab switching
    tabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            var tabName = this.getAttribute('data-tab');
            
            // Update active state
            tabs.forEach(function(t) { t.classList.remove('active'); });
            this.classList.add('active');
            
            // Show/hide content
            if (tabName === 'images') {
                imagesContent.style.display = 'flex';
                videosContent.style.display = 'none';
            } else {
                imagesContent.style.display = 'none';
                videosContent.style.display = 'flex';
            }
        });
    });
    
    // Load thumbnails
    function loadThumbnails(images, activeIndex) {
        if (typeof activeIndex === 'undefined') activeIndex = 0;
        
        thumbnailsContainer.innerHTML = '';
        images.forEach(function(img, index) {
            var thumb = document.createElement('div');
            thumb.className = 'product-lightbox-thumbnail' + (index === activeIndex ? ' active' : '');
            thumb.innerHTML = '<img src="' + img + '" alt="Thumbnail ' + (index + 1) + '">';
            thumb.addEventListener('click', function() {
                selectThumbnail(index);
            });
            thumbnailsContainer.appendChild(thumb);
        });
    }
    
    // Select thumbnail
    function selectThumbnail(index) {
        if (!currentProduct || !currentProduct.images) return;
        
        mainImage.src = currentProduct.images[index];
        resetZoom();
        
        // Update active state
        var thumbs = thumbnailsContainer.querySelectorAll('.product-lightbox-thumbnail');
        thumbs.forEach(function(t) { t.classList.remove('active'); });
        thumbs[index].classList.add('active');
    }
    
    // Image zoom functionality
    imageWrapper.addEventListener('click', function(e) {
        if (!isZoomed) {
            zoomIn();
        } else {
            resetZoom();
        }
    });
    
    // Touch swipe and double-tap functionality for main image
    var touchStartX = 0;
    var touchStartY = 0;
    var touchEndX = 0;
    var touchEndY = 0;
    var minSwipeDistance = 50; // minimum distance for a swipe
    var lastTapTime = 0;
    var doubleTapDelay = 300; // milliseconds
    var touchMoveX = 0;
    var touchMoveY = 0;
    var isPanning = false;
    
    imageWrapper.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
        touchStartY = e.changedTouches[0].screenY;
        
        // Check for double tap
        var currentTime = new Date().getTime();
        var tapLength = currentTime - lastTapTime;
        
        if (tapLength < doubleTapDelay && tapLength > 0) {
            // Double tap detected
            e.preventDefault();
            if (isZoomed) {
                resetZoom();
            } else {
                zoomIn();
            }
            lastTapTime = 0; // Reset to prevent triple tap
        } else {
            lastTapTime = currentTime;
        }
        
        // Store initial position for panning when zoomed
        if (isZoomed) {
            isPanning = true;
            touchMoveX = touchStartX;
            touchMoveY = touchStartY;
        }
    }, { passive: false });
    
    imageWrapper.addEventListener('touchmove', function(e) {
        if (isZoomed && isPanning) {
            e.preventDefault();
            var currentX = e.changedTouches[0].screenX;
            var currentY = e.changedTouches[0].screenY;
            
            // Calculate delta from last position
            var deltaX = currentX - touchMoveX;
            var deltaY = currentY - touchMoveY;
            
            // Update position
            touchMoveX = currentX;
            touchMoveY = currentY;
            
            // Apply panning
            handleTouchPan(deltaX, deltaY);
        }
    }, { passive: false });
    
    imageWrapper.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        touchEndY = e.changedTouches[0].screenY;
        
        if (isPanning) {
            isPanning = false;
        } else {
            // Only handle swipe if not zoomed and not double-tapping
            var currentTime = new Date().getTime();
            var timeSinceLastTap = currentTime - lastTapTime;
            
            if (!isZoomed && timeSinceLastTap > doubleTapDelay) {
                handleSwipe();
            }
        }
    }, { passive: true });
    
    function handleSwipe() {
        // Only handle swipe if not zoomed
        if (isZoomed) return;
        
        var deltaX = touchEndX - touchStartX;
        var deltaY = touchEndY - touchStartY;
        
        // Check if horizontal swipe is dominant and exceeds minimum distance
        if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > minSwipeDistance) {
            if (deltaX > 0) {
                // Swipe right - go to previous image
                navigateThumbnail(-1);
            } else {
                // Swipe left - go to next image
                navigateThumbnail(1);
            }
        }
    }
    
    // Handle touch-based panning when zoomed
    var currentPanX = 0;
    var currentPanY = 0;
    
    function handleTouchPan(deltaX, deltaY) {
        if (!isZoomed) return;
        
        // Get container and image dimensions
        var container = imageWrapper.getBoundingClientRect();
        var img = mainImage.getBoundingClientRect();
        
        // Calculate boundaries
        var overflowX = Math.max(0, img.width - container.width);
        var overflowY = Math.max(0, img.height - container.height);
        var maxMoveX = overflowX / 2;
        var maxMoveY = overflowY / 2;
        
        // Update pan position
        currentPanX += deltaX;
        currentPanY += deltaY;
        
        // Clamp to boundaries
        currentPanX = Math.max(-maxMoveX, Math.min(maxMoveX, currentPanX));
        currentPanY = Math.max(-maxMoveY, Math.min(maxMoveY, currentPanY));
        
        // Apply transform
        mainImage.style.transform = 'translate(calc(-50% + ' + currentPanX + 'px), calc(-50% + ' + currentPanY + 'px))';
    }
    
    // Zoom in
    function zoomIn() {
        isZoomed = true;
        imageWrapper.classList.add('zoomed');
        // Enable mouse move for panning
        imageWrapper.addEventListener('mousemove', handleZoomPan);
    }
    
    // Reset zoom
    function resetZoom() {
        isZoomed = false;
        imageWrapper.classList.remove('zoomed');
        mainImage.style.transform = 'translate(0, 0)';
        currentPanX = 0;
        currentPanY = 0;
        imageWrapper.removeEventListener('mousemove', handleZoomPan);
    }
    
    // Handle zoom panning - Amazon style: amplified movement from center
    function handleZoomPan(e) {
        if (!isZoomed) return;
        
        // Get container dimensions and mouse position
        var container = imageWrapper.getBoundingClientRect();
        var mouseX = e.clientX - container.left;
        var mouseY = e.clientY - container.top;
        
        // Calculate mouse position from center (-0.5 to 0.5)
        // Center = 0, left edge = -0.5, right edge = 0.5
        var centerX = (mouseX / container.width) - 0.5;
        var centerY = (mouseY / container.height) - 0.5;
        
        // Amplify the movement (3x faster response)
        var amplifiedX = centerX * 3;
        var amplifiedY = centerY * 3;
        
        // Get actual image dimensions when zoomed
        var img = mainImage.getBoundingClientRect();
        
        // Calculate how much image extends beyond container
        var overflowX = Math.max(0, img.width - container.width);
        var overflowY = Math.max(0, img.height - container.height);
        
        // Calculate movement: from center (0) to edges
        // Negative centerX (left) = move image right (positive translateX)
        // Positive centerX (right) = move image left (negative translateX)
        var moveX = -amplifiedX * (overflowX / 2);
        var moveY = -amplifiedY * (overflowY / 2);
        
        // Clamp to boundaries (don't show beyond image edges)
        var maxMoveX = overflowX / 2;
        var maxMoveY = overflowY / 2;
        
        moveX = Math.max(-maxMoveX, Math.min(maxMoveX, moveX));
        moveY = Math.max(-maxMoveY, Math.min(maxMoveY, moveY));
        
        // Apply transform (translate from center position)
        mainImage.style.transform = 'translate(calc(-50% + ' + moveX + 'px), calc(-50% + ' + moveY + 'px))';
    }
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (lightbox.style.display === 'none') return;
        
        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            navigateThumbnail(-1);
        } else if (e.key === 'ArrowRight') {
            navigateThumbnail(1);
        }
    });
    
    // Navigate thumbnails with keyboard
    function navigateThumbnail(direction) {
        if (!currentProduct || !currentProduct.images) return;
        
        var thumbs = thumbnailsContainer.querySelectorAll('.product-lightbox-thumbnail');
        var currentIndex = Array.from(thumbs).findIndex(function(t) {
            return t.classList.contains('active');
        });
        
        var newIndex = currentIndex + direction;
        if (newIndex >= 0 && newIndex < currentProduct.images.length) {
            selectThumbnail(newIndex);
        }
    }
});
</script>
{{-- Product Card Enhanced global styles - loaded on every page so single product page gets them too --}}
{{-- Phosphor Icons for rating stars (filled, half-filled, regular) --}}
<script src="https://unpkg.com/@phosphor-icons/web"></script>

{{-- ?v=filectime — same versioning pattern Botble uses for all theme CSS files --}}
@php $ecCardCssFile = public_path('themes/' . Theme::getPublicThemeName() . '/css/product-card-enhanced.css'); @endphp
<link rel="stylesheet" href="{{ Theme::asset()->url('css/product-card-enhanced.css') }}?v={{ file_exists($ecCardCssFile) ? filectime($ecCardCssFile) : '1' }}">
</head>

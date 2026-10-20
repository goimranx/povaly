<?php

use Botble\Theme\Events\RenderingThemeOptionSettings;
use Botble\Theme\ThemeOption\Fields\MediaImageField;

app('events')->listen(RenderingThemeOptionSettings::class, function (): void {

    // 8 tabs: Global Styling (renamed from General)
    $tabs = [
        ['label' => __('Global Styling')],
        ['label' => __('Top Bar')],
        ['label' => __('Nav Bar')],
        ['label' => __('Mobile')],
        ['label' => __('Bottom Bar')],
        ['label' => __('Footer')],
        ['label' => __('Footer Bottom')],
        ['label' => __('Page Elements')],
        ['label' => __('Go to Top')],
    ];

    $sc = 'opt-text-subsection-style';

    $mk = function (string $id, int $tab) use ($sc): array {
        return ['id' => $id, 'section_id' => $sc, 'type' => 'tabMarker', 'label' => '',
            'attributes' => ['name' => $id, 'value' => null, 'options' => ['tab' => $tab]]];
    };

    $st = function (string $id, string $title, int $tab) use ($sc): array {
        return ['id' => $id, 'section_id' => $sc, 'type' => 'sectionTitle', 'label' => '',
            'attributes' => ['name' => $id, 'value' => null, 'options' => ['title' => $title, 'tab' => $tab]]];
    };

    theme_option()

        ->setSection(['title' => __('Style'), 'desc' => __('Style of theme'), 'id' => $sc, 'subsection' => true, 'icon' => 'ti ti-brush'])
        ->setField(['id' => 'tabs_open', 'section_id' => $sc, 'type' => 'tabsOpen', 'label' => '', 'attributes' => ['name' => 'style_tabs', 'value' => null, 'options' => ['tabs' => $tabs]]])

        // ═══════════════════════════════════════════════════════════════
        // TAB 0 — GLOBAL STYLING (Povaly Brand Design System)
        // ═══════════════════════════════════════════════════════════════
        
        // ── PRIMARY FONT ──
        ->setField(['id' => 'primary_font', 'section_id' => $sc, 'type' => 'googleFonts', 'label' => __('Primary Font'), 'attributes' => ['name' => 'primary_font', 'value' => 'Work Sans']])
        ->setField($mk('gs_font', 0))
        
        // ── PRIMARY COLORS ──
        ->setField($st('global_primary', 'Primary Colors', 0))
        ->setField($mk('gs_primary_mk', 0))
        ->setField(['id' => 'primary_color',               'section_id' => $sc, 'type' => 'customColor', 'label' => __('Primary'),               'attributes' => ['name' => 'primary_color',               'value' => '#FF642C']])
        ->setField($mk('gs_p1', 0))
        ->setField(['id' => 'global_color_primary_hover',  'section_id' => $sc, 'type' => 'customColor', 'label' => __('Primary Hover'),         'attributes' => ['name' => 'global_color_primary_hover',  'value' => '#E54E18']])
        ->setField($mk('gs_p2', 0))
        ->setField(['id' => 'global_color_primary_active', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Primary Active'),        'attributes' => ['name' => 'global_color_primary_active', 'value' => '#C83C0A']])
        ->setField($mk('gs_p3', 0))
        ->setField(['id' => 'global_color_primary_subtle', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Primary Subtle'),        'attributes' => ['name' => 'global_color_primary_subtle', 'value' => '#FFF0EB']])
        ->setField($mk('gs_p4', 0))
        ->setField(['id' => 'global_color_primary_border', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Primary Border'),        'attributes' => ['name' => 'global_color_primary_border', 'value' => '#FFB399']])
        ->setField($mk('gs_p5', 0))
        
        // ── SECONDARY COLORS ──
        ->setField($st('global_secondary', 'Secondary Colors', 0))
        ->setField($mk('gs_secondary_mk', 0))
        ->setField(['id' => 'secondary_color',             'section_id' => $sc, 'type' => 'customColor', 'label' => __('Secondary'),             'attributes' => ['name' => 'secondary_color',             'value' => '#0D1B2A']])
        ->setField($mk('gs_s1', 0))
        ->setField(['id' => 'global_color_secondary_soft', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Secondary Soft'),        'attributes' => ['name' => 'global_color_secondary_soft', 'value' => '#182A3E']])
        ->setField($mk('gs_s2', 0))
        ->setField(['id' => 'global_color_secondary_mid',  'section_id' => $sc, 'type' => 'customColor', 'label' => __('Secondary Mid'),         'attributes' => ['name' => 'global_color_secondary_mid',  'value' => '#243B53']])
        ->setField($mk('gs_s3', 0))
        
        // ── TEXT COLORS ──
        ->setField($st('global_text', 'Text Colors', 0))
        ->setField($mk('gs_text_mk', 0))
        ->setField(['id' => 'global_color_text_main',      'section_id' => $sc, 'type' => 'customColor', 'label' => __('Text Main'),             'attributes' => ['name' => 'global_color_text_main',      'value' => '#0D1B2A']])
        ->setField($mk('gs_t1', 0))
        ->setField(['id' => 'global_color_text_body',      'section_id' => $sc, 'type' => 'customColor', 'label' => __('Text Body'),             'attributes' => ['name' => 'global_color_text_body',      'value' => '#2E3D52']])
        ->setField($mk('gs_t2', 0))
        ->setField(['id' => 'global_color_text_muted',     'section_id' => $sc, 'type' => 'customColor', 'label' => __('Text Muted'),            'attributes' => ['name' => 'global_color_text_muted',     'value' => '#7A8FA6']])
        ->setField($mk('gs_t3', 0))
        ->setField(['id' => 'global_color_text_subtle',    'section_id' => $sc, 'type' => 'customColor', 'label' => __('Text Subtle'),           'attributes' => ['name' => 'global_color_text_subtle',    'value' => '#A8BED4']])
        ->setField($mk('gs_t4', 0))
        ->setField(['id' => 'global_color_text_on_dark',   'section_id' => $sc, 'type' => 'customColor', 'label' => __('Text On Dark'),          'attributes' => ['name' => 'global_color_text_on_dark',   'value' => '#F4F9FD']])
        ->setField($mk('gs_t5', 0))
        
        // ── SURFACE COLORS ──
        ->setField($st('global_surfaces', 'Surface Colors', 0))
        ->setField($mk('gs_surfaces_mk', 0))
        ->setField(['id' => 'global_color_site_bg',        'section_id' => $sc, 'type' => 'customColor', 'label' => __('Site Background'),       'attributes' => ['name' => 'global_color_site_bg',        'value' => '#F4F9FD']])
        ->setField($mk('gs_surf1', 0))
        ->setField(['id' => 'global_color_card_bg',        'section_id' => $sc, 'type' => 'customColor', 'label' => __('Card Background'),       'attributes' => ['name' => 'global_color_card_bg',        'value' => '#FFFFFF']])
        ->setField($mk('gs_surf2', 0))
        ->setField(['id' => 'global_color_card_bg_raised', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Card Raised'),           'attributes' => ['name' => 'global_color_card_bg_raised', 'value' => '#FFF3EC']])
        ->setField($mk('gs_surf3', 0))
        ->setField(['id' => 'global_color_card_bg_dark',   'section_id' => $sc, 'type' => 'customColor', 'label' => __('Card Dark'),             'attributes' => ['name' => 'global_color_card_bg_dark',   'value' => '#0D1B2A']])
        ->setField($mk('gs_surf4', 0))
        ->setField(['id' => 'global_color_border_light',   'section_id' => $sc, 'type' => 'customColor', 'label' => __('Border Light'),          'attributes' => ['name' => 'global_color_border_light',   'value' => '#F0D8CB']])
        ->setField($mk('gs_surf5', 0))
        ->setField(['id' => 'global_color_border_strong',  'section_id' => $sc, 'type' => 'customColor', 'label' => __('Border Strong'),         'attributes' => ['name' => 'global_color_border_strong',  'value' => '#E8B49B']])
        ->setField($mk('gs_surf6', 0))

        // ── STATUS COLORS ──
        ->setField($st('global_status', 'Status Colors', 0))
        ->setField($mk('gs_status_mk', 0))
        ->setField(['id' => 'global_color_success',        'section_id' => $sc, 'type' => 'customColor', 'label' => __('Success'),               'attributes' => ['name' => 'global_color_success',        'value' => '#2E7D32']])
        ->setField($mk('gs_st1', 0))
        ->setField(['id' => 'global_color_success_subtle', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Success Subtle'),        'attributes' => ['name' => 'global_color_success_subtle', 'value' => '#E8F5E9']])
        ->setField($mk('gs_st2', 0))
        ->setField(['id' => 'global_color_success_text',   'section_id' => $sc, 'type' => 'customColor', 'label' => __('Success Text'),          'attributes' => ['name' => 'global_color_success_text',   'value' => '#1B5E20']])
        ->setField($mk('gs_st3', 0))
        ->setField(['id' => 'global_color_alert',          'section_id' => $sc, 'type' => 'customColor', 'label' => __('Alert'),                 'attributes' => ['name' => 'global_color_alert',          'value' => '#FF5C5C']])
        ->setField($mk('gs_st4', 0))
        ->setField(['id' => 'global_color_alert_hover',    'section_id' => $sc, 'type' => 'customColor', 'label' => __('Alert Hover'),           'attributes' => ['name' => 'global_color_alert_hover',    'value' => '#D94040']])
        ->setField($mk('gs_st5', 0))
        ->setField(['id' => 'global_color_alert_subtle',   'section_id' => $sc, 'type' => 'customColor', 'label' => __('Alert Subtle'),          'attributes' => ['name' => 'global_color_alert_subtle',   'value' => '#FDECEA']])
        ->setField($mk('gs_st6', 0))
        ->setField(['id' => 'global_color_alert_text',     'section_id' => $sc, 'type' => 'customColor', 'label' => __('Alert Text'),            'attributes' => ['name' => 'global_color_alert_text',     'value' => '#B71C1C']])
        ->setField($mk('gs_st7', 0))
        ->setField(['id' => 'global_color_warning',        'section_id' => $sc, 'type' => 'customColor', 'label' => __('Warning'),               'attributes' => ['name' => 'global_color_warning',        'value' => '#FBB13C']])
        ->setField($mk('gs_st8', 0))
        ->setField(['id' => 'global_color_warning_subtle', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Warning Subtle'),        'attributes' => ['name' => 'global_color_warning_subtle', 'value' => '#FFFBEB']])
        ->setField($mk('gs_st9', 0))

        // ── LINK COLORS ──
        ->setField($st('global_links', 'Link Colors', 0))
        ->setField($mk('gs_links_mk', 0))
        ->setField(['id' => 'global_color_link',           'section_id' => $sc, 'type' => 'customColor', 'label' => __('Link'),                  'attributes' => ['name' => 'global_color_link',           'value' => '#13acdf']])
        ->setField($mk('gs_l1', 0))
        ->setField(['id' => 'global_color_link_hover',     'section_id' => $sc, 'type' => 'customColor', 'label' => __('Link Hover'),            'attributes' => ['name' => 'global_color_link_hover',     'value' => '#1dbff5']])
        ->setField($mk('gs_l2', 0))
        ->setField(['id' => 'global_color_link_subtle',    'section_id' => $sc, 'type' => 'customColor', 'label' => __('Link Subtle'),           'attributes' => ['name' => 'global_color_link_subtle',    'value' => '#FFF3EC']])
        ->setField($mk('gs_l3', 0))

        // ── ECOMMERCE BUTTONS ──
        ->setField($st('global_ecommerce', 'Ecommerce', 0))
        ->setField($mk('gs_ec_mk', 0))
        ->setField(['id' => 'ec_btn_cart_bg',       'section_id' => $sc, 'type' => 'customColor', 'label' => __('Add to Cart Background'),       'attributes' => ['name' => 'ec_btn_cart_bg',       'value' => '#FFD814']])
        ->setField($mk('gs_ec1', 0))
        ->setField(['id' => 'ec_btn_cart_bg_hover', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Add to Cart Hover Background'), 'attributes' => ['name' => 'ec_btn_cart_bg_hover', 'value' => '#f0c800']])
        ->setField($mk('gs_ec2', 0))
        ->setField(['id' => 'ec_btn_cart_text',     'section_id' => $sc, 'type' => 'customColor', 'label' => __('Add to Cart Text Color'),       'attributes' => ['name' => 'ec_btn_cart_text',     'value' => '#111111']])
        ->setField($mk('gs_ec3', 0))
        ->setField(['id' => 'ec_btn_buy_bg',        'section_id' => $sc, 'type' => 'customColor', 'label' => __('Buy Now Background'),           'attributes' => ['name' => 'ec_btn_buy_bg',        'value' => '#FFA41C']])
        ->setField($mk('gs_ec4', 0))
        ->setField(['id' => 'ec_btn_buy_bg_hover',  'section_id' => $sc, 'type' => 'customColor', 'label' => __('Buy Now Hover Background'),     'attributes' => ['name' => 'ec_btn_buy_bg_hover',  'value' => '#f09600']])
        ->setField($mk('gs_ec5', 0))
        ->setField(['id' => 'ec_btn_buy_text',      'section_id' => $sc, 'type' => 'customColor', 'label' => __('Buy Now Text Color'),           'attributes' => ['name' => 'ec_btn_buy_text',      'value' => '#111111']])
        ->setField($mk('gs_ec6', 0))

        // TAB 1 — Top Bar (Container)
        ->setField(['id' => 'header_topbar_padding',          'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Padding'),           'attributes' => ['name' => 'header_topbar_padding', 'value' => '{"top":"7","right":"0","bottom":"7","left":"0","unit":"px","linked":false}']])
        ->setField($mk('t1_m1', 1))
        ->setField(['id' => 'header_topbar_margin',           'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Margin'),            'attributes' => ['name' => 'header_topbar_margin',  'value' => '{"top":"0","right":"0","bottom":"0","left":"0","unit":"px","linked":true}']])
        ->setField($mk('t1_m2', 1))

        // TAB 1 — Search Box section
        ->setField($st('t1_st_search', 'Search Box', 1))
        ->setField($mk('t1_st_s_mk', 1))
        ->setField(['id' => 'search_button_style', 'section_id' => $sc, 'type' => 'customSelect', 'label' => __('Search button style'), 'attributes' => ['name' => 'search_button_style', 'list' => ['text' => __('Text Only'), 'icon' => __('Icon Only'), 'icon_text' => __('Icon + Text')], 'value' => 'text', 'options' => ['class' => 'form-control']]])
        ->setField($mk('t1_m2a', 1))
        ->setField(['id' => 'search_product_title_hover_decoration', 'section_id' => $sc, 'type' => 'customSelect', 'label' => __('Product title hover decoration'), 'attributes' => ['name' => 'search_product_title_hover_decoration', 'list' => ['underline' => __('Underline'), 'none' => __('None')], 'value' => 'underline', 'options' => ['class' => 'form-control']]])
        ->setField($mk('t1_m3', 1))

        // TAB 2 — Nav Bar Spacing
        ->setField($st('t2_st_nav_spacing', 'Nav Bar Spacing', 2))
        ->setField($mk('t2_st_nav_sp_mk', 2))
        ->setField(['id' => 'header_nav_padding',          'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Navbar padding'),              'attributes' => ['name' => 'header_nav_padding', 'value' => '{"top":"0","right":"0","bottom":"0","left":"0","unit":"px","linked":true}']])
        ->setField($mk('t2_m3', 2))
        ->setField(['id' => 'header_menu_link_font_size',    'section_id' => $sc, 'type' => 'number',         'label' => __('Menu link font size (px)'),   'attributes' => ['name' => 'header_menu_link_font_size',    'value' => 14, 'options' => ['class' => 'form-control', 'min' => 10, 'max' => 22]]])
        ->setField($mk('t2_m24', 2))
        ->setField(['id' => 'header_nav_link_padding_y',     'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Menu link padding'),     'attributes' => ['name' => 'header_nav_link_padding_y', 'value' => '{"top":"7","right":"0","bottom":"7","left":"0","unit":"px","linked":false}']])
        ->setField($mk('t2_m25', 2))

        // TAB 2 — Category Menu Padding
        ->setField($st('t2_st_cat', 'Category Menu Padding', 2))
        ->setField($mk('t2_st_c_mk', 2))
        ->setField(['id' => 'header_cat_dropdown_padding',      'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Category item padding'),            'attributes' => ['name' => 'header_cat_dropdown_padding', 'value' => '{"top":"10","right":"20","bottom":"10","left":"20","unit":"px","linked":false}']])
        ->setField($mk('t2_m9', 2))
        ->setField(['id' => 'header_cat_submenu_padding',       'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Submenu box padding'),              'attributes' => ['name' => 'header_cat_submenu_padding', 'value' => '{"top":"10","right":"15","bottom":"10","left":"15","unit":"px","linked":false}']])
        ->setField($mk('t2_m15', 2))
        ->setField(['id' => 'header_cat_submenu_item_padding',  'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Submenu item padding'),             'attributes' => ['name' => 'header_cat_submenu_item_padding', 'value' => '{"top":"8","right":"15","bottom":"8","left":"15","unit":"px","linked":false}']])
        ->setField($mk('t2_m15b', 2))

        // TAB 2 — Dropdown Menus section

        // TAB 3 — Mobile
        ->setField(['id' => 'header_mobile_sticky_element',         'section_id' => $sc, 'type' => 'customSelect', 'label' => __('Sticky element on scroll'), 'attributes' => ['name' => 'header_mobile_sticky_element', 'list' => ['topbar' => __('Top bar (logo + icons)'), 'searchbar' => __('Search bar')], 'value' => 'topbar', 'options' => ['class' => 'form-control']]])
        ->setField($mk('t3_sticky_mk', 3))
        ->setField(['id' => 'header_mobile_sticky_top',             'section_id' => $sc, 'type' => 'number',         'label' => __('Sticky bar top offset (px)'), 'attributes' => ['name' => 'header_mobile_sticky_top', 'value' => 0, 'options' => ['class' => 'form-control', 'min' => 0, 'max' => 200]]])
        ->setField($mk('t3_sticky_top_mk', 3))
        ->setField(['id' => 'header_mobile_background_color',      'section_id' => $sc, 'type' => 'customColor',    'label' => __('Background color'),          'attributes' => ['name' => 'header_mobile_background_color',      'value' => '#0d1b2a']])
        ->setField($mk('t3_m1', 3))
        ->setField(['id' => 'header_mobile_logo_max_height',       'section_id' => $sc, 'type' => 'number',         'label' => __('Logo max-height (px)'),      'attributes' => ['name' => 'header_mobile_logo_max_height',       'value' => 40, 'options' => ['class' => 'form-control', 'min' => 20, 'max' => 120]]])
        ->setField($mk('t3_m2', 3))
        ->setField(['id' => 'header_mobile_padding',               'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Header padding'),            'attributes' => ['name' => 'header_mobile_padding', 'value' => '{"top":"8","right":"15","bottom":"8","left":"15","unit":"px","linked":false}']])
        ->setField($mk('t3_m3', 3))
        ->setField($st('t3_st_search', 'Mobile Search Bar', 3))
        ->setField($mk('t3_st_s_mk', 3))
        ->setField(['id' => 'header_mobile_search_btn_color',      'section_id' => $sc, 'type' => 'customColor', 'label' => __('Search button background'), 'attributes' => ['name' => 'header_mobile_search_btn_color',      'value' => '#FBB13C']])
        ->setField($mk('t3_m4', 3))
        ->setField(['id' => 'header_mobile_search_input_bg',       'section_id' => $sc, 'type' => 'customColor', 'label' => __('Input background'),         'attributes' => ['name' => 'header_mobile_search_input_bg',       'value' => '#ffffff']])
        ->setField($mk('t3_m5', 3))
        ->setField(['id' => 'header_mobile_search_input_color',    'section_id' => $sc, 'type' => 'customColor', 'label' => __('Input text color'),         'attributes' => ['name' => 'header_mobile_search_input_color',    'value' => '#333333']])
        ->setField($mk('t3_m6', 3))
        ->setField(['id' => 'header_mobile_search_input_border',   'section_id' => $sc, 'type' => 'customColor', 'label' => __('Input border color'),       'attributes' => ['name' => 'header_mobile_search_input_border',   'value' => '#e5e7eb']])
        ->setField($mk('t3_m7', 3))
        ->setField(['id' => 'header_mobile_search_btn_text_color', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Button text color'),        'attributes' => ['name' => 'header_mobile_search_btn_text_color', 'value' => '#000000']])
        ->setField($mk('t3_m8', 3))
        ->setField($st('t3_st_icons', 'Mobile Icons', 3))
        ->setField($mk('t3_st_i_mk', 3))
        ->setField(['id' => 'header_mobile_icon_color', 'section_id' => $sc, 'type' => 'customColor', 'label' => __('Icon color'),       'attributes' => ['name' => 'header_mobile_icon_color', 'value' => '#ffffff']])
        ->setField($mk('t3_m9', 3))
        ->setField(['id' => 'header_mobile_badge_bg',   'section_id' => $sc, 'type' => 'customColor', 'label' => __('Badge background'), 'attributes' => ['name' => 'header_mobile_badge_bg',   'value' => '#fcb800']])
        ->setField($mk('t3_m10', 3))

        // TAB 4 — Bottom Bar
        ->setField([
            'id' => 'bottom_bar_logo',
            'section_id' => $sc,
            'type' => 'mediaImage',
            'label' => __('Bottom Bar Logo'),
            'attributes' => [
                'name' => 'bottom_bar_logo',
                'value' => null
            ]
        ])
        ->setField($mk('t4_logo', 4))
        ->setField(['id' => 'bottom_bar_background_color',    'section_id' => $sc, 'type' => 'customColor',  'label' => __('Background color'),        'attributes' => ['name' => 'bottom_bar_background_color',    'value' => '#ffffff']])
        ->setField($mk('t4_m1', 4))
        ->setField(['id' => 'bottom_bar_color',               'section_id' => $sc, 'type' => 'customColor',  'label' => __('Icon / text color'),       'attributes' => ['name' => 'bottom_bar_color',               'value' => '#666666']])
        ->setField($mk('t4_m2', 4))
        ->setField(['id' => 'bottom_bar_active_color',        'section_id' => $sc, 'type' => 'customColor',  'label' => __('Active item color'),       'attributes' => ['name' => 'bottom_bar_active_color',        'value' => '#fcb800']])
        ->setField($mk('t4_m3', 4))
        ->setField(['id' => 'bottom_bar_border_color',        'section_id' => $sc, 'type' => 'customColor',  'label' => __('Border top color'),        'attributes' => ['name' => 'bottom_bar_border_color',        'value' => '#e5e7eb']])
        ->setField($mk('t4_m4', 4))
        ->setField(['id' => 'bottom_bar_menu_show_text',      'section_id' => $sc, 'type' => 'customSelect', 'label' => __('Show menu text'),          'attributes' => ['name' => 'bottom_bar_menu_show_text',      'list' => ['yes' => trans('core/base::base.yes'), 'no' => trans('core/base::base.no')], 'value' => 'yes', 'options' => ['class' => 'form-control']]])
        ->setField($mk('t4_m5', 4))
        ->setField(['id' => 'bottom_bar_menu_text_font_size', 'section_id' => $sc, 'type' => 'number',       'label' => __('Menu text font size (px)'), 'attributes' => ['name' => 'bottom_bar_menu_text_font_size', 'value' => 14, 'options' => ['class' => 'form-control', 'min' => 8, 'max' => 20]]])
        ->setField($mk('t4_m6', 4))

        // TAB 5 — Footer
        ->setField(['id' => 'footer_background_color',       'section_id' => $sc, 'type' => 'customColor',    'label' => __('Background color'),            'attributes' => ['name' => 'footer_background_color',       'value' => '#ffffff']])
        ->setField($mk('t5_m1', 5))
        ->setField(['id' => 'footer_heading_color',          'section_id' => $sc, 'type' => 'customColor',    'label' => __('Heading color'),               'attributes' => ['name' => 'footer_heading_color',          'value' => '#000']])
        ->setField($mk('t5_m2', 5))
        ->setField(['id' => 'footer_text_color',             'section_id' => $sc, 'type' => 'customColor',    'label' => __('Text color'),                  'attributes' => ['name' => 'footer_text_color',             'value' => '#666']])
        ->setField($mk('t5_m3', 5))
        ->setField(['id' => 'footer_text_link_color',        'section_id' => $sc, 'type' => 'customColor',    'label' => __('Link color'),                  'attributes' => ['name' => 'footer_text_link_color',        'value' => '#666']])
        ->setField($mk('t5_m4', 5))
        ->setField(['id' => 'footer_text_link_hover_color',  'section_id' => $sc, 'type' => 'customColor',    'label' => __('Link hover color'),            'attributes' => ['name' => 'footer_text_link_hover_color',  'value' => '#fcb800']])
        ->setField($mk('t5_m5', 5))
        ->setField(['id' => 'footer_border_color',           'section_id' => $sc, 'type' => 'customColor',    'label' => __('Border color'),                'attributes' => ['name' => 'footer_border_color',           'value' => '#e1e1e1']])
        ->setField($mk('t5_m6', 5))
        ->setField(['id' => 'footer_contact_heading_color',  'section_id' => $sc, 'type' => 'customColor',    'label' => __('Contact heading color'),       'attributes' => ['name' => 'footer_contact_heading_color',  'value' => '#FBB13C']])
        ->setField($mk('t5_m7', 5))
        ->setField(['id' => 'footer_widget_bullet_color',    'section_id' => $sc, 'type' => 'customColor',    'label' => __('Widget bullet color'),         'attributes' => ['name' => 'footer_widget_bullet_color',    'value' => '#ffffff']])
        ->setField($mk('t5_m8', 5))
        ->setField(['id' => 'footer_padding',                'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Footer padding'),              'attributes' => ['name' => 'footer_padding', 'value' => '{"top":"40","right":"0","bottom":"40","left":"0","unit":"px","linked":false}']])
        ->setField($mk('t5_m9', 5))
        ->setField(['id' => 'footer_contact_desc_color',     'section_id' => $sc, 'type' => 'customColor',    'label' => __('Contact description color'),   'attributes' => ['name' => 'footer_contact_desc_color',     'value' => '#999999']])
        ->setField($mk('t5_m10', 5))
        ->setField(['id' => 'footer_widget_title_font_size', 'section_id' => $sc, 'type' => 'number',         'label' => __('Widget title font size (px)'), 'attributes' => ['name' => 'footer_widget_title_font_size', 'value' => 16, 'options' => ['class' => 'form-control', 'min' => 12, 'max' => 24]]])
        ->setField($mk('t5_m11', 5))

        // TAB 6 — Footer Bottom
        ->setField(['id' => 'footer_bottom_bar_background_color', 'section_id' => $sc, 'type' => 'customColor',    'label' => __('Background color'),           'attributes' => ['name' => 'footer_bottom_bar_background_color', 'value' => '#0d1b2a']])
        ->setField($mk('t6_m1', 6))
        ->setField(['id' => 'footer_links_bar_text_color',        'section_id' => $sc, 'type' => 'customColor',    'label' => __('Links bar text color'),       'attributes' => ['name' => 'footer_links_bar_text_color',        'value' => '#aaaaaa']])
        ->setField($mk('t6_m2', 6))
        ->setField(['id' => 'footer_links_bar_link_color',        'section_id' => $sc, 'type' => 'customColor',    'label' => __('Links bar link color'),       'attributes' => ['name' => 'footer_links_bar_link_color',        'value' => '#cccccc']])
        ->setField($mk('t6_m3', 6))
        ->setField(['id' => 'footer_links_bar_link_hover_color',  'section_id' => $sc, 'type' => 'customColor',    'label' => __('Links bar link hover color'), 'attributes' => ['name' => 'footer_links_bar_link_hover_color',  'value' => '#ffffff']])
        ->setField($mk('t6_m4', 6))
        ->setField(['id' => 'footer_links_bar_padding',           'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Links bar padding'),          'attributes' => ['name' => 'footer_links_bar_padding', 'value' => '{"top":"15","right":"0","bottom":"15","left":"0","unit":"px","linked":false}']])
        ->setField($mk('t6_m5', 6))
        ->setField(['id' => 'footer_copyright_text_color',        'section_id' => $sc, 'type' => 'customColor',    'label' => __('Copyright text color'),       'attributes' => ['name' => 'footer_copyright_text_color',        'value' => '#aaaaaa']])
        ->setField($mk('t6_m6', 6))
        ->setField(['id' => 'footer_payment_label_color',         'section_id' => $sc, 'type' => 'customColor',    'label' => __('Payment label color'),        'attributes' => ['name' => 'footer_payment_label_color',         'value' => '#cccccc']])
        ->setField($mk('t6_m7', 6))
        ->setField(['id' => 'footer_copyright_padding',           'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Copyright bar padding'),      'attributes' => ['name' => 'footer_copyright_padding', 'value' => '{"top":"15","right":"0","bottom":"15","left":"0","unit":"px","linked":false}']])
        ->setField($mk('t6_m8', 6))

        // TAB 7 — Page Elements
        ->setField(['id' => 'breadcrumb_background_color',   'section_id' => $sc, 'type' => 'customColor',    'label' => __('Breadcrumb background'),       'attributes' => ['name' => 'breadcrumb_background_color',   'value' => '#f5f5f5']])
        ->setField($mk('t7_m1', 7))
        ->setField(['id' => 'breadcrumb_text_color',         'section_id' => $sc, 'type' => 'customColor',    'label' => __('Breadcrumb text color'),       'attributes' => ['name' => 'breadcrumb_text_color',         'value' => '#666666']])
        ->setField($mk('t7_m2', 7))
        ->setField(['id' => 'breadcrumb_link_color',         'section_id' => $sc, 'type' => 'customColor',    'label' => __('Breadcrumb link color'),       'attributes' => ['name' => 'breadcrumb_link_color',         'value' => '#333333']])
        ->setField($mk('t7_m3', 7))
        ->setField(['id' => 'breadcrumb_link_hover_color',   'section_id' => $sc, 'type' => 'customColor',    'label' => __('Breadcrumb link hover color'), 'attributes' => ['name' => 'breadcrumb_link_hover_color',   'value' => '#fcb800']])
        ->setField($mk('t7_m4', 7))
        ->setField(['id' => 'breadcrumb_padding',            'section_id' => $sc, 'type' => 'spacingControl', 'label' => __('Breadcrumb padding'),          'attributes' => ['name' => 'breadcrumb_padding', 'value' => '{"top":"10","right":"0","bottom":"10","left":"0","unit":"px","linked":false}']])
        ->setField($mk('t7_m5', 7))
        ->setField(['id' => 'newsletter_popup_heading_color','section_id' => $sc, 'type' => 'customColor',    'label' => __('Popup heading color'),         'attributes' => ['name' => 'newsletter_popup_heading_color','value' => '#333333']])
        ->setField($mk('t7_m6', 7))
        ->setField(['id' => 'newsletter_popup_text_color',   'section_id' => $sc, 'type' => 'customColor',    'label' => __('Popup text color'),            'attributes' => ['name' => 'newsletter_popup_text_color',   'value' => '#666666']])
        ->setField($mk('t7_m7', 7))
        ->setField(['id' => 'newsletter_popup_btn_bg',       'section_id' => $sc, 'type' => 'customColor',    'label' => __('Popup button background'),     'attributes' => ['name' => 'newsletter_popup_btn_bg',       'value' => '#fcb800']])
        ->setField($mk('t7_m8', 7))
        ->setField(['id' => 'newsletter_popup_btn_color',    'section_id' => $sc, 'type' => 'customColor',    'label' => __('Popup button text color'),     'attributes' => ['name' => 'newsletter_popup_btn_color',    'value' => '#000000']])
        ->setField($mk('t7_m9', 7))

        // TAB 8 — Go to Top Button
        ->setField(['id' => 'backtotop_bg',            'section_id' => $sc, 'type' => 'customColor',  'label' => __('Background color'),       'attributes' => ['name' => 'backtotop_bg',            'value' => '#fcb800']])
        ->setField($mk('t8_m1', 8))
        ->setField(['id' => 'backtotop_color',         'section_id' => $sc, 'type' => 'customColor',  'label' => __('Icon color'),              'attributes' => ['name' => 'backtotop_color',         'value' => '#000000']])
        ->setField($mk('t8_m2', 8))
        ->setField(['id' => 'backtotop_bg_hover',      'section_id' => $sc, 'type' => 'customColor',  'label' => __('Hover background'),        'attributes' => ['name' => 'backtotop_bg_hover',      'value' => '#e0a000']])
        ->setField($mk('t8_m3', 8))
        ->setField(['id' => 'backtotop_color_hover',   'section_id' => $sc, 'type' => 'customColor',  'label' => __('Hover icon color'),        'attributes' => ['name' => 'backtotop_color_hover',   'value' => '#000000']])
        ->setField($mk('t8_m4', 8))
        ->setField(['id' => 'backtotop_border_color',  'section_id' => $sc, 'type' => 'customColor',  'label' => __('Border color'),            'attributes' => ['name' => 'backtotop_border_color',  'value' => '#e0a000']])
        ->setField($mk('t8_m5', 8))
        ->setField(['id' => 'backtotop_size',          'section_id' => $sc, 'type' => 'number',       'label' => __('Button size (px)'),        'attributes' => ['name' => 'backtotop_size',          'value' => 40,  'options' => ['class' => 'form-control', 'min' => 24,  'max' => 80]]])
        ->setField($mk('t8_m6', 8))
        ->setField(['id' => 'backtotop_icon_size',     'section_id' => $sc, 'type' => 'number',       'label' => __('Icon size (px)'),          'attributes' => ['name' => 'backtotop_icon_size',     'value' => 14,  'options' => ['class' => 'form-control', 'min' => 10,  'max' => 40]]])
        ->setField($mk('t8_m7', 8))
        ->setField(['id' => 'backtotop_border_radius', 'section_id' => $sc, 'type' => 'number',       'label' => __('Border radius (px)'),      'attributes' => ['name' => 'backtotop_border_radius', 'value' => 4,   'options' => ['class' => 'form-control', 'min' => 0,   'max' => 50]]])
        ->setField($mk('t8_m8', 8))
        ->setField(['id' => 'backtotop_bottom',        'section_id' => $sc, 'type' => 'number',       'label' => __('Distance from bottom (px)'), 'attributes' => ['name' => 'backtotop_bottom',      'value' => 30,  'options' => ['class' => 'form-control', 'min' => 0,   'max' => 200]]])
        ->setField($mk('t8_m9', 8))
        ->setField(['id' => 'backtotop_right',         'section_id' => $sc, 'type' => 'number',       'label' => __('Distance from right (px)'), 'attributes' => ['name' => 'backtotop_right',       'value' => 30,  'options' => ['class' => 'form-control', 'min' => 0,   'max' => 200]]])
        ->setField($mk('t8_m10', 8))
        ->setField(['id' => 'backtotop_shadow',        'section_id' => $sc, 'type' => 'customSelect', 'label' => __('Box shadow'),              'attributes' => ['name' => 'backtotop_shadow', 'list' => ['none' => __('None'), 'soft' => __('Soft'), 'strong' => __('Strong')], 'value' => 'soft', 'options' => ['class' => 'form-control']]])
        ->setField($mk('t8_m11', 8))

        ->setField(['id' => 'tabs_close', 'section_id' => $sc, 'type' => 'tabsClose', 'label' => '', 'attributes' => ['name' => 'tabs_close', 'value' => null]])

        // GENERAL SECTION
        ->setField(['id' => 'preloader_enabled',            'section_id' => 'opt-text-subsection-general', 'type' => 'customSelect', 'label' => __('Enable Preloader?'),              'attributes' => ['name' => 'preloader_enabled',           'list' => ['yes' => trans('core/base::base.yes'), 'no' => trans('core/base::base.no')], 'value' => 'no',  'options' => ['class' => 'form-control']]])
        ->setField(['id' => 'preloader_image',              'section_id' => 'opt-text-subsection-general', 'type' => 'mediaImage',   'label' => __('Preloader Image'),                'attributes' => ['name' => 'preloader_image', 'value' => null]])
        ->setField(['id' => 'sticky_header_enabled',        'section_id' => 'opt-text-subsection-general', 'type' => 'customSelect', 'label' => __('Enable sticky header?'),           'attributes' => ['name' => 'sticky_header_enabled',       'list' => ['yes' => trans('core/base::base.yes'), 'no' => trans('core/base::base.no')], 'value' => 'yes', 'options' => ['class' => 'form-control']]])
        ->setField(['id' => 'sticky_header_mobile_enabled', 'section_id' => 'opt-text-subsection-general', 'type' => 'customSelect', 'label' => __('Enable sticky header on mobile?'),'attributes' => ['name' => 'sticky_header_mobile_enabled','list' => ['yes' => trans('core/base::base.yes'), 'no' => trans('core/base::base.no')], 'value' => 'yes', 'options' => ['class' => 'form-control']]])
        ->setField(MediaImageField::make()->sectionId('opt-text-subsection-general')->name('404_page_image')->label(__('404 page image'))->priority(100))
        ->setField(['id' => 'show_back_to_top_button',      'section_id' => 'opt-text-subsection-general', 'type' => 'customSelect', 'label' => __('Show back to top button?'),        'attributes' => ['name' => 'show_back_to_top_button',     'list' => ['yes' => trans('core/base::base.yes'), 'no' => trans('core/base::base.no')], 'value' => 'yes', 'options' => ['class' => 'form-control']]])
        ->setField(['id' => 'enable_newsletter_popup',      'section_id' => 'opt-text-subsection-general', 'type' => 'customSelect', 'label' => __('Enable newsletter popup?'),         'attributes' => ['name' => 'enable_newsletter_popup',     'list' => ['no' => trans('core/base::base.no'), 'yes' => trans('core/base::base.yes')], 'value' => 'yes', 'options' => ['class' => 'form-control']]])
        ->setField(['id' => 'newsletter_image',             'section_id' => 'opt-text-subsection-general', 'type' => 'mediaImage',   'label' => __('Image for newsletter popup'),      'attributes' => ['name' => 'newsletter_image', 'value' => null]])
        ->setField(['id' => 'newsletter_popup_title',       'section_id' => 'opt-text-subsection-general', 'type' => 'text',         'label' => __('Newsletter popup title (Default: Get 25% Discount)'), 'attributes' => ['name' => 'newsletter_popup_title', 'value' => null, 'options' => ['class' => 'form-control', 'placeholder' => __('Newsletter popup title')]]])
        ->setField(['id' => 'newsletter_popup_description', 'section_id' => 'opt-text-subsection-general', 'type' => 'text',         'label' => __('Newsletter popup description (Default: Subscribe to the mailing list to receive updates on new arrivals, special offers and our promotions.)'), 'attributes' => ['name' => 'newsletter_popup_description', 'value' => null, 'options' => ['class' => 'form-control', 'placeholder' => __('Newsletter popup description')]]])
        ->setField(['id' => 'newsletter_show_after_seconds','section_id' => 'opt-text-subsection-general', 'type' => 'number',        'label' => __('Newsletter popup delay time (seconds)'), 'attributes' => ['name' => 'newsletter_show_after_seconds', 'value' => 10, 'options' => ['class' => 'form-control', 'placeholder' => __('Default: 10 (seconds)')]]])
        ->when(is_plugin_active('marketplace'), function (): void {
            theme_option()
                ->setField(['id' => 'show_sell_on_marketplace_link', 'section_id' => 'opt-text-subsection-general', 'type' => 'customSelect', 'label' => __('Show "Sell on Marketplace" link?'), 'attributes' => ['name' => 'show_sell_on_marketplace_link', 'list' => ['yes' => trans('core/base::base.yes'), 'no' => trans('core/base::base.no')], 'value' => 'yes', 'options' => ['class' => 'form-control']]])
                ->setField(['id' => 'sell_on_site_text', 'section_id' => 'opt-text-subsection-general', 'type' => 'text', 'label' => __('Sell on site text (default: Sell on Martfury)'), 'attributes' => ['name' => 'sell_on_site_text', 'value' => null, 'options' => ['class' => 'form-control', 'placeholder' => __('Sell on site text'), 'data-counter' => 120]]]);
        })
        ->setField(['id' => 'hotline', 'section_id' => 'opt-text-subsection-general', 'type' => 'text',  'label' => __('Hotline'), 'attributes' => ['name' => 'hotline', 'value' => null, 'options' => ['class' => 'form-control', 'placeholder' => __('Hotline'), 'data-counter' => 30]]])
        ->setField(['id' => 'address', 'section_id' => 'opt-text-subsection-general', 'type' => 'text',  'label' => __('Address'), 'attributes' => ['name' => 'address', 'value' => null, 'options' => ['class' => 'form-control', 'placeholder' => __('Address'), 'data-counter' => 120]]])
        ->setField(['id' => 'email',   'section_id' => 'opt-text-subsection-general', 'type' => 'email', 'label' => __('Email'),   'attributes' => ['name' => 'email',   'value' => null, 'options' => ['class' => 'form-control', 'placeholder' => __('Email'), 'data-counter' => 120]]])
        ->setField(['id' => 'payment_methods',     'section_id' => 'opt-text-subsection-ecommerce', 'type' => 'mediaImages',  'label' => __('Accepted Payment methods'), 'attributes' => ['name' => 'payment_methods[]', 'values' => theme_option('payment_methods', []), 'attributes' => ['allow_thumb' => false]]])
        ->setField(['id' => 'payment_methods_link','section_id' => 'opt-text-subsection-ecommerce', 'type' => 'text',         'label' => __('Accepted Payment methods link (optional)'), 'attributes' => ['name' => 'payment_methods_link', 'values' => null, 'options' => ['class' => 'form-control', 'placeholder' => 'https://...', 'data-counter' => 255]]])
        ->setField(['id' => 'show_featured_brands_on_products_page', 'section_id' => 'opt-text-subsection-ecommerce', 'type' => 'customSelect', 'label' => __('Show featured brands on the products page?'),  'attributes' => ['name' => 'show_featured_brands_on_products_page', 'list' => ['no' => trans('core/base::base.no'), 'yes' => trans('core/base::base.yes')], 'value' => 'yes', 'options' => ['class' => 'form-control']]])
        ->setField(['id' => 'show_recommend_items_on_products_page', 'section_id' => 'opt-text-subsection-ecommerce', 'type' => 'customSelect', 'label' => __('Show recommend items on the products page?'),  'attributes' => ['name' => 'show_recommend_items_on_products_page', 'list' => ['no' => trans('core/base::base.no'), 'yes' => trans('core/base::base.yes')], 'value' => 'yes', 'options' => ['class' => 'form-control']]])
        ->setSection(['title' => __('Product features'), 'desc' => __('Product features'), 'id' => 'opt-text-subsection-product-features', 'subsection' => true, 'icon' => 'ti ti-cube'])
        ->setSection(['title' => __('Contact info boxes'), 'desc' => __('Contact info boxes'), 'id' => 'opt-text-subsection-contact-info-boxes', 'subsection' => true, 'icon' => 'ti ti-mail']);

    for ($i = 1; $i <= 5; $i++) {
        theme_option()
            ->setField(['id' => 'product_feature_'.$i.'_title', 'section_id' => 'opt-text-subsection-product-features', 'type' => 'text',      'label' => __('Product feature title :number', ['number' => $i]), 'attributes' => ['name' => 'product_feature_'.$i.'_title', 'value' => null, 'options' => ['class' => 'form-control']]])
            ->setField(['id' => 'product_feature_'.$i.'_icon',  'section_id' => 'opt-text-subsection-product-features', 'type' => 'themeIcon', 'label' => __('Product feature icon :number',  ['number' => $i]), 'attributes' => ['name' => 'product_feature_'.$i.'_icon',  'value' => null, 'options' => ['class' => 'form-control']]]);
    }

    for ($i = 1; $i <= 6; $i++) {
        theme_option()
            ->setField(['id' => 'contact_info_box_'.$i.'_title',    'section_id' => 'opt-text-subsection-contact-info-boxes', 'type' => 'text', 'label' => __('Contact box title :number',    ['number' => $i]), 'attributes' => ['name' => 'contact_info_box_'.$i.'_title',    'value' => null, 'options' => ['class' => 'form-control']]])
            ->setField(['id' => 'contact_info_box_'.$i.'_subtitle', 'section_id' => 'opt-text-subsection-contact-info-boxes', 'type' => 'text', 'label' => __('Contact box subtitle :number', ['number' => $i]), 'attributes' => ['name' => 'contact_info_box_'.$i.'_subtitle', 'value' => null, 'options' => ['class' => 'form-control']]])
            ->setField(['id' => 'contact_info_box_'.$i.'_details',  'section_id' => 'opt-text-subsection-contact-info-boxes', 'type' => 'text', 'label' => __('Contact box detail :number',   ['number' => $i]), 'attributes' => ['name' => 'contact_info_box_'.$i.'_details',  'value' => null, 'options' => ['class' => 'form-control']]]);
    }
});

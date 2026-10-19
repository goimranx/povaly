 <footer class="ps-footer">
        <div class="ps-container">
            <div class="ps-footer__widgets">
                @if (theme_option('hotline') || theme_option('address') || theme_option('email') || theme_option('social-name-1'))
                    <aside class="widget widget_footer widget_contact-us">
                        <h4 class="widget-title">{{ __('Contact us') }}</h4>
                        <div class="widget_content">
                            @if (theme_option('hotline'))
                                <p>{{ __('Call us 24/7') }}</p>
                                <h3>{{ theme_option('hotline') }}</h3>
                            @endif
                            <p>{{ theme_option('address') }} <br><a href="mailto:{{ theme_option('email') }}">{{ theme_option('email') }}</a></p>
                            {!! Theme::partial('social-links') !!}
                        </div>
                    </aside>
                @endif
                {!! dynamic_sidebar('footer_sidebar') !!}
            </div>
            @if (Widget::group('bottom_footer_sidebar')->getWidgets())
                <div class="ps-footer__links" id="footer-links">
                    {!! dynamic_sidebar('bottom_footer_sidebar') !!}
                </div>
            @endif
            <div class="ps-footer__copyright">
                <p class="site-copyright">{!! Theme::getSiteCopyright() !!}</p>
                @php $paymentMethods = array_filter(json_decode(theme_option('payment_methods', []), true)); @endphp
                @if ($paymentMethods)
                    <div class="footer-payments">
                        <span class="payment-method-title">{{ __('We Using Safe Payment For') }}:</span>
                        <p class="d-sm-inline-block d-block">
                            @if (theme_option('payment_methods_link'))
                                <a href="{{ url(theme_option('payment_methods_link')) }}" target="_blank">
                            @endif
                            @foreach($paymentMethods as $method)
                                @if (!empty($method))
                                    <span>
                                        {!! RvMedia::image($method, __('Payment methods')) !!}
                                    </span>
                                @endif
                            @endforeach
                            @if (theme_option('payment_methods_link'))
                                </a>
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </footer>

    @if (is_plugin_active('newsletter') && theme_option('enable_newsletter_popup', 'yes') === 'yes')
        <div data-session-domain="{{ config('session.domain') ?? request()->getHost() }}"></div>
        <div class="ps-popup" id="subscribe" data-time="{{ (int)theme_option('newsletter_show_after_seconds', 10) * 1000 }}">
            <div class="ps-popup__content bg--cover" data-background="{{ RvMedia::getImageUrl(theme_option('newsletter_image')) }}" style="background-size: cover!important;"><a class="ps-popup__close" title="{{ __('Close') }}" href="#"><i class="icon-cross"></i></a>
                <form method="post" action="{{ route('public.newsletter.subscribe') }}" class="ps-form--subscribe-popup newsletter-form">
                    @csrf
                    <div class="ps-form__content">
                        <h4>{{ theme_option('newsletter_popup_title') ?: __('Get 25% Discount') }}</h4>
                        <p>{{ theme_option('newsletter_popup_description') ?: __('Subscribe to the mailing list to receive updates on new arrivals, special offers and our promotions.') }}</p>
                        <div class="mb-3">
                            <input class="form-control" name="email" type="email" placeholder="{{ __('Email Address') }}" required>
                        </div>

                        {!! apply_filters('form_extra_fields_render', null, \Botble\Newsletter\Forms\Fronts\NewsletterForm::class) !!}

                        <div class="mb-3">
                            <button class="ps-btn" type="submit" >{{ __('Subscribe') }}</button>
                        </div>
                        <div class="ps-checkbox">
                            <input class="form-control" type="checkbox" id="dont_show_again" name="dont_show_again">
                            <label for="dont_show_again">{{ __("Don't show this popup again") }}</label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {!! Theme::get('bottomFooter') !!}

    @if (theme_option('show_back_to_top_button', 'yes') === 'yes')
        <div id="back2top">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="18 15 12 9 6 15"></polyline>
            </svg>
        </div>
    @endif
    <div class="ps-site-overlay"></div>
    <div id="bb-content-overlay"></div>
    @if (is_plugin_active('ecommerce'))
        <div class="ps-search" id="site-search"><a class="ps-btn--close" href="#"></a>
            <div class="ps-search__content">
                <form class="ps-form--primary-search" action="{{ route('public.products') }}" data-ajax-url="{{ route('public.ajax.search-products') }}" method="get">
                    <input class="form-control input-search-product" name="q" value="{{ BaseHelper::stringify(request()->query('q')) }}" type="text" autocomplete="off" placeholder="{{ __('Search for...') }}">
                    <div class="spinner-icon">
                        <i class="fa fa-spin fa-spinner"></i>
                    </div>
                    <button title="{{ __('Search') }}"><i class="aroma-magnifying-glass"></i></button>
                    <div class="ps-panel--search-result"></div>
                </form>
            </div>
        </div>
    @endif
    {{-- Quick View Offcanvas (replaces modal) --}}
    <div class="offcanvas offcanvas-end" id="product-quickview" tabindex="-1" aria-labelledby="product-quickview-title" data-bs-scroll="false" data-bs-backdrop="true">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="product-quickview-title">{{ __('Quick View') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}"></button>
        </div>
        <div class="offcanvas-body">
            <article class="ps-product--detail ps-product--fullwidth ps-product--quickview">
            </article>
        </div>
    </div>

    @include(Theme::getThemeNamespace('views.ecommerce.includes.quick-shop-modal'))

    {{-- Inline CSS for Quick Shop Offcanvas --}}
    @once
    <style>
    /* ── Fix Bootstrap offcanvas/backdrop z-index to be above header (1050) ── */
    /* Note: final values set in thumbnail/mobile section above */
    /* ── Offcanvas shell ─────────────────────────────────────────── */
    .qs-offcanvas {
        width: 560px !important;
        max-width: 90vw;
        padding: 0 !important;
        overflow: hidden;
    }
    .qs-body {
        padding: 0 !important;
        overflow-y: auto;
        height: 100%;
    }

    /* ── Close button – floats outside the panel ─────────────────── */
    .qs-close-btn {
        position: fixed;
        top: 14px;
        right: calc(560px + 12px); /* just left of panel */
        z-index: 1060;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,.2);
        color: #333;
        transition: background .2s, opacity .2s;
        opacity: 0;
        pointer-events: none;
    }
    .qs-offcanvas.show .qs-close-btn,
    .qs-offcanvas.showing .qs-close-btn {
        opacity: 1;
        pointer-events: auto;
    }
    .qs-close-btn:hover { background: #f5f5f5; }

    @media (max-width: 768px) {
        .qs-offcanvas {
            width: 100% !important;
            max-width: 100vw;
        }
        .qs-close-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            left: auto;
            background: rgba(255,255,255,.92);
            box-shadow: 0 1px 4px rgba(0,0,0,.15);
            opacity: 1;
            pointer-events: auto;
        }
    }

    /* ── Product wrapper ──────────────────────────────────────────── */
    .qs-product {
        display: flex;
        flex-direction: column;
    }

    /* ── Gallery ──────────────────────────────────────────────────── */
    .qs-gallery {
        position: relative;
        background: #f8f8f8;
        flex-shrink: 0;
    }
    .qs-gallery__viewport {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 380px;
        cursor: grab;
        user-select: none;
    }
    .qs-gallery__viewport:active { cursor: grabbing; }
    @media (max-width: 576px) {
        .qs-gallery__viewport { height: 280px; }
    }
    .qs-track {
        display: flex;
        height: 100%;
        transition: transform 0.3s ease;
        will-change: transform;
    }
    .qs-track.dragging {
        transition: none; /* no animation while dragging */
    }
    .qs-slide {
        min-width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .qs-slide img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
        pointer-events: none;
        background: #f8f8f8;
    }
    .qs-slide--video {
        background: #000;
        align-items: stretch;
        position: relative; /* needed for iframe overlay positioning */
    }
    .qs-video-facade {
        position: relative;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .qs-video-facade img { width:100%; height:100%; object-fit:cover; display:block; }
    .qs-play-btn {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%,-50%);
        width: 60px; height: 60px;
        background: rgba(255,255,255,0.9);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #333;
        box-shadow: 0 4px 20px rgba(0,0,0,.25);
        transition: background .2s;
        pointer-events: none;
    }
    .qs-video-facade:hover .qs-play-btn { background: #fff; }

    /* Remove arrows from image slides, keep for video via click zones */
    .qs-arrow { display: none !important; }

    /* Video click zones - full height, 45px wide, show arrow icon only */
    .qs-video-zone {
        position: absolute;
        top: 0;
        height: 100%;
        width: 45px;
        z-index: 8;
        display: none; /* shown only in video tab via JS */
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: transparent;
        border: none;
        color: rgba(255,255,255,0.85);
        transition: color .2s;
    }
    .qs-video-zone:hover { color: #fff; }
    .qs-video-zone--prev { left: 0; cursor: pointer; }
    .qs-video-zone--next { right: 0; cursor: pointer; }
    .qs-video-zone svg { filter: drop-shadow(0 1px 3px rgba(0,0,0,.5)); }
    /* Play facade: default cursor so it doesn't look like a link */
    .qs-video-facade { cursor: default; }
    .qs-play-btn { cursor: pointer; pointer-events: auto; }

    /* Dots - above the pill */
    .qs-dots {
        position: absolute;
        bottom: 46px; left: 0; right: 0;
        display: flex; justify-content: center; gap: 5px;
        pointer-events: none;
    }
    /* When no pill (no video), dots go back to bottom */
    .qs-gallery[data-has-video="false"] .qs-dots {
        bottom: 10px;
    }
    .qs-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: rgba(255,255,255,0.5);
        transition: background .2s, transform .2s;
        pointer-events: auto;
        border: none;
        cursor: pointer;
        padding: 0;
    }
    .qs-dot.active {
        background: #fff;
        transform: scale(1.4);
    }

    /* Images / Videos pill — with counter */
    .qs-pill {
        position: absolute;
        bottom: 12px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        gap: 2px;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(4px);
        border-radius: 20px;
        padding: 3px;
    }
    .qs-pill__btn {
        padding: 4px 14px;
        border-radius: 16px;
        border: none;
        background: transparent;
        font-size: 12px;
        font-weight: 500;
        color: rgba(255,255,255,0.8);
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
        min-width: 60px;
        text-align: center;
    }
    .qs-pill__btn.active {
        background: var(--global-color-site-bg, #fff);
        color: #222;
    }

    /* ── Mobile header (close + share) — visible only on mobile ── */
    .qs-mobile-header {
        display: none;
        position: absolute;
        top: 10px;
        left: 0; right: 0;
        z-index: 25; /* above viewport (z-index: auto) and dots (z-index: 10) */
        padding: 0 12px;
        justify-content: space-between;
        align-items: center;
        pointer-events: none;
    }
    .qs-mobile-header__btn {
        width: 36px; height: 36px;
        border-radius: 50%;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(4px);
        border: none;
        color: #fff;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        pointer-events: auto;
        flex-shrink: 0;
        position: relative;
        z-index: 26;
    }
    .qs-mobile-header__btn:hover { background: rgba(0,0,0,0.65); }
    /* Desktop: hide mobile header, show desktop close button */
    .qs-close-btn { display: flex; }
    @media (max-width: 768px) {
        .qs-mobile-header { display: flex; }
        /* Hide desktop close button on mobile */
        .qs-close-btn { display: none !important; }
        /* On mobile, desktop pill hidden */
        .qs-pill--desktop { display: none !important; }
        /* Mobile pill inside header — reset positioning, keep pill styles, enable clicks */
        .qs-pill--mobile-top {
            position: static !important;
            transform: none !important;
            bottom: auto !important;
            pointer-events: auto !important;
            z-index: 26;
        }
        /* Pill buttons inside mobile header must be tappable */
        .qs-pill--mobile-top .qs-pill__btn {
            pointer-events: auto !important;
            -webkit-tap-highlight-color: rgba(255,255,255,0.15);
            touch-action: manipulation;
        }
    }
    @media (min-width: 769px) {
        /* Desktop: hide mobile-only pill */
        .qs-pill--mobile-top { display: none !important; }
    }

    /* ── Thumbnail strip ── */
    .qs-thumbs {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding: 8px 12px;
        background: var(--global-color-site-bg, #fff);
        scrollbar-width: none;
    }
    .qs-thumbs::-webkit-scrollbar { display: none; }
    .qs-thumb {
        flex-shrink: 0;
        width: 52px; height: 52px;
        border-radius: 4px;
        overflow: hidden;
        border: 2px solid transparent;
        cursor: pointer;
        transition: border-color .15s;
        background: #f0f0f0;
    }
    .qs-thumb img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        pointer-events: none;
    }
    .qs-thumb.active { border-color: var(--global-color-link, #007bff); }
    .qs-thumb:hover:not(.active) { border-color: #aaa; }
    .qs-thumb--video {
        position: relative;
        background: #111;
        border: 2px solid transparent;
    }
    .qs-thumb--video.active { border-color: var(--global-color-link, #007bff); }
    .qs-thumb--video:hover:not(.active) { border-color: #aaa; }
    .qs-thumb--video::after {
        content: '▶';
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%,-50%);
        font-size: 14px;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,.5);
    }

    /* ── Style tab grid ── */
    .qs-slide--style {
        overflow-y: auto;
        align-items: flex-start;
        padding: 12px;
        background: var(--global-color-site-bg, #f8f8f8);
    }
    .qs-style-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        width: 100%;
    }
    .qs-style-item {
        position: relative;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
        background: #fff;
        aspect-ratio: 1;
        cursor: pointer;
    }
    .qs-style-item img,
    .qs-style-item__color {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
    }
    .qs-style-item__name {
        position: absolute;
        bottom: 0; right: 0;
        background: rgba(0,0,0,0.55);
        color: #fff;
        font-size: 10px;
        font-weight: 500;
        padding: 2px 6px;
        border-radius: 4px 0 0 0;
        white-space: nowrap;
        max-width: 90%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    /* Thumbnail divider between groups */
    .qs-thumbs-divider {
        flex-shrink: 0;
        width: 1.5px;
        height: 26px;
        background: var(--global-color-border-strong, #ccc);
        margin: 0 4px;
        align-self: center;
    }

    /* ── Style grid ── */
    .qs-slide--style {
        overflow-y: auto;
        align-items: flex-start;
        padding: 12px;
        background: var(--global-color-site-bg, #f8f8f8);
    }
    .qs-style-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        width: 100%;
    }
    .qs-style-item {
        position: relative;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
        background: #fff;
        aspect-ratio: 1;
    }
    .qs-style-item img,
    .qs-style-item__color {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
    }
    .qs-style-item__name {
        position: absolute;
        bottom: 0; right: 0;
        background: rgba(0,0,0,0.55);
        color: #fff;
        font-size: 10px;
        font-weight: 500;
        padding: 2px 6px;
        border-radius: 4px 0 0 0;
        white-space: nowrap;
        max-width: 90%;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .offcanvas-backdrop { z-index: 10100 !important; }
    .offcanvas           { z-index: 10110 !important; }
    .qs-close-btn        { z-index: 10115 !important; }

    /* ── Title clamp + expand ── */
    .qs-title-wrap {
        margin-bottom: 6px;
        position: relative;
    }
    .qs-title--clamped {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        padding-right: 20px; /* space for chevron */
    }
    .qs-title--expanded {
        -webkit-line-clamp: unset !important;
        overflow: visible !important;
        padding-right: 20px;
    }
    /* Chevron positioned at bottom-right of the clamped area */
    .qs-title-expand {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 18px; height: 18px;
        background: var(--global-color-site-bg, #fff);
        border: none;
        cursor: pointer;
        color: #888;
        padding: 0;
        display: flex; align-items: center; justify-content: center;
        transition: color .15s;
        line-height: 1;
    }
    .qs-title-expand:hover { color: #444; }
    .qs-title-expand.expanded svg { transform: rotate(180deg); }

    /* ── "View full details" — above title, right-aligned, small ── */
    .qs-above-title {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 4px;
    }
    .qs-view-link {
        font-size: 11px;
        color: var(--global-color-link, #007bff);
        text-decoration: none;
        font-weight: 400;
        letter-spacing: 0.01em;
        opacity: 0.8;
        transition: opacity .15s, color .15s;
    }
    .qs-view-link:hover {
        color: var(--global-color-link-hover, #0056b3);
        text-decoration: underline;
        opacity: 1;
    }

    /* ── Override plugin's front-ecommerce.css swatch styles inside quick-shop ── */
    #quick-shop-modal .bb-product-attribute-swatch-list.visual-swatch .bb-product-attribute-swatch-item,
    #quick-shop-modal .qs-var-item {
        border-radius: 4px !important;
        box-shadow: none !important;
    }
    /* Override plugin disabled pointer-events — keep clickable */
    #quick-shop-modal .bb-product-attribute-swatch-item.disabled,
    #quick-shop-modal .qs-var-item.disabled,
    #quick-shop-modal .qs-color-item.disabled,
    .bb-product-attribute-swatch-list .bb-product-attribute-swatch-item.disabled {
        pointer-events: auto !important;
        cursor: pointer !important;
    }
    .qs-swatch-wrap { margin-bottom: 12px; }

    .qs-swatch__label {
        display: flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 8px;
        font-size: 13px;
    }
    .qs-swatch__set-name { font-weight: 700; color: var(--global-color-text-main, #0D1B2A); }
    .qs-swatch__selected-val { color: var(--global-color-text-body, #2E3D52); font-weight: 400; }

    /* Grid wrapper */
    .qs-var-grid-wrap {
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .qs-var-grid__viewport { overflow: hidden; flex: 1; }

    /* The grid — 6 items per row for desktop */
    .qs-var-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 8px;
        list-style: none;
        padding: 2px;
        margin: 0;
    }

    /* Each item */
    .qs-var-item {
        cursor: pointer;
        border-radius: 4px;
        border: 1px solid #d0d0d0;
        background: #fff;
        transition: border-color .15s, outline .15s;
        overflow: hidden;
    }
    .qs-var-item label {
        cursor: pointer;
        display: block;
        margin: 0;
        padding: 0;
    }
    .qs-var-item input[type="radio"] { display: none; }

    /* Thumbnail - square, fills width */
    .qs-var-item__thumb {
        width: 100%;
        aspect-ratio: 1;
        overflow: hidden;
        background: #f0f0f0;
    }
    .qs-var-item__thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .qs-var-item__color-fill {
        display: block;
        width: 100%;
        height: 100%;
    }

    /* Price below thumbnail */
    .qs-var-item__price {
        padding: 3px 4px 4px;
        display: flex;
        flex-direction: column;
        gap: 0;
    }
    .qs-var-item__price-sale {
        font-size: 11px;
        font-weight: 700;
        color: var(--global-color-text-body, #2E3D52);
        line-height: 1.3;
        display: block;
    }
    .qs-var-item__price-orig {
        font-size: 10px;
        color: var(--global-color-text-muted, #767676);
        line-height: 1.2;
        display: block;
        text-decoration: line-through;
    }

    /* Hover - 2px total (1px border + 1px outline) */
    .qs-var-item:not(.disabled):hover {
        border: 2px solid var(--global-color-link, #007bff);
    }
    /* Selected — 3px total (2px border + 1px outline) */
    .qs-var-item.selected,
    .qs-var-item:has(input:checked) {
        border: 2px solid var(--global-color-link, #007bff);
        outline: 1px solid var(--global-color-link, #007bff);
        outline-offset: 0px;
    }
    .qs-var-item.selected:hover,
    .qs-var-item:has(input:checked):hover {
        /* Keep same on hover when selected */
        border: 2px solid var(--global-color-link, #007bff);
        outline: 1px solid var(--global-color-link, #007bff);
    }
    /* Disabled — diagonal strikethrough, dashed border normally */
    .qs-var-item.disabled {
        opacity: .55;
        cursor: pointer;
        border: 1px dashed #bbb !important;
        outline: none !important;
    }
    /* Disabled hover — same border change as normal hover, no extra outline weight */
    .qs-var-item.disabled:hover {
        opacity: 1;
        border: 1px dashed var(--global-color-link, #007bff) !important;
        outline: none !important;
    }
    /* Disabled selected — full ring like normal selected, full opacity */
    .qs-var-item.disabled.selected,
    .qs-var-item.disabled:has(input:checked) {
        opacity: 1;
        border: 2px solid var(--global-color-link, #007bff) !important;
        outline: 2px solid var(--global-color-link, #007bff) !important;
        outline-offset: 0px !important;
    }
    /* Diagonal strikethrough line over the thumbnail */
    .qs-var-item.disabled .qs-var-item__thumb {
        position: relative;
    }
    .qs-var-item.disabled .qs-var-item__thumb::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to top right,
            transparent calc(50% - 0.5px),
            rgba(180,0,0,0.55) calc(50% - 0.5px),
            rgba(180,0,0,0.55) calc(50% + 0.5px),
            transparent calc(50% + 0.5px)
        );
        pointer-events: none;
    }
    /* Price on disabled swatches — muted */
    .qs-var-item.disabled .qs-var-item__price-sale {
        color: var(--global-color-text-muted, #767676) !important;
        text-decoration: line-through;
    }

    /* Pagination arrows */
    .qs-var-grid__arrow {
        flex-shrink: 0;
        width: 24px; height: 24px;
        border-radius: 50%;
        border: 1px solid #ccc;
        background: #fff;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #444;
        transition: background .15s;
        box-shadow: 0 1px 2px rgba(0,0,0,.08);
    }
    .qs-var-grid__arrow:hover { background: #f0f0f0; }
    .qs-var-grid__arrow:disabled { opacity: .3; cursor: not-allowed; }

    /* Color-only swatches (text attributes like Size) — same states as image swatches */
    .qs-color-list {
        display: flex; flex-wrap: wrap; gap: 6px;
        list-style: none; padding: 0; margin: 0;
    }
    .qs-color-item {
        cursor: pointer;
        border: 1px solid #d0d0d0;
        border-radius: 4px;
        padding: 0;
        font-size: 13px;
        font-weight: 500;
        background: #fff;
        color: var(--global-color-text-body, #2E3D52);
        transition: border-color .15s, outline .15s;
        overflow: hidden;
    }
    /* Label fills the entire li — whole box is clickable */
    .qs-color-item label {
        cursor: pointer;
        margin: 0;
        display: block;
        width: 100%;
        padding: 5px 12px;
        user-select: none;
    }
    .qs-color-item input[type="radio"] { display: none; }
    /* Hover - 2px border total */
    .qs-color-item:not(.disabled):hover { 
        border: 2px solid var(--global-color-link, #007bff);
    }
    /* Selected - 3px total (2px border + 1px outline) */
    .qs-color-item.selected,
    .qs-color-item:has(input:checked) {
        border: 2px solid var(--global-color-link, #007bff);
        outline: 1px solid var(--global-color-link, #007bff);
        outline-offset: 0;
        background: #fff;
    }
    .qs-color-item.selected:hover,
    .qs-color-item:has(input:checked):hover { 
        border: 2px solid var(--global-color-link, #007bff);
        outline: 1px solid var(--global-color-link, #007bff);
    }
    /* Disabled — dashed border, still clickable */
    .qs-color-item.disabled {
        opacity: .55;
        cursor: pointer;
        border: 1px dashed #aaa !important;
        outline: none !important;
    }
    /* Disabled hover — match normal hover weight, restore opacity */
    .qs-color-item.disabled:hover {
        opacity: 1;
        border: 1px dashed var(--global-color-link, #007bff) !important;
        outline: none !important;
    }
    /* Disabled selected — full ring, full opacity */
    .qs-color-item.disabled.selected,
    .qs-color-item.disabled:has(input:checked) {
        opacity: 1;
        border: 2px solid var(--global-color-link, #007bff) !important;
        outline: 2px solid var(--global-color-link, #007bff) !important;
        outline-offset: 0 !important;
    }

    /* Always hide CMS default stock message — we use .js-qs-stock */
    #quick-shop-modal .number-items-available { display: none !important; }

    .qs-info {
        padding: 20px 24px 24px;
        position: relative;
    }
    @media (max-width: 576px) {
        .qs-info { padding: 16px; }
    }
    /* h3 not h1 — SEO safe */
    .qs-title {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.4;
        margin: 0 0 10px;
        color: var(--global-color-text-main, #0D1B2A);
    }
    /* Price block — % badge LEFT, sale price RIGHT, same row, vertically centred */
    .qs-price { margin-bottom: 12px; }
    .qs-price > div,
    .qs-price > p {
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap;
        gap: 12px;
    }
    .qs-price.sale {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .qs-price h4,
    .qs-price [data-bb-value="product-price"],
    .qs-price .ps-product__price-sale {
        font-size: 20px;
        font-weight: 700;
        color: var(--global-color-text-body, #2E3D52) !important;
        margin: 0;
        line-height: 1;
    }
    /* Hide strikethrough original price — replaced with % badge */
    .qs-price [data-bb-value="product-original-price"],
    .qs-price del,
    .qs-price small { display: none !important; }
    /* % badge — appears to the LEFT of the price */
    .qs-price-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--global-color-link, #007bff);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 5px;
        border-radius: 4px;
        line-height: 1;
        letter-spacing: 0.02em;
        flex-shrink: 0;
        order: -1; /* always renders before the price span */
    }

    /* Qty row */
    .qs-qty-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 14px 0;
        flex-wrap: wrap;
    }
    .qs-qty-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--global-color-text-main, #0D1B2A);
        white-space: nowrap;
    }
    /* Stock — color-body */
    .qs-stock {
        font-size: 12px;
        color: var(--global-color-text-body, #2E3D52);
        font-weight: 500;
        white-space: nowrap;
    }
    .qs-stock--out {
        color: #cc0000;
    }
    .qs-qty {
        display: flex;
        align-items: center;
        border: 1px solid var(--global-color-primary-border, #ccc);
        border-radius: 6px;
        overflow: hidden;
        width: fit-content;
    }
    .qs-qty__btn {
        width: 36px; height: 36px;
        border: none;
        background: #f5f5f5;
        font-size: 18px; font-weight: 400;
        color: var(--global-color-text-main, #0D1B2A);
        cursor: pointer;
        transition: background .2s;
        display: flex; align-items: center; justify-content: center;
        line-height: 1;
    }
    .qs-qty__btn:hover { background: #e8e8e8; }
    .qs-qty__input {
        width: 50px; height: 36px;
        border: none;
        border-left: 1px solid var(--global-color-primary-border, #ccc);
        border-right: 1px solid var(--global-color-primary-border, #ccc);
        text-align: center;
        font-size: 15px;
        font-weight: 600;
        background: #fff;
        -moz-appearance: textfield;
    }
    .qs-qty__input::-webkit-inner-spin-button,
    .qs-qty__input::-webkit-outer-spin-button { -webkit-appearance: none; }

    /* Action buttons */
    .qs-actions {
        display: flex;
        align-items: stretch;
        gap: 10px;
        margin-bottom: 16px;
    }
    .qs-store-link {
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        gap: 3px;
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        background: #fff;
        color: var(--global-color-text-body, #2E3D52);
        text-decoration: none;
        transition: all .2s;
    }
    .qs-store-link:hover {
        background: #f8f9fa;
        color: var(--global-color-link, #007bff);
    }
    .qs-store-link i {
        font-size: 22px;
    }
    .qs-store-link-text {
        font-size: 10px;
        font-weight: 500;
        line-height: 1;
    }
    .qs-buttons-wrap {
        display: flex;
        gap: 10px;
        flex: 1;
    }
    .qs-btn {
        flex: 1;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
        border: 1px solid transparent;
        cursor: pointer;
        text-align: center;
        transition: all .2s;
    }
    /* Add to cart — uses Ecommerce theme option colors */
    .qs-btn--cart {
        background: var(--ec-btn-cart-bg, #FFD814);
        border-color: var(--ec-btn-cart-bg, #FFD814);
        color: var(--ec-btn-cart-text, #111111);
    }
    .qs-btn--cart:hover {
        background: var(--ec-btn-cart-bg-hover, #f0c800);
        border-color: var(--ec-btn-cart-bg-hover, #f0c800);
    }
    /* Buy now — uses Ecommerce theme option colors */
    .qs-btn--buy {
        background: var(--ec-btn-buy-bg, #FFA41C);
        border-color: var(--ec-btn-buy-bg, #FFA41C);
        color: var(--ec-btn-buy-text, #111111);
    }
    .qs-btn--buy:hover {
        background: var(--ec-btn-buy-bg-hover, #f09600);
        border-color: var(--ec-btn-buy-bg-hover, #f09600);
    }
    .qs-btn:disabled { opacity: .5; cursor: not-allowed; }
    
    @media (max-width: 767px) {
        /* Change visual swatch grid to 5 columns on mobile */
        .qs-var-grid {
            grid-template-columns: repeat(5, 1fr);
        }
        
        .qs-actions {
            position: sticky;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            padding: 10px 16px 10px;
            margin: 0 -16px 0;
            border-top: 1px solid var(--global-color-border-strong, #ccc);
            z-index: 10;
            gap: 8px;
            align-items: stretch;
        }
        .qs-store-link {
            padding: 6px 10px;
        }
        .qs-store-link i {
            font-size: 20px;
        }
        .qs-btn {
            padding: 10px 12px;
        }
        
        /* Remove bottom padding from info on mobile to prevent huge space */
        .qs-info {
            padding-bottom: 0 !important;
        }
    }

    /* Quick view (product-quickview) basic resets */
    #product-quickview.offcanvas {
        width: 560px !important;
        max-width: 90vw;
    }
    #product-quickview .offcanvas-header { border-bottom: none; }
    #product-quickview .offcanvas-body { padding: 20px 24px; overflow-y: auto; }

    @media (max-width: 768px) {
        #product-quickview.offcanvas { width: 100% !important; max-width: 100vw; }
    }
    </style>
    @endonce

    <script>
        window.trans = {
            "View All": "{{ __('View All') }}",
            "No reviews!": "{{ __('No reviews!') }}",
        };
        window.siteConfig = window.siteConfig || {};
        window.siteConfig.ajaxCartUrl = "{{ route('public.ajax.cart') }}";
    </script>

    {!! Theme::footer() !!}

    {{-- Quick Shop Offcanvas Handler --}}
    <script>
    (function($) {
        'use strict';

        // ── Helpers ──────────────────────────────────────────────────────────
        function bsOffcanvas(el) {
            if (!el || typeof bootstrap === 'undefined') return null;
            return bootstrap.Offcanvas.getInstance(el) || new bootstrap.Offcanvas(el, { backdrop: true, scroll: false });
        }

        // ── Quick Shop Click ──────────────────────────────────────────────────
        $(document).on('click', '[data-bb-toggle="quick-shop"]', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            const url      = $(this).data('url');
            const offcanvasEl = document.getElementById('quick-shop-modal');
            if (!url || !offcanvasEl) return;

            const $content = $('#quick-shop-modal .quick-shop-content');

            // Show spinner, then open
            $content.html(
                '<div style="display:flex;align-items:center;justify-content:center;min-height:300px;">' +
                '<div class="ps-loading"><div class="ps-loading__spinner"></div></div></div>'
            );
            bsOffcanvas(offcanvasEl).show();

            $.ajax({
                url:  url,
                type: 'GET',
                success: function(res) {
                    if (res.error) {
                        $content.html('<p class="p-4 text-danger">' + (res.message || 'Error') + '</p>');
                        return;
                    }
                    $content.html(res.data);
                    // Reset last-clicked state for each new product load
                    _qsLastClickedAttrId   = null;
                    _qsLastClickedAttrType = null;
                    _qsUserPick            = {}; // clear per-set memory for new product
                    initQSGallery(offcanvasEl);
                    initQSQty(offcanvasEl);
                    initQSSwatches(offcanvasEl);
                    initQSTitleExpand(offcanvasEl);
                    // Inject % discount badge on initial render
                    (function() {
                        var $modal = $('#quick-shop-modal');
                        $modal.find('.qs-price-badge').remove();
                        // Read price values from hidden data attributes set by the partial
                        var $priceEl = $modal.find('[data-bb-value="product-price"]');
                        var $origEl  = $modal.find('[data-bb-value="product-original-price"]');
                        if ($origEl.length && $origEl.closest('span').is(':visible') || $origEl.text().trim()) {
                            // Parse raw numeric values from data attrs if available, else skip
                            // We rely on the .sale class on the wrapper to detect a sale
                            if ($modal.find('.qs-price.sale').length) {
                                var origText = $origEl.text().replace(/[^\d.]/g, '');
                                var saleText = $priceEl.text().replace(/[^\d.]/g, '');
                                var origVal  = parseFloat(origText);
                                var saleVal  = parseFloat(saleText);
                                if (origVal > 0 && saleVal > 0 && saleVal < origVal) {
                                    var pct = Math.round((1 - saleVal / origVal) * 100);
                                    if (pct > 0) $priceEl.before('<span class="qs-price-badge">-' + pct + '%</span>');
                                }
                            }
                        }
                    })();
                    // Initial swatch visibility pass — apply show/hide + price based on
                    // whatever non-visual attributes are already selected on render
                    initQSVisualSwatchVisibility(offcanvasEl);
                    document.dispatchEvent(new CustomEvent('ecommerce.quick-shop.completed', {
                        detail: { element: $(offcanvasEl), modal: $('#quick-shop-modal') }
                    }));
                },
                error: function() {
                    $content.html('<p class="p-4 text-danger">Failed to load product.</p>');
                }
            });
        });

        // ── Gallery ───────────────────────────────────────────────────────────
        function initQSGallery(scope) {
            const gallery = scope.querySelector('.qs-gallery');
            if (!gallery) return;

            const track   = gallery.querySelector('.qs-track');
            if (!track) return;

            // All slides — images first, then videos. All visible always.
            const imageSlides = Array.from(gallery.querySelectorAll('.qs-slide--image'));
            const videoSlides = Array.from(gallery.querySelectorAll('.qs-slide--video'));
            const allSlides   = Array.from(track.querySelectorAll('.qs-slide'));
            const total       = allSlides.length;

            // Make all slides visible
            allSlides.forEach(function(s) { s.style.display = ''; });

            const pillBtns  = Array.from(scope.querySelectorAll('.qs-pill__btn'));
            const prevBtn   = gallery.querySelector('.qs-arrow--prev');
            const nextBtn   = gallery.querySelector('.qs-arrow--next');
            const videoPrev = gallery.querySelector('.qs-video-zone--prev');
            const videoNext = gallery.querySelector('.qs-video-zone--next');
            const viewport  = gallery.querySelector('.qs-gallery__viewport');

            let idx = 0;

            // Get group info for any global slide index
            function getGroup(n) {
                var s = allSlides[n];
                if (!s) return { tab: 'images', groupIdx: 0, groupTotal: imageSlides.length };
                if (s.classList.contains('qs-slide--video')) {
                    return { tab: 'videos', groupIdx: videoSlides.indexOf(s), groupTotal: videoSlides.length };
                }
                return { tab: 'images', groupIdx: imageSlides.indexOf(s), groupTotal: imageSlides.length };
            }

            // First global index of a tab group
            function firstOf(tab) {
                for (var i = 0; i < allSlides.length; i++) {
                    if (tab === 'images' && allSlides[i].classList.contains('qs-slide--image')) return i;
                    if (tab === 'videos' && allSlides[i].classList.contains('qs-slide--video')) return i;
                }
                return 0;
            }

            function render() {
                track.style.transform = 'translateX(-' + (idx * 100) + '%)';
                if (prevBtn) prevBtn.disabled = idx === 0;
                if (nextBtn) nextBtn.disabled = idx >= total - 1;

                var g = getGroup(idx);

                // Video nav zones
                if (videoPrev) videoPrev.style.display = (g.tab === 'videos' && g.groupIdx > 0) ? 'flex' : 'none';
                if (videoNext) videoNext.style.display = (g.tab === 'videos' && g.groupIdx < g.groupTotal - 1) ? 'flex' : 'none';

                // Pill active + counters
                pillBtns.forEach(function(btn) {
                    var tab = btn.dataset.tab;
                    var isActive = tab === g.tab;
                    btn.classList.toggle('active', isActive);
                    var grp   = (tab === 'videos') ? videoSlides : imageSlides;
                    var label = (tab === 'videos') ? 'Videos' : 'Images';
                    if (isActive && grp.length > 1) {
                        // Active tab: show counter
                        btn.textContent = label + ' ' + (g.groupIdx + 1) + '/' + grp.length;
                    } else {
                        // Inactive tab: label only, no counter
                        btn.textContent = label;
                    }
                });

                // Sync strip thumbnail
                var thumbs = Array.from(gallery.querySelectorAll('.qs-thumb'));
                thumbs.forEach(function(t) {
                    var match;
                    if (t.dataset.slideIndex !== undefined) {
                        // Image thumb — match by global slide index
                        match = parseInt(t.dataset.slideIndex) === idx;
                    } else {
                        // Video thumb — match by tab + group index
                        match = (t.dataset.tab === g.tab) && (parseInt(t.dataset.index) === g.groupIdx);
                    }
                    t.classList.toggle('active', match);
                    if (match) t.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                });
            }

            function stopAllMedia() {
                gallery.querySelectorAll('video').forEach(function(v) { v.pause(); });
                // Remove injected iframes and restore the facade so re-clicking works
                gallery.querySelectorAll('.qs-video-iframe-overlay').forEach(function(iframe) {
                    var facade = iframe._qsFacade;
                    if (facade) {
                        iframe.parentNode.removeChild(iframe);
                        facade.style.display = '';
                    } else {
                        iframe.parentNode.removeChild(iframe);
                    }
                });
            }

            function go(n) {
                stopAllMedia();
                idx = Math.max(0, Math.min(n, total - 1));
                render();
            }

            // Expose go() so variation change can jump to image 0
            gallery._qsGo = go;
            gallery._qsFirstOf = firstOf;

            // Arrow buttons
            if (prevBtn) prevBtn.addEventListener('click', function() { go(idx - 1); });
            if (nextBtn) nextBtn.addEventListener('click', function() { go(idx + 1); });
            if (videoPrev) videoPrev.addEventListener('click', function() { go(idx - 1); });
            if (videoNext) videoNext.addEventListener('click', function() { go(idx + 1); });

            // Pill tab buttons → jump to first of that group
            pillBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    go(firstOf(btn.dataset.tab));
                });
            });

            // Strip thumbnail click → jump to that slide
            Array.from(gallery.querySelectorAll('.qs-thumb')).forEach(function(thumb) {
                thumb.addEventListener('click', function() {
                    // Image thumbs have data-slide-index (global index)
                    if (thumb.dataset.slideIndex !== undefined) {
                        go(parseInt(thumb.dataset.slideIndex));
                        return;
                    }
                    // Video thumbs: find by tab + index
                    var tab      = thumb.dataset.tab;
                    var groupIdx = parseInt(thumb.dataset.index);
                    var grp      = videoSlides;
                    var slide    = grp[groupIdx];
                    var gIdx     = allSlides.indexOf(slide);
                    if (gIdx >= 0) go(gIdx);
                });
            });

            // Swipe / drag — skip if target is video, iframe or interactive element
            var dragStartX = 0, dragDelta = 0, isDragging = false, THRESHOLD = 50;
            function onDragStart(x, target) {
                // Don't hijack video controls, iframes, buttons, or links
                if (!target) return;
                var tag = target.tagName.toLowerCase();
                if (tag === 'video' || tag === 'iframe' || tag === 'button' || tag === 'a' || tag === 'input') return;
                if (target.closest('video') || target.closest('iframe') || target.closest('.qs-video-zone') || target.closest('.qs-pill')) return;
                dragStartX = x; isDragging = true; track.classList.add('dragging');
            }
            function onDragMove(x) {
                if (!isDragging) return;
                dragDelta = x - dragStartX;
                track.style.transform = 'translateX(calc(-' + (idx * 100) + '% + ' + dragDelta + 'px))';
            }
            function onDragEnd() {
                if (!isDragging) return;
                isDragging = false;
                track.classList.remove('dragging');
                if (dragDelta < -THRESHOLD && idx < total - 1) go(idx + 1);
                else if (dragDelta > THRESHOLD && idx > 0) go(idx - 1);
                else render();
                dragDelta = 0;
            }
            if (viewport) {
                viewport.addEventListener('mousedown',  function(e) { onDragStart(e.clientX, e.target); });
                window.addEventListener('mousemove',    function(e) { onDragMove(e.clientX); });
                window.addEventListener('mouseup',      function()  { onDragEnd(); });
                viewport.addEventListener('touchstart', function(e) { onDragStart(e.touches[0].clientX, e.target); }, { passive: true });
                viewport.addEventListener('touchmove',  function(e) { onDragMove(e.touches[0].clientX); },  { passive: true });
                viewport.addEventListener('touchend',   function()  { onDragEnd(); });
            }

            // YouTube/Vimeo facade — overlay iframe on top, keep facade so re-click works
            gallery.querySelectorAll('.qs-video-facade').forEach(function(facade) {
                facade.addEventListener('click', function() {
                    var src = facade.dataset.src;
                    if (!src) return;
                    stopAllMedia();
                    var sep = src.indexOf('?') !== -1 ? '&' : '?';
                    if (facade.dataset.provider === 'youtube' && src.indexOf('enablejsapi') === -1) src += sep + 'enablejsapi=1';
                    else if (facade.dataset.provider === 'vimeo' && src.indexOf('api=1') === -1) src += sep + 'api=1';

                    var iframe = document.createElement('iframe');
                    iframe.src = src;
                    iframe.frameBorder = '0';
                    iframe.allow = 'accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture';
                    iframe.allowFullscreen = true;
                    iframe.className = 'qs-video-iframe-overlay';
                    iframe.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;border:0;display:block;z-index:2;';
                    iframe._qsFacade = facade;

                    // Hide facade, insert iframe as sibling inside the slide
                    facade.style.display = 'none';
                    facade.parentNode.appendChild(iframe);
                });
            });

            // Share button
            var shareBtn = document.querySelector('#quick-shop-modal .qs-share-btn');
            if (shareBtn && !shareBtn._qsBound) {
                shareBtn._qsBound = true;
                shareBtn.addEventListener('click', function() {
                    var url = shareBtn.dataset.url || window.location.href;
                    var title = shareBtn.dataset.title || document.title;
                    if (navigator.share) navigator.share({ title: title, url: url }).catch(function() {});
                    else navigator.clipboard && navigator.clipboard.writeText(url).then(function() { alert('Link copied!'); });
                });
            }

            // Start: jump to the default variation image (data-jump attribute)
            var jumpIdx = parseInt(gallery.dataset.jump) || 0;
            go(jumpIdx);
        }

        // ── Qty buttons ───────────────────────────────────────────────────────
        function initQSQty(scope) {
            $(scope).on('click', '.qs-qty__btn.up', function() {
                var $inp = $(this).siblings('.qs-qty__input');
                $inp.val(Math.max(1, parseInt($inp.val() || 1) + 1));
            }).on('click', '.qs-qty__btn.down', function() {
                var $inp = $(this).siblings('.qs-qty__input');
                $inp.val(Math.max(1, parseInt($inp.val() || 1) - 1));
            });
        }

        // ─────────────────────────────────────────────────────────────────────
        // qsUpdateSwatches(scope)
        //
        // Central function. Called on open AND after every AJAX variation response.
        //
        // Rules (per user spec):
        //   A) Visual selected → text/dropdown:
        //      - Text/dropdown items that have a variation containing the selected
        //        visual attr → NORMAL (enabled)
        //      - Text/dropdown items with no matching variation → DASHED DISABLED
        //        but remain VISIBLE and CLICKABLE
        //
        //   B) Text/dropdown selected → visual:
        //      - Visual items that have a variation containing the selected text/
        //        dropdown attr → VISIBLE, with the price for that combination
        //      - Visual items with NO matching variation → HIDDEN (display:none)
        //
        //   C) Price on each visible visual swatch:
        //      - Shows the price of the variation that matches BOTH the color AND
        //        any currently-selected text/dropdown attr.
        //      - If no text/dropdown selected, shows the cheapest variation for
        //        that color (first match).
        //
        //   D) Nothing selected: all swatches shown/enabled, prices are each
        //      color's first (default) variation price.
        // ─────────────────────────────────────────────────────────────────────
        function qsUpdateSwatches(scope) {
            var $container = $(scope).find('.product-attribute-swatches');
            if (!$container.length) return;

            // Parse price map once
            var priceMap = null;
            try { priceMap = JSON.parse($container.attr('data-variation-prices') || 'null'); } catch(e) {}
            if (!priceMap || !priceMap.length) return;

            // ── Step 0: re-sync .selected class from actual radio/checkbox state ──
            // The CMS may have unchecked inputs (unavailable attr) without updating classes,
            // so we drive .selected purely from the input checked state.
            $container.find('.attribute-swatches-wrapper').each(function() {
                $(this).find('.qs-var-item, .qs-color-item, .bb-product-attribute-swatch-item').each(function() {
                    var $li = $(this);
                    var checked = $li.find('input.product-filter-item:checked').length > 0;
                    $li.toggleClass('selected', checked);
                    // Also sync the label text for this swatch set
                    if (checked) {
                        var title = $li.data('title') || '';
                        $li.closest('.qs-swatch-wrap').find('.qs-swatch__selected-val').text(title).attr('data-default', title);
                    }
                });
            });

            // Collect selected visual attr IDs
            // Use checked inputs AND the last-clicked id if it was visual
            var visualSelected = [];
            $container.find('.attribute-swatches-wrapper[data-type="visual"] input.product-filter-item:checked').each(function() {
                var v = parseInt($(this).val());
                if (!isNaN(v) && v > 0) visualSelected.push(v);
            });
            // If last click was visual and got unchecked by CMS, still honour it
            if (_qsLastClickedAttrType === 'visual' && _qsLastClickedAttrId &&
                visualSelected.indexOf(_qsLastClickedAttrId) === -1) {
                visualSelected.push(_qsLastClickedAttrId);
            }

            // Collect selected non-visual (text / dropdown) attr IDs — DOM only.
            // We intentionally do NOT fall back to _qsLastClickedAttrId for nonvisual here.
            // If the user clicked a disabled text swatch, the server won't have checked it,
            // so forcing it in causes all visuals to hide leaving nothing selected.
            // Instead, Step 3 auto-select handles the "disabled text clicked" case by
            // keeping the current visual and letting the CMS resolve the best combo.
            var nonVisualSelected = [];
            $container.find('.attribute-swatches-wrapper:not([data-type="visual"])').each(function() {
                var $wrap = $(this);
                var val;
                if ($wrap.data('type') === 'dropdown') {
                    val = parseInt($wrap.find('select').val());
                } else {
                    val = parseInt($wrap.find('input.product-filter-item:checked').val());
                }
                if (!isNaN(val) && val > 0) nonVisualSelected.push(val);
            });
            // Only honour _qsLastClickedAttrId for nonvisual - regardless of disabled state.
            // Even if disabled, user clicked it intentionally, so respect their choice:
            // add to nonVisualSelected → hide incompatible visuals → Step 3 auto-selects available visual.
            if (_qsLastClickedAttrType === 'nonvisual' && _qsLastClickedAttrId &&
                nonVisualSelected.indexOf(_qsLastClickedAttrId) === -1) {
                // Add clicked item to selection array
                nonVisualSelected.push(_qsLastClickedAttrId);
                // Force .selected class so it looks active (even if CMS didn't check the radio)
                var $clickedItem = $container.find(
                    '.qs-color-item[data-id="' + _qsLastClickedAttrId + '"], ' +
                    '.bb-product-attribute-swatch-item[data-id="' + _qsLastClickedAttrId + '"]:not(.qs-var-item)'
                );
                
                // Check if this was a disabled item
                var isDisabledNonVisual = $clickedItem.hasClass('disabled');
                
                $clickedItem.each(function() {
                    var $wrap = $(this).closest('.qs-swatch-wrap');
                    $wrap.find('.qs-color-item, .bb-product-attribute-swatch-item:not(.qs-var-item)').removeClass('selected');
                    $(this).addClass('selected');
                    var t = $(this).data('title') || '';
                    $wrap.find('.qs-swatch__selected-val').text(t).attr('data-default', t);
                    
                    // Also force-check the radio/checkbox even if disabled, so CMS AJAX includes it
                    var $input = $(this).find('input.product-filter-item');
                    if ($input.length) {
                        $wrap.find('input.product-filter-item').prop('checked', false);
                        $input.prop('disabled', false).prop('checked', true);
                    }
                });
                
                // Mark if this was a disabled non-visual click - Step 3 will use memory for disabled only
                $container[0]._qsLastNonVisualWasDisabled = isDisabledNonVisual;
            }

            // ── Rule A: update text / dropdown swatches based on visual selection ──
            if (visualSelected.length > 0) {
                $container.find('.attribute-swatches-wrapper:not([data-type="visual"])').each(function() {
                    var $wrap = $(this);
                    $wrap.find('.qs-color-item, .bb-product-attribute-swatch-item:not(.qs-var-item)').each(function() {
                        var $item  = $(this);
                        var itemId = parseInt($item.data('id'));
                        // Does any variation contain BOTH this text attr AND ALL selected visuals?
                        var hasMatch = priceMap.some(function(pv) {
                            if (pv.attrs.indexOf(itemId) === -1) return false;
                            return visualSelected.every(function(vid) { return pv.attrs.indexOf(vid) !== -1; });
                        });
                        if (hasMatch) {
                            $item.removeClass('disabled');
                            $item.css('display', '');
                        } else {
                            // Keep visible + clickable, just mark disabled
                            $item.addClass('disabled');
                            $item.css('display', '');
                        }
                    });
                });
            } else {
                // No visual selected — restore all text/dropdown items to their server-rendered state
                // (the CMS already marks truly-impossible ones disabled via variationInfo)
                // We don't touch them here — leave as rendered.
            }

            // ── Rule B & C: update visual swatches based on text/dropdown selection ──
            $container.find('.attribute-swatches-wrapper[data-type="visual"]').each(function() {
                var $attrWrap = $(this);
                var $allItems = $attrWrap.find('.qs-var-item');
                if (!$allItems.length) return;

                $allItems.each(function() {
                    var $item   = $(this);
                    var colorId = parseInt($item.data('id'));

                    // Find best matching variation for this color + all selected non-visual attrs
                    var matched = null;
                    for (var i = 0; i < priceMap.length; i++) {
                        var pv = priceMap[i];
                        if (pv.attrs.indexOf(colorId) === -1) continue;
                        var fits = nonVisualSelected.length === 0 ||
                                   nonVisualSelected.every(function(id) { return pv.attrs.indexOf(id) !== -1; });
                        if (fits) { matched = pv; break; }
                    }

                    if (matched) {
                        // Show this color
                        $item[0].style.display = '';
                        // Update price
                        var ph = '<span class="qs-var-item__price-sale">' + matched.price_fmt + '</span>';
                        if (matched.orig_fmt) ph += '<del class="qs-var-item__price-orig">' + matched.orig_fmt + '</del>';
                        $item.find('.qs-var-item__price').html(ph);
                        // Availability
                        if (!matched.available) {
                            $item.addClass('disabled');
                        } else {
                            $item.removeClass('disabled');
                        }
                    } else {
                        // No variation for this color + selected size — HIDE it
                        $item[0].style.display = 'none';
                    }
                });

                // Rebuild pagination for this grid (only visible items count)
                var $gridWrap = $attrWrap.find('.qs-var-grid-wrap');
                if (!$gridWrap.length) return;

                var prevBtn    = $gridWrap[0].querySelector('.qs-var-grid__arrow--prev');
                var nextBtn    = $gridWrap[0].querySelector('.qs-var-grid__arrow--next');
                var pageSize   = 10;
                var allArr     = $allItems.toArray();

                // Visible = not display:none
                var visArr = allArr.filter(function(el) { return el.style.display !== 'none'; });

                // If nothing is visible, restore all and bail — don't hide everything
                if (visArr.length === 0) {
                    allArr.forEach(function(el) { el.style.display = ''; });
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                    return;
                }

                var totalPages = Math.ceil(visArr.length / pageSize);

                // Page that contains selected item
                var selIdx = visArr.findIndex(function(el) {
                    return el.classList.contains('selected') || !!el.querySelector('input:checked');
                });
                var page = selIdx >= 0 ? Math.floor(selIdx / pageSize) : 0;

                // Hide all, re-show current page of visible items
                allArr.forEach(function(el) { el.style.display = 'none'; });
                visArr.forEach(function(el, i) {
                    if (i >= page * pageSize && i < (page + 1) * pageSize) el.style.display = '';
                });

                // Persist state for arrow navigation
                $gridWrap[0]._qsPage       = page;
                $gridWrap[0]._qsTotalPages  = totalPages;
                $gridWrap[0]._qsVisItems    = visArr;
                $gridWrap[0]._qsAllItems    = allArr;
                $gridWrap[0]._qsPageSize    = pageSize;

                if (prevBtn) prevBtn.style.display = page > 0 ? 'flex' : 'none';
                if (nextBtn) nextBtn.style.display = (totalPages > 1 && page < totalPages - 1) ? 'flex' : 'none';
            });

            // ── Step 3: If the currently-selected visual item just got hidden,
            // auto-select the best available visual item so the user
            // is never left with no visual selected after picking a non-visual (size/material/style/etc).
            // Priority (for DISABLED non-visual clicks):
            //   1) Use THIS disabled non-visual's own pairing (e.g., M→Blue + M→300G)
            //   2) Also try to select other non-visuals that were paired (restore full combo)
            //   3) General visual preference
            //   4) First available
            // Priority (for AVAILABLE non-visual clicks): first available
            $container.find('.attribute-swatches-wrapper[data-type="visual"]').each(function() {
                var $attrWrap  = $(this);
                var $allItems  = $attrWrap.find('.qs-var-item');
                var $selItem   = $allItems.filter('.selected, :has(input:checked)').first();
                var setSlug    = $attrWrap.data('slug') || '';

                // Is the currently selected item hidden?
                var selHidden = $selItem.length && $selItem[0].style.display === 'none';
                if (!selHidden) return; // nothing to do

                var $visAvail = $allItems.filter(function() {
                    return this.style.display !== 'none' && !$(this).hasClass('disabled');
                });
                var $visAll = $allItems.filter(function() {
                    return this.style.display !== 'none';
                });

                // Check if this was triggered by a disabled non-visual click
                var wasDisabledClick = $container[0]._qsLastNonVisualWasDisabled || false;
                
                var $preferred = $();
                
                // Special logic for DISABLED non-visual clicks
                if (wasDisabledClick) {
                    var disabledNonVisualId = nonVisualSelected.length > 0 ? nonVisualSelected[0] : null;
                    
                    // Check if this disabled non-visual has its own pairing (e.g., M→Blue)
                    if (disabledNonVisualId && _qsNonVisualToVisual[disabledNonVisualId]) {
                        var rememberedVisualId = _qsNonVisualToVisual[disabledNonVisualId];
                        $preferred = $visAvail.filter('[data-id="' + rememberedVisualId + '"]');
                        if (!$preferred.length) {
                            $preferred = $visAll.filter('[data-id="' + rememberedVisualId + '"]');
                        }
                        
                        // Also try to select the other non-visuals that were paired with this one (e.g., M→300G)
                        if ($preferred.length && _qsNonVisualToNonVisuals[disabledNonVisualId]) {
                            var otherNonVisualIds = _qsNonVisualToNonVisuals[disabledNonVisualId];
                            for (var i = 0; i < otherNonVisualIds.length; i++) {
                                var otherId = otherNonVisualIds[i];
                                // Find this non-visual's wrapper and try to select it
                                $container.find('.attribute-swatches-wrapper:not([data-type="visual"])').each(function() {
                                    var $nvWrap = $(this);
                                    var $item = $nvWrap.find('[data-id="' + otherId + '"]');
                                    if ($item.length && !$item.hasClass('disabled')) {
                                        // Click it to select
                                        var $radio = $item.find('input.product-filter-item');
                                        if ($radio.length) {
                                            $nvWrap.find('input.product-filter-item').prop('checked', false);
                                            $radio.prop('checked', true);
                                            $nvWrap.find('.qs-color-item, .bb-product-attribute-swatch-item:not(.qs-var-item)').removeClass('selected');
                                            $item.addClass('selected');
                                            var title = $item.data('title') || '';
                                            $nvWrap.find('.qs-swatch__selected-val').text(title).attr('data-default', title);
                                        }
                                    }
                                });
                            }
                        }
                    }
                    
                    // Fallback: try general remembered user pick for this visual set
                    if (!$preferred.length) {
                        var rememberedId = setSlug ? _qsUserPick[setSlug] : null;
                        if (rememberedId) {
                            $preferred = $visAvail.filter('[data-id="' + rememberedId + '"]');
                            if (!$preferred.length) {
                                $preferred = $visAll.filter('[data-id="' + rememberedId + '"]');
                            }
                        }
                    }
                }
                // For available non-visual clicks: just pick first available (current visual is hidden so can't keep it)

                var $target = $preferred.length ? $preferred : ($visAvail.length ? $visAvail.first() : $visAll.first());
                if (!$target.length) return;

                // Deselect previous, select target
                $allItems.removeClass('selected');
                $attrWrap.find('input.product-filter-item').prop('checked', false);
                $target.addClass('selected');
                var $radio = $target.find('input.product-filter-item');
                $radio.prop('checked', true);

                // Update label
                var title = $target.data('title') || '';
                $attrWrap.find('.qs-swatch__selected-val').text(title).attr('data-default', title);

                // Keep _qsLastClickedAttrId in sync (type stays 'visual')
                _qsLastClickedAttrId   = parseInt($target.data('id'));
                _qsLastClickedAttrType = 'visual';
                // Note: do NOT update _qsUserPick here — this is an auto-select, not a user choice
                
                // Remember this visual for the disabled non-visual for future reference
                if (wasDisabledClick) {
                    var disabledNonVisualId = nonVisualSelected.length > 0 ? nonVisualSelected[0] : null;
                    if (disabledNonVisualId) {
                        _qsNonVisualToVisual[disabledNonVisualId] = parseInt($target.data('id'));
                    }
                }
                
                // Clear the flag
                $container[0]._qsLastNonVisualWasDisabled = false;

                // Trigger CMS AJAX with auto-select flag so our change handler doesn't interfere
                $radio[0]._qsAutoSelect = true;
                $radio.trigger('change');
                setTimeout(function() { if ($radio[0]) $radio[0]._qsAutoSelect = false; }, 100);
            });
        }

        // Alias so the two call-sites (initQSVisualSwatchVisibility + qsHandleVariationResponse) still work
        function initQSVisualSwatchVisibility(scope) { qsUpdateSwatches(scope); }

        // Track the last swatch the user intentionally clicked (before AJAX may uncheck it)
        var _qsLastClickedAttrId   = null;
        var _qsLastClickedAttrType = null; // 'visual' | 'nonvisual'

        // Per-set memory of the last attr the user MANUALLY chose (not auto-selected).
        // Key = attribute-set slug, value = attr id (int).
        // Consulted by Step 3 so returning to a size re-picks the color the user had chosen.
        var _qsUserPick = {}; // { 'color': 3, 'size': 7, ... }
        
        // Memory of which visual was selected for each non-visual choice
        // Key = nonvisual attr id, value = visual attr id
        // E.g. { 5: 3 } means "when size M (id=5) was selected, color Black (id=3) was paired"
        // Works for any product attributes: size→color, material→finish, style→pattern, etc.
        var _qsNonVisualToVisual = {}; // { nonVisualId: visualId, ... }
        
        // Memory of which OTHER non-visuals were selected with each non-visual
        // Key = nonvisual attr id, value = array of other non-visual attr ids
        // E.g. { 5: [10] } means "when size M (id=5) was selected, weight 300G (id=10) was also selected"
        var _qsNonVisualToNonVisuals = {}; // { nonVisualId: [otherNonVisualIds], ... }

        // ── Variation grid pagination + hover label ───────────────────────
        function initQSSwatches(scope) {
            var $scope = $(scope);
            // Remove any previously-bound QS swatch handlers before re-binding
            // (prevents listener accumulation across multiple product opens)
            $scope.off('.qs-swatches');

            // Hover: show attr title beside set name
            $scope.on('mouseenter.qs-swatches', '.qs-var-item, .qs-color-item', function() {
                var $label = $(this).closest('.qs-swatch-wrap').find('.qs-swatch__selected-val');
                $label.text($(this).data('title') || '');
            }).on('mouseleave.qs-swatches', '.qs-var-item, .qs-color-item', function() {
                var $wrap = $(this).closest('.qs-swatch-wrap');
                var cur = $wrap.find('.selected').first().data('title')
                       || $wrap.find('input:checked').closest('li').data('title')
                       || $wrap.find('.qs-swatch__selected-val').data('default') || '';
                $wrap.find('.qs-swatch__selected-val').text(cur);
            });

            // On click: record which attr was clicked and its type BEFORE AJAX fires,
            // and save to per-set user-pick memory (only for genuine user clicks).
            $scope.on('click.qs-swatches', '.qs-var-item label, .qs-color-item label', function() {
                var $li   = $(this).closest('.qs-var-item, .qs-color-item');
                var $wrap = $(this).closest('.attribute-swatches-wrapper');
                var attrId   = parseInt($li.data('id'));
                var setSlug  = $wrap.data('slug') || '';
                var isVisual = $wrap.data('type') === 'visual';

                _qsLastClickedAttrId   = attrId;
                _qsLastClickedAttrType = isVisual ? 'visual' : 'nonvisual';

                // Record as user's deliberate choice for this attribute set
                if (setSlug) _qsUserPick[setSlug] = attrId;
                
                if (!isVisual) {
                    // If clicking ANY non-visual (size/material/style/text/dropdown), remember which visual is currently selected
                    // AND remember which OTHER non-visuals are selected
                    var $visualWrap = $scope.find('.attribute-swatches-wrapper[data-type="visual"]').first();
                    var $selectedVisual = $visualWrap.find('.qs-var-item.selected, .qs-var-item:has(input:checked)').first();
                    if ($selectedVisual.length) {
                        var visualId = parseInt($selectedVisual.data('id'));
                        if (!isNaN(visualId)) {
                            _qsNonVisualToVisual[attrId] = visualId;
                        }
                    }
                    
                    // Remember other non-visuals selected at the same time
                    var otherNonVisuals = [];
                    $scope.find('.attribute-swatches-wrapper:not([data-type="visual"])').each(function() {
                        var $nvWrap = $(this);
                        var $checkedInput = $nvWrap.find('input.product-filter-item:checked');
                        if ($checkedInput.length) {
                            var checkedId = parseInt($checkedInput.val());
                            if (!isNaN(checkedId) && checkedId !== attrId) {
                                otherNonVisuals.push(checkedId);
                            }
                        }
                    });
                    _qsNonVisualToNonVisuals[attrId] = otherNonVisuals;
                } else {
                    // If clicking a visual, update memory for all currently selected non-visuals
                    // E.g., M is selected, user clicks Brown → update M→Brown pairing
                    $scope.find('.attribute-swatches-wrapper:not([data-type="visual"])').each(function() {
                        var $nvWrap = $(this);
                        var $selectedNonVisual = $nvWrap.find('.qs-color-item.selected, .bb-product-attribute-swatch-item.selected:not(.qs-var-item), .qs-color-item:has(input:checked), .bb-product-attribute-swatch-item:has(input:checked):not(.qs-var-item)').first();
                        if ($selectedNonVisual.length) {
                            var nonVisualId = parseInt($selectedNonVisual.data('id'));
                            if (!isNaN(nonVisualId)) {
                                _qsNonVisualToVisual[nonVisualId] = attrId;
                            }
                        }
                    });
                }
            });

            // On selection: update .selected class + label text
            $scope.on('change.qs-swatches', '.product-filter-item', function() {
                // Skip if this change was triggered programmatically by auto-select
                if (this._qsAutoSelect) return;
                var $wrap = $(this).closest('.qs-swatch-wrap');
                $wrap.find('.qs-var-item, .qs-color-item').removeClass('selected');
                $(this).closest('li').addClass('selected');
                var title = $(this).closest('li').data('title') || '';
                $wrap.find('.qs-swatch__selected-val').text(title).attr('data-default', title);
                
                // Also update memory: if this is a visual change, update all selected non-visuals
                var $changedItem = $(this).closest('.qs-var-item, .qs-color-item');
                var $attrWrap = $(this).closest('.attribute-swatches-wrapper');
                var isVisual = $attrWrap.data('type') === 'visual';
                
                if (isVisual) {
                    var visualId = parseInt($changedItem.data('id'));
                    if (!isNaN(visualId)) {
                        // Find all selected non-visuals and update their memory
                        $scope.find('.attribute-swatches-wrapper:not([data-type="visual"])').each(function() {
                            var $nvWrap = $(this);
                            // Check for checked inputs (more reliable after change event)
                            var $checkedInput = $nvWrap.find('input.product-filter-item:checked');
                            if ($checkedInput.length) {
                                var nonVisualId = parseInt($checkedInput.val());
                                if (!isNaN(nonVisualId)) {
                                    _qsNonVisualToVisual[nonVisualId] = visualId;
                                }
                            }
                        });
                    }
                }
            });

            // Arrow pagination — uses state stored by qsUpdateSwatches
            $scope.on('click.qs-swatches', '.qs-var-grid__arrow--prev, .qs-var-grid__arrow--next', function() {
                var wrap = $(this).closest('.qs-var-grid-wrap')[0];
                if (!wrap || !wrap._qsVisItems) return;

                var isPrev     = this.classList.contains('qs-var-grid__arrow--prev');
                var page       = wrap._qsPage || 0;
                var totalPages = wrap._qsTotalPages || 1;
                var visArr     = wrap._qsVisItems;
                var allArr     = wrap._qsAllItems;
                var pageSize   = wrap._qsPageSize || 10;

                page = isPrev ? Math.max(0, page - 1) : Math.min(totalPages - 1, page + 1);
                wrap._qsPage = page;

                allArr.forEach(function(el) { el.style.display = 'none'; });
                visArr.forEach(function(el, i) {
                    if (i >= page * pageSize && i < (page + 1) * pageSize) el.style.display = '';
                });

                var prevBtn = wrap.querySelector('.qs-var-grid__arrow--prev');
                var nextBtn = wrap.querySelector('.qs-var-grid__arrow--next');
                if (prevBtn) prevBtn.style.display = page > 0 ? 'flex' : 'none';
                if (nextBtn) nextBtn.style.display = page < totalPages - 1 ? 'flex' : 'none';
            });
        }

        // ── Hook into variation AJAX ──
        // Runs AFTER defaultOnChangeSwatchesSuccess (which resets .disabled + unavailableAttributeIds).
        // We use setTimeout(fn, 0) to defer past any synchronous CMS post-processing.
        setTimeout(function() {
            var _prevSuccess = window.onChangeSwatchesSuccess;
            window.onChangeSwatchesSuccess = function(response, element) {
                // Call previous handler first (main.js wrapper → its own logic)
                if (_prevSuccess && typeof _prevSuccess === 'function') {
                    _prevSuccess(response, element);
                }

                // Only act inside the quick-shop offcanvas
                var offcanvasEl = document.getElementById('quick-shop-modal');
                if (!offcanvasEl || !offcanvasEl.classList.contains('show')) return;
                var data = response && response.data ? response.data : null;
                if (!data) return;

                // Defer so we always run AFTER defaultOnChangeSwatchesSuccess finishes
                // its own .disabled reset / unavailable re-apply pass
                setTimeout(function() {
                    qsHandleVariationResponse(data);
                }, 0);
            };
        }, 0);

        function qsHandleVariationResponse(data) {
            // Hide CMS stock message, use our own
            $('#quick-shop-modal .number-items-available').html('').hide();

            // Update stock
            var $stock = $('#quick-shop-modal .js-qs-stock');
            $stock.removeClass('qs-stock--out');
            if (data.error_message) {
                $stock.text('Stock not available').addClass('qs-stock--out');
            } else if (data.with_storehouse_management) {
                var qty = parseInt(data.quantity) || 0;
                if (qty <= 0) {
                    $stock.text('Stock not available').addClass('qs-stock--out');
                } else {
                    $stock.text('Out of ' + qty.toLocaleString() + ' units');
                }
            } else {
                $stock.text('Out of available stocks');
            }

            // Update gallery images
            if (data.image_with_sizes && data.image_with_sizes.origin && data.image_with_sizes.origin.length) {
                updateQSGalleryImages(data.image_with_sizes.origin);
            }

            // Update main price + % discount badge
            if (data.display_sale_price) {
                $('#quick-shop-modal [data-bb-value="product-price"]').text(data.display_sale_price);
            }
            // Remove old badge first
            $('#quick-shop-modal .qs-price-badge').remove();
            if (data.sale_price !== undefined && data.price !== undefined && data.sale_price < data.price && data.price > 0) {
                var pct = Math.round((1 - data.sale_price / data.price) * 100);
                if (pct > 0) {
                    var $priceSpan = $('#quick-shop-modal [data-bb-value="product-price"]');
                    $priceSpan.before('<span class="qs-price-badge">-' + pct + '%</span>');
                }
            }

            // Update hidden product ID
            $('#quick-shop-modal .hidden-product-id').val(data.id);

            // ── Update swatches: hide/show visuals + disable text/dropdown based on selection ──
            qsUpdateSwatches(document.getElementById('quick-shop-modal'));
        }

        function updateQSGalleryImages(imageUrls) {
            var offcanvas = document.getElementById('quick-shop-modal');
            if (!offcanvas) return;
            var gallery = offcanvas.querySelector('.qs-gallery');
            if (!gallery || !gallery._qsGo) return;

            if (!imageUrls || !imageUrls.length) return;

            // Find the first of these image URLs in the existing track
            var firstUrl = imageUrls[0];
            var slides   = Array.from(gallery.querySelectorAll('.qs-slide--image'));

            for (var i = 0; i < slides.length; i++) {
                var slideSrc = slides[i].dataset.src || '';
                // Match by filename (URL may differ in query params)
                var slideFile = slideSrc.split('/').pop().split('?')[0];
                var targetFile = firstUrl.split('/').pop().split('?')[0];
                if (slideSrc === firstUrl || slideFile === targetFile) {
                    // Jump to this slide's global index
                    var allSlides = Array.from(gallery.querySelector('.qs-track').querySelectorAll('.qs-slide'));
                    var globalIdx = allSlides.indexOf(slides[i]);
                    if (globalIdx >= 0) gallery._qsGo(globalIdx);
                    return;
                }
            }

            // If not found in strip — do nothing, keep current position
            // (variation images will be present once Bug 1 fix is deployed)
        }

        // ── Title expand ────────────────────────────────────────────────
        function initQSTitleExpand(scope) {
            var btn   = scope.querySelector('.qs-title-expand');
            var title = scope.querySelector('.qs-title--clamped');
            if (!btn || !title) return;

            // Only show expand button if title is actually clamped
            if (title.scrollHeight <= title.clientHeight + 2) {
                btn.style.display = 'none';
                return;
            }

            function toggleExpand() {
                var expanded = title.classList.toggle('qs-title--expanded');
                btn.classList.toggle('expanded', expanded);
            }

            // Chevron button click
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleExpand();
            });

            // Clicking the title text itself also toggles expand/collapse
            title.style.cursor = 'pointer';
            title.addEventListener('click', function() {
                toggleExpand();
            });
        }

        // Pause ALL media when offcanvas closes — remove iframe overlays, restore facades
        document.getElementById('quick-shop-modal') && document.getElementById('quick-shop-modal').addEventListener('hide.bs.offcanvas', function() {
            this.querySelectorAll('video').forEach(function(v) { v.pause(); });
            this.querySelectorAll('.qs-video-iframe-overlay').forEach(function(iframe) {
                var facade = iframe._qsFacade;
                if (facade) {
                    iframe.parentNode.removeChild(iframe);
                    facade.style.display = '';
                } else {
                    iframe.parentNode.removeChild(iframe);
                }
            });
        });

    })(jQuery);
    </script>

    <script>
        // Floating Action Button for Offer Products - Global Script (Owl Carousel Compatible)
        if (!window.floatingActionsInitialized) {
            window.floatingActionsInitialized = true;
            
            // Mobile/Touch: use click on toggle button
            document.body.addEventListener('click', function(e) {
                const toggleBtn = e.target.closest('.ps-floating-toggle');
                if (!toggleBtn) return;
                
                e.preventDefault();
                e.stopPropagation();
                
                const capsule = toggleBtn.closest('.ps-floating-actions-capsule');
                if (!capsule) return;
                
                // Close all other active floating menus first
                document.querySelectorAll('.ps-floating-actions-capsule.active').forEach(function(otherCapsule) {
                    if (otherCapsule !== capsule) {
                        otherCapsule.classList.remove('active');
                    }
                });
                
                // Toggle active class on clicked one
                capsule.classList.toggle('active');
            }, false);
            
            // Add handler specifically for our floating cart button
            $(document).on('click', '.ps-product__floating-actions .ps-floating-action.add-to-cart-button', function(event) {
                event.preventDefault();
                let _self = $(this);

                _self.prop('disabled', true).addClass('button-loading');

                $.ajax({
                    url: _self.data('url'),
                    method: 'POST',
                    data: {
                        id: _self.data('id'),
                    },
                    dataType: 'json',
                    success: res => {
                        _self.prop('disabled', false).removeClass('button-loading').addClass('active');

                        if (res.error) {
                            if (typeof Theme !== 'undefined' && Theme.showError) {
                                Theme.showError(res.message);
                            }
                            if (res.data.next_url !== undefined) {
                                setTimeout(() => {
                                    window.location.href = res.data.next_url;
                                }, 500);
                            }
                            return false;
                        }

                        if (typeof Theme !== 'undefined' && Theme.showSuccess) {
                            Theme.showSuccess(res.message);
                        }

                        if (res.data.next_url !== undefined) {
                            window.location.href = res.data.next_url;
                        } else {
                            $.ajax({
                                url: window.siteUrl + '/ajax/cart',
                                method: 'GET',
                                success: response => {
                                    if (!response.error) {
                                        $('.ps-cart--mobile').html(response.data.html);
                                        $('.btn-shopping-cart span i').text(response.data.count);

                                        if (typeof Theme !== 'undefined' && typeof Theme.lazyLoadInstance !== 'undefined') {
                                            Theme.lazyLoadInstance.update();
                                        }
                                    }
                                },
                            });
                        }
                    },
                    error: res => {
                        _self.prop('disabled', false).removeClass('button-loading');
                        if (typeof Theme !== 'undefined' && Theme.showError) {
                            Theme.showError(res.message);
                        }
                    },
                });
            });
        }
    </script>

    </body>
</html>
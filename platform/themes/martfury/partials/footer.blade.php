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
        z-index: 20;
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
    }
    .qs-mobile-header__btn:hover { background: rgba(0,0,0,0.65); }
    @media (max-width: 768px) {
        .qs-mobile-header { display: flex; }
        /* On mobile, pill moves up a bit to not overlap thumbnails */
        .qs-pill { bottom: 68px; }
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
    .qs-thumb img, .qs-thumb video {
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
    }
    .qs-thumb--video::after {
        content: '▶';
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%,-50%);
        font-size: 14px;
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,.5);
    }

    /* ── Z-index: offcanvas above everything (bottom bar, scroll-to-top) ── */
    .offcanvas-backdrop { z-index: 10100 !important; }
    .offcanvas           { z-index: 10110 !important; }
    .qs-close-btn        { z-index: 10115 !important; }

    /* ── Override plugin's front-ecommerce.css swatch styles inside quick-shop ── */
    #quick-shop-modal .bb-product-attribute-swatch-list.visual-swatch .bb-product-attribute-swatch-item,
    #quick-shop-modal .qs-var-item {
        border-radius: 4px !important;
        box-shadow: none !important;
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

    /* The grid — 5 items per row like Amazon */
    .qs-var-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 6px;
        list-style: none;
        padding: 0; margin: 0;
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

    /* Hover */
    .qs-var-item:not(.disabled):hover {
        border-color: var(--global-color-link, #007bff);
    }
    /* Selected — 2px blue border + subtle outline */
    .qs-var-item.selected,
    .qs-var-item:has(input:checked) {
        border: 2px solid var(--global-color-link, #007bff);
        outline: 2px solid var(--global-color-link, #007bff);
        outline-offset: 0px;
    }
    .qs-var-item.selected:hover,
    .qs-var-item:has(input:checked):hover {
        outline-width: 1px;
    }
    /* Disabled */
    .qs-var-item.disabled {
        opacity: .4;
        cursor: not-allowed;
        pointer-events: none;
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

    /* Color-only swatches (text attributes like Size) */
    .qs-color-list {
        display: flex; flex-wrap: wrap; gap: 6px;
        list-style: none; padding: 0; margin: 0;
    }
    .qs-color-item {
        cursor: pointer;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 4px 10px;
        font-size: 13px;
        background: #fff;
        transition: border-color .15s;
    }
    .qs-color-item label { cursor: pointer; margin: 0; }
    .qs-color-item input[type="radio"] { display: none; }
    .qs-color-item:not(.disabled):hover { border-color: var(--global-color-link, #007bff); }
    .qs-color-item.selected,
    .qs-color-item:has(input:checked) {
        border: 2px solid var(--global-color-link, #007bff);
        outline: 2px solid var(--global-color-link, #007bff);
        outline-offset: 0;
    }
    .qs-color-item.selected:hover,
    .qs-color-item:has(input:checked):hover { outline-width: 1px; }

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
    /* Price — text-body color, original muted */
    .qs-price { margin-bottom: 12px; }
    .qs-price h4,
    .qs-price [data-bb-value="product-price"],
    .qs-price .ps-product__price-sale {
        font-size: 20px;
        font-weight: 700;
        color: var(--global-color-text-body, #2E3D52) !important;
        margin: 0;
    }
    .qs-price [data-bb-value="product-original-price"],
    .qs-price del {
        color: var(--global-color-text-muted, #767676) !important;
        font-size: 13px !important;
        font-weight: 400 !important;
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
        gap: 10px;
        margin-bottom: 16px;
    }
    .qs-btn {
        flex: 1;
        padding: 11px 16px;
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
    @media (max-width: 420px) {
        .qs-actions { flex-direction: column; }
    }

    /* View link */
    .qs-view-link {
        font-size: 13px;
        color: #007185;
        text-decoration: none;
        display: inline-block;
    }
    .qs-view-link:hover { color: #C7511F; text-decoration: underline; }

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
                    initQSGallery(offcanvasEl);
                    initQSQty(offcanvasEl);
                    initQSSwatches(offcanvasEl);
                    document.dispatchEvent(new CustomEvent('ecommerce.quick-shop.completed', {
                        detail: { element: offcanvasEl, modal: $('#quick-shop-modal') }
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

            const track    = gallery.querySelector('.qs-track');
            const dotsEl   = gallery.querySelector('.qs-dots');
            if (!track) return;

            const imageSlides = Array.from(gallery.querySelectorAll('.qs-slide--image'));
            const videoSlides = Array.from(gallery.querySelectorAll('.qs-slide--video'));
            const prevBtn     = gallery.querySelector('.qs-arrow--prev');
            const nextBtn     = gallery.querySelector('.qs-arrow--next');
            const pillBtns    = Array.from(gallery.querySelectorAll('.qs-pill__btn'));
            const videoPrev   = gallery.querySelector('.qs-video-zone--prev');
            const videoNext   = gallery.querySelector('.qs-video-zone--next');

            let active = [];  // currently visible slides
            let idx    = 0;

            function buildDots() {
                if (!dotsEl) return;
                dotsEl.innerHTML = '';
                if (active.length <= 1) return;
                active.forEach(function(_, i) {
                    const d = document.createElement('button');
                    d.className = 'qs-dot' + (i === 0 ? ' active' : '');
                    d.addEventListener('click', function() { go(i); });
                    dotsEl.appendChild(d);
                });
            }

            function render() {
                track.style.transform = 'translateX(-' + (idx * 100) + '%)';
                if (prevBtn) prevBtn.disabled = idx === 0;
                if (nextBtn) nextBtn.disabled = idx >= active.length - 1;
                // Video zones: update visibility based on position
                if (videoPrev) videoPrev.style.display = (active === videoSlides && idx > 0) ? 'flex' : 'none';
                if (videoNext) videoNext.style.display = (active === videoSlides && idx < active.length - 1) ? 'flex' : 'none';
                // Update dot indicators
                if (dotsEl) {
                    Array.from(dotsEl.querySelectorAll('.qs-dot')).forEach(function(d, i) {
                        d.classList.toggle('active', i === idx);
                    });
                }
                // Update pill button counters
                updatePillCounters();
                // Update active thumbnail
                updateActiveThumbnail();
            }

            function updatePillCounters() {
                pillBtns.forEach(function(btn) {
                    var tab  = btn.dataset.tab;
                    var list = tab === 'videos' ? videoSlides : imageSlides;
                    var total = list.length;
                    var label = tab === 'images' ? 'Image' : 'Video';
                    if (total <= 1) {
                        btn.textContent = label + (total === 0 ? '' : '');
                    } else {
                        var current = (active === list) ? idx + 1 : 1;
                        btn.textContent = label + ' ' + current + '/' + total;
                    }
                });
            }

            function updateActiveThumbnail() {
                var currentTab = (active === videoSlides) ? 'videos' : 'images';
                Array.from(gallery.querySelectorAll('.qs-thumb')).forEach(function(t) {
                    var matches = t.dataset.tab === currentTab && parseInt(t.dataset.index) === idx;
                    t.classList.toggle('active', matches);
                    if (matches) {
                        // Scroll thumbnail into view
                        t.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                    }
                });
            }

            // Stop ALL media in the entire gallery — pause only, don't destroy
            function stopAllMedia() {
                gallery.querySelectorAll('video').forEach(function(v) {
                    v.pause();
                    // Don't reset currentTime — user may want to resume from same position
                });
                gallery.querySelectorAll('iframe').forEach(function(f) {
                    // Use postMessage API to pause — doesn't destroy the iframe
                    try {
                        // YouTube
                        f.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                        // Vimeo
                        f.contentWindow.postMessage('{"method":"pause"}', '*');
                    } catch(e) {}
                });
            }

            function go(n) {
                stopAllMedia();
                idx = n;
                render();
            }

            function setTab(tab) {
                stopAllMedia();

                const show = tab === 'videos' ? videoSlides : imageSlides;
                const hide = tab === 'videos' ? imageSlides : videoSlides;

                hide.forEach(function(s) { s.style.display = 'none'; });
                show.forEach(function(s) { s.style.display = ''; });
                active = show;
                idx = 0;
                buildDots();
                render();
            }

            if (prevBtn) prevBtn.addEventListener('click', function() { if (idx > 0) go(idx - 1); });
            if (nextBtn) nextBtn.addEventListener('click', function() { if (idx < active.length - 1) go(idx + 1); });
            if (videoPrev) videoPrev.addEventListener('click', function() { if (idx > 0) go(idx - 1); });
            if (videoNext) videoNext.addEventListener('click', function() { if (idx < active.length - 1) go(idx + 1); });

            // ── Swipe / drag support (mouse + touch) ─────────────────────────
            var dragStartX = 0;
            var dragDelta  = 0;
            var isDragging = false;
            var THRESHOLD  = 50; // px to trigger slide change

            function onDragStart(x) {
                dragStartX = x;
                isDragging = true;
                track.classList.add('dragging');
            }
            function onDragMove(x) {
                if (!isDragging) return;
                dragDelta = x - dragStartX;
                var base = -(idx * 100);
                var offset = (dragDelta / viewport.offsetWidth) * 100;
                track.style.transform = 'translateX(calc(' + base + '% + ' + dragDelta + 'px))';
            }
            function onDragEnd() {
                if (!isDragging) return;
                isDragging = false;
                track.classList.remove('dragging');
                if (dragDelta < -THRESHOLD && idx < active.length - 1) go(idx + 1);
                else if (dragDelta > THRESHOLD && idx > 0) go(idx - 1);
                else render(); // snap back
                dragDelta = 0;
            }

            var viewport = gallery.querySelector('.qs-gallery__viewport');

            // Mouse
            viewport.addEventListener('mousedown',  function(e) { onDragStart(e.clientX); });
            window.addEventListener('mousemove',    function(e) { onDragMove(e.clientX); });
            window.addEventListener('mouseup',      function()  { onDragEnd(); });

            // Touch
            viewport.addEventListener('touchstart', function(e) { onDragStart(e.touches[0].clientX); }, { passive: true });
            viewport.addEventListener('touchmove',  function(e) { onDragMove(e.touches[0].clientX); },  { passive: true });
            viewport.addEventListener('touchend',   function()  { onDragEnd(); });

            pillBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    pillBtns.forEach(function(b) { b.classList.remove('active'); });
                    btn.classList.add('active');
                    setTab(btn.dataset.tab);
                });
            });

            // Thumbnail click → go to that slide/tab
            gallery.querySelectorAll('.qs-thumb').forEach(function(thumb) {
                thumb.addEventListener('click', function() {
                    var tab   = thumb.dataset.tab;
                    var index = parseInt(thumb.dataset.index);
                    // Switch tab if needed
                    if (tab === 'videos' && active !== videoSlides) {
                        pillBtns.forEach(function(b) { b.classList.remove('active'); });
                        var videoBtn = gallery.querySelector('.qs-pill__btn[data-tab="videos"]');
                        if (videoBtn) videoBtn.classList.add('active');
                        setTab('videos');
                    } else if (tab === 'images' && active !== imageSlides) {
                        pillBtns.forEach(function(b) { b.classList.remove('active'); });
                        var imgBtn = gallery.querySelector('.qs-pill__btn[data-tab="images"]');
                        if (imgBtn) imgBtn.classList.add('active');
                        setTab('images');
                    }
                    go(index);
                });
            });

            // Share button
            var shareBtn = gallery.closest('.qs-product') ? gallery.closest('.qs-product').parentElement.querySelector('.qs-share-btn') : null;
            if (!shareBtn) shareBtn = document.querySelector('#quick-shop-modal .qs-share-btn');
            if (shareBtn) {
                shareBtn.addEventListener('click', function() {
                    var url   = shareBtn.dataset.url || window.location.href;
                    var title = shareBtn.dataset.title || document.title;
                    if (navigator.share) {
                        navigator.share({ title: title, url: url }).catch(function() {});
                    } else {
                        // Fallback: copy to clipboard
                        navigator.clipboard && navigator.clipboard.writeText(url).then(function() {
                            alert('Link copied!');
                        });
                    }
                });
            }

            // YouTube / Vimeo facade → inject iframe on click
            gallery.querySelectorAll('.qs-video-facade').forEach(function(facade) {
                facade.addEventListener('click', function() {
                    var src = facade.dataset.src;
                    if (!src) return;
                    // Stop all other media first
                    stopAllMedia();
                    
                    // Add JS API params so postMessage pause works
                    var separator = src.indexOf('?') !== -1 ? '&' : '?';
                    if (facade.dataset.provider === 'youtube' && src.indexOf('enablejsapi') === -1) {
                        src += separator + 'enablejsapi=1';
                    } else if (facade.dataset.provider === 'vimeo' && src.indexOf('api=1') === -1) {
                        src += separator + 'api=1';
                    }
                    
                    var iframe = document.createElement('iframe');
                    iframe.src = src;
                    iframe.frameBorder = '0';
                    iframe.allow = 'accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture';
                    iframe.allowFullscreen = true;
                    iframe.style.cssText = 'width:100%;height:100%;border:0;display:block;';
                    facade.replaceWith(iframe);
                });
            });

            setTab('images');
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

        // ── Variation grid pagination + hover label ───────────────────────
        function initQSSwatches(scope) {
            // Hover: update selected label beside attribute name
            $(scope).on('mouseenter', '.qs-var-item, .qs-color-item', function() {
                var title = $(this).data('title');
                var $label = $(this).closest('.qs-swatch-wrap').find('.qs-swatch__selected-val');
                $label.text(title);
            }).on('mouseleave', '.qs-var-item, .qs-color-item', function() {
                var $wrap = $(this).closest('.qs-swatch-wrap');
                var selected = $wrap.find('.qs-var-item.selected, .qs-color-item.selected').first().data('title')
                               || $wrap.find('input:checked').closest('li').data('title')
                               || $wrap.find('.qs-swatch__selected-val').data('default') || '';
                $wrap.find('.qs-swatch__selected-val').text(selected);
            });

            // When a swatch is selected, update selected class + label + stock
            $(scope).on('change', '.product-filter-item', function() {
                var $input = $(this);
                var $wrap  = $input.closest('.qs-swatch-wrap');
                $wrap.find('.qs-var-item, .qs-color-item').removeClass('selected');
                $input.closest('li').addClass('selected');
                var title = $input.closest('li').data('title') || '';
                $wrap.find('.qs-swatch__selected-val').text(title).attr('data-default', title);
                // Stock is handled by defaultOnChangeSwatchesSuccess patch
            });

            // Grid pagination (2-column pages, 2 rows = 4 per page)
            scope.querySelectorAll('.qs-var-grid-wrap').forEach(function(wrap) {
                var grid    = wrap.querySelector('.qs-var-grid');
                var prevBtn = wrap.querySelector('.qs-var-grid__arrow--prev');
                var nextBtn = wrap.querySelector('.qs-var-grid__arrow--next');
                if (!grid) return;

                var items      = Array.from(grid.querySelectorAll('.qs-var-item'));
                var pageSize   = 10; // 5 columns × 2 rows
                var page       = 0;
                var totalPages = Math.ceil(items.length / pageSize);

                // Find selected item page
                var selectedIdx = items.findIndex(function(i) { return i.classList.contains('selected'); });
                var startPage   = selectedIdx >= 0 ? Math.floor(selectedIdx / pageSize) : 0;

                function showPage(p) {
                    page = Math.max(0, Math.min(p, totalPages - 1));
                    items.forEach(function(item, i) {
                        item.style.display = (i >= page * pageSize && i < (page + 1) * pageSize) ? '' : 'none';
                    });
                    if (prevBtn) prevBtn.style.display = page > 0 ? 'flex' : 'none';
                    if (nextBtn) nextBtn.style.display = page < totalPages - 1 ? 'flex' : 'none';
                }

                if (prevBtn) prevBtn.addEventListener('click', function() { showPage(page - 1); });
                if (nextBtn) nextBtn.addEventListener('click', function() { showPage(page + 1); });

                if (items.length > pageSize) {
                    showPage(startPage);
                } else {
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                }
            });
        }

        // ── Override defaultOnChangeSwatchesSuccess to suppress CMS stock message ─
        // The CMS updates .number-items-available — we intercept after it runs
        // and update our .js-qs-stock instead, then blank .number-items-available
        var _origDefaultOnChange = null;

        function patchSwatchesHandler() {
            if (window.EcommerceApp && window.EcommerceApp.defaultOnChangeSwatchesSuccess && !window.EcommerceApp._qsPatched) {
                _origDefaultOnChange = window.EcommerceApp.defaultOnChangeSwatchesSuccess.bind(window.EcommerceApp);
                window.EcommerceApp.defaultOnChangeSwatchesSuccess = function(response, element) {
                    // Run the original handler
                    _origDefaultOnChange(response, element);

                    // Only interfere if we're inside the quick-shop offcanvas
                    if (!document.getElementById('quick-shop-modal') || !document.getElementById('quick-shop-modal').classList.contains('show')) return;

                    // Blank the CMS stock message immediately
                    $('#quick-shop-modal .number-items-available').html('').hide();

                    // Now update our stock element
                    var data = response && response.data ? response.data : null;
                    if (!data) return;
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
                    } else if (data.success_message || data.success_message === '') {
                        $stock.text('Out of available stocks');
                    } else {
                        $stock.text('Out of available stocks');
                    }
                };
                window.EcommerceApp._qsPatched = true;
            }
        }

        // Patch immediately and also after quick-shop opens (EcommerceApp may load late)
        patchSwatchesHandler();
        document.getElementById('quick-shop-modal') && document.getElementById('quick-shop-modal').addEventListener('shown.bs.offcanvas', function() {
            patchSwatchesHandler();
            // Also blank any existing CMS message
            $(this).find('.number-items-available').html('').hide();
        });
        // Hook into product-filter-item change — intercept the variation AJAX response
        $(document).on('change', '#quick-shop-modal .product-filter-item', function() {
            var $input   = $(this);
            var $product = $input.closest('.product-attributes');
            var url      = $product.data('target');
            if (!url) return;

            // Collect all selected attribute IDs
            var attrs = {};
            $product.find('input.product-filter-item:checked').each(function() {
                var slug = $(this).data('slug');
                attrs['attributes[' + slug + ']'] = $(this).val();
            });

            $.ajax({
                url: url,
                data: attrs,
                type: 'GET',
                success: function(res) {
                    if (res.error || !res.data) return;
                    var data = res.data;

                    // Update gallery images
                    if (data.image_with_sizes && data.image_with_sizes.origin && data.image_with_sizes.origin.length) {
                        updateQSGalleryImages(data.image_with_sizes.origin);
                    }

                    // Update price
                    if (data.display_sale_price) {
                        $('#quick-shop-modal [data-bb-value="product-price"]').text(data.display_sale_price);
                    }
                    if (data.sale_price !== data.price) {
                        $('#quick-shop-modal [data-bb-value="product-original-price"]').text(data.display_price).closest('span').show();
                    } else {
                        $('#quick-shop-modal [data-bb-value="product-original-price"]').closest('span').hide();
                    }

                    // Update hidden product ID
                    $('#quick-shop-modal .hidden-product-id').val(data.id);

                    // Update stock — handled by defaultOnChangeSwatchesSuccess patch above
                }
            });
        });

        function updateQSGalleryImages(imageUrls) {
            var offcanvas = document.getElementById('quick-shop-modal');
            if (!offcanvas) return;
            var track = offcanvas.querySelector('.qs-track');
            if (!track) return;

            // Remove old image slides
            Array.from(track.querySelectorAll('.qs-slide--image')).forEach(function(s) { s.remove(); });

            // Insert new image slides before first video slide
            var firstVideo = track.querySelector('.qs-slide--video');
            imageUrls.forEach(function(url) {
                var slide = document.createElement('div');
                slide.className = 'qs-slide qs-slide--image';
                slide.innerHTML = '<img src="' + url + '" alt="" loading="lazy" />';
                if (firstVideo) track.insertBefore(slide, firstVideo);
                else track.appendChild(slide);
            });

            // Re-init gallery (images tab)
            initQSGallery(offcanvas);
        }

        // Pause ALL media when offcanvas closes
        document.getElementById('quick-shop-modal') && document.getElementById('quick-shop-modal').addEventListener('hide.bs.offcanvas', function() {
            this.querySelectorAll('video').forEach(function(v) { v.pause(); });
            this.querySelectorAll('iframe').forEach(function(f) {
                try {
                    f.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
                    f.contentWindow.postMessage('{"method":"pause"}', '*');
                } catch(e) {}
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
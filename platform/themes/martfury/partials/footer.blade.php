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
    }
    .qs-gallery__viewport {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 380px;
    }
    @media (max-width: 576px) {
        .qs-gallery__viewport { height: 280px; }
    }
    .qs-track {
        display: flex;
        height: 100%;
        transition: transform 0.3s ease;
        will-change: transform;
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
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        display: block;
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
        background: rgba(255,255,255,.9);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #333;
        box-shadow: 0 4px 20px rgba(0,0,0,.25);
        transition: background .2s;
    }
    .qs-video-facade:hover .qs-play-btn { background: #fff; }

    /* Arrows */
    .qs-arrow {
        position: absolute;
        top: 50%; transform: translateY(-50%);
        width: 36px; height: 36px;
        border-radius: 50%;
        background: rgba(255,255,255,.88);
        border: none;
        cursor: pointer;
        z-index: 10;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
        transition: background .2s, opacity .2s;
        color: #222;
    }
    .qs-arrow:hover { background: #fff; }
    .qs-arrow:disabled { opacity: .3; cursor: not-allowed; }
    .qs-arrow--prev { left: 10px; }
    .qs-arrow--next { right: 10px; }

    /* Dots */
    .qs-dots {
        position: absolute;
        bottom: 10px; left: 0; right: 0;
        display: flex; justify-content: center; gap: 6px;
        pointer-events: none;
    }
    .qs-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: rgba(255,255,255,.55);
        transition: background .2s, transform .2s;
        pointer-events: auto;
        border: none;
        cursor: pointer;
        padding: 0;
    }
    .qs-dot.active {
        background: #fff;
        transform: scale(1.3);
    }

    /* Images / Videos pill */
    .qs-pill {
        display: flex;
        justify-content: center;
        padding: 10px 0 8px;
        gap: 4px;
    }
    .qs-pill__btn {
        padding: 5px 20px;
        border-radius: 20px;
        border: 1px solid #d0d0d0;
        background: #fff;
        font-size: 13px;
        font-weight: 500;
        color: #555;
        cursor: pointer;
        transition: all .2s;
    }
    .qs-pill__btn.active {
        background: #222;
        color: #fff;
        border-color: #222;
    }

    /* ── Info section ─────────────────────────────────────────────── */
    .qs-info {
        padding: 20px 24px 24px;
        position: relative;
    }
    @media (max-width: 576px) {
        .qs-info { padding: 16px; }
    }
    .qs-title {
        font-size: 17px;
        font-weight: 600;
        line-height: 1.4;
        margin: 0 0 10px;
        color: #111;
    }
    .qs-price { margin-bottom: 14px; }
    .qs-price h4, .qs-price .ps-product__price-sale {
        font-size: 22px;
        font-weight: 700;
        color: #B12704;
        margin: 0;
    }

    /* Qty row */
    .qs-qty-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 16px 0;
    }
    .qs-qty-label {
        font-size: 14px;
        font-weight: 600;
        color: #222;
        white-space: nowrap;
    }
    .qs-qty {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 6px;
        overflow: hidden;
        width: fit-content;
    }
    .qs-qty__btn {
        width: 36px; height: 36px;
        border: none;
        background: #f5f5f5;
        font-size: 18px; font-weight: 400;
        color: #333;
        cursor: pointer;
        transition: background .2s;
        display: flex; align-items: center; justify-content: center;
        line-height: 1;
    }
    .qs-qty__btn:hover { background: #e8e8e8; }
    .qs-qty__input {
        width: 50px; height: 36px;
        border: none;
        border-left: 1px solid #ccc;
        border-right: 1px solid #ccc;
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
    .qs-btn--cart {
        background: #FFD814;
        border-color: #FCD200;
        color: #111;
    }
    .qs-btn--cart:hover { background: #f0c800; }
    .qs-btn--buy {
        background: #FFA41C;
        border-color: #FF8F00;
        color: #111;
    }
    .qs-btn--buy:hover { background: #f09600; }
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
                if (dotsEl) {
                    Array.from(dotsEl.querySelectorAll('.qs-dot')).forEach(function(d, i) {
                        d.classList.toggle('active', i === idx);
                    });
                }
            }

            function go(n) { idx = n; render(); }

            function setTab(tab) {
                const show = tab === 'videos' ? videoSlides : imageSlides;
                const hide = tab === 'videos' ? imageSlides : videoSlides;

                // Detach hidden slides from flex flow without removing
                hide.forEach(function(s) { s.style.display = 'none'; });
                show.forEach(function(s) { s.style.display = ''; });
                active = show;
                idx = 0;
                buildDots();
                render();
            }

            if (prevBtn) prevBtn.addEventListener('click', function() { if (idx > 0) go(idx - 1); });
            if (nextBtn) nextBtn.addEventListener('click', function() { if (idx < active.length - 1) go(idx + 1); });

            pillBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    pillBtns.forEach(function(b) { b.classList.remove('active'); });
                    btn.classList.add('active');
                    setTab(btn.dataset.tab);
                });
            });

            // YouTube / Vimeo facade → inject iframe on click
            gallery.querySelectorAll('.qs-video-facade').forEach(function(facade) {
                facade.addEventListener('click', function() {
                    const src = facade.dataset.src;
                    if (!src) return;
                    const iframe = document.createElement('iframe');
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

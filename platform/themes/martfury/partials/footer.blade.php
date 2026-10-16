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

    {{-- Inline CSS for Quick View & Quick Shop Offcanvas --}}
    @once
    <style>
    /**
     * Quick View & Quick Shop Offcanvas Styles
     * Converts modal to side drawer similar to product details page
     */

    /* Offcanvas Base Styling */
    #product-quickview.offcanvas,
    #quick-shop-modal.offcanvas {
        width: 600px !important;
        max-width: 90vw;
    }

    @media (max-width: 768px) {
        #product-quickview.offcanvas,
        #quick-shop-modal.offcanvas {
            width: 100% !important;
            max-width: 100vw;
        }
    }

    /* Offcanvas Header with Title */
    #product-quickview .offcanvas-header,
    #quick-shop-modal .offcanvas-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e5e5;
        background: #fff;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    #product-quickview .offcanvas-title,
    #quick-shop-modal .offcanvas-title {
        font-size: 20px;
        font-weight: 600;
        color: #0F1111;
        margin: 0;
    }

    #product-quickview .btn-close,
    #quick-shop-modal .btn-close {
        width: 32px;
        height: 32px;
        background-size: 16px;
        opacity: 0.6;
        transition: opacity 0.2s ease;
    }

    #product-quickview .btn-close:hover,
    #quick-shop-modal .btn-close:hover {
        opacity: 1;
    }

    /* Offcanvas Body */
    #product-quickview .offcanvas-body,
    #quick-shop-modal .offcanvas-body {
        padding: 24px;
        overflow-y: auto;
        background: #fff;
    }

    /* Product Content Styling */
    #product-quickview .ps-product--detail,
    #quick-shop-modal .ps-product--detail {
        background: transparent;
        border: none;
        box-shadow: none;
        margin: 0;
        padding: 0;
    }

    /* Quick View Product Header */
    #product-quickview .ps-product__header {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    #product-quickview .ps-product__thumbnail {
        width: 100%;
        margin-bottom: 20px;
    }

    #product-quickview .ps-product__thumbnail img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    #product-quickview .ps-product__info h1 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    #product-quickview .ps-product__info h1 a {
        color: #0F1111;
        text-decoration: none;
    }

    #product-quickview .ps-product__info h1 a:hover {
        color: #C7511F;
    }

    /* Price Styling */
    #product-quickview .ps-product__price,
    #quick-shop-modal .ps-product__price {
        margin-bottom: 16px;
    }

    #product-quickview .ps-product__price h4,
    #quick-shop-modal .ps-product__price h4 {
        font-size: 24px;
        font-weight: 700;
        color: #B12704;
        margin: 0;
    }

    /* Description */
    #product-quickview .ps-product__desc {
        margin-bottom: 16px;
        font-size: 14px;
        line-height: 1.6;
        color: #565959;
    }

    /* Variation Swatches */
    #product-quickview .pr_switch_wrap,
    #quick-shop-modal .pr_switch_wrap {
        margin-bottom: 16px;
    }

    /* Add to Cart Form */
    #product-quickview .add-to-cart-form,
    #quick-shop-modal .add-to-cart-form {
        margin-top: 20px;
    }

    #product-quickview .ps-product__shopping,
    #quick-shop-modal .ps-product__shopping {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    /* Quantity Input */
    #product-quickview figure,
    #quick-shop-modal figure {
        margin: 0;
    }

    #product-quickview figcaption,
    #quick-shop-modal figcaption {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #0F1111;
    }

    #product-quickview .form-group--number,
    #quick-shop-modal .form-group--number {
        display: flex;
        align-items: center;
        border: 1px solid #D5D9D9;
        border-radius: 8px;
        overflow: hidden;
        width: fit-content;
    }

    #product-quickview .form-group--number button,
    #quick-shop-modal .form-group--number button {
        width: 40px;
        height: 40px;
        border: none;
        background: #F0F2F2;
        color: #0F1111;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    #product-quickview .form-group--number button:hover,
    #quick-shop-modal .form-group--number button:hover {
        background: #E3E6E6;
    }

    #product-quickview .form-group--number input,
    #quick-shop-modal .form-group--number input {
        width: 60px;
        height: 40px;
        border: none;
        text-align: center;
        font-size: 16px;
        font-weight: 600;
        background: #fff;
    }

    /* Action Buttons */
    #product-quickview .ps-product__actions,
    #quick-shop-modal .ps-product__actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    #product-quickview .ps-btn,
    #quick-shop-modal .ps-btn {
        flex: 1;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
        border: 1px solid;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    #product-quickview .ps-btn--black,
    #quick-shop-modal .ps-btn--black {
        background: #FFD814;
        border-color: #FCD200;
        color: #0F1111;
    }

    #product-quickview .ps-btn--black:hover,
    #quick-shop-modal .ps-btn--black:hover {
        background: #F7CA00;
        border-color: #F2C200;
    }

    #product-quickview .ps-btn:not(.ps-btn--black),
    #quick-shop-modal .ps-btn:not(.ps-btn--black) {
        background: #FFA41C;
        border-color: #FF8F00;
        color: #0F1111;
    }

    #product-quickview .ps-btn:not(.ps-btn--black):hover,
    #quick-shop-modal .ps-btn:not(.ps-btn--black):hover {
        background: #FA8900;
        border-color: #E77600;
    }

    /* Wishlist & Compare Actions */
    #product-quickview .ps-product__shopping .ps-product__actions,
    #quick-shop-modal .ps-product__shopping .ps-product__actions {
        margin-top: 0;
        justify-content: flex-start;
    }

    #product-quickview .ps-product__shopping .ps-product__actions a,
    #quick-shop-modal .ps-product__shopping .ps-product__actions a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid #D5D9D9;
        border-radius: 8px;
        color: #565959;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    #product-quickview .ps-product__shopping .ps-product__actions a:hover,
    #quick-shop-modal .ps-product__shopping .ps-product__actions a:hover {
        border-color: #0F1111;
        color: #0F1111;
    }

    /* View Full Details Link */
    #product-quickview .ps-product__specification,
    #quick-shop-modal .ps-product__specification {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #e5e5e5;
    }

    #product-quickview .ps-product__specification .report,
    #quick-shop-modal .ps-product__specification .report,
    #product-quickview .btn-link,
    #quick-shop-modal .btn-link {
        color: #007185;
        text-decoration: none;
        font-size: 14px;
        font-weight: 400;
        transition: color 0.2s ease, text-decoration 0.2s ease;
    }

    #product-quickview .ps-product__specification .report:hover,
    #quick-shop-modal .ps-product__specification .report:hover,
    #product-quickview .btn-link:hover,
    #quick-shop-modal .btn-link:hover {
        color: #C7511F;
        text-decoration: underline;
    }

    /* Backdrop */
    .offcanvas-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
    }

    /* Loading State */
    #product-quickview .loading-spinner,
    #quick-shop-modal .loading-spinner {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
    }

    /* Mobile Responsive */
    @media (max-width: 576px) {
        #product-quickview .offcanvas-header,
        #quick-shop-modal .offcanvas-header {
            padding: 16px;
        }
        
        #product-quickview .offcanvas-body,
        #quick-shop-modal .offcanvas-body {
            padding: 16px;
        }
        
        #product-quickview .offcanvas-title,
        #quick-shop-modal .offcanvas-title {
            font-size: 18px;
        }
        
        #product-quickview .ps-product__info h1,
        #quick-shop-modal .ps-product__info h1 {
            font-size: 18px;
        }
        
        #product-quickview .ps-product__actions,
        #quick-shop-modal .ps-product__actions {
            flex-direction: column;
        }
        
        #product-quickview .ps-btn,
        #quick-shop-modal .ps-btn {
            width: 100%;
        }
    }

    /* Smooth Animations */
    .offcanvas.offcanvas-end {
        transition: transform 0.3s ease-in-out;
    }

    .offcanvas.show {
        transform: translateX(0);
    }

    /* Hide old modal styles if any */
    #product-quickview.modal,
    #quick-shop-modal.modal {
        display: none !important;
    }
    </style>
    @endonce

    {{-- Override quick-shop to use offcanvas instead of modal --}}
    <script>
    (function() {
        console.log('[OFFCANVAS DEBUG] Quick view/shop override script loaded');
        
        // Override quick-shop modal behavior to use offcanvas
        $(document).on('click', '[data-bb-toggle="quick-shop"]', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('[OFFCANVAS DEBUG] Quick shop button clicked');
            
            const $btn = $(this);
            const $offcanvas = $('#quick-shop-modal');
            const url = $btn.data('url');
            
            console.log('[OFFCANVAS DEBUG] URL:', url);
            console.log('[OFFCANVAS DEBUG] Offcanvas element:', $offcanvas[0]);
            
            if (!url) {
                console.error('[OFFCANVAS DEBUG] No URL found');
                return;
            }
            
            $.ajax({
                url: url,
                type: 'GET',
                beforeSend: function() {
                    console.log('[OFFCANVAS DEBUG] Loading product...');
                    $btn.addClass('loading');
                    $offcanvas.find('.modal-body').html('<div class="loading-spinner" style="padding: 40px; text-align: center;"><i class="icon-spinner"></i> Loading...</div>');
                },
                success: function(response) {
                    console.log('[OFFCANVAS DEBUG] AJAX success:', response);
                    
                    if (response.error) {
                        alert(response.message || 'Error loading product');
                        return;
                    }
                    
                    // Insert content
                    $offcanvas.find('.modal-body').html(response.data);
                    console.log('[OFFCANVAS DEBUG] Content inserted');
                    
                    // Try to show offcanvas using Bootstrap 5 API
                    try {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Offcanvas) {
                            console.log('[OFFCANVAS DEBUG] Using Bootstrap 5 Offcanvas API');
                            let offcanvasInstance = bootstrap.Offcanvas.getInstance($offcanvas[0]);
                            if (!offcanvasInstance) {
                                offcanvasInstance = new bootstrap.Offcanvas($offcanvas[0]);
                            }
                            offcanvasInstance.show();
                        } else {
                            console.error('[OFFCANVAS DEBUG] Bootstrap Offcanvas not found, trying manual method');
                            $offcanvas.addClass('show');
                            $('body').append('<div class="offcanvas-backdrop fade show"></div>');
                        }
                    } catch(error) {
                        console.error('[OFFCANVAS DEBUG] Error showing offcanvas:', error);
                    }
                    
                    // Update title if product name is available
                    setTimeout(function() {
                        const productName = $offcanvas.find('h1').first().text();
                        if (productName) {
                            $offcanvas.find('.offcanvas-title').text(productName.substring(0, 40) + (productName.length > 40 ? '...' : ''));
                            console.log('[OFFCANVAS DEBUG] Title updated:', productName);
                        }
                    }, 100);
                    
                    // Dispatch event
                    document.dispatchEvent(new CustomEvent('ecommerce.quick-shop.loaded', {
                        detail: { element: $btn[0] }
                    }));
                },
                error: function(xhr) {
                    console.error('[OFFCANVAS DEBUG] AJAX error:', xhr);
                    alert('Failed to load product details');
                },
                complete: function() {
                    $btn.removeClass('loading');
                }
            });
        });
        
        // Test quick view on page load
        console.log('[OFFCANVAS DEBUG] Quick view offcanvas element:', $('#product-quickview')[0]);
        console.log('[OFFCANVAS DEBUG] Quick shop offcanvas element:', $('#quick-shop-modal')[0]);
    })();
    </script>

    <script>
        window.trans = {
            "View All": "{{ __('View All') }}",
            "No reviews!": "{{ __('No reviews!') }}",
        };
        window.siteConfig = window.siteConfig || {};
        window.siteConfig.ajaxCartUrl = "{{ route('public.ajax.cart') }}";
    </script>

    {!! Theme::footer() !!}

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

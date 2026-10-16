/**
 * Owl Carousel Initialization Script
 * Based on simple-slider plugin implementation
 */
(function($) {
    'use strict';
    
    class CarouselManager {
        init() {
            let target = $(document).find('.owl-slider');
            
            if (target.length > 0) {
                target.each(function() {
                    let el = $(this);
                    
                    // Skip if already initialized
                    if (el.hasClass('owl-loaded') || el.data('owl.carousel')) {
                        return;
                    }
                    
                    let dataAuto = el.data('owl-auto'),
                        dataLoop = el.data('owl-loop'),
                        dataSpeed = el.data('owl-speed'),
                        dataGap = el.data('owl-gap'),
                        dataNav = el.data('owl-nav'),
                        dataDots = el.data('owl-dots'),
                        dataAnimateIn = el.data('owl-animate-in') ? el.data('owl-animate-in') : '',
                        dataAnimateOut = el.data('owl-animate-out') ? el.data('owl-animate-out') : '',
                        dataDefaultItem = el.data('owl-item'),
                        dataItemXS = el.data('owl-item-xs'),
                        dataItemSM = el.data('owl-item-sm'),
                        dataItemMD = el.data('owl-item-md'),
                        dataItemLG = el.data('owl-item-lg'),
                        dataItemXL = el.data('owl-item-xl'),
                        dataNavLeft = el.data('owl-nav-left') ? el.data('owl-nav-left') : '<i class="icon-chevron-left"></i>',
                        dataNavRight = el.data('owl-nav-right') ? el.data('owl-nav-right') : '<i class="icon-chevron-right"></i>',
                        duration = el.data('owl-duration'),
                        dataMouseDrag = el.data('owl-mousedrag') === 'on',
                        center = el.data('owl-center');
                    
                    // Count only visible div/article children (exclude script/style)
                    if (el.children('div, article, span, a, img, h1, h2, h3, h4, h5').length >= 1) {
                        el.owlCarousel({
                            rtl: $('body').prop('dir') === 'rtl',
                            animateIn: dataAnimateIn,
                            animateOut: dataAnimateOut,
                            margin: dataGap,
                            autoplay: dataAuto,
                            autoplayTimeout: dataSpeed,
                            autoplayHoverPause: true,
                            loop: dataLoop,
                            nav: dataNav,
                            mouseDrag: dataMouseDrag,
                            touchDrag: true,
                            autoplaySpeed: duration,
                            navSpeed: duration,
                            dotsSpeed: duration,
                            dragEndSpeed: duration,
                            navText: [dataNavLeft, dataNavRight],
                            dots: dataDots,
                            items: dataDefaultItem,
                            center: Boolean(center),
                            responsive: {
                                0: { items: dataItemXS },
                                480: { items: dataItemSM },
                                768: { items: dataItemMD },
                                992: { items: dataItemLG },
                                1200: { items: dataItemXL },
                                1680: { items: dataDefaultItem }
                            }
                        });
                    }
                });
            }
        }
    }
    
    $(function() {
        new CarouselManager().init();
        
        document.addEventListener('shortcode.loaded', function(event) {
            new CarouselManager().init();
        });
    });
    
})(jQuery);

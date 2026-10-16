/**
 * Owl Carousel Initialization Script
 * This script initializes all .owl-slider elements on page load
 */
(function($) {
    'use strict';
    
    function initializeCarousels() {
        let owlSliders = $('.owl-slider');
        
        if (owlSliders.length === 0) {
            return;
        }
        
        owlSliders.each(function() {
            let $this = $(this);
            
            // Skip if already initialized
            if ($this.hasClass('owl-loaded') || $this.data('owl.carousel')) {
                return;
            }
            
            // Check if owlCarousel plugin is available
            if (typeof $.fn.owlCarousel === 'undefined') {
                console.error('[Carousel Init] Owl Carousel plugin not loaded');
                return;
            }
            
            // Get configuration from data attributes
            let config = {
                rtl: $('body').prop('dir') === 'rtl',
                animateIn: $this.data('owl-animate-in') || '',
                animateOut: $this.data('owl-animate-out') || '',
                margin: $this.data('owl-gap') || 0,
                autoplay: $this.data('owl-auto') || false,
                autoplayTimeout: $this.data('owl-speed') || 5000,
                autoplayHoverPause: true,
                loop: $this.data('owl-loop') || false,
                nav: $this.data('owl-nav') || false,
                mouseDrag: $this.data('owl-mousedrag') === 'on',
                touchDrag: true,
                autoplaySpeed: $this.data('owl-duration') || 1000,
                navSpeed: $this.data('owl-duration') || 1000,
                dotsSpeed: $this.data('owl-duration') || 1000,
                dragEndSpeed: $this.data('owl-duration') || 1000,
                navText: [
                    $this.data('owl-nav-left') || "<i class='icon-chevron-left'></i>",
                    $this.data('owl-nav-right') || "<i class='icon-chevron-right'></i>"
                ],
                dots: $this.data('owl-dots') || false,
                items: $this.data('owl-item') || 1,
                responsive: {
                    0: { items: $this.data('owl-item-xs') || 1 },
                    480: { items: $this.data('owl-item-sm') || 2 },
                    768: { items: $this.data('owl-item-md') || 3 },
                    992: { items: $this.data('owl-item-lg') || 4 },
                    1200: { items: $this.data('owl-item-xl') || 5 },
                    1680: { items: $this.data('owl-item') || 1 }
                }
            };
            
            // Remove style and script tags before initializing carousel
            $this.children('style, script').remove();
            
            // Count actual carousel items
            let itemCount = $this.children().length;
            
            if (itemCount >= 2) {
                $this.addClass('owl-carousel').owlCarousel(config);
            }
        });
    }
    
    // Initialize on document ready
    $(document).ready(function() {
        initializeCarousels();
    });
    
    // Re-initialize after AJAX content loads
    document.addEventListener('shortcode.loaded', function() {
        setTimeout(initializeCarousels, 100);
    });
    
    // Re-initialize for lazy-loaded content
    $(document).on('lazyContentLoaded', function() {
        setTimeout(initializeCarousels, 100);
    });
    
})(jQuery);

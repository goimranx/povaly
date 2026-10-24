/**
 * Carousel Debug Script
 * Tests what's preventing Owl Carousel from working
 */
(function ($) {
    'use strict';

    console.log('🔍 Carousel Debug Script Loaded');

    // Test 1: Check if Owl Carousel library is loaded
    $(document).ready(function() {
        console.log('🔍 jQuery loaded:', !!$);
        console.log('🔍 Owl Carousel plugin available:', !!$.fn.owlCarousel);
        
        // Test 2: Check if carousel elements exist
        var $dealCarousel = $('.ps-carousel--deal');
        var $offersCarousel = $('.ps-carousel--offers');
        
        console.log('🔍 Deal carousel element found:', $dealCarousel.length);
        console.log('🔍 Offers carousel element found:', $offersCarousel.length);
        
        if ($dealCarousel.length) {
            console.log('🔍 Deal carousel classes:', $dealCarousel.attr('class'));
            console.log('🔍 Deal carousel has owl-loaded:', $dealCarousel.hasClass('owl-loaded'));
            console.log('🔍 Deal carousel children count:', $dealCarousel.children().length);
        }
        
        if ($offersCarousel.length) {
            console.log('🔍 Offers carousel classes:', $offersCarousel.attr('class'));
            console.log('🔍 Offers carousel has owl-loaded:', $offersCarousel.hasClass('owl-loaded'));
            console.log('🔍 Offers carousel children count:', $offersCarousel.children().length);
        }
        
        // Test 3: Check if event propagation is being blocked
        console.log('🔍 Testing event propagation...');
        
        $('.owl-slider').each(function(index) {
            var $carousel = $(this);
            var carouselName = $carousel.hasClass('ps-carousel--deal') ? 'Deal' : 'Offers';
            
            console.log(`🔍 Testing ${carouselName} carousel #${index}`);
            
            // Add test event handlers
            $carousel.on('mousedown', function(e) {
                console.log(`✅ ${carouselName}: mousedown event received`, e.target);
            });
            
            $carousel.on('click', function(e) {
                console.log(`✅ ${carouselName}: click event received`, e.target);
            });
            
            $carousel.on('touchstart', function(e) {
                console.log(`✅ ${carouselName}: touchstart event received`, e.target);
            });
        });
        
        // Test 4: Check for stopPropagation calls
        var originalStopProp = Event.prototype.stopPropagation;
        Event.prototype.stopPropagation = function() {
            console.log('⚠️ stopPropagation called on:', this.type, 'target:', this.target);
            console.trace('Call stack:');
            originalStopProp.call(this);
        };
        
        // Test 5: Try to manually initialize a carousel
        setTimeout(function() {
            console.log('🔍 Attempting manual carousel initialization...');
            
            if ($dealCarousel.length && !$dealCarousel.hasClass('owl-loaded')) {
                console.log('🔧 Manually initializing Deal carousel...');
                try {
                    $dealCarousel.owlCarousel({
                        items: 1,
                        loop: true,
                        nav: false,
                        dots: false,
                        autoplay: false
                    });
                    console.log('✅ Deal carousel manually initialized');
                } catch(e) {
                    console.error('❌ Failed to initialize Deal carousel:', e);
                }
            }
            
            if ($offersCarousel.length && !$offersCarousel.hasClass('owl-loaded')) {
                console.log('🔧 Manually initializing Offers carousel...');
                try {
                    $offersCarousel.owlCarousel({
                        items: 4,
                        loop: true,
                        nav: true,
                        dots: false,
                        autoplay: false,
                        margin: 10,
                        responsive: {
                            0: { items: 1 },
                            576: { items: 2 },
                            768: { items: 3 },
                            992: { items: 3 },
                            1200: { items: 4 }
                        }
                    });
                    console.log('✅ Offers carousel manually initialized');
                } catch(e) {
                    console.error('❌ Failed to initialize Offers carousel:', e);
                }
            }
        }, 2000);
        
        // Test 6: Check for JS errors
        window.addEventListener('error', function(e) {
            console.error('❌ JavaScript Error:', e.message, 'at', e.filename, e.lineno);
        });
        
        // Test 7: Check what's inside the carousel
        setTimeout(function() {
            console.log('🔍 Checking carousel content structure...');
            
            if ($dealCarousel.length) {
                var $dealChildren = $dealCarousel.children();
                console.log('🔍 Deal carousel direct children:', $dealChildren.length);
                $dealChildren.each(function(i) {
                    console.log(`  - Child ${i}:`, $(this).prop('tagName'), $(this).attr('class'));
                });
            }
            
            if ($offersCarousel.length) {
                var $offersChildren = $offersCarousel.children();
                console.log('🔍 Offers carousel direct children:', $offersChildren.length);
                $offersChildren.each(function(i) {
                    console.log(`  - Child ${i}:`, $(this).prop('tagName'), $(this).attr('class'));
                });
            }
        }, 1000);
        
        // Test 8: Monitor when Owl tries to initialize
        var originalOwl = $.fn.owlCarousel;
        $.fn.owlCarousel = function(options) {
            console.log('🦉 Owl Carousel called on:', this.selector || this[0], 'with options:', options);
            var result = originalOwl.apply(this, arguments);
            console.log('🦉 Owl Carousel result:', result);
            return result;
        };
    });
    
})(jQuery);

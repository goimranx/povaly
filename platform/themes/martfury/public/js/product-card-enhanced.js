/**
 * product-card-enhanced.js
 * Loaded via config.php after main.js — handles ec-card button interactions.
 */
(function ($) {
    'use strict';

    // ── Tooltip smart positioning ─────────────────────────────────────────
    // Default: below. If not enough space below, show above.
    $(document).on('mouseenter', '.ec-card__info-icon', function () {
        var $icon = $(this);
        var rect  = this.getBoundingClientRect();
        var spaceBelow = window.innerHeight - rect.bottom;
        var spaceAbove = rect.top;
        // Tooltip height approx 80px — show above if less than 100px below
        if (spaceBelow < 100 && spaceAbove > spaceBelow) {
            $icon.addClass('tooltip-above');
        } else {
            $icon.removeClass('tooltip-above');
        }
    });

    // ── Floating capsule toggle (touch devices) ──────────────────────────
    $(document).on('click', '.ec-card__float-toggle', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).closest('.ec-card__float-capsule').toggleClass('active');
    });

    // Close capsule when clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.ec-card__float').length) {
            $('.ec-card__float-capsule').removeClass('active');
        }
    });

    // Prevent card overlay link firing when clicking interactive elements
    $(document).on('click', '.ec-card__float-btn, .ec-card__actions, .ec-card__btn, .ec-card__rating, .ec-card__title, .ec-card__seller, .ec-card__qty-control', function (e) {
        e.stopPropagation();
    });

    // ── Cart update after add-to-cart ─────────────────────────────────────
    // Suppress toast notification and transform button to quantity control
    document.addEventListener('ecommerce.cart.added', function (e) {
        // Update mini-cart
        if (typeof window.loadCart === 'function') {
            window.loadCart();
        }

        // Transform the button to quantity control
        var productId = e.detail && e.detail.product ? e.detail.product.id : null;
        if (productId) {
            transformToQuantityControl(productId, 1);
        }
    }, true); // Use capture to prevent default toast

    // ── Quantity Control Functions ────────────────────────────────────────
    
    function transformToQuantityControl(productId, quantity) {
        var $btn = $('.ec-card__btn--cart[data-id="' + productId + '"]');
        if (!$btn.length) return;

        var $actions = $btn.closest('.ec-card__actions');
        
        // Create quantity control HTML
        var iconLeft = quantity === 1 
            ? '<i class="ph ph-trash"></i>' 
            : '<i class="ph ph-minus"></i>';
        
        var controlHtml = 
            '<div class="ec-card__qty-control" data-id="' + productId + '">' +
                '<button type="button" class="ec-card__qty-btn ec-card__qty-btn--minus" data-action="decrease">' + iconLeft + '</button>' +
                '<span class="ec-card__qty-text">' + quantity + ' at cart</span>' +
                '<button type="button" class="ec-card__qty-btn ec-card__qty-btn--plus" data-action="increase"><i class="ph ph-plus"></i></button>' +
            '</div>';
        
        $actions.html(controlHtml);
    }

    // ── Quantity Control Click Handlers ───────────────────────────────────
    
    $(document).on('click', '.ec-card__qty-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $btn = $(this);
        var $control = $btn.closest('.ec-card__qty-control');
        var productId = $control.data('id');
        var action = $btn.data('action');
        var $qtyText = $control.find('.ec-card__qty-text');
        var currentQty = parseInt($qtyText.text().match(/\d+/)[0]);
        
        if (action === 'decrease') {
            if (currentQty === 1) {
                // Remove from cart
                removeFromCart(productId, $control);
            } else {
                // Decrease quantity
                updateCartQuantity(productId, currentQty - 1, $control);
            }
        } else if (action === 'increase') {
            // Increase quantity
            updateCartQuantity(productId, currentQty + 1, $control);
        }
    });

    function updateCartQuantity(productId, newQty, $control) {
        var $qtyText = $control.find('.ec-card__qty-text');
        var $minusBtn = $control.find('.ec-card__qty-btn--minus');
        
        $.ajax({
            url: '/cart/update/' + productId,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                qty: newQty
            },
            success: function (response) {
                // Update quantity text
                $qtyText.text(newQty + ' at cart');
                
                // Update icon (trash vs minus)
                if (newQty === 1) {
                    $minusBtn.html('<i class="ph ph-trash"></i>');
                } else {
                    $minusBtn.html('<i class="ph ph-minus"></i>');
                }
                
                // Update mini-cart
                if (typeof window.loadCart === 'function') {
                    window.loadCart();
                }
            },
            error: function () {
                // Silently fail or show minimal error
                console.error('Failed to update cart quantity');
            }
        });
    }

    function removeFromCart(productId, $control) {
        var $actions = $control.closest('.ec-card__actions');
        
        $.ajax({
            url: '/cart/remove/' + productId,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Transform back to "Add to Cart" button
                var btnHtml = '<button type="button" class="ec-card__btn ec-card__btn--cart" ' +
                    'data-id="' + productId + '" ' +
                    'data-url="/ajax/cart/add">' +
                    'Add to Cart</button>';
                
                $actions.html(btnHtml);
                
                // Update mini-cart
                if (typeof window.loadCart === 'function') {
                    window.loadCart();
                }
            },
            error: function () {
                console.error('Failed to remove from cart');
            }
        });
    }

    // ── Initialize: Check cart state on page load ─────────────────────────
    // If product is already in cart, show quantity control
    $(document).ready(function() {
        // Get cart data and transform buttons for products already in cart
        if (typeof window.cartItems !== 'undefined' && window.cartItems) {
            Object.keys(window.cartItems).forEach(function(productId) {
                var qty = window.cartItems[productId];
                transformToQuantityControl(productId, qty);
            });
        }
    });

})(jQuery);

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

        // Check if this was from an enhanced card button
        var element = e.detail && e.detail.element ? e.detail.element : null;
        if (element && element.hasClass('ec-card__btn--cart')) {
            var productId = element.data('id');
            if (productId) {
                transformToQuantityControl(productId, 1);
            }
        }
    });

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
        
        // Disable buttons during operation
        $control.find('.ec-card__qty-btn').prop('disabled', true);
        
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
        
        // Use Laravel's Cart session directly by parsing the mini-cart HTML
        // Or use the cart count endpoint and try to get rowId from data attributes
        
        // For now, let's use a simpler approach: add/remove items to change quantity
        var currentQty = parseInt($qtyText.text().match(/\d+/)[0]);
        var diff = newQty - currentQty;
        
        if (diff === 0) {
            $control.find('.ec-card__qty-btn').prop('disabled', false);
            return;
        }
        
        // We'll add the product multiple times or use the update endpoint with product ID
        // Let's try posting directly to cart update with product ID
        $.ajax({
            url: '/cart/update',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: productId,
                qty: newQty
            },
            dataType: 'json',
            success: function(response) {
                console.log('Cart updated successfully', response);
                
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
                
                // Re-enable buttons
                $control.find('.ec-card__qty-btn').prop('disabled', false);
            },
            error: function(xhr) {
                console.error('Failed to update cart quantity', xhr.responseText);
                // Try alternative: remove and re-add
                if (diff < 0) {
                    // Decrease by removing and re-adding
                    removeAndReAdd(productId, newQty, $control);
                } else {
                    // Increase by adding more
                    addMore(productId, diff, $control, newQty);
                }
            }
        });
    }
    
    function addMore(productId, qty, $control, targetQty) {
        $.ajax({
            url: '/ajax/cart/add-to-cart',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: productId,
                qty: qty
            },
            dataType: 'json',
            success: function(response) {
                var $qtyText = $control.find('.ec-card__qty-text');
                $qtyText.text(targetQty + ' at cart');
                
                var $minusBtn = $control.find('.ec-card__qty-btn--minus');
                if (targetQty === 1) {
                    $minusBtn.html('<i class="ph ph-trash"></i>');
                } else {
                    $minusBtn.html('<i class="ph ph-minus"></i>');
                }
                
                if (typeof window.loadCart === 'function') {
                    window.loadCart();
                }
                
                $control.find('.ec-card__qty-btn').prop('disabled', false);
            },
            error: function(xhr) {
                console.error('Failed to add more', xhr.responseText);
                $control.find('.ec-card__qty-btn').prop('disabled', false);
            }
        });
    }
    
    function removeAndReAdd(productId, newQty, $control) {
        // This is complex without rowId, for now just refresh the page or show message
        console.error('Cannot decrease quantity without rowId - need to implement cart item lookup');
        $control.find('.ec-card__qty-btn').prop('disabled', false);
    }

    function removeFromCart(productId, $control) {
        var $actions = $control.closest('.ec-card__actions');
        
        // We need to find the cart item by product ID
        // Since /ajax/cart doesn't return items, we'll need to query the cart page or use a different approach
        // For now, let's try to remove by making a request to find it first
        
        // Alternative: use the cart HTML to find the remove link
        $.ajax({
            url: '/ajax/cart',
            method: 'GET',
            success: function(cartResponse) {
                console.log('Cart HTML response:', cartResponse);
                
                // Parse HTML to find remove link for this product
                var $html = $(cartResponse.data.html);
                var $removeLink = null;
                
                // Find the remove link - check for data-url attribute
                $html.find('[data-url*="/cart/remove/"]').each(function() {
                    var $link = $(this);
                    // Check if this link belongs to our product
                    // We need to check the product in the same row/container
                    var $container = $link.closest('li, tr, .ps-product');
                    var productLink = $container.find('a[href*="' + productId + '"]');
                    
                    if (productLink.length > 0 || $container.find('[data-id="' + productId + '"]').length > 0) {
                        $removeLink = $link;
                        return false; // break
                    }
                });
                
                if (!$removeLink || !$removeLink.length) {
                    console.error('Remove link not found in cart HTML for product', productId);
                    $control.find('.ec-card__qty-btn').prop('disabled', false);
                    return;
                }
                
                var removeUrl = $removeLink.data('url') || $removeLink.attr('href');
                console.log('Found remove URL:', removeUrl);
                
                $.ajax({
                    url: removeUrl,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Item removed successfully', response);
                        
                        // Transform back to "Add to Cart" button
                        var btnHtml = '<button type="button" class="ec-card__btn ec-card__btn--cart" ' +
                            'data-id="' + productId + '" ' +
                            'data-url="/ajax/cart/add-to-cart" ' +
                            'data-show-toast-on-success="false" ' +
                            'data-bb-toggle="add-to-cart">' +
                            'Add to Cart</button>';
                        
                        $actions.html(btnHtml);
                        
                        // Update mini-cart
                        if (typeof window.loadCart === 'function') {
                            window.loadCart();
                        }
                    },
                    error: function(xhr) {
                        console.error('Failed to remove from cart', xhr.responseText);
                        $control.find('.ec-card__qty-btn').prop('disabled', false);
                    }
                });
            },
            error: function(xhr) {
                console.error('Failed to get cart HTML', xhr.responseText);
                $control.find('.ec-card__qty-btn').prop('disabled', false);
            }
        });
    }

    // ── Initialize: Check cart state on page load ─────────────────────────
    // Sync with cart on page load
    $(document).ready(function() {
        syncCartState();
    });
    
    // ── Listen to cart changes from other sources ─────────────────────────
    document.addEventListener('ecommerce.cart.removed', function(e) {
        var productId = e.detail && e.detail.productId ? e.detail.productId : null;
        if (productId) {
            // Reset button to "Add to Cart"
            var $control = $('.ec-card__qty-control[data-id="' + productId + '"]');
            if ($control.length) {
                var $actions = $control.closest('.ec-card__actions');
                var btnHtml = '<button type="button" class="ec-card__btn ec-card__btn--cart" ' +
                    'data-id="' + productId + '" ' +
                    'data-url="/ajax/cart/add-to-cart" ' +
                    'data-show-toast-on-success="false" ' +
                    'data-bb-toggle="add-to-cart">' +
                    'Add to Cart</button>';
                $actions.html(btnHtml);
            }
        }
    });
    
    document.addEventListener('ecommerce.cart.updated', function(e) {
        // Sync cart state after updates
        syncCartState();
    });
    
    function syncCartState() {
        $.ajax({
            url: '/ajax/cart',
            method: 'GET',
            success: function(cartResponse) {
                console.log('Syncing cart state with HTML parsing');
                
                if (!cartResponse || !cartResponse.data || !cartResponse.data.html) {
                    console.log('No cart HTML available');
                    return;
                }
                
                var $html = $(cartResponse.data.html);
                var cartItems = {};
                
                // Parse the cart HTML to extract product IDs and quantities
                // Look for input[name="items[...][qty]"] or data-id attributes
                $html.find('[data-id]').each(function() {
                    var $el = $(this);
                    var productId = $el.data('id');
                    if (productId) {
                        // Find qty in the same container
                        var $container = $el.closest('li, tr, .ps-product');
                        var $qtyInput = $container.find('input[name*="[qty]"], .qty-input, input.qty');
                        var qty = $qtyInput.length ? parseInt($qtyInput.val()) : 1;
                        
                        if (qty > 0) {
                            cartItems[productId] = qty;
                        }
                    }
                });
                
                console.log('Cart items found:', cartItems);
                
                // Update all enhanced cards
                $('.ec-card__btn--cart').each(function() {
                    var $btn = $(this);
                    var productId = $btn.data('id');
                    
                    if (cartItems[productId]) {
                        // Product is in cart, show quantity control
                        console.log('Transforming button for product', productId, 'qty:', cartItems[productId]);
                        transformToQuantityControl(productId, cartItems[productId]);
                    }
                });
                
                // Update existing quantity controls
                $('.ec-card__qty-control').each(function() {
                    var $control = $(this);
                    var productId = $control.data('id');
                    
                    if (cartItems[productId]) {
                        // Update quantity
                        var qty = cartItems[productId];
                        $control.find('.ec-card__qty-text').text(qty + ' at cart');
                        
                        var $minusBtn = $control.find('.ec-card__qty-btn--minus');
                        if (qty === 1) {
                            $minusBtn.html('<i class="ph ph-trash"></i>');
                        } else {
                            $minusBtn.html('<i class="ph ph-minus"></i>');
                        }
                    } else {
                        // Product removed from cart, show button
                        console.log('Resetting button for product', productId);
                        var $actions = $control.closest('.ec-card__actions');
                        var btnHtml = '<button type="button" class="ec-card__btn ec-card__btn--cart" ' +
                            'data-id="' + productId + '" ' +
                            'data-url="/ajax/cart/add-to-cart" ' +
                            'data-show-toast-on-success="false" ' +
                            'data-bb-toggle="add-to-cart">' +
                            'Add to Cart</button>';
                        $actions.html(btnHtml);
                    }
                });
            },
            error: function(xhr) {
                console.error('Failed to sync cart state', xhr.responseText);
            }
        });
    }

})(jQuery);

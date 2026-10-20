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
        
        // Create quantity control HTML with editable input and text
        var iconLeft = quantity === 1 
            ? '<i class="ph ph-trash"></i>' 
            : '<i class="ph ph-minus"></i>';
        
        var controlHtml = 
            '<div class="ec-card__qty-control" data-id="' + productId + '">' +
                '<button type="button" class="ec-card__qty-btn ec-card__qty-btn--minus" data-action="decrease">' + iconLeft + '</button>' +
                '<div class="ec-card__qty-display">' +
                    '<input type="number" class="ec-card__qty-input" value="' + quantity + '" min="1" />' +
                    '<span class="ec-card__qty-label">at cart</span>' +
                '</div>' +
                '<button type="button" class="ec-card__qty-btn ec-card__qty-btn--plus" data-action="increase"><i class="ph ph-plus"></i></button>' +
            '</div>';
        
        $actions.html(controlHtml);
    }

    // ── Quantity Control Click Handlers ───────────────────────────────────
    
    // Handle +/- button clicks
    $(document).on('click', '.ec-card__qty-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        var $btn = $(this);
        var $control = $btn.closest('.ec-card__qty-control');
        var productId = $control.data('id');
        var action = $btn.data('action');
        var $qtyInput = $control.find('.ec-card__qty-input');
        var currentQty = parseInt($qtyInput.val()) || 1;
        
        if (action === 'decrease') {
            if (currentQty === 1) {
                removeFromCart(productId, $control);
            } else {
                updateCartQuantity(productId, currentQty - 1, $control);
            }
        } else if (action === 'increase') {
            updateCartQuantity(productId, currentQty + 1, $control);
        }
    });
    
    // Handle direct input change
    $(document).on('change', '.ec-card__qty-input', function(e) {
        var $input = $(this);
        var $control = $input.closest('.ec-card__qty-control');
        var productId = $control.data('id');
        var newQty = parseInt($input.val()) || 1;
        
        // Ensure minimum qty is 1
        if (newQty < 1) {
            newQty = 1;
            $input.val(1);
        }
        
        updateCartQuantity(productId, newQty, $control);
    });
    
    // Handle input blur to update if changed
    $(document).on('blur', '.ec-card__qty-input', function(e) {
        $(this).trigger('change');
    });

    function updateCartQuantity(productId, newQty, $control) {
        $control.find('.ec-card__qty-btn, .ec-card__qty-input').prop('disabled', true);
        
        // Get slug from the card
        var $card = $control.closest('.ec-card');
        var productSlug = $card.find('[data-slug]').data('slug');
        
        // Get cart to find the rowId
        $.ajax({
            url: '/ajax/cart',
            method: 'GET',
            success: function(cartResponse) {
                if (!cartResponse || !cartResponse.data || !cartResponse.data.html) {
                    console.error('No cart HTML available');
                    $control.find('.ec-card__qty-btn, .ec-card__qty-input').prop('disabled', false);
                    return;
                }
                
                var $html = $(cartResponse.data.html);
                var rowId = null;
                
                // Find the cart item by matching product URL
                $html.find('.ps-product--cart-mobile').each(function() {
                    var $item = $(this);
                    var $productLink = $item.find('.ps-product__content > a[href*="/products/"]').first();
                    
                    if (!$productLink.length) return;
                    
                    var productUrl = $productLink.attr('href');
                    var urlParts = productUrl.split('/products/');
                    if (urlParts.length < 2) return;
                    
                    var productIdentifier = urlParts[1].replace(/\/$/, '');
                    
                    // Match by slug first (most reliable), then by ID
                    if (productIdentifier == productSlug || productIdentifier == productId) {
                        // Extract rowId from remove URL
                        var $removeLink = $item.find('.ps-product__remove[data-url]');
                        var removeUrl = $removeLink.data('url') || $removeLink.attr('data-url');
                        if (removeUrl) {
                            // URL format: /cart/remove/a855706bc817b0c0edcef8af347f0b93
                            var parts = removeUrl.split('/cart/remove/');
                            if (parts.length > 1) {
                                rowId = parts[1];
                            }
                        }
                        return false; // break
                    }
                });
                
                if (!rowId) {
                    console.error('Could not find rowId for product', productId, 'slug:', productSlug);
                    $control.find('.ec-card__qty-btn, .ec-card__qty-input').prop('disabled', false);
                    return;
                }
                
                // Update cart using proper format
                // Format: items[0][rowId]=xxx&items[0][values][qty]=2
                var formData = {};
                formData['_token'] = $('meta[name="csrf-token"]').attr('content');
                formData['items[0][rowId]'] = rowId;
                formData['items[0][values][qty]'] = newQty;
                
                $.ajax({
                    url: '/cart/update',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        // Update UI
                        var $qtyInput = $control.find('.ec-card__qty-input');
                        $qtyInput.val(newQty);
                        
                        var $minusBtn = $control.find('.ec-card__qty-btn--minus');
                        if (newQty === 1) {
                            $minusBtn.html('<i class="ph ph-trash"></i>');
                        } else {
                            $minusBtn.html('<i class="ph ph-minus"></i>');
                        }
                        
                        // Update mini-cart
                        if (typeof window.loadCart === 'function') {
                            window.loadCart();
                        }
                        
                        $control.find('.ec-card__qty-btn, .ec-card__qty-input').prop('disabled', false);
                    },
                    error: function(xhr) {
                        console.error('Failed to update cart', xhr.responseText);
                        $control.find('.ec-card__qty-btn, .ec-card__qty-input').prop('disabled', false);
                    }
                });
            },
            error: function(xhr) {
                console.error('Failed to get cart', xhr.responseText);
                $control.find('.ec-card__qty-btn, .ec-card__qty-input').prop('disabled', false);
            }
        });
    }

    function removeFromCart(productId, $control) {
        var $actions = $control.closest('.ec-card__actions');
        
        // Get slug from the card
        var $card = $control.closest('.ec-card');
        var productSlug = $card.find('[data-slug]').data('slug');
        
        // Get cart to find the remove URL
        $.ajax({
            url: '/ajax/cart',
            method: 'GET',
            success: function(cartResponse) {
                if (!cartResponse || !cartResponse.data || !cartResponse.data.html) {
                    console.error('No cart HTML available for remove operation');
                    $control.find('.ec-card__qty-btn').prop('disabled', false);
                    return;
                }
                
                var $html = $(cartResponse.data.html);
                var removeUrl = null;
                
                // Parse cart items to find remove URL
                $html.find('.ps-product--cart-mobile').each(function() {
                    var $item = $(this);
                    var $productLink = $item.find('.ps-product__content > a[href*="/products/"]').first();
                    
                    if (!$productLink.length) return;
                    
                    var productUrl = $productLink.attr('href');
                    var urlParts = productUrl.split('/products/');
                    if (urlParts.length < 2) return;
                    
                    var productIdentifier = urlParts[1].replace(/\/$/, '');
                    
                    // Match by slug first, then ID
                    if (productIdentifier == productSlug || productIdentifier == productId) {
                        var $removeLink = $item.find('.ps-product__remove[data-url]');
                        removeUrl = $removeLink.data('url') || $removeLink.attr('data-url');
                        return false; // break
                    }
                });
                
                if (!removeUrl) {
                    console.error('Remove link not found for product', productId, 'slug:', productSlug);
                    $control.find('.ec-card__qty-btn').prop('disabled', false);
                    return;
                }
                
                $.ajax({
                    url: removeUrl,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // Transform back to "Add to Cart" button
                        var btnHtml = '<button type="button" class="ec-card__btn ec-card__btn--cart" ' +
                            'data-id="' + productId + '" ' +
                            (productSlug ? 'data-slug="' + productSlug + '" ' : '') +
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
                if (!cartResponse || !cartResponse.data || !cartResponse.data.html) {
                    return;
                }
                
                var $html = $(cartResponse.data.html);
                var cartItems = {};
                
                // Parse cart items by finding product containers
                $html.find('.ps-product--cart-mobile').each(function() {
                    var $item = $(this);
                    var $productLink = $item.find('.ps-product__content > a[href*="/products/"]').first();
                    
                    if (!$productLink.length) return;
                    
                    var productUrl = $productLink.attr('href');
                    var urlParts = productUrl.split('/products/');
                    if (urlParts.length < 2) return;
                    
                    var productIdentifier = urlParts[1].replace(/\/$/, '');
                    
                    // Find quantity in the text "X x" format
                    var $qtyText = $item.find('.ps-product__content p small .d-inline-block');
                    var qtyMatch = $qtyText.text().trim().match(/^(\d+)\s*x$/);
                    var qty = qtyMatch ? parseInt(qtyMatch[1]) : 1;
                    
                    // Find remove link
                    var $removeLink = $item.find('.ps-product__remove[data-url]');
                    var removeUrl = $removeLink.data('url') || $removeLink.attr('data-url');
                    
                    if (productIdentifier && qty > 0) {
                        cartItems[productIdentifier] = {
                            qty: qty,
                            removeUrl: removeUrl
                        };
                    }
                });
                
                // Update all enhanced cards
                $('.ec-card__btn--cart').each(function() {
                    var $btn = $(this);
                    var productId = $btn.data('id');
                    var productSlug = $btn.data('slug');
                    
                    if (!productId && !productSlug) return;
                    
                    // Try to find cart item by slug first (most reliable), then by ID
                    var cartItem = cartItems[productSlug] || cartItems[productId];
                    
                    if (cartItem && cartItem.qty) {
                        transformToQuantityControl(productId, cartItem.qty);
                    }
                });
                
                // Update existing quantity controls
                $('.ec-card__qty-control').each(function() {
                    var $control = $(this);
                    var productId = $control.data('id');
                    
                    if (!productId) return;
                    
                    // Find the original button to get its slug
                    var $actions = $control.closest('.ec-card__actions');
                    var $card = $actions.closest('.ec-card');
                    var $originalLink = $card.find('a[href*="/products/"]').first();
                    var productSlug = null;
                    
                    if ($originalLink.length) {
                        var href = $originalLink.attr('href');
                        var parts = href.split('/products/');
                        if (parts.length > 1) {
                            productSlug = parts[1].replace(/\/$/, '');
                        }
                    }
                    
                    var cartItem = cartItems[productSlug] || cartItems[productId];
                    
                    if (cartItem && cartItem.qty) {
                        var qty = cartItem.qty;
                        $control.find('.ec-card__qty-input').val(qty);
                        
                        var $minusBtn = $control.find('.ec-card__qty-btn--minus');
                        if (qty === 1) {
                            $minusBtn.html('<i class="ph ph-trash"></i>');
                        } else {
                            $minusBtn.html('<i class="ph ph-minus"></i>');
                        }
                    } else {
                        var btnHtml = '<button type="button" class="ec-card__btn ec-card__btn--cart" ' +
                            'data-id="' + productId + '" ' +
                            (productSlug ? 'data-slug="' + productSlug + '" ' : '') +
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

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
                    
                    // Check if this matches our product
                    if (productIdentifier == productId || 
                        productIdentifier.indexOf(productId) > -1 || 
                        productIdentifier.endsWith('-' + productId)) {
                        
                        var $removeLink = $item.find('.ps-product__remove[data-url]');
                        removeUrl = $removeLink.data('url') || $removeLink.attr('data-url');
                        console.log('Found remove URL for product', productId, ':', removeUrl);
                        return false; // break
                    }
                });
                
                if (!removeUrl) {
                    console.error('Remove link not found in cart HTML for product', productId);
                    $control.find('.ec-card__qty-btn').prop('disabled', false);
                    return;
                }
                
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
                console.log('=== CART SYNC START ===');
                console.log('Cart response:', cartResponse);
                
                if (!cartResponse || !cartResponse.data || !cartResponse.data.html) {
                    console.log('No cart HTML available');
                    return;
                }
                
                // Log the actual HTML content
                console.log('Cart HTML length:', cartResponse.data.html.length);
                console.log('Cart HTML (full):', cartResponse.data.html);
                
                var $html = $(cartResponse.data.html);
                console.log('Parsed jQuery elements:', $html.length);
                
                var cartItems = {};
                
                // Parse cart items by finding product containers
                // Each cart item is in a .ps-product--cart-mobile div
                $html.find('.ps-product--cart-mobile').each(function() {
                    var $item = $(this);
                    
                    // Find the product link to extract product ID or slug
                    var $productLink = $item.find('.ps-product__content > a[href*="/products/"]').first();
                    
                    if (!$productLink.length) {
                        console.log('No product link found in cart item');
                        return; // continue
                    }
                    
                    var productUrl = $productLink.attr('href');
                    console.log('Product URL:', productUrl);
                    
                    // Try to extract product ID from URL or use the slug
                    // URL format: https://povaly.com/products/demo-product or /products/136
                    var urlParts = productUrl.split('/products/');
                    if (urlParts.length < 2) {
                        console.log('Could not parse product URL:', productUrl);
                        return;
                    }
                    
                    var productIdentifier = urlParts[1].replace(/\/$/, ''); // Remove trailing slash
                    console.log('Product identifier from URL:', productIdentifier);
                    
                    // Find quantity in the text "X x" format
                    var $qtyText = $item.find('.ps-product__content p small .d-inline-block');
                    var qtyMatch = $qtyText.text().trim().match(/^(\d+)\s*x$/);
                    var qty = qtyMatch ? parseInt(qtyMatch[1]) : 1;
                    
                    console.log('Product quantity:', qty);
                    
                    // Find remove link to store rowId for later use
                    var $removeLink = $item.find('.ps-product__remove[data-url]');
                    var removeUrl = $removeLink.data('url') || $removeLink.attr('data-url');
                    
                    console.log('Remove URL:', removeUrl);
                    
                    if (productIdentifier && qty > 0) {
                        cartItems[productIdentifier] = {
                            qty: qty,
                            removeUrl: removeUrl
                        };
                        console.log('Added to cart items:', productIdentifier, cartItems[productIdentifier]);
                    }
                });
                
                console.log('Cart items parsed:', cartItems);
                console.log('Number of products in cart:', Object.keys(cartItems).length);
                console.log('=== CART SYNC PARSING COMPLETE ===');
                
                // Update all enhanced cards
                $('.ec-card__btn--cart').each(function() {
                    var $btn = $(this);
                    var productId = $btn.data('id');
                    var productSlug = $btn.data('slug');
                    
                    if (!productId && !productSlug) return;
                    
                    console.log('Checking button - ID:', productId, 'Slug:', productSlug);
                    
                    // Try to find cart item by slug first (most reliable), then by ID
                    var cartItem = cartItems[productSlug] || cartItems[productId];
                    
                    console.log('Cart item found:', !!cartItem, cartItem);
                    
                    if (cartItem && cartItem.qty) {
                        // Product is in cart, show quantity control
                        console.log('Transforming button - ID:', productId, 'Qty:', cartItem.qty);
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
                        // Update quantity
                        var qty = cartItem.qty;
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

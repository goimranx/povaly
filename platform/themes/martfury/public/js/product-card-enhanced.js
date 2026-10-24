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
        if (element) {
            // Ensure element is a jQuery object
            var $element = element instanceof $ ? element : $(element);
            
            if ($element.hasClass('ec-card__btn--cart')) {
                var productId = $element.data('id');
                if (productId) {
                    transformToQuantityControl(productId, 1);
                }
            }
        }
        
        // Update cart icon state
        syncCartIconStates();
    });

    // ── Quantity Control Functions ────────────────────────────────────────
    
    function transformToQuantityControl(productId, quantity) {
        var $btn = $('.ec-card__btn--cart[data-id="' + productId + '"]');
        if (!$btn.length) return;

        var $actions = $btn.closest('.ec-card__actions');
        
        // Store the slug before replacing the button
        var productSlug = $btn.data('slug');
        
        // Create quantity control HTML with editable input and text
        var iconLeft = quantity === 1 
            ? '<i class="ph ph-trash"></i>' 
            : '<i class="ph ph-minus"></i>';
        
        var controlHtml = 
            '<div class="ec-card__qty-control" data-id="' + productId + '"' + 
            (productSlug ? ' data-slug="' + productSlug + '"' : '') + '>' +
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
        var newQty = currentQty;
        
        if (action === 'decrease') {
            if (currentQty === 1) {
                removeFromCart(productId, $control);
                return;
            } else {
                newQty = currentQty - 1;
            }
        } else if (action === 'increase') {
            newQty = currentQty + 1;
        }
        
        // OPTIMISTIC UPDATE: Update UI immediately on ALL controls for this product (including carousel clones)
        $('.ec-card__qty-control[data-id="' + productId + '"]').each(function() {
            var $ctrl = $(this);
            $ctrl.find('.ec-card__qty-input').val(newQty);
            
            var $minusBtn = $ctrl.find('.ec-card__qty-btn--minus');
            if (newQty === 1) {
                $minusBtn.html('<i class="ph ph-trash"></i>');
            } else {
                $minusBtn.html('<i class="ph ph-minus"></i>');
            }
        });
        
        // Sync to server in background
        updateCartQuantity(productId, newQty, $control);
    });
    
    // Handle direct input change
    $(document).on('change', '.ec-card__qty-input', function(e) {
        var $input = $(this);
        var $control = $input.closest('.ec-card__qty-control');
        var productId = $control.data('id');
        var newQty = parseInt($input.val()) || 0;
        
        // If 0 or less, remove from cart
        if (newQty <= 0) {
            removeFromCart(productId, $control);
            return;
        }
        
        // Update all controls for this product (including carousel clones)
        $('.ec-card__qty-control[data-id="' + productId + '"]').each(function() {
            var $ctrl = $(this);
            $ctrl.find('.ec-card__qty-input').val(newQty);
            
            var $minusBtn = $ctrl.find('.ec-card__qty-btn--minus');
            if (newQty === 1) {
                $minusBtn.html('<i class="ph ph-trash"></i>');
            } else {
                $minusBtn.html('<i class="ph ph-minus"></i>');
            }
        });
        
        updateCartQuantity(productId, newQty, $control);
    });
    
    // Handle keyboard up/down arrows
    $(document).on('keydown', '.ec-card__qty-input', function(e) {
        var $input = $(this);
        var $control = $input.closest('.ec-card__qty-control');
        var productId = $control.data('id');
        var currentQty = parseInt($input.val()) || 1;
        var newQty = currentQty;
        
        // Up arrow (38) or Down arrow (40)
        if (e.which === 38) {
            // Up arrow - increase
            e.preventDefault();
            newQty = currentQty + 1;
            
            // Update all controls for this product
            $('.ec-card__qty-control[data-id="' + productId + '"]').each(function() {
                var $ctrl = $(this);
                $ctrl.find('.ec-card__qty-input').val(newQty);
                $ctrl.find('.ec-card__qty-btn--minus').html('<i class="ph ph-minus"></i>');
            });
            
            updateCartQuantity(productId, newQty, $control);
        } else if (e.which === 40) {
            // Down arrow - decrease
            e.preventDefault();
            if (currentQty > 1) {
                newQty = currentQty - 1;
                
                // Update all controls for this product
                $('.ec-card__qty-control[data-id="' + productId + '"]').each(function() {
                    var $ctrl = $(this);
                    $ctrl.find('.ec-card__qty-input').val(newQty);
                    
                    var $minusBtn = $ctrl.find('.ec-card__qty-btn--minus');
                    if (newQty === 1) {
                        $minusBtn.html('<i class="ph ph-trash"></i>');
                    } else {
                        $minusBtn.html('<i class="ph ph-minus"></i>');
                    }
                });
                
                updateCartQuantity(productId, newQty, $control);
            }
        }
    });
    
    // Handle input blur to update if changed
    $(document).on('blur', '.ec-card__qty-input', function(e) {
        $(this).trigger('change');
    });
    
    // Make entire display area clickable to focus input
    $(document).on('click', '.ec-card__qty-display', function(e) {
        // Don't focus if already clicking the input
        if ($(e.target).hasClass('ec-card__qty-input')) return;
        
        var $input = $(this).find('.ec-card__qty-input');
        $input.focus().select();
    });
    
    // Prevent drag/swipe on quantity control (for carousels)
    $(document).on('mousedown touchstart', '.ec-card__qty-control', function(e) {
        e.stopPropagation();
    });
    
    $(document).on('click', '.ec-card__qty-control', function(e) {
        e.stopPropagation();
    });

    function updateCartQuantity(productId, newQty, $control) {
        // Allow multiple simultaneous updates for instant feel
        // Each click creates its own request
        
        // Get slug from the control itself (stored when transformed)
        var productSlug = $control.data('slug');
        
        // Get cart to find the rowId
        $.ajax({
            url: '/ajax/cart',
            method: 'GET',
            success: function(cartResponse) {
                if (!cartResponse || !cartResponse.data || !cartResponse.data.html) {
                    console.error('No cart HTML available');
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
                        // UI already updated optimistically, just update mini-cart
                        if (typeof window.loadCart === 'function') {
                            window.loadCart();
                        }
                    },
                    error: function(xhr) {
                        console.error('Failed to update cart', xhr.responseText);
                        // On error, sync from server to get correct state
                        syncCartState();
                    }
                });
            },
            error: function(xhr) {
                console.error('Failed to get cart', xhr.responseText);
            }
        });
    }

    function removeFromCart(productId, $control) {
        var $actions = $control.closest('.ec-card__actions');
        
        // Get slug from the control itself
        var productSlug = $control.data('slug');
        
        // Get cart to find the remove URL
        $.ajax({
            url: '/ajax/cart',
            method: 'GET',
            success: function(cartResponse) {
                if (!cartResponse || !cartResponse.data || !cartResponse.data.html) {
                    console.error('No cart HTML available for remove operation');
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
                            'data-url="/cart/add-to-cart" ' +
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
                    }
                });
            },
            error: function(xhr) {
                console.error('Failed to get cart HTML', xhr.responseText);
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
                    'data-url="/cart/add-to-cart" ' +
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
                            'data-url="/cart/add-to-cart" ' +
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


    // ── Icon State Management (Phosphor Icons) ───────────────────────────
    
    /**
     * Update icon state to filled or outline
     * @param {jQuery} $button - The button element
     * @param {boolean} isFilled - True for filled, false for outline
     */
    function updateIconState($button, isFilled) {
        var $icon = $button.find('i.ph');
        
        if (isFilled) {
            // Add ph-fill class directly (no duplicate ph)
            if (!$icon.hasClass('ph-fill')) {
                $icon.addClass('ph-fill');
            }
        } else {
            // Remove ph-fill class for outline state
            $icon.removeClass('ph-fill');
        }
    }
    
    /**
     * Initialize icon states based on current cart/wishlist/compare
     */
    function initializeIconStates() {
        // Initialize cart icon states
        syncCartIconStates();
        
        // Initialize wishlist icon states
        syncWishlistIconStates();
        
        // Initialize compare icon states
        syncCompareIconStates();
    }
    
    /**
     * Sync cart icon states
     */
    function syncCartIconStates() {
        $.ajax({
            url: '/ajax/cart',
            method: 'GET',
            success: function(cartResponse) {
                if (!cartResponse || !cartResponse.data || !cartResponse.data.html) {
                    // No cart data - all icons should be outline
                    $('.ec-card__float-btn--cart').each(function() {
                        updateIconState($(this), false);
                    });
                    return;
                }
                
                var $html = $(cartResponse.data.html);
                var cartProductIds = [];
                
                // Parse cart items to get product IDs
                $html.find('.ps-product--cart-mobile').each(function() {
                    var $item = $(this);
                    var $productLink = $item.find('.ps-product__content > a[href*="/products/"]').first();
                    
                    if (!$productLink.length) return;
                    
                    var productUrl = $productLink.attr('href');
                    var urlParts = productUrl.split('/products/');
                    if (urlParts.length < 2) return;
                    
                    var productIdentifier = urlParts[1].replace(/\/$/, '');
                    cartProductIds.push(productIdentifier);
                });
                
                // Update all cart button icons
                $('.ec-card__float-btn--cart').each(function() {
                    var $btn = $(this);
                    var productId = $btn.data('product-id') || $btn.data('id');
                    var productSlug = $btn.closest('.ec-card').find('a[href*="/products/"]').first().attr('href');
                    
                    if (productSlug) {
                        var parts = productSlug.split('/products/');
                        productSlug = parts.length > 1 ? parts[1].replace(/\/$/, '') : null;
                    }
                    
                    // Check if product is in cart
                    var isInCart = cartProductIds.includes(String(productId)) || 
                                   (productSlug && cartProductIds.includes(productSlug));
                    
                    updateIconState($btn, isInCart);
                });
            },
            error: function() {
                // On error, set all icons to outline
                $('.ec-card__float-btn--cart').each(function() {
                    updateIconState($(this), false);
                });
            }
        });
    }
    
    /**
     * Sync wishlist icon states
     */
    function syncWishlistIconStates() {
        console.log('[Wishlist Sync] Starting...');
        // Get wishlist items from server
        $.ajax({
            url: '/ajax/wishlist',
            method: 'GET',
            success: function(response) {
                console.log('[Wishlist Sync] Response:', response);
                if (!response || !response.data) {
                    console.log('[Wishlist Sync] No response data');
                    return;
                }
                
                var wishlistProductIds = [];
                
                // Parse wishlist items
                if (response.data.products && Array.isArray(response.data.products)) {
                    wishlistProductIds = response.data.products.map(function(product) {
                        return String(product.id);
                    });
                    console.log('[Wishlist Sync] Found products array:', wishlistProductIds);
                } else if (response.data.html) {
                    // Parse HTML if products array not available
                    console.log('[Wishlist Sync] Parsing HTML...');
                    var $html = $(response.data.html);
                    $html.find('[data-id], [data-product-id]').each(function() {
                        var id = $(this).data('id') || $(this).data('product-id');
                        if (id) wishlistProductIds.push(String(id));
                    });
                    console.log('[Wishlist Sync] Parsed IDs from HTML:', wishlistProductIds);
                }
                
                console.log('[Wishlist Sync] Final product IDs:', wishlistProductIds);
                
                // Update all wishlist button icons
                var buttonsFound = 0;
                $('.ec-card__float-btn--wishlist').each(function() {
                    var $btn = $(this);
                    var productId = String($btn.data('product-id'));
                    var isInWishlist = wishlistProductIds.includes(productId);
                    
                    console.log('[Wishlist Sync] Button product ID:', productId, 'In wishlist:', isInWishlist);
                    buttonsFound++;
                    
                    updateIconState($btn, isInWishlist);
                    
                    // Also update button state class for backend.js compatibility
                    if (isInWishlist) {
                        $btn.removeClass('js-add-to-wishlist-button')
                            .addClass('js-remove-from-wishlist-button active');
                        console.log('[Wishlist Sync] Updated button to REMOVE state');
                    } else {
                        $btn.removeClass('js-remove-from-wishlist-button active')
                            .addClass('js-add-to-wishlist-button');
                        console.log('[Wishlist Sync] Updated button to ADD state');
                    }
                });
                console.log('[Wishlist Sync] Total buttons updated:', buttonsFound);
            },
            error: function(xhr, status, error) {
                console.log('[Wishlist Sync] ERROR:', status, error);
                // If wishlist endpoint doesn't exist, try to infer from button state
                $('.ec-card__float-btn--wishlist.js-remove-from-wishlist-button, .ec-card__float-btn--wishlist.active').each(function() {
                    updateIconState($(this), true);
                });
            }
        });
    }
    
    /**
     * Sync compare icon states
     */
    function syncCompareIconStates() {
        console.log('[Compare Sync] Starting...');
        // Get compare items from server
        $.ajax({
            url: '/ajax/compare-list',
            method: 'GET',
            success: function(response) {
                console.log('[Compare Sync] Response:', response);
                if (!response || !response.data) {
                    console.log('[Compare Sync] No response data');
                    return;
                }
                
                var compareProductIds = [];
                
                // Parse compare items
                if (response.data.products && Array.isArray(response.data.products)) {
                    compareProductIds = response.data.products.map(function(product) {
                        return String(product.id);
                    });
                    console.log('[Compare Sync] Found products array:', compareProductIds);
                } else if (response.data.html) {
                    // Parse HTML if products array not available
                    console.log('[Compare Sync] Parsing HTML...');
                    var $html = $(response.data.html);
                    $html.find('[data-id], [data-product-id]').each(function() {
                        var id = $(this).data('id') || $(this).data('product-id');
                        if (id) compareProductIds.push(String(id));
                    });
                    console.log('[Compare Sync] Parsed IDs from HTML:', compareProductIds);
                }
                
                console.log('[Compare Sync] Final product IDs:', compareProductIds);
                
                // Update all compare button icons
                var buttonsFound = 0;
                $('.ec-card__float-btn--compare').each(function() {
                    var $btn = $(this);
                    var productId = String($btn.data('product-id'));
                    var isInCompare = compareProductIds.includes(productId);
                    
                    console.log('[Compare Sync] Button product ID:', productId, 'In compare:', isInCompare);
                    buttonsFound++;
                    
                    updateIconState($btn, isInCompare);
                    
                    // Also update button state class for backend.js compatibility
                    if (isInCompare) {
                        $btn.removeClass('js-add-to-compare-button')
                            .addClass('js-remove-from-compare-button active');
                        console.log('[Compare Sync] Updated button to REMOVE state');
                    } else {
                        $btn.removeClass('js-remove-from-compare-button active')
                            .addClass('js-add-to-compare-button');
                        console.log('[Compare Sync] Updated button to ADD state');
                    }
                });
                console.log('[Compare Sync] Total buttons updated:', buttonsFound);
            },
            error: function(xhr, status, error) {
                console.log('[Compare Sync] ERROR:', status, error);
                // If compare endpoint doesn't exist, try to infer from button state
                $('.ec-card__float-btn--compare.js-remove-from-compare-button, .ec-card__float-btn--compare.active').each(function() {
                    updateIconState($(this), true);
                });
            }
        });
    }
    
    // ── Real-time Icon Updates ────────────────────────────────────────────
    
    // Update cart icon when item is added
    document.addEventListener('ecommerce.cart.added', function (e) {
        var element = e.detail && e.detail.element ? e.detail.element : null;
        var productId = e.detail && e.detail.data && e.detail.data.id ? e.detail.data.id : null;
        
        if (element && $(element).hasClass('ec-card__float-btn--cart')) {
            updateIconState($(element), true);
        }
        
        // Update all cart buttons for this product
        if (productId) {
            $('.ec-card__float-btn--cart[data-product-id="' + productId + '"]').each(function() {
                updateIconState($(this), true);
            });
        }
        
        // Sync all cart icons to be sure
        setTimeout(syncCartIconStates, 500);
    });
    
    // Update cart icon when item is removed
    document.addEventListener('ecommerce.cart.removed', function(e) {
        // Cart removed event doesn't include productId, so sync all icons from server
        syncCartIconStates();
    });
    
    // Listen to mini cart remove button clicks directly (fallback if event doesn't fire)
    $(document).on('click', '.remove-cart-item, .remove-cart-button, .ps-product__remove', function(e) {
        // Give the cart a moment to update, then sync icons
        setTimeout(function() {
            syncCartIconStates();
        }, 300);
    });
    
    // Update cart icons after cart update
    document.addEventListener('ecommerce.cart.updated', function(e) {
        syncCartIconStates();
    });
    
    // Intercept wishlist add/remove to update icon state immediately
    $(document).on('click', '.ec-card__float-btn--wishlist.js-add-to-wishlist-button', function(e) {
        var $btn = $(this);
        var productId = $btn.data('product-id');
        
        // Optimistically update icon
        updateIconState($btn, true);
        
        // Update all wishlist buttons for this product
        $('.ec-card__float-btn--wishlist[data-product-id="' + productId + '"]').each(function() {
            updateIconState($(this), true);
        });
    });
    
    $(document).on('click', '.ec-card__float-btn--wishlist.js-remove-from-wishlist-button', function(e) {
        var $btn = $(this);
        var productId = $btn.data('product-id');
        
        // Optimistically update icon
        updateIconState($btn, false);
        
        // Update all wishlist buttons for this product
        $('.ec-card__float-btn--wishlist[data-product-id="' + productId + '"]').each(function() {
            updateIconState($(this), false);
        });
    });
    
    // Intercept compare add/remove to update icon state immediately
    $(document).on('click', '.ec-card__float-btn--compare.js-add-to-compare-button', function(e) {
        var $btn = $(this);
        var productId = $btn.data('product-id');
        
        // Optimistically update icon
        updateIconState($btn, true);
        
        // Update all compare buttons for this product
        $('.ec-card__float-btn--compare[data-product-id="' + productId + '"]').each(function() {
            updateIconState($(this), true);
        });
    });
    
    $(document).on('click', '.ec-card__float-btn--compare.js-remove-from-compare-button', function(e) {
        var $btn = $(this);
        var productId = $btn.data('product-id');
        
        // Optimistically update icon
        updateIconState($btn, false);
        
        // Update all compare buttons for this product
        $('.ec-card__float-btn--compare[data-product-id="' + productId + '"]').each(function() {
            updateIconState($(this), false);
        });
    });
    
    // Initialize icon states on page load
    $(document).ready(function() {
        initializeIconStates();
    });
    
    // Re-initialize icon states when new products are loaded dynamically
    $(document).on('DOMNodeInserted', '.ec-card', function() {
        // Debounce to avoid too many calls
        clearTimeout(window.iconStateInitTimeout);
        window.iconStateInitTimeout = setTimeout(function() {
            initializeIconStates();
        }, 500);
    });


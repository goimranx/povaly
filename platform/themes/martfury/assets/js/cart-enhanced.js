/**
 * Shopping Cart Enhanced - Single Cart (No Tabs)
 * WITH STATE PERSISTENCE across AJAX reloads
 */
(function() {
    'use strict';
    
    let initialized = false;
    const STORAGE_KEY = 'cart_selected_items';
    const OFFCANVAS_STATE_KEY = 'cart_offcanvas_open';
    
    /**
     * Save selected item states to sessionStorage
     */
    function saveSelectionState() {
        const checked = document.querySelectorAll('.ps-cart-checkbox-item:checked');
        const selectedIds = Array.from(checked).map(cb => cb.dataset.rowId);
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(selectedIds));
        console.log('[Cart] Saved selection state:', selectedIds);
    }
    
    /**
     * Save offcanvas open state
     */
    function saveOffcanvasState(isOpen) {
        sessionStorage.setItem(OFFCANVAS_STATE_KEY, isOpen ? '1' : '0');
        console.log('[Cart] Saved offcanvas state:', isOpen);
    }
    
    /**
     * Get offcanvas open state
     */
    function getOffcanvasState() {
        return sessionStorage.getItem(OFFCANVAS_STATE_KEY) === '1';
    }
    
    /**
     * Restore selected item states from sessionStorage
     */
    function restoreSelectionState() {
        const saved = sessionStorage.getItem(STORAGE_KEY);
        if (!saved) return;
        
        try {
            const selectedIds = JSON.parse(saved);
            console.log('[Cart] Restoring selection state:', selectedIds);
            
            selectedIds.forEach(rowId => {
                const checkbox = document.querySelector(`.ps-cart-checkbox-item[data-row-id="${rowId}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        } catch (e) {
            console.error('[Cart] Failed to restore selection state:', e);
        }
    }
    
    /**
     * Position mobile bottom bar above navigation bar
     */
    function positionMobileBottomBar() {
        const cartBottomBar = document.querySelector('.ps-cart-mobile-bottom-bar');
        const navigationBar = document.querySelector('.navigation--list');
        
        if (!cartBottomBar) return;
        
        function updatePosition() {
            if (navigationBar && window.innerWidth <= 767) {
                const navHeight = navigationBar.offsetHeight;
                
                // Position checkout bar above navigation
                cartBottomBar.style.bottom = navHeight + 'px';
                
                console.log('[Cart] Positioned mobile bar above navigation:', navHeight + 'px');
            } else {
                cartBottomBar.style.bottom = '0px';
            }
        }
        
        // Initial position
        updatePosition();
        
        // Update on window resize
        window.addEventListener('resize', updatePosition);
        
        // Update after a short delay to ensure navigation bar is rendered
        setTimeout(updatePosition, 100);
        setTimeout(updatePosition, 500);
        
        // Return the function so it can be called after AJAX updates
        return updatePosition;
    }
    
    /**
     * Setup product detail offcanvas
     */
    function setupProductDetailOffcanvas() {
        const productDetailOffcanvas = document.querySelector('#productDetailOffcanvas');
        const productDetailOverlay = document.querySelector('#productDetailOverlay');
        const productDetailClose = document.querySelector('#productDetailClose');
        const productDetailContent = document.querySelector('#productDetailContent');
        
        if (!productDetailOffcanvas) return;
        
        // Open product detail on mobile when clicking product name
        document.body.addEventListener('click', function(e) {
            if (window.innerWidth > 767) return; // Only on mobile
            
            const trigger = e.target.closest('[data-product-detail-trigger]');
            if (!trigger) return;
            
            e.preventDefault();
            
            // Get product data from attributes
            const productData = {
                rowId: trigger.dataset.rowId,
                name: trigger.dataset.productName,
                image: trigger.dataset.productImage,
                price: trigger.dataset.productPrice,
                priceRaw: trigger.dataset.productPriceRaw,
                originalPrice: trigger.dataset.productOriginalPrice,
                qty: trigger.dataset.productQty,
                stockStatus: trigger.dataset.productStockStatus,
                attributes: trigger.dataset.productAttributes,
                url: trigger.dataset.productUrl
            };
            
            openProductDetail(productData);
        });
        
        // Close handlers
        if (productDetailOverlay) {
            productDetailOverlay.addEventListener('click', closeProductDetail);
        }
        if (productDetailClose) {
            productDetailClose.addEventListener('click', closeProductDetail);
        }
        
        function openProductDetail(data) {
            // Build HTML
            const isOnSale = data.originalPrice !== '';
            const isOutOfStock = data.stockStatus.toLowerCase().includes('out of stock');
            
            let html = `
                <div class="ps-product-detail-top">
                    <div class="ps-product-detail-image">
                        <img src="${data.image}" alt="${data.name}">
                    </div>
                    <div class="ps-product-detail-price-qty">
                        <div class="ps-product-detail-price">
                            <div class="product__price ${isOnSale ? 'sale' : ''}">
                                <span>${data.price}</span>
                                ${isOnSale ? `<small><del>${data.originalPrice}</del></small>` : ''}
                            </div>
                        </div>
                        <div class="ps-product-detail-qty">
                            <div class="form-group--number product__qty">
                                <button class="up" type="button">+</button>
                                <button class="down" type="button">-</button>
                                <input type="number" class="form-control qty-input" min="1" value="${data.qty}" 
                                       data-row-id="${data.rowId}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ps-product-detail-stock ${isOutOfStock ? 'out-of-stock' : ''}">
                    ${data.stockStatus}
                </div>
                <div class="ps-product-detail-name">
                    ${data.name}
                </div>
            `;
            
            if (data.attributes) {
                html += `
                    <div class="ps-product-detail-attributes">
                        <p><strong>Variations:</strong> ${data.attributes}</p>
                    </div>
                `;
            }
            
            html += `
                <div class="ps-product-detail-actions">
                    <a href="${data.url}">View Full Details</a>
                </div>
            `;
            
            productDetailContent.innerHTML = html;
            
            // Position above checkout bar
            const cartBottomBar = document.querySelector('.ps-cart-mobile-bottom-bar');
            const navigationBar = document.querySelector('.navigation--list');
            
            if (cartBottomBar && navigationBar && window.innerWidth <= 767) {
                const navHeight = navigationBar.offsetHeight;
                const bottomBarHeight = cartBottomBar.offsetHeight;
                const offcanvasBottom = navHeight + bottomBarHeight;
                
                productDetailOffcanvas.style.bottom = offcanvasBottom + 'px';
                productDetailOverlay.style.bottom = offcanvasBottom + 'px';
            }
            
            // Show offcanvas
            setTimeout(() => {
                productDetailOffcanvas.classList.add('active');
                productDetailOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }, 10);
        }
        
        function closeProductDetail() {
            productDetailOffcanvas.classList.remove('active');
            productDetailOverlay.classList.remove('active');
            document.body.style.overflow = '';
            
            setTimeout(() => {
                productDetailOffcanvas.style.bottom = '0px';
                productDetailOverlay.style.bottom = '0px';
            }, 300);
        }
    }
    
    /**
     * Initialize the cart functionality
     */
    function initCart() {
        if (initialized) return;
        
        const mainCheckbox = document.querySelector('#selectAllCheckbox-shopping');
        const mobileCheckbox = document.querySelector('#selectAllCheckbox-shopping-mobile');
        const selectAllText = document.querySelector('#selectAllText-shopping');
        const selectAllTextMobile = document.querySelector('#selectAllText-shopping-mobile');
        const deleteBtn = document.querySelector('#deleteSelectedBtn-shopping');
        const deleteBtnHeader = document.querySelector('#deleteSelectedBtn-shopping-header');
        const itemCheckboxes = document.querySelectorAll('.ps-cart-checkbox-item');
        const mobileTotalAmount = document.querySelector('#mobileTotalAmount');
        const mobileSelectAllWrapper = document.querySelector('.ps-cart-mobile-select-all');
        
        if (!mainCheckbox || !itemCheckboxes.length) return;
        
        initialized = true;
        
        console.log('[Cart] Initializing cart');
        
        // Restore selection state FIRST
        restoreSelectionState();
        
        // Position mobile bars and store the updater function
        window.cartPositionUpdater = positionMobileBottomBar();
        
        function updateMainCheckbox() {
            const selected = document.querySelectorAll('.ps-cart-checkbox-item:checked').length;
            const total = itemCheckboxes.length;
            const mobileCheckoutBtn = document.querySelector('#mobileCheckoutBtn');
            const mobileDetailsBtn = document.querySelector('#mobileDetailsBtn');
            
            if (selected === 0) {
                mainCheckbox.checked = false;
                mainCheckbox.indeterminate = false;
                if (mobileCheckbox) {
                    mobileCheckbox.checked = false;
                    mobileCheckbox.indeterminate = false;
                }
                selectAllText.textContent = selectAllText.dataset.textAll.replace('{count}', total);
                if (selectAllTextMobile) selectAllTextMobile.textContent = 'Select all';
                if (mobileSelectAllWrapper) mobileSelectAllWrapper.classList.remove('ps-hide-text');
                if (deleteBtn) deleteBtn.disabled = true;
                if (deleteBtnHeader) deleteBtnHeader.disabled = true;
                if (mobileCheckoutBtn) mobileCheckoutBtn.disabled = true;
                if (mobileDetailsBtn) mobileDetailsBtn.disabled = true;
            } else if (selected === total) {
                mainCheckbox.checked = true;
                mainCheckbox.indeterminate = false;
                if (mobileCheckbox) {
                    mobileCheckbox.checked = true;
                    mobileCheckbox.indeterminate = false;
                }
                selectAllText.textContent = selectAllText.dataset.textAllSelected.replace('{count}', total);
                // Hide text on mobile when all selected
                if (mobileSelectAllWrapper) mobileSelectAllWrapper.classList.add('ps-hide-text');
                if (deleteBtn) deleteBtn.disabled = false;
                if (deleteBtnHeader) deleteBtnHeader.disabled = false;
                if (mobileCheckoutBtn) mobileCheckoutBtn.disabled = false;
                if (mobileDetailsBtn) mobileDetailsBtn.disabled = false;
            } else {
                mainCheckbox.checked = false;
                mainCheckbox.indeterminate = true;
                if (mobileCheckbox) {
                    mobileCheckbox.checked = false;
                    mobileCheckbox.indeterminate = true;
                }
                selectAllText.textContent = selectAllText.dataset.textPartial.replace('{selected}', selected).replace('{total}', total);
                // Hide text on mobile when partial selected
                if (mobileSelectAllWrapper) mobileSelectAllWrapper.classList.add('ps-hide-text');
                if (deleteBtn) deleteBtn.disabled = false;
                if (deleteBtnHeader) deleteBtnHeader.disabled = false;
                if (mobileCheckoutBtn) mobileCheckoutBtn.disabled = false;
                if (mobileDetailsBtn) mobileDetailsBtn.disabled = false;
            }
            updateSummary();
        }
        
        function updateSummary() {
            const checked = document.querySelectorAll('.ps-cart-checkbox-item:checked');
            const itemCountEl = document.querySelector('#cart-summary-item-count');
            const subtotalEl = document.querySelector('#cart-summary-subtotal');
            const taxEl = document.querySelector('#cart-summary-tax');
            const totalEl = document.querySelector('#cart-summary-total');
            const mobileTotalAmount = document.querySelector('#mobileTotalAmount');
            
            if (!itemCountEl || !subtotalEl) return;
            
            const currency = (subtotalEl.textContent.match(/[^\d.,\s]+/) || ['৳'])[0];
            const formatPrice = val => currency + parseFloat(val || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            
            if (checked.length === 0) {
                itemCountEl.textContent = '0';
                subtotalEl.textContent = formatPrice(0);
                if (taxEl) taxEl.textContent = formatPrice(0);
                if (totalEl) totalEl.textContent = formatPrice(0);
                if (mobileTotalAmount) mobileTotalAmount.textContent = formatPrice(0);
                return;
            }
            
            let subtotal = 0;
            checked.forEach(cb => {
                const item = cb.closest('.ps-cart-product-item');
                const priceEl = item?.querySelector('.ps-cart-product-price .product__price span');
                const qtyInput = item?.querySelector('.qty-input');
                if (priceEl && qtyInput) {
                    const price = parseFloat((priceEl.textContent.match(/[\d,]+\.?\d*/) || ['0'])[0].replace(/,/g, ''));
                    const qty = parseInt(qtyInput.value) || 0;
                    subtotal += price * qty;
                }
            });
            
            itemCountEl.textContent = checked.length;
            subtotalEl.textContent = formatPrice(subtotal);
            
            // Handle tax if present
            let finalTotal = subtotal;
            if (taxEl && taxEl.dataset.total) {
                const originalSubtotal = parseFloat(subtotalEl.dataset.total) || 1;
                const originalTax = parseFloat(taxEl.dataset.total) || 0;
                const calculatedTax = subtotal * (originalTax / originalSubtotal);
                taxEl.textContent = formatPrice(calculatedTax);
                finalTotal = subtotal + calculatedTax;
                if (totalEl) totalEl.textContent = formatPrice(finalTotal);
            } else if (totalEl) {
                totalEl.textContent = formatPrice(subtotal);
            }
            
            // Update mobile total
            if (mobileTotalAmount) {
                mobileTotalAmount.textContent = formatPrice(finalTotal);
            }
        }
        
        function updateStoreCheckbox(storeId) {
            const storeCheckbox = document.querySelector(`.ps-cart-checkbox-store[data-store-id="${storeId}"]`);
            if (!storeCheckbox) return;
            
            const storeItems = document.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]`);
            const checkedItems = document.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]:checked`);
            
            storeCheckbox.checked = checkedItems.length === storeItems.length;
            storeCheckbox.indeterminate = checkedItems.length > 0 && checkedItems.length < storeItems.length;
        }
        
        // Main checkbox - select/deselect all
        mainCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            document.querySelectorAll('.ps-cart-checkbox-store').forEach(cb => {
                cb.checked = this.checked;
                cb.indeterminate = false;
            });
            if (mobileCheckbox) mobileCheckbox.checked = this.checked;
            updateMainCheckbox();
            saveSelectionState();
        });
        
        // Mobile checkbox - sync with main checkbox
        if (mobileCheckbox) {
            mobileCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                document.querySelectorAll('.ps-cart-checkbox-store').forEach(cb => {
                    cb.checked = this.checked;
                    cb.indeterminate = false;
                });
                mainCheckbox.checked = this.checked;
                updateMainCheckbox();
                saveSelectionState();
            });
        }
        
        // Store header checkboxes
        document.querySelectorAll('.ps-cart-checkbox-store').forEach(storeCheckbox => {
            storeCheckbox.addEventListener('change', function() {
                const storeId = this.dataset.storeId;
                document.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]`).forEach(cb => {
                    cb.checked = this.checked;
                });
                this.indeterminate = false;
                updateMainCheckbox();
                saveSelectionState();
            });
        });
        
        // Item checkboxes
        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateStoreCheckbox(this.dataset.storeId);
                updateMainCheckbox();
                saveSelectionState();
            });
        });
        
        // Save state before qty input changes (before AJAX)
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('focus', () => {
                saveSelectionState();
                // Also save offcanvas state
                if (cartDetailsOffcanvas && cartDetailsOffcanvas.classList.contains('active')) {
                    saveOffcanvasState(true);
                }
                console.log('[Cart] Qty input focused, saved state');
            });
            input.addEventListener('change', () => {
                saveSelectionState();
                // Also save offcanvas state
                if (cartDetailsOffcanvas && cartDetailsOffcanvas.classList.contains('active')) {
                    saveOffcanvasState(true);
                }
                console.log('[Cart] Qty changed, saved state');
            });
        });
        
        // Initialize store checkbox states
        updateMainCheckbox();
        const storeIds = new Set();
        itemCheckboxes.forEach(cb => storeIds.add(cb.dataset.storeId));
        storeIds.forEach(id => updateStoreCheckbox(id));
        
        // Restore offcanvas state if it was open before AJAX reload
        if (getOffcanvasState() && mobileDetailsBtn && cartDetailsOffcanvas) {
            // Reopen offcanvas after a short delay to allow DOM to settle
            setTimeout(() => {
                openCartDetails();
            }, 50);
        }
        
        // Mobile Details button - toggle offcanvas
        const mobileDetailsBtn = document.querySelector('#mobileDetailsBtn');
        const cartDetailsOffcanvas = document.querySelector('#cartDetailsOffcanvas');
        const cartDetailsOverlay = document.querySelector('#cartDetailsOverlay');
        const cartDetailsClose = document.querySelector('#cartDetailsClose');
        
        if (mobileDetailsBtn && cartDetailsOffcanvas) {
            // Toggle offcanvas on Details button click
            mobileDetailsBtn.addEventListener('click', function() {
                if (cartDetailsOffcanvas.classList.contains('active')) {
                    closeCartDetails();
                } else {
                    openCartDetails();
                }
            });
            
            // Close on overlay click
            if (cartDetailsOverlay) {
                cartDetailsOverlay.addEventListener('click', function() {
                    closeCartDetails();
                });
            }
            
            // Close on close button click
            if (cartDetailsClose) {
                cartDetailsClose.addEventListener('click', function() {
                    closeCartDetails();
                });
            }
        }
        
        function openCartDetails() {
            updateCartDetailsContent();
            
            // Save offcanvas open state
            saveOffcanvasState(true);
            
            // Add active class to Details button to rotate chevron
            if (mobileDetailsBtn) {
                mobileDetailsBtn.classList.add('active');
            }
            
            // Calculate position above checkout bar only when opening
            const cartBottomBar = document.querySelector('.ps-cart-mobile-bottom-bar');
            const navigationBar = document.querySelector('.navigation--list');
            
            if (cartBottomBar && navigationBar && window.innerWidth <= 767) {
                const navHeight = navigationBar.offsetHeight;
                const bottomBarHeight = cartBottomBar.offsetHeight;
                const offcanvasBottom = navHeight + bottomBarHeight;
                
                // Position offcanvas above checkout bar
                cartDetailsOffcanvas.style.bottom = offcanvasBottom + 'px';
                
                // Position overlay to stop at the checkout bar (not cover bottom bars)
                cartDetailsOverlay.style.bottom = offcanvasBottom + 'px';
                
                console.log('[Cart] Positioned offcanvas and overlay above checkout bar:', offcanvasBottom + 'px');
            }
            
            // Small delay to ensure bottom is set before adding active class
            setTimeout(() => {
                cartDetailsOffcanvas.classList.add('active');
                cartDetailsOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }, 10);
        }
        
        function closeCartDetails() {
            // Save offcanvas closed state
            saveOffcanvasState(false);
            
            // Remove active class from Details button to restore chevron
            if (mobileDetailsBtn) {
                mobileDetailsBtn.classList.remove('active');
            }
            
            cartDetailsOffcanvas.classList.remove('active');
            cartDetailsOverlay.classList.remove('active');
            document.body.style.overflow = '';
            
            // Reset bottom to 0 after closing animation
            setTimeout(() => {
                cartDetailsOffcanvas.style.bottom = '0px';
                cartDetailsOverlay.style.bottom = '0px';
            }, 300);
        }
        
        function updateCartDetailsContent() {
            const checked = document.querySelectorAll('.ps-cart-checkbox-item:checked');
            const itemsContainer = document.querySelector('#cartDetailsItems');
            
            if (!itemsContainer) return;
            
            // Clear existing items
            itemsContainer.innerHTML = '';
            
            // Add selected item images (up to 3 columns)
            checked.forEach(cb => {
                const item = cb.closest('.ps-cart-product-item');
                const imgEl = item?.querySelector('.ps-cart-product-image img');
                
                if (imgEl) {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'ps-cart-details-item';
                    itemDiv.innerHTML = `
                        <img src="${imgEl.src}" alt="${imgEl.alt}">
                        <div class="ps-cart-details-item-check">
                            <i class="icon-check"></i>
                        </div>
                    `;
                    itemsContainer.appendChild(itemDiv);
                }
            });
            
            // Update summary amounts
            const subtotalEl = document.querySelector('#cart-summary-subtotal');
            const taxEl = document.querySelector('#cart-summary-tax');
            const totalEl = document.querySelector('#cart-summary-total');
            
            if (subtotalEl) document.querySelector('#detailsSubtotal').textContent = subtotalEl.textContent;
            if (taxEl) document.querySelector('#detailsTax').textContent = taxEl.textContent;
            if (totalEl) document.querySelector('#detailsTotal').textContent = totalEl.textContent;
        }
        
        // Mobile Checkout button - trigger validation and checkout
        const mobileCheckoutBtn = document.querySelector('#mobileCheckoutBtn');
        if (mobileCheckoutBtn) {
            mobileCheckoutBtn.addEventListener('click', function() {
                const checkoutLink = document.querySelector('.ps-cart-actions a[href*="checkout"], .ps-btn--primary[href*="checkout"], a.ps-btn[href*="information"]');
                if (checkoutLink) {
                    checkoutLink.click();
                }
            });
        }
        
        // Product Detail Offcanvas - open when clicking product name chevron on mobile
        setupProductDetailOffcanvas();
    }
    
    /**
     * Setup bulk delete functionality
     */
    function setupBulkDelete() {
        document.body.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('#deleteSelectedBtn-shopping, #deleteSelectedBtn-shopping-header');
            if (!deleteBtn) return;
            
            e.preventDefault();
            e.stopPropagation();
            
            const checkedItems = document.querySelectorAll('.ps-cart-checkbox-item:checked');
            if (!checkedItems.length) {
                alert('Please select items to delete');
                return;
            }
            
            if (!confirm(deleteBtn.dataset.confirmMessage || 'Remove selected items?')) return;
            
            deleteBtn.disabled = true;
            const originalText = deleteBtn.textContent;
            deleteBtn.textContent = 'Deleting...';
            
            const rowIds = Array.from(checkedItems).map(cb => cb.dataset.rowId);
            const firstItem = checkedItems[0].closest('.ps-cart-product-item');
            const cartInstance = firstItem?.querySelector('.remove-cart-button')?.dataset.cartInstance || 'cart';
            
            console.log('[Cart] Deleting items:', { cartInstance, count: rowIds.length });
            
            // Update saved state (remove deleted items)
            const saved = sessionStorage.getItem(STORAGE_KEY);
            if (saved) {
                try {
                    const selectedIds = JSON.parse(saved);
                    const remaining = selectedIds.filter(id => !rowIds.includes(id));
                    sessionStorage.setItem(STORAGE_KEY, JSON.stringify(remaining));
                } catch (e) {}
            }
            
            if (typeof jQuery !== 'undefined') {
                jQuery.ajax({
                    url: window.siteUrl + '/ajax/cart/remove-multiple',
                    method: 'POST',
                    data: {
                        _token: jQuery('meta[name="csrf-token"]').attr('content'),
                        cart_instance: cartInstance,
                        row_ids: rowIds
                    },
                    success: (response) => {
                        if (response.error) {
                            Theme.showError(response.message);
                            deleteBtn.disabled = false;
                            deleteBtn.textContent = originalText;
                        } else {
                            Theme.showSuccess(response.message || 'Items removed');
                            location.reload();
                        }
                    },
                    error: () => {
                        Theme.showError('Failed to remove items');
                        deleteBtn.disabled = false;
                        deleteBtn.textContent = originalText;
                    }
                });
            } else {
                location.reload();
            }
        });
    }
    
    /**
     * Setup checkout button to store selected items
     */
    function setupCheckoutButton() {
        document.addEventListener('click', function(e) {
            const checkoutBtn = e.target.closest('.ps-cart-actions a[href*="checkout"], .ps-btn--primary[href*="checkout"], a.ps-btn[href*="information"]');
            if (!checkoutBtn || !checkoutBtn.closest('.ps-cart-summary-card')) return;
            
            const checkedItems = document.querySelectorAll('.ps-cart-checkbox-item:checked');
            if (!checkedItems.length) {
                e.preventDefault();
                alert('Please select at least one item');
                return false;
            }
            
            // Store selected items before checkout
            if (typeof jQuery !== 'undefined') {
                e.preventDefault();
                jQuery.ajax({
                    url: window.siteUrl + '/ajax/cart/store-selected-items',
                    method: 'POST',
                    data: {
                        _token: jQuery('meta[name="csrf-token"]').attr('content'),
                        selected_items: Array.from(checkedItems).map(cb => cb.dataset.rowId)
                    },
                    success: () => {
                        // Clear selection state when going to checkout
                        sessionStorage.removeItem(STORAGE_KEY);
                        window.location.href = checkoutBtn.href;
                    },
                    error: () => {
                        sessionStorage.removeItem(STORAGE_KEY);
                        window.location.href = checkoutBtn.href;
                    }
                });
            }
        }, true);
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initCart();
            setupBulkDelete();
            setupCheckoutButton();
            window.cartPositionUpdater = positionMobileBottomBar();
        });
    } else {
        initCart();
        setupBulkDelete();
        setupCheckoutButton();
        window.cartPositionUpdater = positionMobileBottomBar();
    }
    
    // Export for external use and reset initialization flag
    window.initCartEnhanced = function() {
        initialized = false; // Reset flag so it can reinitialize
        initCart();
    };
    
})();

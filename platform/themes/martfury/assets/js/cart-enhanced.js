/**
 * Shopping Cart Enhanced - Checkbox Selection System
 */
(function() {
    'use strict';
    
    /**
     * Initialize cart enhanced functionality
     */
    function initCartEnhanced() {
        const mainCheckbox = document.getElementById('selectAllCheckbox');
        const selectAllText = document.getElementById('selectAllText');
        const deleteBtn = document.getElementById('deleteSelectedBtn');
        const storeCheckboxes = document.querySelectorAll('.ps-cart-checkbox-store');
        const itemCheckboxes = document.querySelectorAll('.ps-cart-checkbox-item');
        
        if (!mainCheckbox || !itemCheckboxes.length) {
            return; // No cart items
        }
        
        let selectedCount = 0;
        const totalItems = itemCheckboxes.length;
        
        /**
         * Update main checkbox state and text
         */
        function updateMainCheckbox() {
            selectedCount = document.querySelectorAll('.ps-cart-checkbox-item:checked').length;
            const currentTotal = document.querySelectorAll('.ps-cart-checkbox-item').length;
            
            if (selectedCount === 0) {
                mainCheckbox.checked = false;
                mainCheckbox.indeterminate = false;
                selectAllText.textContent = selectAllText.dataset.textAll.replace('{count}', currentTotal);
                if (deleteBtn) deleteBtn.disabled = true;
            } else if (selectedCount === currentTotal) {
                mainCheckbox.checked = true;
                mainCheckbox.indeterminate = false;
                selectAllText.textContent = selectAllText.dataset.textAllSelected.replace('{count}', currentTotal);
                if (deleteBtn) deleteBtn.disabled = false;
            } else {
                mainCheckbox.checked = false;
                mainCheckbox.indeterminate = true;
                selectAllText.textContent = selectAllText.dataset.textPartial
                    .replace('{selected}', selectedCount)
                    .replace('{total}', currentTotal);
                if (deleteBtn) deleteBtn.disabled = false;
            }
            
            // Update cart summary
            updateCartSummary();
        }
        
        /**
         * Update cart summary based on selected items
         */
        function updateCartSummary() {
            const checkedItems = document.querySelectorAll('.ps-cart-checkbox-item:checked');
            const itemCountEl = document.getElementById('cart-summary-item-count');
            const subtotalEl = document.getElementById('cart-summary-subtotal');
            const taxEl = document.getElementById('cart-summary-tax');
            const totalEl = document.getElementById('cart-summary-total');
            
            if (!itemCountEl || !subtotalEl) return;
            
            // If nothing selected, show 0
            if (checkedItems.length === 0) {
                itemCountEl.textContent = '0';
                
                // Get currency from original
                const originalText = subtotalEl.textContent;
                const currencyMatch = originalText.match(/[^\d.,\s]+/);
                const currency = currencyMatch ? currencyMatch[0] : '৳';
                
                subtotalEl.textContent = currency + '0.00';
                if (taxEl) taxEl.textContent = currency + '0.00';
                if (totalEl) totalEl.textContent = currency + '0.00';
                return;
            }
            
            // Calculate based on selected items only
            let selectedSubtotal = 0;
            
            checkedItems.forEach(checkbox => {
                const productItem = checkbox.closest('.ps-cart-product-item');
                if (productItem) {
                    const priceEl = productItem.querySelector('.ps-cart-product-price .product__price span');
                    const qtyInput = productItem.querySelector('.qty-input');
                    
                    if (priceEl && qtyInput) {
                        // Parse price (remove currency symbol, commas, and keep only numbers and decimal)
                        const priceText = priceEl.textContent.trim();
                        const priceMatch = priceText.match(/[\d,]+\.?\d*/);
                        
                        if (priceMatch) {
                            const price = parseFloat(priceMatch[0].replace(/,/g, ''));
                            const qty = parseInt(qtyInput.value);
                            
                            if (!isNaN(price) && !isNaN(qty)) {
                                selectedSubtotal += price * qty;
                            }
                        }
                    }
                }
            });
            
            itemCountEl.textContent = checkedItems.length;
            
            // Format price with proper currency
            const originalText = subtotalEl.textContent;
            const currencyMatch = originalText.match(/[^\d.,\s]+/);
            const currency = currencyMatch ? currencyMatch[0] : '৳';
            
            const formattedSubtotal = currency + selectedSubtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            subtotalEl.textContent = formattedSubtotal;
            
            // Calculate tax if tax element exists
            if (taxEl && taxEl.dataset.total) {
                const originalTotal = parseFloat(subtotalEl.dataset.total);
                const originalTax = parseFloat(taxEl.dataset.total);
                
                // Calculate tax proportionally
                const taxRate = originalTotal > 0 ? originalTax / originalTotal : 0;
                const selectedTax = selectedSubtotal * taxRate;
                
                const formattedTax = currency + selectedTax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                taxEl.textContent = formattedTax;
                
                // Update total (subtotal + tax)
                if (totalEl) {
                    const selectedTotal = selectedSubtotal + selectedTax;
                    const formattedTotal = currency + selectedTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                    totalEl.textContent = formattedTotal;
                }
            } else {
                // No tax, total = subtotal
                if (totalEl) {
                    totalEl.textContent = formattedSubtotal;
                }
            }
        }
        
        /**
         * Update store checkbox state
         */
        function updateStoreCheckbox(storeId) {
            const storeCheckbox = document.querySelector(`.ps-cart-checkbox-store[data-store-id="${storeId}"]`);
            if (!storeCheckbox) return;
            
            const storeItems = document.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]`);
            const checkedItems = document.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]:checked`);
            
            if (checkedItems.length === 0) {
                storeCheckbox.checked = false;
                storeCheckbox.indeterminate = false;
            } else if (checkedItems.length === storeItems.length) {
                storeCheckbox.checked = true;
                storeCheckbox.indeterminate = false;
            } else {
                storeCheckbox.checked = false;
                storeCheckbox.indeterminate = true;
            }
        }
        
        /**
         * Main checkbox change event
         */
        mainCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            const currentItems = document.querySelectorAll('.ps-cart-checkbox-item');
            const currentStores = document.querySelectorAll('.ps-cart-checkbox-store');
            currentItems.forEach(cb => cb.checked = isChecked);
            currentStores.forEach(cb => {
                cb.checked = isChecked;
                cb.indeterminate = false;
            });
            updateMainCheckbox();
        });
        
        /**
         * Store checkbox change event
         */
        storeCheckboxes.forEach(storeCheckbox => {
            storeCheckbox.addEventListener('change', function() {
                const storeId = this.dataset.storeId;
                const isChecked = this.checked;
                const storeItems = document.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]`);
                storeItems.forEach(cb => cb.checked = isChecked);
                this.indeterminate = false;
                updateMainCheckbox();
            });
        });
        
        /**
         * Item checkbox change event
         */
        itemCheckboxes.forEach(itemCheckbox => {
            itemCheckbox.addEventListener('change', function() {
                const storeId = this.dataset.storeId;
                updateStoreCheckbox(storeId);
                updateMainCheckbox();
            });
        });
        
        /**
         * Delete selected items
         */
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                const checkedItems = document.querySelectorAll('.ps-cart-checkbox-item:checked');
                if (checkedItems.length === 0) return;
                
                const confirmMessage = deleteBtn.dataset.confirmMessage || 'Are you sure you want to remove the selected items?';
                
                if (confirm(confirmMessage)) {
                    // Get all row IDs
                    const rowIds = Array.from(checkedItems).map(cb => cb.dataset.rowId);
                    
                    // Remove items sequentially using the existing cart remove system
                    let currentIndex = 0;
                    
                    function removeNext() {
                        if (currentIndex >= rowIds.length) {
                            return;
                        }
                        
                        const rowId = rowIds[currentIndex];
                        const removeBtn = document.querySelector(`[data-row-id="${rowId}"] .remove-cart-button`);
                        
                        if (removeBtn) {
                            if (typeof jQuery !== 'undefined') {
                                jQuery(removeBtn).trigger('click');
                                setTimeout(() => {
                                    currentIndex++;
                                    removeNext();
                                }, 500);
                            } else {
                                currentIndex++;
                                removeNext();
                            }
                        } else {
                            currentIndex++;
                            removeNext();
                        }
                    }
                    
                    removeNext();
                }
            });
        }
        
        /**
         * Initialize on page load
         */
        // Don't select anything by default - let user choose
        updateMainCheckbox();
        storeCheckboxes.forEach(cb => {
            if (cb.dataset.storeId) {
                updateStoreCheckbox(cb.dataset.storeId);
            }
        });
        
        /**
         * Monitor quantity changes to update totals
         */
        const qtyInputs = document.querySelectorAll('.ps-cart-product-item .qty-input');
        qtyInputs.forEach(input => {
            // Listen for input changes
            input.addEventListener('change', function() {
                updateCartSummary();
            });
            
            // Listen for button clicks (+ and -)
            const productItem = input.closest('.ps-cart-product-item');
            if (productItem) {
                const decreaseBtn = productItem.querySelector('.decrease');
                const increaseBtn = productItem.querySelector('.increase');
                
                if (decreaseBtn) {
                    decreaseBtn.addEventListener('click', function() {
                        setTimeout(updateCartSummary, 100);
                    });
                }
                
                if (increaseBtn) {
                    increaseBtn.addEventListener('click', function() {
                        setTimeout(updateCartSummary, 100);
                    });
                }
            }
        });
        
        // Also monitor for updates via cart update AJAX
        if (typeof jQuery !== 'undefined') {
            jQuery(document).on('cart.updated', function() {
                setTimeout(updateCartSummary, 300);
            });
        }
    }
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', initCartEnhanced);
    
    // Reinitialize after AJAX cart reload
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('cartEnhancedReloaded', initCartEnhanced);
    }
    
    // Make function globally available for manual reinit
    window.initCartEnhanced = initCartEnhanced;
    
    /**
     * Handle checkout with selected items only
     */
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutBtn = document.querySelector('.ps-cart-actions a[href*="checkout.information"]');
        
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function(e) {
                const checkedItems = document.querySelectorAll('.ps-cart-checkbox-item:checked');
                
                if (checkedItems.length === 0) {
                    e.preventDefault();
                    alert('Please select at least one item to checkout');
                    return false;
                }
                
                // Get selected row IDs
                const selectedRowIds = Array.from(checkedItems).map(cb => cb.dataset.rowId);
                
                console.log('Selected row IDs:', selectedRowIds);
                console.log('Site URL:', window.siteUrl);
                console.log('CSRF Token:', jQuery('meta[name="csrf-token"]').attr('content'));
                
                // Store in session via AJAX before redirecting
                if (typeof jQuery !== 'undefined') {
                    e.preventDefault();
                    
                    const ajaxUrl = window.siteUrl + '/ajax/cart/store-selected-items';
                    console.log('Making AJAX call to:', ajaxUrl);
                    
                    jQuery.ajax({
                        url: ajaxUrl,
                        method: 'POST',
                        data: {
                            _token: jQuery('meta[name="csrf-token"]').attr('content'),
                            selected_items: selectedRowIds
                        },
                        success: function(response) {
                            console.log('AJAX success:', response);
                            // Redirect to checkout
                            window.location.href = checkoutBtn.href;
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX error:', status, error);
                            console.error('Response:', xhr.responseText);
                            // If AJAX fails, go to checkout with all items (fallback)
                            window.location.href = checkoutBtn.href;
                        }
                    });
                }
            });
        }
        
        /**
         * Handle flyout cart checkout - should use ALL items
         */
        document.addEventListener('click', function(e) {
            const flyoutCheckoutBtn = e.target.closest('.ps-btn-flyout-checkout');
            
            if (flyoutCheckoutBtn && typeof jQuery !== 'undefined') {
                e.preventDefault();
                
                // Clear selected items session for flyout cart checkout
                jQuery.ajax({
                    url: window.siteUrl + '/ajax/cart/clear-selected-items',
                    method: 'POST',
                    data: {
                        _token: jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        window.location.href = flyoutCheckoutBtn.href;
                    },
                    error: function() {
                        // If AJAX fails, proceed anyway
                        window.location.href = flyoutCheckoutBtn.href;
                    }
                });
            }
        });
        
        /**
         * Handle Buy Now button - should clear selected items session
         */
        document.addEventListener('click', function(e) {
            const buyNowBtn = e.target.closest('[data-id][data-url][name="checkout"]');
            
            if (buyNowBtn && typeof jQuery !== 'undefined') {
                // Clear selected items session before Buy Now proceeds
                jQuery.ajax({
                    url: window.siteUrl + '/ajax/cart/clear-selected-items',
                    method: 'POST',
                    data: {
                        _token: jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    async: false // Make it synchronous so session clears before buy now proceeds
                });
            }
        });
    });
})();


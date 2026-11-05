/**
 * Shopping Cart Enhanced - Multi-Tab Checkbox Selection System
 */
(function() {
    'use strict';
    
    // Track initialized tabs to prevent duplicate event listeners
    const initializedTabs = new Set();
    
    /**
     * Initialize cart enhanced functionality for a specific tab
     */
    function initCartTab(tabId) {
        const tabContent = document.querySelector(`[data-tab-content="${tabId}"]`);
        if (!tabContent) return;
        
        // Check if this tab is already initialized
        if (initializedTabs.has(tabId)) {
            return;
        }
        
        const mainCheckbox = tabContent.querySelector(`#selectAllCheckbox-${tabId}`);
        const selectAllText = tabContent.querySelector(`#selectAllText-${tabId}`);
        const deleteBtn = tabContent.querySelector(`#deleteSelectedBtn-${tabId}`);
        const storeCheckboxes = tabContent.querySelectorAll('.ps-cart-checkbox-store');
        const itemCheckboxes = tabContent.querySelectorAll('.ps-cart-checkbox-item');
        
        if (!mainCheckbox || !itemCheckboxes.length) {
            return; // No cart items in this tab
        }
        
        // Mark this tab as initialized
        initializedTabs.add(tabId);
        
        /**
         * Update main checkbox state and text
         */
        function updateMainCheckbox() {
            const selectedCount = tabContent.querySelectorAll('.ps-cart-checkbox-item:checked').length;
            const currentTotal = tabContent.querySelectorAll('.ps-cart-checkbox-item').length;
            
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
            
            // Update cart summary for this tab
            updateCartSummary(tabContent);
        }
        
        /**
         * Update cart summary based on selected items
         */
        function updateCartSummary(container) {
            const checkedItems = container.querySelectorAll('.ps-cart-checkbox-item:checked');
            const itemCountEl = container.querySelector('#cart-summary-item-count');
            const subtotalEl = container.querySelector('#cart-summary-subtotal');
            const taxEl = container.querySelector('#cart-summary-tax');
            const totalEl = container.querySelector('#cart-summary-total');
            
            if (!itemCountEl || !subtotalEl) return;
            
            // Get currency from original text
            const originalText = subtotalEl.textContent;
            const currencyMatch = originalText.match(/[^\d.,\s]+/);
            const currency = currencyMatch ? currencyMatch[0] : '৳';
            
            const formatPrice = (value) => {
                const num = parseFloat(value);
                if (isNaN(num)) return currency + '0.00';
                return currency + num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            };
            
            // If nothing selected, show zero
            if (checkedItems.length === 0) {
                itemCountEl.textContent = '0';
                subtotalEl.textContent = formatPrice(0);
                if (taxEl) taxEl.textContent = formatPrice(0);
                if (totalEl) totalEl.textContent = formatPrice(0);
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
            
            const formattedSubtotal = formatPrice(selectedSubtotal);
            subtotalEl.textContent = formattedSubtotal;
            
            // Calculate tax if tax element exists
            if (taxEl && taxEl.dataset.total) {
                const originalTotal = parseFloat(subtotalEl.dataset.total);
                const originalTax = parseFloat(taxEl.dataset.total);
                
                const taxRate = originalTotal > 0 ? originalTax / originalTotal : 0;
                const selectedTax = selectedSubtotal * taxRate;
                
                const formattedTax = formatPrice(selectedTax);
                taxEl.textContent = formattedTax;
                
                if (totalEl) {
                    const selectedTotal = selectedSubtotal + selectedTax;
                    const formattedTotal = formatPrice(selectedTotal);
                    totalEl.textContent = formattedTotal;
                }
            } else {
                if (totalEl) {
                    totalEl.textContent = formattedSubtotal;
                }
            }
        }
        
        /**
         * Update store checkbox state
         */
        function updateStoreCheckbox(storeId) {
            const storeCheckbox = tabContent.querySelector(`.ps-cart-checkbox-store[data-store-id="${storeId}"]`);
            if (!storeCheckbox) return;
            
            const storeItems = tabContent.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]`);
            const checkedItems = tabContent.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]:checked`);
            
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
            itemCheckboxes.forEach(cb => cb.checked = isChecked);
            storeCheckboxes.forEach(cb => {
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
                const storeItems = tabContent.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]`);
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
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const checkedItems = tabContent.querySelectorAll('.ps-cart-checkbox-item:checked');
                if (checkedItems.length === 0) return;
                
                const confirmMessage = this.dataset.confirmMessage || 'Are you sure you want to remove the selected items?';
                
                if (!confirm(confirmMessage)) {
                    return;
                }
                
                // Disable button to prevent multiple clicks
                this.disabled = true;
                this.textContent = 'Deleting...';
                
                const rowIds = Array.from(checkedItems).map(cb => cb.dataset.rowId);
                
                // Get cart instance from first item's remove button
                const firstItem = checkedItems[0].closest('.ps-cart-product-item');
                const removeBtn = firstItem ? firstItem.querySelector('.remove-cart-button') : null;
                const cartInstance = removeBtn ? removeBtn.dataset.cartInstance : 'cart';
                
                const self = this;
                
                // Send all row IDs in a single batch request
                if (typeof jQuery !== 'undefined') {
                    jQuery.ajax({
                        url: window.siteUrl + '/ajax/cart/remove-multiple',
                        method: 'POST',
                        data: {
                            _token: jQuery('meta[name="csrf-token"]').attr('content'),
                            cart_instance: cartInstance,
                            row_ids: rowIds
                        },
                        success: function(response) {
                            if (response.error) {
                                Theme.showError(response.message);
                                self.disabled = false;
                                self.textContent = 'Delete Selected';
                            } else {
                                Theme.showSuccess(response.message || 'Items removed successfully');
                                location.reload();
                            }
                        },
                        error: function() {
                            // Fallback: remove items one by one
                            removeItemsSequentially(rowIds, cartInstance, self);
                        }
                    });
                } else {
                    location.reload();
                }
            });
        }
        
        /**
         * Fallback function to remove items one by one
         */
        function removeItemsSequentially(rowIds, cartInstance, deleteBtn) {
            let currentIndex = 0;
            let successCount = 0;
            
            function removeNext() {
                if (currentIndex >= rowIds.length) {
                    if (successCount > 0) {
                        Theme.showSuccess(successCount + ' item(s) removed successfully');
                        location.reload();
                    } else {
                        Theme.showError('Failed to remove items');
                        deleteBtn.disabled = false;
                        deleteBtn.textContent = 'Delete Selected';
                    }
                    return;
                }
                
                const rowId = rowIds[currentIndex];
                
                jQuery.ajax({
                    url: window.siteUrl + '/ajax/cart/remove',
                    method: 'POST',
                    data: {
                        _token: jQuery('meta[name="csrf-token"]').attr('content'),
                        cart_instance: cartInstance,
                        row_id: rowId
                    },
                    success: function() {
                        successCount++;
                        currentIndex++;
                        removeNext();
                    },
                    error: function() {
                        currentIndex++;
                        removeNext();
                    }
                });
            }
            
            removeNext();
        }
        
        /**
         * Initialize on page load
         */
        // For confirm-payment tab, select all items by default
        if (tabId === 'confirm-payment') {
            setTimeout(() => {
                itemCheckboxes.forEach(cb => cb.checked = true);
                storeCheckboxes.forEach(cb => {
                    cb.checked = true;
                    cb.indeterminate = false;
                });
                updateMainCheckbox();
            }, 100);
        } else {
            // For shopping cart, don't select anything by default
            updateMainCheckbox();
            storeCheckboxes.forEach(cb => {
                if (cb.dataset.storeId) {
                    updateStoreCheckbox(cb.dataset.storeId);
                }
            });
        }
        
        /**
         * Monitor quantity changes to update summary
         */
        const qtyInputs = tabContent.querySelectorAll('.ps-cart-product-item .qty-input');
        qtyInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Save current state before qty change triggers reload
                saveCartState(tabId);
            });
        });
        
        /**
         * Save cart state to sessionStorage
         */
        function saveCartState(currentTabId) {
            const checkedRowIds = Array.from(tabContent.querySelectorAll('.ps-cart-checkbox-item:checked'))
                .map(cb => cb.dataset.rowId);
            
            sessionStorage.setItem('cart_active_tab', currentTabId);
            sessionStorage.setItem(`cart_selected_items_${currentTabId}`, JSON.stringify(checkedRowIds));
        }
    }
    
    /**
     * Restore cart state after page reload
     */
    function restoreCartState() {
        const activeTabId = sessionStorage.getItem('cart_active_tab');
        
        if (activeTabId) {
            // Switch to the active tab
            const tabButton = document.querySelector(`[data-tab="${activeTabId}"]`);
            if (tabButton && !tabButton.classList.contains('active')) {
                tabButton.click();
            }
            
            // Restore selected items for this tab
            const selectedItems = sessionStorage.getItem(`cart_selected_items_${activeTabId}`);
            if (selectedItems) {
                const rowIds = JSON.parse(selectedItems);
                const tabContent = document.querySelector(`[data-tab-content="${activeTabId}"]`);
                
                if (tabContent) {
                    rowIds.forEach(rowId => {
                        const checkbox = tabContent.querySelector(`.ps-cart-checkbox-item[data-row-id="${rowId}"]`);
                        if (checkbox) {
                            checkbox.checked = true;
                        }
                    });
                    
                    // Trigger update to recalculate checkboxes and summary
                    const mainCheckbox = tabContent.querySelector(`#selectAllCheckbox-${activeTabId}`);
                    if (mainCheckbox) {
                        // Manually trigger the update logic
                        const event = new Event('change');
                        const firstCheckbox = tabContent.querySelector('.ps-cart-checkbox-item');
                        if (firstCheckbox) {
                            firstCheckbox.dispatchEvent(event);
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Initialize all tabs
     */
    function initCartEnhanced() {
        // Clear initialization tracking when page reloads
        initializedTabs.clear();
        
        initCartTab('shopping');
        initCartTab('confirm-payment');
        
        // Restore state after initialization
        restoreCartState();
    }
    
    /**
     * Reset initialization tracking (for when cart content is fully reloaded)
     */
    function resetCartEnhanced() {
        initializedTabs.clear();
        initCartEnhanced();
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCartEnhanced);
    } else {
        // DOM already loaded, initialize immediately
        initCartEnhanced();
    }
    
    // Reinitialize after AJAX cart reload
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('cartEnhancedReloaded', function() {
            resetCartEnhanced();
        });
    }
    
    // Make functions globally available
    window.initCartEnhanced = initCartEnhanced;
    window.resetCartEnhanced = resetCartEnhanced;
    
    /**
     * Handle checkout with selected items only
     */
    document.addEventListener('click', function(e) {
        const checkoutBtn = e.target.closest('.ps-cart-actions a[href*="checkout"]') || 
                           e.target.closest('.ps-btn--primary[href*="checkout"]') ||
                           e.target.closest('a.ps-btn[href*="information"]');
        
        if (checkoutBtn && checkoutBtn.closest('.ps-cart-summary-card')) {
            const activeTab = document.querySelector('.ps-cart-tab-content.active');
            const checkedItems = activeTab 
                ? activeTab.querySelectorAll('.ps-cart-checkbox-item:checked')
                : [];
            
            if (checkedItems.length === 0) {
                e.preventDefault();
                alert('Please select at least one item to checkout');
                return false;
            }
            
            const selectedRowIds = Array.from(checkedItems).map(cb => cb.dataset.rowId);
            
            if (typeof jQuery !== 'undefined') {
                e.preventDefault();
                
                jQuery.ajax({
                    url: window.siteUrl + '/ajax/cart/store-selected-items',
                    method: 'POST',
                    data: {
                        _token: jQuery('meta[name="csrf-token"]').attr('content'),
                        selected_items: selectedRowIds
                    },
                    success: function() {
                        window.location.href = checkoutBtn.href;
                    },
                    error: function() {
                        window.location.href = checkoutBtn.href;
                    }
                });
            }
        }
    }, true);
})();

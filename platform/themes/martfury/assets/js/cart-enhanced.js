/**
 * Shopping Cart Enhanced - Multi-Tab Checkbox Selection System
 */
(function() {
    'use strict';
    
    /**
     * Initialize cart enhanced functionality for a specific tab
     */
    function initCartTab(tabId) {
        const tabContent = document.querySelector(`[data-tab-content="${tabId}"]`);
        if (!tabContent) return;
        
        const mainCheckbox = tabContent.querySelector(`#selectAllCheckbox-${tabId}`);
        const selectAllText = tabContent.querySelector(`#selectAllText-${tabId}`);
        const deleteBtn = tabContent.querySelector(`#deleteSelectedBtn-${tabId}`);
        const storeCheckboxes = tabContent.querySelectorAll('.ps-cart-checkbox-store');
        const itemCheckboxes = tabContent.querySelectorAll('.ps-cart-checkbox-item');
        
        if (!mainCheckbox || !itemCheckboxes.length) {
            return; // No cart items in this tab
        }
        
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
            
            // If nothing selected, show original full totals (not zero)
            if (checkedItems.length === 0) {
                // Restore from data attributes
                const originalCount = itemCountEl.dataset.total || '0';
                const originalSubtotal = subtotalEl.dataset.total || '0';
                const originalTax = taxEl ? (taxEl.dataset.total || '0') : '0';
                const originalTotal = totalEl ? (totalEl.dataset.total || '0') : '0';
                
                itemCountEl.textContent = originalCount;
                
                // Get currency from original text
                const originalText = subtotalEl.textContent;
                const currencyMatch = originalText.match(/[^\d.,\s]+/);
                const currency = currencyMatch ? currencyMatch[0] : '৳';
                
                const formatPrice = (value) => {
                    const num = parseFloat(value);
                    if (isNaN(num)) return currency + '0.00';
                    return currency + num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                };
                
                subtotalEl.textContent = formatPrice(originalSubtotal);
                if (taxEl) taxEl.textContent = formatPrice(originalTax);
                if (totalEl) totalEl.textContent = formatPrice(originalTotal);
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
                
                const taxRate = originalTotal > 0 ? originalTax / originalTotal : 0;
                const selectedTax = selectedSubtotal * taxRate;
                
                const formattedTax = currency + selectedTax.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                taxEl.textContent = formattedTax;
                
                if (totalEl) {
                    const selectedTotal = selectedSubtotal + selectedTax;
                    const formattedTotal = currency + selectedTotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
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
            deleteBtn.addEventListener('click', function() {
                const checkedItems = tabContent.querySelectorAll('.ps-cart-checkbox-item:checked');
                if (checkedItems.length === 0) return;
                
                const confirmMessage = deleteBtn.dataset.confirmMessage || 'Are you sure you want to remove the selected items?';
                
                if (confirm(confirmMessage)) {
                    const rowIds = Array.from(checkedItems).map(cb => cb.dataset.rowId);
                    
                    // Get cart instance from first item's remove button
                    const firstItem = checkedItems[0].closest('.ps-cart-product-item');
                    const removeBtn = firstItem ? firstItem.querySelector('.remove-cart-button') : null;
                    const cartInstance = removeBtn ? removeBtn.dataset.cartInstance : 'cart';
                    
                    let currentIndex = 0;
                    
                    function removeNext() {
                        if (currentIndex >= rowIds.length) {
                            location.reload();
                            return;
                        }
                        
                        const rowId = rowIds[currentIndex];
                        
                        if (typeof jQuery !== 'undefined') {
                            jQuery.ajax({
                                url: window.siteUrl + '/ajax/cart/remove',
                                method: 'POST',
                                data: {
                                    _token: jQuery('meta[name="csrf-token"]').attr('content'),
                                    cart_instance: cartInstance,
                                    row_id: rowId
                                },
                                success: function() {
                                    currentIndex++;
                                    removeNext();
                                },
                                error: function() {
                                    currentIndex++;
                                    removeNext();
                                }
                            });
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
                setTimeout(() => updateCartSummary(tabContent), 100);
            });
        });
    }
    
    /**
     * Initialize all tabs
     */
    function initCartEnhanced() {
        initCartTab('shopping');
        initCartTab('confirm-payment');
    }
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', initCartEnhanced);
    
    // Reinitialize after tab switch
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('cartEnhancedReloaded', initCartEnhanced);
    }
    
    // Make function globally available
    window.initCartEnhanced = initCartEnhanced;
    
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

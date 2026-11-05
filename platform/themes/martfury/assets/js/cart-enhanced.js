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
            
            if (!itemCountEl || !subtotalEl) return;
            
            let selectedSubtotal = 0;
            
            // If nothing selected, show all items
            if (checkedItems.length === 0) {
                const totalCount = document.querySelectorAll('.ps-cart-checkbox-item').length;
                itemCountEl.textContent = totalCount;
                
                // Use the original total from data attribute
                const originalTotal = parseFloat(subtotalEl.dataset.total);
                subtotalEl.textContent = formatPrice(originalTotal);
            } else {
                // Calculate subtotal for selected items
                checkedItems.forEach(checkbox => {
                    const productItem = checkbox.closest('.ps-cart-product-item');
                    if (productItem) {
                        const priceEl = productItem.querySelector('.ps-cart-product-price .product__price span');
                        const qtyInput = productItem.querySelector('.qty-input');
                        
                        if (priceEl && qtyInput) {
                            // Parse price (remove currency symbol and commas)
                            const priceText = priceEl.textContent.replace(/[^0-9.]/g, '');
                            const price = parseFloat(priceText);
                            const qty = parseInt(qtyInput.value);
                            
                            if (!isNaN(price) && !isNaN(qty)) {
                                selectedSubtotal += price * qty;
                            }
                        }
                    }
                });
                
                itemCountEl.textContent = checkedItems.length;
                subtotalEl.textContent = formatPrice(selectedSubtotal);
            }
        }
        
        /**
         * Format price helper
         */
        function formatPrice(amount) {
            // Get currency symbol from original element
            const subtotalEl = document.getElementById('cart-summary-subtotal');
            if (!subtotalEl) return amount.toFixed(2);
            
            const originalText = subtotalEl.textContent;
            const currencyMatch = originalText.match(/[^\d.,\s]+/);
            const currency = currencyMatch ? currencyMatch[0] : '৳';
            
            // Format with commas
            const formatted = amount.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            return currency + formatted;
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
        updateMainCheckbox();
        storeCheckboxes.forEach(cb => {
            if (cb.dataset.storeId) {
                updateStoreCheckbox(cb.dataset.storeId);
            }
        });
    }
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', initCartEnhanced);
    
    // Reinitialize after AJAX cart reload
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('cartEnhancedReloaded', initCartEnhanced);
    }
    
    // Make function globally available for manual reinit
    window.initCartEnhanced = initCartEnhanced;
})();


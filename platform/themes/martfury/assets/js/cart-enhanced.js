/**
 * Shopping Cart Enhanced - Single Cart (No Tabs)
 * WITH STATE PERSISTENCE across AJAX reloads
 */
(function() {
    'use strict';
    
    let initialized = false;
    const STORAGE_KEY = 'cart_selected_items';
    
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
     * Initialize the cart functionality
     */
    function initCart() {
        if (initialized) return;
        
        const mainCheckbox = document.querySelector('#selectAllCheckbox-shopping');
        const selectAllText = document.querySelector('#selectAllText-shopping');
        const deleteBtn = document.querySelector('#deleteSelectedBtn-shopping');
        const itemCheckboxes = document.querySelectorAll('.ps-cart-checkbox-item');
        
        if (!mainCheckbox || !itemCheckboxes.length) return;
        
        initialized = true;
        
        console.log('[Cart] Initializing cart');
        
        // Restore selection state FIRST
        restoreSelectionState();
        
        function updateMainCheckbox() {
            const selected = document.querySelectorAll('.ps-cart-checkbox-item:checked').length;
            const total = itemCheckboxes.length;
            
            if (selected === 0) {
                mainCheckbox.checked = false;
                mainCheckbox.indeterminate = false;
                selectAllText.textContent = selectAllText.dataset.textAll.replace('{count}', total);
                if (deleteBtn) deleteBtn.disabled = true;
            } else if (selected === total) {
                mainCheckbox.checked = true;
                mainCheckbox.indeterminate = false;
                selectAllText.textContent = selectAllText.dataset.textAllSelected.replace('{count}', total);
                if (deleteBtn) deleteBtn.disabled = false;
            } else {
                mainCheckbox.checked = false;
                mainCheckbox.indeterminate = true;
                selectAllText.textContent = selectAllText.dataset.textPartial.replace('{selected}', selected).replace('{total}', total);
                if (deleteBtn) deleteBtn.disabled = false;
            }
            updateSummary();
        }
        
        function updateSummary() {
            const checked = document.querySelectorAll('.ps-cart-checkbox-item:checked');
            const itemCountEl = document.querySelector('#cart-summary-item-count');
            const subtotalEl = document.querySelector('#cart-summary-subtotal');
            const taxEl = document.querySelector('#cart-summary-tax');
            const totalEl = document.querySelector('#cart-summary-total');
            
            if (!itemCountEl || !subtotalEl) return;
            
            const currency = (subtotalEl.textContent.match(/[^\d.,\s]+/) || ['৳'])[0];
            const formatPrice = val => currency + parseFloat(val || 0).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            
            if (checked.length === 0) {
                itemCountEl.textContent = '0';
                subtotalEl.textContent = formatPrice(0);
                if (taxEl) taxEl.textContent = formatPrice(0);
                if (totalEl) totalEl.textContent = formatPrice(0);
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
            if (taxEl && taxEl.dataset.total) {
                const originalSubtotal = parseFloat(subtotalEl.dataset.total) || 1;
                const originalTax = parseFloat(taxEl.dataset.total) || 0;
                const calculatedTax = subtotal * (originalTax / originalSubtotal);
                taxEl.textContent = formatPrice(calculatedTax);
                if (totalEl) totalEl.textContent = formatPrice(subtotal + calculatedTax);
            } else if (totalEl) {
                totalEl.textContent = formatPrice(subtotal);
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
            updateMainCheckbox();
            saveSelectionState();
        });
        
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
                console.log('[Cart] Qty input focused, saved state');
            });
            input.addEventListener('change', () => {
                saveSelectionState();
                console.log('[Cart] Qty changed, saved state');
            });
        });
        
        // Initialize store checkbox states
        updateMainCheckbox();
        const storeIds = new Set();
        itemCheckboxes.forEach(cb => storeIds.add(cb.dataset.storeId));
        storeIds.forEach(id => updateStoreCheckbox(id));
    }
    
    /**
     * Setup bulk delete functionality
     */
    function setupBulkDelete() {
        document.body.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('#deleteSelectedBtn-shopping');
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
        });
    } else {
        initCart();
        setupBulkDelete();
        setupCheckoutButton();
    }
    
    // Export for external use and reset initialization flag
    window.initCartEnhanced = function() {
        initialized = false; // Reset flag so it can reinitialize
        initCart();
    };
    
})();

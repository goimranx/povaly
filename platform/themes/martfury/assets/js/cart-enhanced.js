/**
 * Shopping Cart Enhanced - Checkbox Selection System
 */
(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
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
            
            if (selectedCount === 0) {
                mainCheckbox.checked = false;
                mainCheckbox.indeterminate = false;
                selectAllText.textContent = selectAllText.dataset.textAll.replace('{count}', totalItems);
                if (deleteBtn) deleteBtn.disabled = true;
            } else if (selectedCount === totalItems) {
                mainCheckbox.checked = true;
                mainCheckbox.indeterminate = false;
                selectAllText.textContent = selectAllText.dataset.textAllSelected.replace('{count}', totalItems);
                if (deleteBtn) deleteBtn.disabled = false;
            } else {
                mainCheckbox.checked = false;
                mainCheckbox.indeterminate = true;
                selectAllText.textContent = selectAllText.dataset.textPartial
                    .replace('{selected}', selectedCount)
                    .replace('{total}', totalItems);
                if (deleteBtn) deleteBtn.disabled = false;
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
                    let totalRemoved = 0;
                    
                    function removeNext() {
                        if (currentIndex >= rowIds.length) {
                            // All done - the last item's removal will trigger cart reload
                            return;
                        }
                        
                        const rowId = rowIds[currentIndex];
                        const removeBtn = document.querySelector(`[data-row-id="${rowId}"] .remove-cart-button`);
                        
                        if (removeBtn) {
                            // Use jQuery to trigger the existing event handler
                            if (typeof jQuery !== 'undefined') {
                                // Trigger jQuery click event - this will use the existing backend.js handler
                                jQuery(removeBtn).trigger('click');
                                
                                // Wait a bit before removing next item to prevent race conditions
                                setTimeout(() => {
                                    currentIndex++;
                                    totalRemoved++;
                                    removeNext();
                                }, 500); // Delay between removals
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
    });
})();

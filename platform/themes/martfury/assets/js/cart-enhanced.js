/**
 * Shopping Cart Enhanced - Completely Separated Carts
 * Shopping and Confirm Payment carts are 100% independent
 */
(function() {
    'use strict';
    
    // Completely separate state for each cart - NO shared state
    const cartStates = {
        shopping: { initialized: false },
        'confirm-payment': { initialized: false }
    };
    
    /**
     * Initialize a cart tab - completely independent from other tabs
     */
    function initCartTab(tabId) {
        // Skip if already initialized
        if (cartStates[tabId].initialized) return;
        
        const tabContent = document.querySelector(`[data-tab-content="${tabId}"]`);
        if (!tabContent) return;
        
        const mainCheckbox = tabContent.querySelector(`#selectAllCheckbox-${tabId}`);
        const selectAllText = tabContent.querySelector(`#selectAllText-${tabId}`);
        const deleteBtn = tabContent.querySelector(`#deleteSelectedBtn-${tabId}`);
        const itemCheckboxes = tabContent.querySelectorAll('.ps-cart-checkbox-item');
        
        if (!mainCheckbox || !itemCheckboxes.length) return;
        
        cartStates[tabId].initialized = true;
        
        function updateMainCheckbox() {
            const selected = tabContent.querySelectorAll('.ps-cart-checkbox-item:checked').length;
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
            const checked = tabContent.querySelectorAll('.ps-cart-checkbox-item:checked');
            // Scope all queries to THIS tab's content only
            const itemCountEl = tabContent.querySelector('#cart-summary-item-count');
            const subtotalEl = tabContent.querySelector('#cart-summary-subtotal');
            const taxEl = tabContent.querySelector('#cart-summary-tax');
            const totalEl = tabContent.querySelector('#cart-summary-total');
            
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
            
            if (taxEl && taxEl.dataset.total) {
                const origSubtotal = parseFloat(subtotalEl.dataset.total) || 1;
                const origTax = parseFloat(taxEl.dataset.total) || 0;
                const tax = subtotal * (origTax / origSubtotal);
                taxEl.textContent = formatPrice(tax);
                if (totalEl) totalEl.textContent = formatPrice(subtotal + tax);
            } else if (totalEl) {
                totalEl.textContent = formatPrice(subtotal);
            }
        }
        
        function updateStoreCheckbox(storeId) {
            const storeCb = tabContent.querySelector(`.ps-cart-checkbox-store[data-store-id="${storeId}"]`);
            if (!storeCb) return;
            const items = tabContent.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]`);
            const checked = tabContent.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]:checked`);
            storeCb.checked = checked.length === items.length;
            storeCb.indeterminate = checked.length > 0 && checked.length < items.length;
        }
        
        mainCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(cb => cb.checked = this.checked);
            tabContent.querySelectorAll('.ps-cart-checkbox-store').forEach(cb => {
                cb.checked = this.checked;
                cb.indeterminate = false;
            });
            updateMainCheckbox();
        });
        
        tabContent.querySelectorAll('.ps-cart-checkbox-store').forEach(storeCb => {
            storeCb.addEventListener('change', function() {
                const storeId = this.dataset.storeId;
                tabContent.querySelectorAll(`.ps-cart-checkbox-item[data-store-id="${storeId}"]`).forEach(cb => cb.checked = this.checked);
                this.indeterminate = false;
                updateMainCheckbox();
            });
        });
        
        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateStoreCheckbox(this.dataset.storeId);
                updateMainCheckbox();
            });
        });
        
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const checked = tabContent.querySelectorAll('.ps-cart-checkbox-item:checked');
                if (!checked.length || !confirm(this.dataset.confirmMessage || 'Remove selected items?')) return;
                
                this.disabled = true;
                this.textContent = 'Deleting...';
                
                const rowIds = Array.from(checked).map(cb => cb.dataset.rowId);
                const firstItem = checked[0].closest('.ps-cart-product-item');
                const cartInstance = firstItem?.querySelector('.remove-cart-button')?.dataset.cartInstance || 'cart';
                
                sessionStorage.setItem('cart_keep_tab', tabId);
                
                if (typeof jQuery !== 'undefined') {
                    jQuery.ajax({
                        url: window.siteUrl + '/ajax/cart/remove-multiple',
                        method: 'POST',
                        data: { _token: jQuery('meta[name="csrf-token"]').attr('content'), cart_instance: cartInstance, row_ids: rowIds },
                        success: res => {
                            if (res.error) {
                                Theme.showError(res.message);
                                this.disabled = false;
                                this.textContent = 'Delete Selected';
                            } else {
                                Theme.showSuccess(res.message || 'Items removed');
                                location.reload();
                            }
                        },
                        error: () => {
                            Theme.showError('Failed to remove items');
                            this.disabled = false;
                            this.textContent = 'Delete Selected';
                        }
                    });
                } else {
                    location.reload();
                }
            });
        }
        
        tabContent.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', () => {
                console.log('[Cart] Saving tab:', tabId);
                sessionStorage.setItem('cart_keep_tab', tabId);
            });
        });
        
        // Initial state
        if (tabId === 'confirm-payment') {
            setTimeout(() => {
                itemCheckboxes.forEach(cb => cb.checked = true);
                tabContent.querySelectorAll('.ps-cart-checkbox-store').forEach(cb => {
                    cb.checked = true;
                    cb.indeterminate = false;
                });
                updateMainCheckbox();
            }, 100);
        } else {
            updateMainCheckbox();
        }
    }
    
    function initAll() {
        console.log('[Cart] Initializing both carts');
        cartStates.shopping.initialized = false;
        cartStates['confirm-payment'].initialized = false;
        
        initCartTab('shopping');
        initCartTab('confirm-payment');
        
        // Restore active tab after reload
        const keepTab = sessionStorage.getItem('cart_keep_tab');
        console.log('[Cart] Restore tab from storage:', keepTab);
        if (keepTab) {
            setTimeout(() => {
                const btn = document.querySelector(`[data-tab="${keepTab}"]`);
                console.log('[Cart] Found tab button:', btn);
                if (btn && !btn.classList.contains('active')) {
                    console.log('[Cart] Clicking tab button for:', keepTab);
                    btn.click();
                }
                sessionStorage.removeItem('cart_keep_tab');
            }, 100);
        }
    }
    
    function setupTabs() {
        document.querySelectorAll('.ps-cart-tab-button').forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.dataset.tab;
                document.querySelectorAll('.ps-cart-tab-button').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.querySelectorAll('.ps-cart-tab-content').forEach(c => {
                    c.classList.toggle('active', c.dataset.tabContent === tab);
                });
            });
        });
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            setupTabs();
            initAll();
        });
    } else {
        setupTabs();
        initAll();
    }
    
    window.initCartEnhanced = initAll;
    
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.ps-cart-actions a[href*="checkout"], .ps-btn--primary[href*="checkout"], a.ps-btn[href*="information"]');
        if (!btn || !btn.closest('.ps-cart-summary-card')) return;
        
        const activeTab = document.querySelector('.ps-cart-tab-content.active');
        const checked = activeTab?.querySelectorAll('.ps-cart-checkbox-item:checked') || [];
        
        if (!checked.length) {
            e.preventDefault();
            alert('Please select at least one item');
            return false;
        }
        
        if (typeof jQuery !== 'undefined') {
            e.preventDefault();
            jQuery.ajax({
                url: window.siteUrl + '/ajax/cart/store-selected-items',
                method: 'POST',
                data: {
                    _token: jQuery('meta[name="csrf-token"]').attr('content'),
                    selected_items: Array.from(checked).map(cb => cb.dataset.rowId)
                },
                success: () => window.location.href = btn.href,
                error: () => window.location.href = btn.href
            });
        }
    }, true);
})();

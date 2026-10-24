/**
 * Fix for collapsed sidebar dropdown hover behavior
 * When sidebar is collapsed, dropdowns should show as flyouts beside the icon
 * 
 * This handles the Tabler admin template sidebar behavior where collapsed
 * sidebar items should show their submenus as flyouts on hover.
 */

(function() {
    'use strict';

    function initCollapsedSidebarDropdowns() {
        const sidebar = document.querySelector('.navbar-vertical');
        if (!sidebar) return;

        // Check if sidebar is collapsed by checking navbar-collapse visibility
        function isSidebarCollapsed() {
            const navbarCollapse = sidebar.querySelector('.navbar-collapse');
            if (!navbarCollapse) return false;
            
            // Sidebar is collapsed if navbar-collapse is NOT shown
            // OR if sidebar has specific width (collapsed state)
            const sidebarWidth = sidebar.offsetWidth;
            return sidebarWidth < 100 || !navbarCollapse.classList.contains('show');
        }

        // Initialize Popper for dropdown positioning
        function createPopperInstance(reference, popper) {
            if (typeof window.Popper === 'undefined') {
                console.warn('Popper.js not loaded, using fallback positioning');
                return null;
            }
            
            return window.Popper.createPopper(reference, popper, {
                placement: 'right-start',
                modifiers: [
                    {
                        name: 'offset',
                        options: {
                            offset: [0, 8],
                        },
                    },
                    {
                        name: 'preventOverflow',
                        options: {
                            boundary: 'viewport',
                        },
                    },
                ],
            });
        }

        // Handle dropdown hover for collapsed sidebar
        function handleCollapsedDropdown(navItem) {
            if (!isSidebarCollapsed()) return;

            const dropdownToggle = navItem.querySelector('.dropdown-toggle');
            const dropdownMenu = navItem.querySelector('.dropdown-menu');
            if (!dropdownMenu || !dropdownToggle) return;

            // Use Popper if available, otherwise fallback to manual positioning
            if (typeof window.Popper !== 'undefined') {
                const popperInstance = createPopperInstance(navItem, dropdownMenu);
                navItem._popperInstance = popperInstance;
            } else {
                // Fallback: Manual positioning
                const rect = navItem.getBoundingClientRect();
                dropdownMenu.style.position = 'fixed';
                dropdownMenu.style.left = rect.right + 8 + 'px';
                dropdownMenu.style.top = rect.top + 'px';
            }
            
            dropdownMenu.style.minWidth = '200px';
            dropdownMenu.classList.add('show');
            navItem.classList.add('show');
            dropdownToggle.setAttribute('aria-expanded', 'true');
        }

        function hideCollapsedDropdown(navItem) {
            const dropdownToggle = navItem.querySelector('.dropdown-toggle');
            const dropdownMenu = navItem.querySelector('.dropdown-menu');
            if (!dropdownMenu) return;

            dropdownMenu.classList.remove('show');
            navItem.classList.remove('show');
            if (dropdownToggle) {
                dropdownToggle.setAttribute('aria-expanded', 'false');
            }
            
            // Destroy popper instance if exists
            if (navItem._popperInstance) {
                navItem._popperInstance.destroy();
                delete navItem._popperInstance;
            }
            
            // Reset inline styles
            dropdownMenu.style.position = '';
            dropdownMenu.style.left = '';
            dropdownMenu.style.top = '';
            dropdownMenu.style.minWidth = '';
        }

        // Add hover listeners to all nav items with dropdowns
        const dropdownItems = sidebar.querySelectorAll('.nav-item.dropdown');
        
        dropdownItems.forEach(function(navItem) {
            let hoverTimeout;

            // Mouse enter - show dropdown after short delay
            navItem.addEventListener('mouseenter', function() {
                clearTimeout(hoverTimeout);
                hoverTimeout = setTimeout(function() {
                    handleCollapsedDropdown(navItem);
                }, 100);
            });

            // Mouse leave - hide dropdown after short delay
            navItem.addEventListener('mouseleave', function() {
                clearTimeout(hoverTimeout);
                hoverTimeout = setTimeout(function() {
                    if (isSidebarCollapsed()) {
                        hideCollapsedDropdown(navItem);
                    }
                }, 100);
            });

            // Also handle dropdown menu hover to keep it open
            const dropdownMenu = navItem.querySelector('.dropdown-menu');
            if (dropdownMenu) {
                dropdownMenu.addEventListener('mouseenter', function() {
                    clearTimeout(hoverTimeout);
                });
                
                dropdownMenu.addEventListener('mouseleave', function() {
                    clearTimeout(hoverTimeout);
                    hoverTimeout = setTimeout(function() {
                        if (isSidebarCollapsed()) {
                            hideCollapsedDropdown(navItem);
                        }
                    }, 100);
                });
            }
        });

        // Re-calculate positions on window resize
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                dropdownItems.forEach(function(navItem) {
                    if (navItem.classList.contains('show') && isSidebarCollapsed()) {
                        // Update popper position if it exists
                        if (navItem._popperInstance) {
                            navItem._popperInstance.update();
                        }
                    } else if (navItem.classList.contains('show') && !isSidebarCollapsed()) {
                        // Hide flyout if sidebar expanded
                        hideCollapsedDropdown(navItem);
                    }
                });
            }, 250);
        });

        // Debug: Log sidebar state
        console.log('Sidebar collapsed fix initialized. Collapsed state:', isSidebarCollapsed());
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCollapsedSidebarDropdowns);
    } else {
        initCollapsedSidebarDropdowns();
    }

})();

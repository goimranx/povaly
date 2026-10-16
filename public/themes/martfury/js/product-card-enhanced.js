// Enhanced Product Card - Mobile cart button handler
(function($) {
    'use strict';
    
    $(document).on('click', '.ps-enhanced-actions .ps-enhanced-btn--cart', function(event) {
        event.preventDefault();
        event.stopPropagation();
        
        let _self = $(this);
        _self.prop('disabled', true).addClass('button-loading');
        
        $.ajax({
            url: _self.data('url'),
            method: 'POST',
            data: { id: _self.data('id') },
            dataType: 'json',
            success: res => {
                _self.prop('disabled', false).removeClass('button-loading');
                if (res.error) {
                    if (typeof Theme !== 'undefined' && Theme.showError) {
                        Theme.showError(res.message);
                    }
                    return false;
                }
                if (typeof Theme !== 'undefined' && Theme.showSuccess) {
                    Theme.showSuccess(res.message);
                }
                if (res.data && res.data.next_url) {
                    window.location.href = res.data.next_url;
                } else {
                    $.ajax({
                        url: window.siteUrl + '/ajax/cart',
                        method: 'GET',
                        success: response => {
                            if (!response.error) {
                                $('.ps-cart--mobile').html(response.data.html);
                                $('.btn-shopping-cart span i').text(response.data.count);
                            }
                        }
                    });
                }
            },
            error: res => {
                _self.prop('disabled', false).removeClass('button-loading');
                if (typeof Theme !== 'undefined' && Theme.showError) {
                    Theme.showError(res.message || 'Error adding to cart');
                }
            }
        });
    });
    
    // Enhanced Product Card - Floating actions toggle
    $(document).on('click', '.ps-floating-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).closest('.ps-floating-actions-capsule').toggleClass('active');
    });
    
    // Enhanced Product Card - Close floating actions when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.ps-product__floating-actions').length) {
            $('.ps-floating-actions-capsule').removeClass('active');
        }
    });
})(jQuery);

/**
 * Location Modal Handler
 * Manages temporary address selection with progressive field display
 * Integrates with location.js plugin for cascading dropdowns
 */

if (typeof jQuery === 'undefined') {
    console.error('LocationModal: jQuery is not loaded!');
} else {
    (function($) {
        'use strict';

        const LocationModal = {
            modal: null,
            form: null,
            countrySelect: null,
            stateSelect: null,
            citySelect: null,
            addressInput: null,
            saveBtn: null,
            selectedData: {
                type: null,
                addressId: null,
                tempAddress: null
            },

            init: function() {
                this.modal = $('#location-modal');
                this.form = $('#temporary-address-form');
                this.countrySelect = $('#temp-country');
                this.stateSelect = $('#temp-state');
                this.citySelect = $('#temp-city');
                this.addressInput = $('#temp-address');
                this.saveBtn = $('#save-location-btn');

                // Progressive display: hide all fields except country initially
                this.stateSelect.closest('.form-group-location').hide();
                this.citySelect.closest('.form-group-location').hide();
                $('.form-group-location.address-group').hide();

                this.bindEvents();
            },

            bindEvents: function() {
                const self = this;

                // Country change - show state dropdown and set data attributes for location.js
                this.countrySelect.on('change', function() {
                    const countryId = $(this).val();
                    
                    // Treat "0" as placeholder/empty
                    if (countryId && countryId !== "0") {
                        // Set data attribute for location.js
                        self.stateSelect.attr('data-country-id', countryId);
                        self.citySelect.attr('data-country-id', countryId);
                        
                        // Show and enable state dropdown
                        self.stateSelect.prop('disabled', false);
                        self.stateSelect.closest('.form-group-location').show();
                        
                        // Hide city and address
                        self.citySelect.closest('.form-group-location').hide();
                        $('.form-group-location.address-group').hide();
                        
                        // Reset state and city (location.js will populate state)
                        self.stateSelect.val('').trigger('change');
                        self.citySelect.val('');
                    } else {
                        // Hide everything except country
                        self.stateSelect.closest('.form-group-location').hide();
                        self.citySelect.closest('.form-group-location').hide();
                        $('.form-group-location.address-group').hide();
                        self.stateSelect.prop('disabled', true);
                        self.citySelect.prop('disabled', true);
                    }
                });

                // State change - show city dropdown and set data attributes for location.js
                this.stateSelect.on('change', function() {
                    const stateId = $(this).val();
                    const countryId = self.countrySelect.val();
                    
                    // Treat "0" or empty as placeholder/empty
                    if (stateId && stateId !== "0" && stateId !== "") {
                        // Set data attributes for location.js
                        self.citySelect.attr('data-state-id', stateId);
                        self.citySelect.attr('data-country-id', countryId);
                        
                        // Show and enable city dropdown
                        self.citySelect.prop('disabled', false);
                        self.citySelect.closest('.form-group-location').show();
                        
                        // Hide address
                        $('.form-group-location.address-group').hide();
                        
                        // Reset city (location.js will populate it)
                        self.citySelect.val('');
                    } else {
                        // Hide city and address
                        self.citySelect.closest('.form-group-location').hide();
                        $('.form-group-location.address-group').hide();
                        self.citySelect.prop('disabled', true);
                    }
                });

                // City change - show address field
                this.citySelect.on('change', function() {
                    const cityId = $(this).val();
                    
                    // Treat "0" or empty as placeholder/empty
                    if (cityId && cityId !== "0" && cityId !== "") {
                        $('.form-group-location.address-group').show();
                    } else {
                        $('.form-group-location.address-group').hide();
                    }
                });

                // Saved address radio selection
                $(document).on('change', '.location-address-radio', function() {
                    if ($(this).is(':checked')) {
                        self.selectedData.type = 'saved';
                        self.selectedData.addressId = $(this).val();
                        self.selectedData.tempAddress = null;
                        self.clearTemporaryForm();
                    }
                });

                // Temporary form field changes - deselect saved addresses
                this.countrySelect.add(this.stateSelect).add(this.citySelect).add(this.addressInput).on('change input', function() {
                    const val = $(this).val();
                    // Only deselect if value is not placeholder
                    if (val && val !== "0" && val !== "") {
                        $('.location-address-radio').prop('checked', false);
                        self.selectedData.type = 'temporary';
                        self.selectedData.addressId = null;
                    }
                });

                // Save button click
                this.saveBtn.on('click', function() {
                    self.saveLocation();
                });

                // Modal shown event - reinitialize location.js for this modal
                this.modal.on('shown.bs.modal', function() {
                    self.initializeModalState();
                });
            },

            clearTemporaryForm: function() {
                // Reset all form values (use "0" for country since EcommerceHelper provides it)
                this.countrySelect.val('0').trigger('change');
                this.stateSelect.val('');
                this.citySelect.val('');
                this.addressInput.val('');
                
                // Remove data attributes
                this.stateSelect.removeAttr('data-country-id');
                this.citySelect.removeAttr('data-country-id data-state-id');
                
                // Hide all progressive fields except country
                this.stateSelect.closest('.form-group-location').hide();
                this.stateSelect.prop('disabled', true);
                
                this.citySelect.closest('.form-group-location').hide();
                this.citySelect.prop('disabled', true);
                
                $('.form-group-location.address-group').hide();
            },

            initializeModalState: function() {
                const checkedRadio = $('.location-address-radio:checked');
                if (checkedRadio.length > 0) {
                    this.selectedData.type = 'saved';
                    this.selectedData.addressId = checkedRadio.val();
                    this.selectedData.tempAddress = null;
                }
            },

            validateTemporaryForm: function() {
                const country = this.countrySelect.val();
                const state = this.stateSelect.val();
                const city = this.citySelect.val();
                const address = this.addressInput.val().trim();

                if (!country || country === "0") {
                    this.showError('Please select a country');
                    return false;
                }
                if (!state || state === "0" || state === "") {
                    this.showError('Please select a state');
                    return false;
                }
                if (!city || city === "0" || city === "") {
                    this.showError('Please select a city');
                    return false;
                }
                if (!address) {
                    this.showError('Please enter a street address');
                    return false;
                }
                return true;
            },

            saveLocation: function() {
                const self = this;

                // Check if user selected a saved address
                if (this.selectedData.type === 'saved' && this.selectedData.addressId) {
                    // User selected saved address - no validation needed
                    this.saveToSession('saved', { addressId: this.selectedData.addressId }, function() {
                        self.updateDeliveryDisplay();
                        self.closeModal();
                    });
                } else {
                    // User is entering temporary address - check if any field is filled
                    const country = this.countrySelect.val();
                    const hasAnyInput = country && country !== "0";
                    
                    if (!hasAnyInput) {
                        // No input at all - just close modal (user might want to cancel)
                        self.closeModal();
                        return;
                    }
                    
                    // User started filling form - validate it
                    if (!this.validateTemporaryForm()) {
                        return;
                    }

                    const tempAddress = {
                        name: '', // Can be filled by user later or from auth
                        phone: '', // Can be filled by user later
                        email: '', // Will be auto-filled from customer email if logged in
                        country: this.countrySelect.val(),
                        state: this.stateSelect.val(),
                        city: this.citySelect.val(),
                        address: this.addressInput.val().trim(),
                        zip_code: ''
                    };

                    this.saveToSession('temporary', tempAddress, function() {
                        self.updateDeliveryDisplay();
                        self.closeModal();
                    });
                }
            },

            saveToSession: function(type, data, callback) {
                $.ajax({
                    url: '/ajax/save-temporary-location',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        type: type,
                        data: data
                    },
                    success: function(response) {
                        if (response.error === false) {
                            if (typeof callback === 'function') {
                                callback();
                            }
                        } else {
                            alert(response.message || 'Error saving location');
                        }
                    },
                    error: function() {
                        alert('Error saving location. Please try again.');
                    }
                });
            },

            updateDeliveryDisplay: function() {
                $.ajax({
                    url: '/ajax/get-delivery-display',
                    type: 'GET',
                    success: function(response) {
                        // BaseHttpResponse returns data inside 'data' property
                        if (response.data && response.data.html) {
                            $('#delivery-address-display').html(response.data.html);
                        } else if (response.html) {
                            // Fallback for direct html response
                            $('#delivery-address-display').html(response.html);
                        }
                        
                        // Reinitialize Bootstrap tooltips after updating display
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.map(function (tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    },
                    error: function() {
                        console.error('Failed to update delivery display');
                    }
                });
            },

            closeModal: function() {
                this.modal.fadeOut(300).removeClass('show');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            },

            showError: function(message) {
                alert(message);
            }
        };

        // Initialize on document ready
        $(document).ready(function() {
            LocationModal.init();

            // Trigger modal on button click
            $(document).on('click', '.delivery-change-btn, .delivery-add-btn, .trigger-location-modal', function(e) {
                e.preventDefault();
                
                const $modal = $('#location-modal');
                if ($modal.length > 0) {
                    $modal.fadeIn(300).addClass('show').css('display', 'block');
                    $('body').addClass('modal-open');
                    
                    if (!$('.modal-backdrop').length) {
                        $('<div class="modal-backdrop fade show"></div>').appendTo('body');
                    }
                }
            });
            
            // Close modal on backdrop click or close button
            $(document).on('click', '.modal-backdrop, [data-bs-dismiss="modal"]', function(e) {
                e.preventDefault();
                $('#location-modal').fadeOut(300).removeClass('show');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });
            
            // Prevent modal close when clicking inside modal content
            $(document).on('click', '.modal-dialog', function(e) {
                e.stopPropagation();
            });
        });

    })(jQuery);
}

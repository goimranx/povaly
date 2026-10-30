/**
 * Location Modal Handler
 * Manages temporary address selection and saved address selection
 */

// Wrap in IIFE with explicit jQuery check
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
        zipInput: null,
        saveBtn: null,
        selectedData: {
            type: null, // 'saved' or 'temporary'
            addressId: null, // For saved addresses
            tempAddress: null // For temporary addresses
        },

        init: function() {
            this.modal = $('#location-modal');
            this.form = $('#temporary-address-form');
            this.countrySelect = $('#temp-country');
            this.stateSelect = $('#temp-state');
            this.citySelect = $('#temp-city');
            this.addressInput = $('#temp-address');
            this.zipInput = $('#temp-zip');
            this.saveBtn = $('#save-location-btn');

            // Hide all fields except country initially
            this.stateSelect.closest('.form-group-location').hide();
            this.citySelect.closest('.form-group-location').hide();
            $('.form-group-location.address-group').hide();

            this.bindEvents();
        },

        bindEvents: function() {
            const self = this;

            // Country change - load states
            this.countrySelect.on('change', function() {
                self.loadStates($(this).val());
            });

            // State change - load cities
            this.stateSelect.on('change', function() {
                self.loadCities($(this).val(), self.countrySelect.val());
            });

            // City change - show address field
            this.citySelect.on('change', function() {
                if ($(this).val()) {
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
                    
                    // Clear temporary form
                    self.clearTemporaryForm();
                }
            });

            // Temporary form field changes - deselect saved addresses
            this.countrySelect.add(this.stateSelect).add(this.citySelect).add(this.addressInput).on('change input', function() {
                if ($(this).val()) {
                    // Deselect all saved addresses
                    $('.location-address-radio').prop('checked', false);
                    
                    self.selectedData.type = 'temporary';
                    self.selectedData.addressId = null;
                }
            });

            // Save button click
            this.saveBtn.on('click', function() {
                self.saveLocation();
            });

            // Modal shown event
            this.modal.on('shown.bs.modal', function() {
                self.initializeModalState();
            });
        },

        loadStates: function(countryId) {
            const self = this;

            if (!countryId) {
                this.stateSelect.html('<option value="">Select state...</option>').prop('disabled', true);
                this.stateSelect.closest('.form-group-location').hide();
                this.citySelect.html('<option value="">Select city...</option>').prop('disabled', true);
                this.citySelect.closest('.form-group-location').hide();
                $('.form-group-location.address-group').hide();
                return;
            }

            // Enable and show loading
            this.stateSelect.prop('disabled', true).html('<option value="">Loading...</option>');
            this.citySelect.html('<option value="">Select city...</option>').prop('disabled', true);
            this.citySelect.closest('.form-group-location').hide();
            $('.form-group-location.address-group').hide();

            $.ajax({
                url: this.stateSelect.data('url'),
                type: 'GET',
                data: { country_id: countryId },
                success: function(response) {
                    let options = '<option value="">Select state...</option>';
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(state) {
                            if (state.id && state.id !== 0) {
                                options += `<option value="${state.id}">${state.name}</option>`;
                            }
                        });
                    }

                    self.stateSelect.html(options).prop('disabled', false);
                    // Show state dropdown after country is selected
                    self.stateSelect.closest('.form-group-location').show();
                },
                error: function() {
                    self.stateSelect.html('<option value="">Error loading states</option>').prop('disabled', false);
                    self.stateSelect.closest('.form-group-location').show();
                }
            });
        },

        loadCities: function(stateId, countryId) {
            const self = this;

            if (!stateId) {
                this.citySelect.html('<option value="">Select city...</option>').prop('disabled', true);
                this.citySelect.closest('.form-group-location').hide();
                $('.form-group-location.address-group').hide();
                return;
            }

            // Enable and show loading
            this.citySelect.prop('disabled', true).html('<option value="">Loading...</option>');
            $('.form-group-location.address-group').hide();

            $.ajax({
                url: this.citySelect.data('url'),
                type: 'GET',
                data: { 
                    state_id: stateId,
                    country_id: countryId
                },
                success: function(response) {
                    let options = '<option value="">Select city...</option>';
                    
                    if (response.data && response.data.length > 0) {
                        response.data.forEach(function(city) {
                            if (city.id && city.id !== 0) {
                                options += `<option value="${city.id}">${city.name}</option>`;
                            }
                        });
                    }

                    self.citySelect.html(options).prop('disabled', false);
                    // Show city dropdown after state is selected
                    self.citySelect.closest('.form-group-location').show();
                },
                error: function() {
                    self.citySelect.html('<option value="">Error loading cities</option>').prop('disabled', false);
                    self.citySelect.closest('.form-group-location').show();
                }
            });
        },

        clearTemporaryForm: function() {
            this.form[0].reset();
            this.stateSelect.html('<option value="">Select state...</option>').prop('disabled', true);
            this.citySelect.html('<option value="">Select city...</option>').prop('disabled', true);
        },

        initializeModalState: function() {
            // Check if there's already a saved address selected
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

            if (!country) {
                this.showError('Please select a country');
                return false;
            }

            if (!state) {
                this.showError('Please select a state');
                return false;
            }

            if (!city) {
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

            if (this.selectedData.type === 'saved' && this.selectedData.addressId) {
                // User selected a saved address
                this.saveToSession('saved', { addressId: this.selectedData.addressId }, function() {
                    self.updateDeliveryDisplay();
                    self.modal.modal('hide');
                });
            } else {
                // User is using temporary address
                if (!this.validateTemporaryForm()) {
                    return;
                }

                const tempAddress = {
                    country: this.countrySelect.val(),
                    country_name: this.countrySelect.find('option:selected').text(),
                    state: this.stateSelect.val(),
                    state_name: this.stateSelect.find('option:selected').text(),
                    city: this.citySelect.val(),
                    city_name: this.citySelect.find('option:selected').text(),
                    address: this.addressInput.val().trim(),
                    zip_code: this.zipInput.val().trim()
                };

                this.saveToSession('temporary', tempAddress, function() {
                    self.updateDeliveryDisplay();
                    self.modal.modal('hide');
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
            // Reload the delivery details section
            $.ajax({
                url: '/ajax/get-delivery-display',
                type: 'GET',
                success: function(response) {
                    if (response.html) {
                        $('#delivery-address-display').html(response.html);
                    }
                },
                error: function() {
                    console.error('Error updating delivery display');
                }
            });
        },

        showError: function(message) {
            // You can customize this to use your preferred notification system
            alert(message);
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        LocationModal.init();

        // Trigger modal on button click - use simple jQuery show
        $(document).on('click', '.delivery-change-btn, .delivery-add-btn, .trigger-location-modal', function(e) {
            e.preventDefault();
            
            const $modal = $('#location-modal');
            if ($modal.length > 0) {
                $modal.fadeIn(300).addClass('show').css('display', 'block');
                $('body').addClass('modal-open');
                
                // Add backdrop if not exists
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

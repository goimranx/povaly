{{-- Location Selection Modal --}}
<div class="modal fade" id="location-modal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">{{ __('Select Delivery Location') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body">
                @if (auth('customer')->check())
                    {{-- Logged-in User: Show Saved Addresses --}}
                    <div class="location-saved-addresses">
                        <h6 class="location-section-title">{{ __('Saved Addresses') }}</h6>
                        
                        @php
                            $userAddresses = auth('customer')->user()->addresses;
                        @endphp
                        
                        @if ($userAddresses->isNotEmpty())
                            <div class="location-addresses-list">
                                @foreach ($userAddresses as $address)
                                    <div class="location-address-item">
                                        <input 
                                            type="radio" 
                                            name="selected_address" 
                                            id="address-{{ $address->id }}" 
                                            value="{{ $address->id }}"
                                            class="location-address-radio"
                                            {{ $address->is_default ? 'checked' : '' }}
                                        >
                                        <label for="address-{{ $address->id }}" class="location-address-label">
                                            <div class="location-address-content">
                                                <div class="location-address-name">{{ $address->name }}</div>
                                                <div class="location-address-details">
                                                    {{ $address->address }}, {{ $address->city }}
                                                    @if ($address->state), {{ $address->state }}@endif
                                                    - {{ $address->zip_code }}
                                                </div>
                                                @if ($address->is_default)
                                                    <span class="location-address-badge">{{ __('Default') }}</span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="location-no-address-message">
                                <p>{{ __('Povaly auto sets the location to a general location based on your country.') }}</p>
                            </div>
                        @endif
                        
                        <div class="location-manage-btn-wrapper">
                            <a href="{{ route('customer.address') }}" class="btn btn-outline-primary btn-sm">
                                <i class="ph ph-address-book"></i> {{ __('Manage address book') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="location-or-separator">
                        <span>{{ __('OR') }}</span>
                    </div>
                @else
                    {{-- Guest User: Show Login Button --}}
                    <div class="location-guest-section">
                        <div class="location-guest-message">
                            <i class="ph ph-user-circle"></i>
                            <p>{{ __('Login to view and manage your saved addresses') }}</p>
                        </div>
                        <a href="{{ route('customer.login') }}" class="btn btn-primary w-100">
                            <i class="ph ph-sign-in"></i> {{ __('Login to view your addresses') }}
                        </a>
                    </div>
                    
                    <div class="location-or-separator">
                        <span>{{ __('OR') }}</span>
                    </div>
                @endif
                
                {{-- Temporary Address Form (For Both Logged-in & Guest) --}}
                <div class="location-temporary-address">
                    <h6 class="location-section-title">{{ __('Use temporary address for this session') }}</h6>
                    <p class="location-temp-description">{{ __('This address will be used only for your current checkout and won\'t be saved.') }}</p>
                    
                    <form id="temporary-address-form" class="location-temp-form">
                        <div class="row g-3">
                            {{-- Country --}}
                            <div class="col-12">
                                <label for="temp-country" class="form-label">{{ __('Country') }} <span class="text-danger">*</span></label>
                                <select name="country" id="temp-country" class="form-select" required>
                                    <option value="">{{ __('Select country...') }}</option>
                                    @foreach(\Botble\Ecommerce\Facades\EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                                        <option value="{{ $countryCode }}">{{ $countryName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- State/Division --}}
                            <div class="col-12">
                                <label for="temp-state" class="form-label">{{ __('State / Division') }} <span class="text-danger">*</span></label>
                                <select 
                                    name="state" 
                                    id="temp-state" 
                                    class="form-select" 
                                    data-type="state"
                                    data-url="{{ route('ajax.states-by-country') }}"
                                    required
                                    disabled
                                >
                                    <option value="">{{ __('Select state...') }}</option>
                                </select>
                            </div>
                            
                            {{-- City --}}
                            <div class="col-12">
                                <label for="temp-city" class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                <select 
                                    name="city" 
                                    id="temp-city" 
                                    class="form-select" 
                                    data-type="city"
                                    data-url="{{ route('ajax.cities-by-state') }}"
                                    required
                                    disabled
                                >
                                    <option value="">{{ __('Select city...') }}</option>
                                </select>
                            </div>
                            
                            {{-- Street Address --}}
                            <div class="col-12">
                                <label for="temp-address" class="form-label">{{ __('Street Address') }} <span class="text-danger">*</span></label>
                                <input 
                                    type="text" 
                                    name="address" 
                                    id="temp-address" 
                                    class="form-control" 
                                    placeholder="{{ __('Enter street address') }}"
                                    required
                                >
                            </div>
                            
                            {{-- Zip Code (Optional) --}}
                            <div class="col-12">
                                <label for="temp-zip" class="form-label">{{ __('Zip Code') }}</label>
                                <input 
                                    type="text" 
                                    name="zip_code" 
                                    id="temp-zip" 
                                    class="form-control" 
                                    placeholder="{{ __('Enter zip code') }}"
                                >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary" id="save-location-btn">{{ __('Save & Continue') }}</button>
            </div>
        </div>
    </div>
</div>

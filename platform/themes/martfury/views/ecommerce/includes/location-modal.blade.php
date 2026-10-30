{{-- Location Selection Modal - Amazon Style --}}
<div class="modal fade location-modal" id="location-modal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">{{ __('Choose your location') }}</h5>
                <button type="button" class="btn-modal-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z" fill="currentColor"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <p class="modal-description">{{ __('Delivery options and delivery speeds may vary for different locations') }}</p>
                
                @if (auth('customer')->check())
                    {{-- Logged-in User: Show saved addresses --}}
                    @php
                        $userAddresses = auth('customer')->user()->addresses()->orderBy('is_default', 'desc')->get();
                    @endphp
                    
                    @if ($userAddresses->isNotEmpty())
                        <div class="saved-addresses-section">
                            @foreach($userAddresses as $address)
                                <div class="saved-address-item">
                                    <label class="saved-address-label">
                                        <input 
                                            type="radio" 
                                            name="saved_address" 
                                            value="{{ $address->id }}" 
                                            class="location-address-radio"
                                            {{ $address->is_default ? 'checked' : '' }}
                                        >
                                        <div class="address-details">
                                            <div class="address-name">
                                                {{ $address->name }}
                                                @if($address->is_default)
                                                    <span class="badge-default">{{ __('Default') }}</span>
                                                @endif
                                            </div>
                                            <div class="address-full">
                                                {{ $address->address }}, {{ $address->city_name }}, {{ $address->state_name }} {{ $address->zip_code }}, {{ $address->country_name }}
                                            </div>
                                            @if($address->phone)
                                                <div class="address-phone">{{ __('Phone') }}: {{ $address->phone }}</div>
                                            @endif
                                            @if($address->is_default)
                                                <div class="address-default-label">{{ __('Default address') }}</div>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="location-manage-actions">
                            <a href="{{ route('customer.address') }}" class="btn-secondary-link">
                                {{ __('Manage address book') }}
                            </a>
                        </div>
                    @else
                        <div class="no-addresses-notice">
                            <p>{{ __('You have no saved addresses yet.') }}</p>
                        </div>
                        <div class="location-manage-actions">
                            <a href="{{ route('customer.address') }}" class="btn-secondary-link">
                                {{ __('Add an address') }}
                            </a>
                        </div>
                    @endif
                    
                    <div class="modal-or-divider">
                        <span>{{ __('or enter a location') }}</span>
                    </div>
                @else
                    {{-- Guest User: Show Sign In Button --}}
                    <a href="{{ route('customer.login') }}" class="btn-primary-action">
                        {{ __('Sign in to see your addresses') }}
                    </a>
                    
                    <div class="modal-or-divider">
                        <span>{{ __('or enter a location') }}</span>
                    </div>
                @endif
                
                {{-- Temporary Address Form (For Both Logged-in & Guest) --}}
                <form id="temporary-address-form" class="location-temp-form">
                    {{-- Country Dropdown (Always visible) --}}
                    <div class="form-group-location country-group">
                        <select 
                            name="country" 
                            id="temp-country" 
                            class="form-select-location select-search-location"
                            data-form-parent="#temporary-address-form"
                            data-type="country"
                        >
                            @foreach(\Botble\Ecommerce\Facades\EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                                <option value="{{ $countryCode }}">{{ $countryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- State Dropdown (Hidden until country selected) --}}
                    <div class="form-group-location state-group">
                        <select 
                            name="state" 
                            id="temp-state" 
                            class="form-select-location select-search-location" 
                            data-form-parent="#temporary-address-form"
                            data-type="state"
                            data-url="{{ route('ajax.states-by-country') }}"
                        >
                            <option value="">{{ __('Select state...') }}</option>
                        </select>
                    </div>
                    
                    {{-- City Dropdown (Hidden until state selected) --}}
                    <div class="form-group-location city-group">
                        <select 
                            name="city" 
                            id="temp-city" 
                            class="form-select-location select-search-location" 
                            data-form-parent="#temporary-address-form"
                            data-type="city"
                            data-url="{{ route('ajax.cities-by-state') }}"
                        >
                            <option value="">{{ __('Select city...') }}</option>
                        </select>
                    </div>
                    
                    {{-- Street Address (Hidden until city selected) --}}
                    <div class="form-group-location address-group">
                        <input 
                            type="text" 
                            name="address" 
                            id="temp-address" 
                            class="form-input-location" 
                            placeholder="{{ __('Enter street address') }}"
                        >
                    </div>
                    
                    {{-- Ship Outside Notice --}}
                    <p class="modal-ship-notice">
                        <a href="#" id="ship-outside-link">{{ __('or ship outside the country') }}</a>
                    </p>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-done" id="save-location-btn">{{ __('Done') }}</button>
            </div>
        </div>
    </div>
</div>

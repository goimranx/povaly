{{-- Location Selection Modal - Amazon Style --}}
@php
    // Get session data to determine which address is currently selected (SAME AS PRODUCT PAGE)
    $orderHelper = app(\Botble\Ecommerce\Supports\OrderHelper::class);
    $token = $orderHelper->getOrderSessionToken();
    $sessionData = $orderHelper->getOrderSessionData($token);
    $sessionAddressId = $sessionData['address_id'] ?? null;
    
    // Detect visitor's GeoIP country when no session or saved address exists (SAME AS PRODUCT PAGE)
    $visitorCountryCode = null;
    $shouldUseGeoIP = false;
    
    // Check if we should use GeoIP: no session address OR temporary address with no country set
    if (!$sessionAddressId || ($sessionAddressId === 'new' && empty($sessionData['country']))) {
        // For logged-in users, also check if they have any saved addresses
        if (auth('customer')->check()) {
            $hasAddresses = auth('customer')->user()->addresses()->count() > 0;
            $shouldUseGeoIP = !$hasAddresses;
        } else {
            // For guests, always use GeoIP if no session
            $shouldUseGeoIP = true;
        }
    }
    
    if ($shouldUseGeoIP) {
        try {
            if (function_exists('geoip')) {
                $geoip = geoip(request()->ip());
                $visitorCountryCode = $geoip->iso_code ?? null;
            } elseif (class_exists('\Stevebauman\Location\Facades\Location')) {
                $position = \Stevebauman\Location\Facades\Location::get(request()->ip());
                $visitorCountryCode = $position ? $position->countryCode : null;
            }
        } catch (\Exception $e) {
            // Fallback to default country if detection fails
            $defaultCountryId = \Botble\Ecommerce\Facades\EcommerceHelper::getFirstCountryId();
            $visitorCountryCode = \App\Helpers\DeliveryHelper::getCountryCodeById($defaultCountryId);
        }
        
        if (!$visitorCountryCode) {
            $defaultCountryId = \Botble\Ecommerce\Facades\EcommerceHelper::getFirstCountryId();
            $visitorCountryCode = \App\Helpers\DeliveryHelper::getCountryCodeById($defaultCountryId);
        }
    }
@endphp

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
                                            @if($sessionAddressId && $sessionAddressId !== 'new')
                                                {{-- Check saved address if it matches session --}}
                                                {{ $sessionAddressId == $address->id ? 'checked' : '' }}
                                            @else
                                                {{-- Default behavior: check default address if no session --}}
                                                {{ $address->is_default && !$sessionAddressId ? 'checked' : '' }}
                                            @endif
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
                <form id="temporary-address-form" class="location-temp-form" data-form-parent="#location-modal">
                    {{-- Country Dropdown (Always visible) --}}
                    <div class="form-group-location country-group">
                        <select 
                            name="country" 
                            id="temp-country" 
                            class="form-select-location"
                            data-type="country"
                            data-form-parent="#location-modal"
                            @if($sessionAddressId === 'new' && !empty($sessionData['country']))
                                data-init-value="{{ $sessionData['country'] }}"
                            @elseif($visitorCountryCode)
                                data-geoip-country="{{ $visitorCountryCode }}"
                            @endif
                        >
                            @foreach(\Botble\Ecommerce\Facades\EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                                <option 
                                    value="{{ $countryCode }}"
                                    @if($sessionAddressId === 'new' && ($sessionData['country'] ?? '') === $countryCode)
                                        selected
                                    @elseif(!$sessionAddressId && $visitorCountryCode === $countryCode)
                                        selected
                                    @endif
                                >{{ $countryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- State Field (Dropdown or Text based on plugin settings) --}}
                    <div class="form-group-location state-group" @if($sessionAddressId === 'new' && !empty($sessionData['state'])) style="display:block" @endif>
                        @if(\Botble\Ecommerce\Facades\EcommerceHelper::loadCountriesStatesCitiesFromPluginLocation())
                            <select 
                                name="state" 
                                id="temp-state" 
                                class="form-select-location" 
                                data-type="state"
                                data-form-parent="#location-modal"
                                data-url="{{ route('ajax.states-by-country') }}"
                                @if($sessionAddressId === 'new' && !empty($sessionData['country']))
                                    data-country-id="{{ $sessionData['country'] }}"
                                    data-init-value="{{ $sessionData['state'] ?? '' }}"
                                @else
                                    disabled
                                @endif
                            ></select>
                        @else
                            <input 
                                type="text" 
                                name="state" 
                                id="temp-state" 
                                class="form-input-location" 
                                placeholder="{{ __('Enter state/province') }}"
                                @if($sessionAddressId === 'new' && !empty($sessionData['state']))
                                    value="{{ $sessionData['state'] }}"
                                @endif
                            >
                        @endif
                    </div>
                    
                    {{-- City Field (Dropdown or Text based on plugin settings) --}}
                    <div class="form-group-location city-group" @if($sessionAddressId === 'new' && !empty($sessionData['city'])) style="display:block" @endif>
                        @if(\Botble\Ecommerce\Facades\EcommerceHelper::useCityFieldAsTextField())
                            <input 
                                type="text" 
                                name="city" 
                                id="temp-city" 
                                class="form-input-location" 
                                placeholder="{{ __('Enter city') }}"
                                @if($sessionAddressId === 'new' && !empty($sessionData['city']))
                                    value="{{ $sessionData['city'] }}"
                                @endif
                            >
                        @else
                            <select 
                                name="city" 
                                id="temp-city" 
                                class="form-select-location" 
                                data-type="city"
                                data-form-parent="#location-modal"
                                data-url="{{ route('ajax.cities-by-state') }}"
                                @if($sessionAddressId === 'new' && !empty($sessionData['state']))
                                    data-state-id="{{ $sessionData['state'] }}"
                                    data-country-id="{{ $sessionData['country'] }}"
                                    data-init-value="{{ $sessionData['city'] ?? '' }}"
                                @else
                                    disabled
                                @endif
                            ></select>
                        @endif
                    </div>
                    
                    {{-- Street Address (Hidden until city selected) --}}
                    <div class="form-group-location address-group" @if($sessionAddressId === 'new' && !empty($sessionData['address'])) style="display:block" @endif>
                        <input 
                            type="text" 
                            name="address" 
                            id="temp-address" 
                            class="form-input-location" 
                            placeholder="{{ __('Enter street address') }}"
                            @if($sessionAddressId === 'new' && !empty($sessionData['address']))
                                value="{{ $sessionData['address'] }}"
                            @endif
                        >
                    </div>
                    
                    {{-- Ship Outside Notice (Hidden for now) --}}
                    <p class="modal-ship-notice" style="display: none;">
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
                    <div class="form-group-location state-group">
                        <select 
                            name="state" 
                            id="temp-state" 
                            class="form-select-location" 
                            data-type="state"
                            data-form-parent="#location-modal"
                            data-url="{{ route('ajax.states-by-country') }}"
                            disabled
                        ></select>
                    </div>
                    
                    {{-- City Dropdown (Hidden until state selected) --}}
                    <div class="form-group-location city-group">
                        <select 
                            name="city" 
                            id="temp-city" 
                            class="form-select-location" 
                            data-type="city"
                            data-form-parent="#location-modal"
                            data-url="{{ route('ajax.cities-by-state') }}"
                            disabled
                        ></select>
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
                    
                    {{-- Ship Outside Notice (Hidden for now) --}}
                    <p class="modal-ship-notice" style="display: none;">
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

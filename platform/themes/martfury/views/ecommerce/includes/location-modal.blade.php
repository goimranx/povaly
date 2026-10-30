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
                    {{-- Logged-in User: Show Saved Addresses --}}
                    @php
                        $userAddresses = auth('customer')->user()->addresses;
                    @endphp
                    
                    @if ($userAddresses->isNotEmpty())
                        <a href="{{ route('customer.login') }}" class="btn-primary-action">
                            {{ __('Sign in to see your addresses') }}
                        </a>
                        
                        <div class="modal-or-divider">
                            <span>{{ __('or enter a location') }}</span>
                        </div>
                    @else
                        <div class="location-manage-actions">
                            <a href="{{ route('customer.address') }}" class="btn-secondary-link">
                                {{ __('Manage address book') }}
                            </a>
                        </div>
                        
                        <div class="modal-or-divider">
                            <span>{{ __('or enter a location') }}</span>
                        </div>
                    @endif
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
                    {{-- Country Dropdown --}}
                    <div class="form-group-location">
                        <select name="country" id="temp-country" class="form-select-location">
                            <option value="">{{ __('Select country...') }}</option>
                            @foreach(\Botble\Ecommerce\Facades\EcommerceHelper::getAvailableCountries() as $countryCode => $countryName)
                                <option value="{{ $countryCode }}">{{ $countryName }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-row-location">
                        {{-- State Dropdown --}}
                        <div class="form-group-location">
                            <select 
                                name="state" 
                                id="temp-state" 
                                class="form-select-location" 
                                data-type="state"
                                data-url="{{ route('ajax.states-by-country') }}"
                                disabled
                            >
                                <option value="">{{ __('Select state...') }}</option>
                            </select>
                        </div>
                        
                        {{-- City Dropdown --}}
                        <div class="form-group-location">
                            <select 
                                name="city" 
                                id="temp-city" 
                                class="form-select-location" 
                                data-type="city"
                                data-url="{{ route('ajax.cities-by-state') }}"
                                disabled
                            >
                                <option value="">{{ __('Select city...') }}</option>
                            </select>
                        </div>
                    </div>
                    
                    {{-- Ship Outside Notice --}}
                    <p class="modal-ship-notice">{{ __('or ship outside the country') }}</p>
                </form>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-done" id="save-location-btn">{{ __('Done') }}</button>
            </div>
        </div>
    </div>
</div>

{{-- Delivered by date --}}
<div class="delivery-date-info">
    {{ __('Delivered by around') }} <strong>{{ now()->addDays(7)->format('l, F j') }}</strong>
</div>

{{-- Deliver to section with icon --}}
<div class="delivery-location-row">
    <i class="ph ph-map-pin delivery-location-icon"></i>
    <div class="delivery-location-content">
        @if ($type === 'temporary')
            {{-- Temporary Address --}}
            <div class="delivery-address-text">
                <span class="delivery-location-label">{{ __('Deliver to') }}</span>
                @php
                    // Get name-based fields for display
                    $countryName = \Botble\Ecommerce\Facades\EcommerceHelper::getCountryNameById($address->country ?? '');
                    $stateName = '';
                    $cityName = '';
                    
                    if (!empty($address->state)) {
                        $state = \Botble\Location\Models\State::find($address->state);
                        $stateName = $state ? $state->name : '';
                    }
                    
                    if (!empty($address->city)) {
                        $city = \Botble\Location\Models\City::find($address->city);
                        $cityName = $city ? $city->name : '';
                    }
                @endphp
                {{ $address->address }}
                @if ($cityName), {{ $cityName }}@endif
                @if ($stateName), {{ $stateName }}@endif
                @if ($countryName), {{ $countryName }}@endif
                
                {{-- Info icon with tooltip for temporary address --}}
                <span class="delivery-temp-info" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('This is a session-based delivery address. It will be used for this session only.') }}">
                    <i class="ph ph-info"></i>
                </span>
            </div>
        @else
            {{-- Saved or Default Address --}}
            <div class="delivery-address-text">
                <span class="delivery-location-label">{{ __('Deliver to') }}</span>
                {{ $address->name }}, {{ $address->address }}, {{ $address->city_name ?? $address->city }}
                @if (!empty($address->state_name ?? $address->state)), {{ $address->state_name ?? $address->state }}@endif
                @if (!empty($address->zip_code)) - {{ $address->zip_code }}@endif
            </div>
        @endif
    </div>
    <a href="#" class="delivery-change-btn trigger-location-modal">{{ __('Change') }}</a>
</div>

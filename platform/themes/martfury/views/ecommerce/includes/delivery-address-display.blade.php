@if ($type === 'temporary')
    {{-- Temporary Address --}}
    <div class="delivery-address-text">
        <span class="delivery-temp-badge">{{ __('Temporary') }}</span>
        @php
            // Get name-based fields for display (country_name, state_name, city_name)
            $countryName = \Botble\Ecommerce\Facades\EcommerceHelper::getCountryNameById($address->country ?? '');
            $stateName = $address->state ? get_state_by_id($address->state) : '';
            $cityName = $address->city ? get_city_by_id($address->city) : '';
        @endphp
        {{ $address->address }}
        @if ($cityName), {{ $cityName }}@endif
        @if ($stateName), {{ $stateName }}@endif
        @if ($countryName), {{ $countryName }}@endif
    </div>
    <a href="#" class="delivery-change-btn trigger-location-modal">{{ __('Change') }}</a>
@else
    {{-- Saved or Default Address --}}
    <div class="delivery-address-text">
        {{ $address->name }}, {{ $address->address }}, {{ $address->city_name ?? $address->city }}
        @if (!empty($address->state_name ?? $address->state)), {{ $address->state_name ?? $address->state }}@endif
        @if (!empty($address->zip_code)) - {{ $address->zip_code }}@endif
    </div>
    <a href="#" class="delivery-change-btn trigger-location-modal">{{ __('Change') }}</a>
@endif

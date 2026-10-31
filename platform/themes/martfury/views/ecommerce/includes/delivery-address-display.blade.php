@if ($type === 'temporary')
    {{-- Temporary Address --}}
    <div class="delivery-address-text">
        <span class="delivery-temp-badge">{{ __('Temporary') }}</span>
        {{ $address->address }}, {{ $address->city_name }}
        @if (!empty($address->state_name)), {{ $address->state_name }}@endif
        , {{ $address->country_name }}
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

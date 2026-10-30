@if ($type === 'temporary')
    {{-- Temporary Address --}}
    <div class="delivery-address-text">
        <span class="delivery-temp-badge">{{ __('Temporary') }}</span>
        {{ $address->address }}, {{ $address->city_name }}
        @if ($address->state_name), {{ $address->state_name }}@endif
        @if ($address->zip_code) - {{ $address->zip_code }}@endif
    </div>
    <a href="#" class="delivery-change-btn trigger-location-modal">{{ __('Change') }}</a>
@else
    {{-- Saved or Default Address --}}
    <div class="delivery-address-text">
        {{ $address->name }}, {{ $address->address }}, {{ $address->city }}
        @if ($address->state), {{ $address->state }}@endif
        - {{ $address->zip_code }}
    </div>
    <a href="#" class="delivery-change-btn trigger-location-modal">{{ __('Change') }}</a>
@endif

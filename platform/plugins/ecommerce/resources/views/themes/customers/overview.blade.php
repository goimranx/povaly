@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', trans('plugins/ecommerce::customer-dashboard.overview'))

@section('content')
    @php
        $customer = auth('customer')->user();
        EcommerceHelper::registerThemeAssets();
    @endphp

    {!! apply_filters('ecommerce_customer_dashboard_before_content', '', $customer) !!}

    <!-- Welcome Section -->
    <div class="bb-customer-profile-wrapper">
        <div class="bb-customer-profile">
            <div class="bb-customer-profile-left">
                <div class="bb-customer-profile-avatar">
                    {{ RvMedia::image($customer->avatar_url, $customer->name, attributes: ['class' => 'bb-customer-profile-avatar-img']) }}
                    <div class="bb-customer-profile-avatar-overlay">
                        <input type="file" id="customer-avatar" name="avatar" data-bb-toggle="change-customer-avatar" data-url="{{ route('customer.avatar') }}" />
                        <label for="customer-avatar" title="{{ trans('plugins/ecommerce::customer-dashboard.change_avatar') }}">
                            <x-core::icon name="ti ti-camera" />
                        </label>
                    </div>
                </div>
                <div class="bb-customer-profile-info">
                    <h2 class="bb-customer-profile-name">{{ $customer->name }}</h2>
                    <p class="bb-customer-profile-email">{{ $customer->email }}</p>
                </div>
            </div>
            <div class="bb-customer-profile-right">
                <a href="{{ route('customer.edit-account') }}" class="bb-customer-profile-link">{{ __('Edit account') }}</a>
                <a href="{{ route('customer.logout') }}" class="bb-customer-profile-link">{{ __('Sign out') }}</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <a href="{{ route('customer.orders') }}" class="bb-quick-action-card">
                <div class="bb-quick-action-icon">
                    <x-core::icon name="ti ti-shopping-bag" />
                </div>
                <div class="bb-quick-action-content">
                    <h5 class="bb-quick-action-title">{{ trans('plugins/ecommerce::customer-dashboard.view_orders') }}</h5>
                    <p class="bb-quick-action-text">{{ trans('plugins/ecommerce::customer-dashboard.track_orders_description') }}</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('customer.address') }}" class="bb-quick-action-card">
                <div class="bb-quick-action-icon">
                    <x-core::icon name="ti ti-map-pin" />
                </div>
                <div class="bb-quick-action-content">
                    <h5 class="bb-quick-action-title">{{ trans('plugins/ecommerce::customer-dashboard.manage_addresses') }}</h5>
                    <p class="bb-quick-action-text">{{ trans('plugins/ecommerce::customer-dashboard.update_addresses_description') }}</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('customer.edit-account') }}" class="bb-quick-action-card">
                <div class="bb-quick-action-icon">
                    <x-core::icon name="ti ti-settings" />
                </div>
                <div class="bb-quick-action-content">
                    <h5 class="bb-quick-action-title">{{ trans('plugins/ecommerce::customer-dashboard.account_settings') }}</h5>
                    <p class="bb-quick-action-text">{{ trans('plugins/ecommerce::customer-dashboard.edit_profile_description') }}</p>
                </div>
            </a>
        </div>
    </div>

    <!-- First Order Prompt -->
    @if (! $customer->orders()->exists())
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body">
                <div class="row align-items-center g-3">
                    <div class="col-auto">
                        <span class="bg-info rounded-circle p-3 d-inline-flex">
                            <x-core::icon name="ti ti-shopping-cart" class="text-white" size="lg" />
                        </span>
                    </div>
                    <div class="col">
                        <h5 class="card-title h6 mb-1">{{ trans('plugins/ecommerce::customer-dashboard.ready_to_start_shopping') }}</h5>
                        <p class="card-text text-muted small mb-0">
                            {{ trans('plugins/ecommerce::customer-dashboard.no_orders_browse_description') }}
                        </p>
                    </div>
                    <div class="col-12 col-md-auto">
                        <a href="{{ route('public.products') }}" class="btn btn-outline-info w-100 w-md-auto">
                            <x-core::icon name="ti ti-shopping-bag" class="me-1" />
                            {{ trans('plugins/ecommerce::customer-dashboard.browse_products') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {!! apply_filters('ecommerce_customer_dashboard_after_content', '', $customer) !!}
@endsection

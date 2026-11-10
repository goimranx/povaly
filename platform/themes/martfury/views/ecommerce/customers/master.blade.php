 <section class="ps-section--account crop-avatar customer-page bb-customer-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="ps-section__left">
                    <aside class="ps-widget--account-dashboard">
                        <div class="ps-widget__header">
                            <div class="ps-widget__header-content">
                                <h2 class="ps-widget__title">@yield('title', __('Your Account'))</h2>
                                <p class="ps-widget__subtitle">
                                    {{ auth('customer')->user()->name }}, Email: {{ auth('customer')->user()->email }}
                                </p>
                            </div>
                        </div>
                        <div class="ps-widget__content">
                            <ul>
                                @foreach (DashboardMenu::getAll('customer') as $item)
                                    <li class="nav-item text-truncate" id="{{ $item['id'] }}">
                                        <a
                                            class="nav-link
                                            @if ($item['active']) active @endif"
                                                href="{{ $item['url']  }}"
                                                aria-current="@if ($item['active']) true @else false @endif"
                                            title="{{ __($item['name']) }}"
                                            >
                                            @if ($item['icon'])
                                                <x-core::icon :name="$item['icon']" />
                                            @endif
                                            {{ __($item['name']) }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="ps-section__right">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    {!! do_shortcode('[trending-products title="Customers who shop on our store mostly viewed" limit="12" enable_caching="no" enable_lazy_loading="no"][/trending-products]') !!}
    
    {!! do_shortcode('[recently-viewed-products title="Your browsing history" enable_caching="no" enable_lazy_loading="no"][/recently-viewed-products]') !!}
</div>

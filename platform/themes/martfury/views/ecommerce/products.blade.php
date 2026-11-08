    <div class="ps-page--shop">
    <div class="bg-light py-2 mb-3 filter-sort-bar">
        <div class="container-fluid filter-sort-container">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3 d-xl-none px-2 px-sm-3 filter-button-col">
                    <div class="header__filter d-xl-none mx-auto mb-3 mb-sm-0">
                        <button id="products-filter-sidebar" type="button">
                            <i class="icon-equalizer"></i><span class="ms-2">{{ __('Filter') }}</span>
                        </button>
                    </div>
                </div>
                <div class="col-12 col-md-3 col-xl-6 d-none d-md-block">
                    <div class="products-found pt-2">
                        <strong>{{ $products->total() }}</strong><span class="ms-1">{{ __('Products found') }}</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6 px-2 px-sm-3 sort-layout-col">
                    <div class="d-flex justify-content-center justify-content-sm-end">
                        @include(Theme::getThemeNamespace() . '::views/ecommerce/includes/sort')
                        <div class="ps-shopping__view ms-2">
                            <ul class="products-layout mb-0 p-0">
                                <li @if (request()->get('layout') != 'list') class="active" @endif><a href="javascript:void(0)" data-layout="grid"><i class="icon-grid"></i></a></li>
                                <li @if (request()->get('layout') == 'list') class="active" @endif><a href="javascript:void(0)" data-layout="list"><i class="icon-list4"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ps-layout--shop">
        <div class="ps-layout__left">
            <div class="screen-darken"></div>
            <div class="ps-layout__left-container">
                <div class="ps-filter__header d-block d-xl-none">
                    <h3>{{ __('Filter Products') }}</h3><a class="ps-btn--close ps-btn--no-border" href="#"></a>
                </div>
                <div class="ps-layout__left-content">
                    {{-- Sort dropdown - shown only on mobile ≤480px --}}
                    <div class="ps-mobile-sort mb-3" style="display: none;">
                        @include(Theme::getThemeNamespace() . '::views/ecommerce/includes/sort')
                    </div>
                    
                    @include(Theme::getThemeNamespace() . '::views.ecommerce.includes.filters')
                </div>
            </div>
        </div>
        <div class="ps-layout__right">
            @include(Theme::getThemeNamespace('views.ecommerce.includes.products-list'))
            
            @if (theme_option('show_recommend_items_on_products_page', 'yes') == 'yes')
                {!! do_shortcode('[trending-products title="Most viewed products" limit="12" enable_caching="no" enable_lazy_loading="no"][/trending-products]') !!}
            @endif
        </div>
    </div>
</div>

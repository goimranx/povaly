@if (Theme::get('showHeader') !== false)
    {!! Theme::partial('header') !!}
@endif
<main>
    @if (Theme::breadcrumb()->enabled())
        <div class="ps-breadcrumb">
            <div class="ps-container">
                {!! Theme::partial('breadcrumbs') !!}
            </div>
        </div>
    @endif

    <div class="ps-container">
        <div class="mb-40">
            {!! Theme::content() !!}
        </div>
    </div>
</main>

@if (Theme::get('showFooter') !== false)
    {!! Theme::partial('footer') !!}
@endif

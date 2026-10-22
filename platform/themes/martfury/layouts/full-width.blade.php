{!! Theme::partial('header') !!}
<main>
    @if (Theme::breadcrumb()->enabled())
        <div class="ps-breadcrumb">
            <div class="ps-container">
                {!! Theme::partial('breadcrumbs') !!}
            </div>
        </div>
    @endif

    {!! Theme::content() !!}
</main>

{!! Theme::partial('footer') !!}

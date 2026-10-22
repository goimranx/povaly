{!! Theme::partial('header') !!}
<main>
    @if (Theme::breadcrumb()->enabled())
        <div class="ps-breadcrumb">
            <div class="ps-container">
                {!! Theme::partial('breadcrumbs') !!}
            </div>
        </div>
    @endif

    <div class="ps-container">
        <div class="mt-40 mb-40">
            {!! Theme::content() !!}
        </div>
    </div>
</main>

{!! Theme::partial('footer') !!}

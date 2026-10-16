{!! Theme::partial('header') !!}
<main>
    <div class="ps-breadcrumb">
        <div class="ps-container">
            {!! Theme::partial('breadcrumbs') !!}
        </div>
    </div>

    <div class="ps-container">
        <div class="mt-40 mb-40">
            {!! Theme::content() !!}
        </div>
    </div>
</main>

{!! Theme::partial('footer') !!}

{!! Theme::partial('header') !!}
<main>
    <div class="ps-breadcrumb">
        <div class="ps-container">
            {!! Theme::partial('breadcrumbs') !!}
        </div>
    </div>

    {!! Theme::content() !!}
</main>

{!! Theme::partial('footer') !!}

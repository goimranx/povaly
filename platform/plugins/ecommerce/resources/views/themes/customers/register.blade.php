@if(theme_option('logo_black'))
    <div class="auth-logo" style="text-align: center; margin-bottom: 30px;">
        <a href="{{ route('public.index') }}">
            <img src="{{ RvMedia::getImageUrl(theme_option('logo_black')) }}" alt="{{ theme_option('site_title') }}" style="max-height: 60px; height: auto;">
        </a>
    </div>
@elseif(theme_option('logo'))
    <div class="auth-logo" style="text-align: center; margin-bottom: 30px;">
        <a href="{{ route('public.index') }}">
            <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="{{ theme_option('site_title') }}" style="max-height: 60px; height: auto;">
        </a>
    </div>
@endif

{!! $form->renderForm() !!}

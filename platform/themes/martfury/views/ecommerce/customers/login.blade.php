@php
    Theme::layout('minimal');
@endphp

<div class="ps-my-account">
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
    
    {!!
        $form
            ->formClass('ps-form--account')
            ->modify('submit', 'submit', [
                    'attr' => [
                        'class' => 'ps-btn ps-btn--fullwidth',
                    ],
                ])
            ->modify('remember', 'html', ['html' => sprintf('<div class="col-6"><div class="ps-checkbox">
                        <input class="form-control" type="checkbox" name="remember" id="remember-me">
                        <label for="remember-me" class="control-label">%s</label>
                    </div></div>', __('Remember me'))], true)
            ->renderForm()
    !!}
</div>

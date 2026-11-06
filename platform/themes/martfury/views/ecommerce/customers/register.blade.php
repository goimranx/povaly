@php
    Theme::layout('minimal');
@endphp

<div class="ps-my-account">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-8">
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
                        ->renderForm()
                !!}
            </div>
        </div>
    </div>
</div>

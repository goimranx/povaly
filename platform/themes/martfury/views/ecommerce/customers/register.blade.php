@php
    Theme::layout('minimal');
@endphp

<div class="ps-my-account">
    {!!
        $form
            ->formClass('ps-form--account')
            ->modify('submit', 'submit', [
                    'attr' => [
                        'class' => 'ps-btn ps-btn--fullwidth',
                    ],
                ])
            ->before('open', function() {
                $logoHtml = '';
                if(theme_option('logo_black')) {
                    $logoHtml = '<div class="auth-logo" style="text-align: center; margin-bottom: 30px;">
                        <a href="' . route('public.index') . '">
                            <img src="' . RvMedia::getImageUrl(theme_option('logo_black')) . '" alt="' . theme_option('site_title') . '" style="max-height: 60px; height: auto;">
                        </a>
                    </div>';
                } elseif(theme_option('logo')) {
                    $logoHtml = '<div class="auth-logo" style="text-align: center; margin-bottom: 30px;">
                        <a href="' . route('public.index') . '">
                            <img src="' . RvMedia::getImageUrl(theme_option('logo')) . '" alt="' . theme_option('site_title') . '" style="max-height: 60px; height: auto;">
                        </a>
                    </div>';
                }
                return $logoHtml;
            })
            ->renderForm()
    !!}
</div>

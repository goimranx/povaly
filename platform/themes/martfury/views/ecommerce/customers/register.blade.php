@php
    Theme::set('showHeader', false);
    Theme::set('showFooter', false);
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
            ->renderForm()
    !!}
</div>

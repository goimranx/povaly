@php
    $icon = Arr::get($formOptions, 'icon');
    $heading = Arr::get($formOptions, 'heading');
    $description = Arr::get($formOptions, 'description');
    $bannerDirection = Arr::get($formOptions, 'bannerDirection', 'vertical');

    // Rendered right above the fields, for pages that need to surface session
    // messages (e.g. the auth pages).
    $messagesView = Arr::get($formOptions, 'messagesView');

    $banner = Arr::get($formOptions, 'banner');

    if (! $banner) {
        $bannerDirection = 'vertical';
    }
@endphp

@if (Arr::get($formOptions, 'has_wrapper', 'yes') === 'yes')
    <div class="auth-wrapper">
        {{-- Logo --}}
        @if(theme_option('logo_black'))
            <div class="auth-logo">
                <a href="{{ route('public.index') }}">
                    <img src="{{ RvMedia::getImageUrl(theme_option('logo_black')) }}" alt="{{ theme_option('site_title') }}">
                </a>
            </div>
        @elseif(theme_option('logo'))
            <div class="auth-logo">
                <a href="{{ route('public.index') }}">
                    <img src="{{ RvMedia::getImageUrl(theme_option('logo')) }}" alt="{{ theme_option('site_title') }}">
                </a>
            </div>
        @endif

        {{-- Auth Card --}}
        <div class="auth-card">
            @if ($banner)
                {{ RvMedia::image($banner, $heading ?: '', attributes: ['class' => 'auth-card__banner']) }}
            @endif

            @if ($icon || $heading || $description)
                <div class="auth-card__header">
                    @if ($icon)
                        <div class="auth-card__header-icon">
                            <x-core::icon :name="$icon" />
                        </div>
                    @endif
                    @if ($heading)
                        <h3 class="auth-card__header-title">{{ $heading }}</h3>
                    @endif
                    @if ($description)
                        <p class="auth-card__header-description">{{ $description }}</p>
                    @endif
                </div>
            @endif
            
            <div class="auth-card__body">
                            @if ($showStart)
                                {{-- The card options below drive this template only; without excluding
                                     them Form::open renders each one as an attribute on the form tag. --}}
                                {!! Form::open(Arr::except($formOptions, [
                                    'template',
                                    'icon',
                                    'heading',
                                    'description',
                                    'banner',
                                    'bannerDirection',
                                    'messagesView',
                                    'has_wrapper',
                                ])) !!}
                            @endif

                            @includeWhen($messagesView, $messagesView)

                            @if ($showFields)
                                {{ $form->getOpenWrapperFormColumns() }}

                                @foreach ($fields as $field)
                                    @continue(in_array($field->getName(), $exclude))

                                    {!! $field->render() !!}
                                @endforeach

                                {{ $form->getCloseWrapperFormColumns() }}
                            @endif

                            @if ($showEnd)
                                {!! Form::close() !!}
                            @endif

                            @if ($form->getValidatorClass())
                                @push('footer')
                                    {!! $form->renderValidatorJs() !!}
                                @endpush
                            @endif
            </div>
        </div>
    </div>
@endif

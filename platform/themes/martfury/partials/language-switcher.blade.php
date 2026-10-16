@php
    $supportedLocales = Language::getSupportedLocales();
    if (!isset($options) || empty($options)) {
        $options = [
            'before' => '',
            'lang_flag' => true,
            'lang_name' => true,
            'class' => '',
            'after' => '',
        ];
    }
@endphp

@if ($supportedLocales && count($supportedLocales) > 1)
    @php
        $languageDisplay = setting('language_display', 'all');
    @endphp
    @if (setting('language_switcher_display', 'dropdown') == 'dropdown')
        <li class="bb-locale-switcher">
            {!! Arr::get($options, 'before') !!}
            <div class="bb-lang-flyout-wrap">
                <button type="button" class="bb-lang-trigger" aria-haspopup="true" aria-expanded="false">
                    {!! language_flag(Language::getCurrentLocaleFlag(), Language::getCurrentLocaleName()) !!}
                    <span class="bb-lang-iso">{{ strtoupper(Language::getCurrentLocale()) }}</span>
                </button>
                <div class="bb-lang-flyout {{ Arr::get($options, 'class') }}" role="dialog" aria-label="{{ __('Language and currency') }}">

                    {{-- Language section --}}
                    <p class="bb-lf-section-title">{{ __('Change language') }}</p>
                    <ul class="bb-lf-list" role="radiogroup">
                        @php 
                            $currentLocale = Language::getCurrentLocale();
                            // Separate current language from others
                            $currentLangData = null;
                            $otherLanguages = [];
                            
                            foreach ($supportedLocales as $localeCode => $properties) {
                                if ($localeCode === $currentLocale) {
                                    $currentLangData = ['code' => $localeCode, 'data' => $properties];
                                } else {
                                    $otherLanguages[] = ['code' => $localeCode, 'data' => $properties];
                                }
                            }
                        @endphp
                        
                        {{-- Show current language first --}}
                        @if($currentLangData)
                            <li class="bb-lf-item bb-lf-item--active">
                                <a href="#"
                                   role="radio"
                                   aria-checked="true"
                                   class="bb-lf-row bb-lf-row--current">
                                    <span class="bb-lf-radio"><span class="bb-lf-radio__dot"></span></span>
                                    <span class="bb-lf-text">{{ $currentLangData['data']['lang_name'] }} – {{ strtoupper($currentLangData['code']) }}</span>
                                    @if(!empty($currentLangData['data']['lang_flag']))
                                        <span class="bb-lf-flag">{!! language_flag($currentLangData['data']['lang_flag'], $currentLangData['data']['lang_name']) !!}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        
                        {{-- Divider after current language - always show if there are multiple languages --}}
                        @if($currentLangData && count($supportedLocales) > 1)
                            <li class="bb-lf-divider"></li>
                        @endif
                        
                        {{-- Show other languages --}}
                        @foreach ($otherLanguages as $lang)
                            <li class="bb-lf-item">
                                <a href="{{ Language::getSwitcherUrl($lang['code'], $lang['data']['lang_code']) }}"
                                   role="radio"
                                   aria-checked="false"
                                   class="bb-lf-row">
                                    <span class="bb-lf-radio"><span class="bb-lf-radio__dot"></span></span>
                                    <span class="bb-lf-text">{{ $lang['data']['lang_name'] }} – {{ strtoupper($lang['code']) }}</span>
                                    @if(!empty($lang['data']['lang_flag']))
                                        <span class="bb-lf-flag">{!! language_flag($lang['data']['lang_flag'], $lang['data']['lang_name']) !!}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Currency section --}}
                    @if (is_plugin_active('ecommerce'))
                        @php
                            $currencies       = get_all_currencies();
                            $currentCurrId    = get_application_currency_id();
                            $currentCurrency  = $currencies->firstWhere('id', $currentCurrId) ?? $currencies->first();
                            
                            // Full currency name mapping
                            $currencyNames = [
                                'USD' => 'US Dollar', 'EUR' => 'Euro', 'GBP' => 'British Pound', 
                                'JPY' => 'Japanese Yen', 'CNY' => 'Chinese Yuan', 'INR' => 'Indian Rupee',
                                'BDT' => 'Bangladeshi Taka', 'PKR' => 'Pakistani Rupee', 'AUD' => 'Australian Dollar',
                                'CAD' => 'Canadian Dollar', 'SGD' => 'Singapore Dollar', 'AED' => 'UAE Dirham',
                                'SAR' => 'Saudi Riyal', 'MYR' => 'Malaysian Ringgit', 'THB' => 'Thai Baht',
                                'IDR' => 'Indonesian Rupiah', 'PHP' => 'Philippine Peso', 'VND' => 'Vietnamese Dong',
                            ];
                            $currFullName = $currencyNames[$currentCurrency->title] ?? $currentCurrency->title;
                        @endphp
                        @if ($currentCurrency)
                            <div class="bb-lf-section-divider"></div>
                            <p class="bb-lf-section-title">{{ __('Change currency') }}</p>
                            <div class="bb-lf-currency-row">
                                <span class="bb-lf-curr-info">{{ $currentCurrency->symbol }} – {{ $currentCurrency->title }} – {{ $currFullName }}</span>
                                <a href="{{ url(Language::getLocalizedURL(Language::getCurrentLocale(), 'customer-experience')) }}" class="bb-lf-change-link">{{ __('Change') }}</a>
                            </div>
                        @endif
                    @endif

                </div>{{-- .bb-lang-flyout --}}
            </div>{{-- .bb-lang-flyout-wrap --}}
            {!! Arr::get($options, 'after') !!}
        </li>
    @else
        @foreach ($supportedLocales as $localeCode => $properties)
            @if ($localeCode != Language::getCurrentLocale())
                <li>
                    <a href="{{ Language::getSwitcherUrl($localeCode, $properties['lang_code']) }}">
                        @if (Arr::get($options, 'lang_flag', true) && ($languageDisplay == 'all' || $languageDisplay == 'flag')){!! language_flag($properties['lang_flag'], $properties['lang_name']) !!}@endif
                        @if (Arr::get($options, 'lang_name', true) && ($languageDisplay == 'all' || $languageDisplay == 'name'))<span>&nbsp;{{ $properties['lang_name'] }}</span>@endif
                    </a>
                </li>
            @endif
        @endforeach
    @endif
@endif


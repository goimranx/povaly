@php
    $supportedLocales = Language::getSupportedLocales();
    $currentLocale    = Language::getCurrentLocale();
    $currencies       = is_plugin_active('ecommerce') ? get_all_currencies() : collect();
    $currentCurrId    = is_plugin_active('ecommerce') ? get_application_currency_id() : null;
    $currentCurrency  = $currencies->firstWhere('id', $currentCurrId) ?? $currencies->first();
    
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
    
    // Full currency name mapping
    $currencyNames = [
        'USD' => 'US Dollar', 'EUR' => 'Euro', 'GBP' => 'British Pound', 
        'JPY' => 'Japanese Yen', 'CNY' => 'Chinese Yuan', 'INR' => 'Indian Rupee',
        'BDT' => 'Bangladeshi Taka', 'PKR' => 'Pakistani Rupee', 'AUD' => 'Australian Dollar',
        'CAD' => 'Canadian Dollar', 'SGD' => 'Singapore Dollar', 'AED' => 'UAE Dirham',
        'SAR' => 'Saudi Riyal', 'MYR' => 'Malaysian Ringgit', 'THB' => 'Thai Baht',
        'IDR' => 'Indonesian Rupiah', 'PHP' => 'Philippine Peso', 'VND' => 'Vietnamese Dong',
    ];
    $currFullName = $currentCurrency ? ($currencyNames[$currentCurrency->title] ?? $currentCurrency->title) : '';
@endphp

<div class="bb-locale-settings">

    {{-- Language Settings section --}}
    <div class="bb-ls-section">
        <h2 class="bb-ls-title">{{ __('Language Settings') }}</h2>
        <p class="bb-ls-subtitle">{{ __('Choose your preferred display language.') }}</p>

        <div class="bb-ls-lang-wrapper">
            <ul class="bb-ls-lang-list">
                {{-- Current language first --}}
                @if($currentLangData)
                    <li class="bb-ls-lang-item bb-ls-lang-item--active">
                        <a href="#" class="bb-ls-lang-link bb-ls-lang-link--current" data-lang-url="">
                            <span class="bb-ls-radio">
                                <span class="bb-ls-radio-dot"></span>
                            </span>
                            <span class="bb-ls-lang-name">{{ $currentLangData['data']['lang_name'] }} – {{ strtoupper($currentLangData['code']) }}</span>
                            @if(!empty($currentLangData['data']['lang_flag']))
                                <span class="bb-ls-flag">
                                    @php
                                        // Render flag with unique IDs to avoid conflicts with flyout
                                        $flagHtml = language_flag($currentLangData['data']['lang_flag'], $currentLangData['data']['lang_name'], 20);
                                        // Add suffix to all IDs to make them unique
                                        $flagHtml = preg_replace('/id="([^"]+)"/', 'id="$1_settings"', $flagHtml);
                                        $flagHtml = preg_replace('/url\(#([^)]+)\)/', 'url(#$1_settings)', $flagHtml);
                                    @endphp
                                    {!! $flagHtml !!}
                                </span>
                            @endif
                        </a>
                    </li>
                @endif
                
                {{-- Divider --}}
                @if($currentLangData && count($supportedLocales) > 1)
                    <li class="bb-ls-divider-item"></li>
                @endif
                
                {{-- Other languages --}}
                @foreach ($otherLanguages as $lang)
                    <li class="bb-ls-lang-item">
                        <a href="#" 
                           class="bb-ls-lang-link"
                           data-lang-url="{{ Language::getSwitcherUrl($lang['code'], $lang['data']['lang_code']) }}">
                            <span class="bb-ls-radio">
                                <span class="bb-ls-radio-dot"></span>
                            </span>
                            <span class="bb-ls-lang-name">{{ $lang['data']['lang_name'] }} – {{ strtoupper($lang['code']) }}</span>
                            @if(!empty($lang['data']['lang_flag']))
                                <span class="bb-ls-flag">
                                    @php
                                        // Render flag with unique IDs to avoid conflicts with flyout
                                        $flagHtml = language_flag($lang['data']['lang_flag'], $lang['data']['lang_name'], 20);
                                        // Add suffix to all IDs to make them unique
                                        $flagHtml = preg_replace('/id="([^"]+)"/', 'id="$1_settings"', $flagHtml);
                                        $flagHtml = preg_replace('/url\(#([^)]+)\)/', 'url(#$1_settings)', $flagHtml);
                                    @endphp
                                    {!! $flagHtml !!}
                                </span>
                            @endif
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Currency section --}}
            @if ($currentCurrency)
                <div class="bb-ls-section-divider"></div>
                <div class="bb-ls-currency-section">
                    <h3 class="bb-ls-currency-title">{{ __('Currency Settings') }}</h3>
                    <p class="bb-ls-currency-subtitle">{{ __('Select your preferred currency for pricing.') }}</p>
                    <div class="bb-ls-currency-wrap">
                        <select id="bb-ls-currency" class="bb-ls-currency-select">
                            @foreach ($currencies as $currency)
                                @php $isCurrent = ($currency->id === $currentCurrId); @endphp
                                <option value="{{ route('public.change-currency', $currency->title) }}" {{ $isCurrent ? 'selected' : '' }}>
                                    {{ $currency->symbol }} – {{ $currency->title }} – {{ $currencyNames[$currency->title] ?? $currency->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Divider before buttons --}}
    <div class="bb-ls-main-divider"></div>

    {{-- Action buttons --}}
    <div class="bb-ls-actions">
        <button type="button" class="bb-ls-btn bb-ls-btn--cancel" onclick="history.length > 1 ? history.back() : (window.location.href = '{{ url('/') }}')">{{ __('Cancel') }}</button>
        <button type="button" class="bb-ls-btn bb-ls-btn--save" id="bb-ls-save">{{ __('Save Changes') }}</button>
    </div>

</div>

<script>
(function () {
    // CRITICAL FIX: Remove lazy loading from all flag images immediately
    var flagImgs = document.querySelectorAll('.bb-locale-settings .bb-ls-flag img[loading="lazy"]');
    flagImgs.forEach(function(img) {
        img.removeAttribute('loading');
        // Force immediate load
        if (!img.complete) {
            var src = img.src;
            img.src = '';
            img.src = src;
        }
    });

    var pendingLangUrl = null;

    // Check for pending language switch
    var storedLang = sessionStorage.getItem('bb_pending_lang');
    if (storedLang) {
        sessionStorage.removeItem('bb_pending_lang');
        window.location.href = storedLang;
        return;
    }

    // Language selection
    var langList = document.querySelector('.bb-ls-lang-list');
    if (langList) {
        langList.addEventListener('click', function (e) {
            var link = e.target.closest('.bb-ls-lang-link');
            if (!link) return;
            e.preventDefault();

            var url = link.getAttribute('data-lang-url');
            if (!url) return; // Current locale

            // Deselect all
            langList.querySelectorAll('.bb-ls-lang-item').forEach(function (item) {
                item.classList.remove('bb-ls-lang-item--active');
            });

            // Select clicked
            var item = link.closest('.bb-ls-lang-item');
            if (item) item.classList.add('bb-ls-lang-item--active');

            pendingLangUrl = url;
        });
    }

    // Save button
    var saveBtn = document.getElementById('bb-ls-save');
    if (saveBtn) {
        saveBtn.addEventListener('click', function () {
            var sel = document.getElementById('bb-ls-currency');
            var selectedOpt = sel ? sel.options[sel.selectedIndex] : null;
            var defaultOpt  = sel ? sel.querySelector('option[selected]') : null;
            var currChanged = selectedOpt && defaultOpt && (selectedOpt !== defaultOpt);
            var currUrl     = currChanged ? sel.value : null;

            if (pendingLangUrl && currUrl) {
                sessionStorage.setItem('bb_pending_lang', pendingLangUrl);
                window.location.href = currUrl;
            } else if (pendingLangUrl) {
                window.location.href = pendingLangUrl;
            } else if (currUrl) {
                window.location.href = currUrl;
            } else {
                history.length > 1 ? history.back() : (window.location.href = '/');
            }
        });
    }
})();
</script>

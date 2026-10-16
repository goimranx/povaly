<?php

use Botble\Ads\Facades\AdsManager;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\Form;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Facades\FlashSale;
use Botble\Ecommerce\Supports\FlashSaleSupport;
use Botble\Media\Facades\RvMedia;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Botble\Widget\Events\RenderingWidgetSettings;

if (! function_exists('theme_get_autoplay_speed_options')) {
    function theme_get_autoplay_speed_options(): array
    {
        $options = [2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000];

        return array_combine($options, $options);
    }
}

app()->booted(function (): void {
    // Register spacingControl as a Form macro so theme options can use type="spacingControl"
    Form::macro('spacingControl', function (string $name, mixed $value = null, array $options = []) {
        $decoded = is_string($value) ? @json_decode($value, true) : null;

        if (is_array($decoded)) {
            $top    = $decoded['top']    ?? '0';
            $right  = $decoded['right']  ?? '0';
            $bottom = $decoded['bottom'] ?? '0';
            $left   = $decoded['left']   ?? '0';
            $unit   = $decoded['unit']   ?? 'px';
            $linked = isset($decoded['linked']) ? (bool)$decoded['linked'] : true;
        } else {
            $plain  = preg_replace('/[^0-9.\s]/', '', $value ?? '0');
            $parts  = preg_split('/\s+/', trim($plain));
            $top    = $parts[0] ?? '0';
            $right  = $parts[1] ?? $top;
            $bottom = $parts[2] ?? $top;
            $left   = $parts[3] ?? $right;
            preg_match('/(px|rem|em|%|vw|vh)/', $value ?? 'px', $unitMatch);
            $unit   = $unitMatch[1] ?? 'px';
            $linked = true;
        }

        $units      = ['px', 'rem', 'em', '%', 'vw', 'vh'];
        $linkedStr  = $linked ? 'true' : 'false';
        $linkedClass = $linked ? 'is-linked' : '';
        $unitOptions = '';
        foreach ($units as $u) {
            $sel = $u === $unit ? ' selected' : '';
            $unitOptions .= "<option value=\"{$u}\"{$sel}>{$u}</option>";
        }

        $sides = [
            'top'    => $top,
            'right'  => $right,
            'bottom' => $bottom,
            'left'   => $left,
        ];
        $boxes = '';
        foreach ($sides as $side => $val) {
            $label = ucfirst($side);
            $boxes .= <<<HTML
                <div class="bb-sc__box">
                    <input type="number" class="bb-sc__num" data-side="{$side}" value="{$val}" min="0" step="1" aria-label="{$label}">
                    <span class="bb-sc__label">{$label}</span>
                </div>
            HTML;
        }

        // Inline SVGs from platform/core/icon/resources/svg — link.svg and unlink.svg
        $iconLinked   = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 15l6 -6"/><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464"/><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463"/></svg>';
        $iconUnlinked = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 22v-2"/><path d="M9 15l6 -6"/><path d="M11 6l.463 -.536a5 5 0 0 1 7.071 7.072l-.534 .464"/><path d="M13 18l-.397 .534a5.068 5.068 0 0 1 -7.127 0a4.972 4.972 0 0 1 0 -7.071l.524 -.463"/><path d="M20 17h2"/><path d="M2 7h2"/><path d="M7 2v2"/></svg>';

        $id = 'bb-sc-' . md5($name);

        return <<<HTML
        <div class="bb-sc" id="{$id}" data-linked="{$linkedStr}">
            <input type="hidden" name="{$name}" class="bb-sc__input" value="{$value}">
            <div class="bb-sc__wrap">
                {$boxes}
                <button type="button" class="bb-sc__link {$linkedClass}" title="Link sides" aria-pressed="{$linkedStr}">
                    <span class="bb-sc__icon-linked">{$iconLinked}</span>
                    <span class="bb-sc__icon-unlinked">{$iconUnlinked}</span>
                </button>
                <select class="bb-sc__unit" aria-label="Unit">{$unitOptions}</select>
            </div>
        </div>
        <style>
        .bb-sc__wrap{display:flex;align-items:flex-start;gap:6px;flex-wrap:nowrap}
        .bb-sc__box{display:flex;flex-direction:column;align-items:center;gap:3px}
        .bb-sc__num{width:58px;text-align:center;padding:4px 6px;border:1px solid #d0d5dd;border-radius:6px;font-size:13px;background:#fff;transition:border-color .15s;-moz-appearance:textfield}
        .bb-sc__num::-webkit-inner-spin-button,.bb-sc__num::-webkit-outer-spin-button{-webkit-appearance:none;margin:0}
        .bb-sc__num:focus{outline:none;border-color:#4f46e5;box-shadow:0 0 0 2px rgba(79,70,229,.15)}
        .bb-sc__label{font-size:10px;color:#888;text-transform:uppercase;letter-spacing:.04em;line-height:1}
        .bb-sc__link{width:30px;height:30px;border:1px solid #d0d5dd;border-radius:6px;background:#f9fafb;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#aaa;transition:background .15s,border-color .15s,color .15s;flex-shrink:0;align-self:flex-start;padding:0;margin-top:0}
        .bb-sc__link:hover{background:#f0f0f0;color:#666}
        .bb-sc__link.is-linked{background:#eef2ff;border-color:#a5b4fc;color:#4f46e5}
        .bb-sc__icon-unlinked{display:none}
        .bb-sc__link.is-linked .bb-sc__icon-linked{display:block}
        .bb-sc__link.is-linked .bb-sc__icon-unlinked{display:none}
        .bb-sc__link:not(.is-linked) .bb-sc__icon-linked{display:none}
        .bb-sc__link:not(.is-linked) .bb-sc__icon-unlinked{display:block}
        .bb-sc__unit{height:30px;padding:0 6px;border:1px solid #d0d5dd;border-radius:6px;font-size:12px;background:#fff;align-self:flex-start;cursor:pointer;min-width:52px}
        .bb-sc__unit:focus{outline:none;border-color:#4f46e5}
        </style>
        <script>
        (function(){
            function init(el){
                if(el._bbSCInit) return; el._bbSCInit=true;
                var hidden=el.querySelector('.bb-sc__input');
                var nums=el.querySelectorAll('.bb-sc__num');
                var btn=el.querySelector('.bb-sc__link');
                var unit=el.querySelector('.bb-sc__unit');
                var linked=el.dataset.linked==='true';
                function sync(){btn.classList.toggle('is-linked',linked);btn.setAttribute('aria-pressed',linked?'true':'false');}
                function save(){var o={linked:linked,unit:unit.value};nums.forEach(function(n){o[n.dataset.side]=n.value;});hidden.value=JSON.stringify(o);}
                nums.forEach(function(n){n.addEventListener('input',function(){if(linked){nums.forEach(function(x){if(x!==n)x.value=n.value;});}save();});});
                btn.addEventListener('click',function(){linked=!linked;sync();if(linked){var v=nums[0].value;nums.forEach(function(n){n.value=v;});}save();});
                unit.addEventListener('change',save);
                sync();save();
            }
            function initAll(){document.querySelectorAll('.bb-sc:not([data-init])').forEach(function(el){el.setAttribute('data-init','1');init(el);});}
            document.addEventListener('DOMContentLoaded',initAll);
            if(document.readyState==='complete'||document.readyState==='interactive') initAll();
        })();
        </script>
        HTML;
    });

    // ── Tab system — CSS-class based, no DOM restructuring needed ────────

    Form::macro('tabsOpen', function (string $name, mixed $value = null, array $options = []) {
        $tabs = $options['tabs'] ?? [];
        $uid  = 'bb-tabs-' . md5($name);

        $navItems = '';
        foreach ($tabs as $i => $tab) {
            $active = $i === 0 ? 'bb-tab__btn--active' : '';
            $label  = htmlspecialchars($tab['label'] ?? ('Tab ' . ($i + 1)));
            $navItems .= "<button type=\"button\" class=\"bb-tab__btn {$active}\" data-bb-tab=\"{$i}\">{$label}</button>";
        }

        return <<<HTML
        <div class="bb-tabs-nav" id="{$uid}" data-bb-uid="{$uid}" style="margin-bottom:0">
            <div class="bb-tab__nav">{$navItems}</div>
        </div>
        <style>
        .bb-tab__nav{display:flex;gap:0;border-bottom:2px solid var(--bb-border-color, #e5e7eb);margin-bottom:12px;flex-wrap:wrap}
        .bb-tab__btn{padding:8px 16px;border:none;background:none;cursor:pointer;font-size:13px;font-weight:500;color:var(--bb-secondary,#6b7280);border-bottom:2px solid transparent;margin-bottom:-2px;transition:color .15s,border-color .15s,background .15s;border-radius:4px 4px 0 0}
        .bb-tab__btn:hover{color:var(--bb-body-color,#374151);background:var(--bb-light,#f9fafb)}
        .bb-tab__btn--active{color:var(--bb-primary,#066fd1);border-bottom-color:var(--bb-primary,#066fd1);background:rgba(6,111,209,.07)}
        /* Hide all tab fields by default; active tab shown via JS-set data attribute on parent */
        .bb-tab-field{display:none!important}
        .bb-tab-field.bb-tab-field--active{display:block!important}
        </style>        <script>
        (function(){
            function run(){
                var nav = document.getElementById('{$uid}');
                if(!nav||nav._init) return; nav._init=true;

                // Move the nav div out of its form-group wrapper, then hide the wrapper
                var navGroup = nav.closest('.form-group,.mb-3');
                if(navGroup){
                    // Insert nav before the form-group so it's visible
                    navGroup.parentNode.insertBefore(nav, navGroup);
                    // Now hide the now-empty form-group
                    navGroup.style.display = 'none';
                }

                // Find the tab-pane container (parent of nav after it's been moved out)
                var container = nav.parentElement;

                function showTab(idx){
                    nav.querySelectorAll('.bb-tab__btn').forEach(function(b){
                        b.classList.toggle('bb-tab__btn--active', b.getAttribute('data-bb-tab')==String(idx));
                    });
                    if(!container) return;
                    container.querySelectorAll('.bb-tab-field').forEach(function(el){
                        el.classList.toggle('bb-tab-field--active', el.getAttribute('data-bb-tab-idx')==String(idx));
                    });
                }

                nav.querySelectorAll('.bb-tab__btn').forEach(function(btn){
                    btn.addEventListener('click',function(){
                        showTab(parseInt(btn.getAttribute('data-bb-tab')));
                    });
                });

                showTab(0);
            }
            document.addEventListener('DOMContentLoaded', run);
            if(document.readyState==='complete'||document.readyState==='interactive') setTimeout(run,0);
        })();
        </script>
        HTML;
    });

    // tabMarker — immediately after a real field; marks its .form-group as belonging to a tab
    Form::macro('tabMarker', function (string $name, mixed $value = null, array $options = []) {
        $tab = (int)($options['tab'] ?? 0);
        // currentScript is inside a form-group for the marker itself.
        // We go to that form-group's previousElementSibling to get the real field's form-group.
        return <<<HTML
        <script>
        (function(){
            var s = document.currentScript;
            if(!s) return;
            var markerGroup = s.closest('.form-group,.mb-3');
            if(!markerGroup) return;
            // Hide the marker's own form-group (it's empty/invisible anyway)
            markerGroup.style.display = 'none';
            // The preceding sibling is the actual field's form-group
            var fieldGroup = markerGroup.previousElementSibling;
            if(fieldGroup){
                fieldGroup.classList.add('bb-tab-field');
                fieldGroup.setAttribute('data-bb-tab-idx', '{$tab}');
            }
        })();
        </script>
        HTML;
    });

    Form::macro('tabsClose', function (string $name, mixed $value = null, array $options = []) {
        return '';
    });

    // sectionTitle — renders a visual section heading inside a tab
    Form::macro('sectionTitle', function (string $name, mixed $value = null, array $options = []) {
        $title = htmlspecialchars($options['title'] ?? $name);
        return <<<HTML
        <div class="bb-section-title" style="margin:20px 0 12px;padding-bottom:8px;border-bottom:2px solid var(--bb-primary,#066fd1);display:flex;align-items:center;gap:8px">
            <span style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--bb-primary,#066fd1)">{$title}</span>
        </div>
        <script>
        (function(){
            var el = document.currentScript;
            if(!el) return;
            var grp = el.closest('.form-group,.mb-3');
            if(grp){
                grp.style.cssText = 'padding:0!important;margin:0!important';
                var lbl = grp.querySelector('label');
                if(lbl) lbl.style.display = 'none';
            }
        })();
        </script>
        HTML;
    });

    ThemeSupport::registerSocialLinks();
    ThemeSupport::registerSocialSharing();
    ThemeSupport::registerSiteLogoHeight(40);
    ThemeSupport::registerSiteCopyright();
    ThemeSupport::registerLazyLoadImages();
    ThemeSupport::registerToastNotification();
    ThemeSupport::registerDateFormatOption();

    $events = app('events');

    $events->listen('core.page::registering-templates', function (): void {
        register_page_template([
            'blog-sidebar' => __('Blog Sidebar'),
            'full-width' => __('Full width'),
            'homepage' => __('Homepage'),
            'coming-soon' => __('Coming soon'),
        ]);
    });

    $events->listen([RenderingWidgetSettings::class, 'core.widget:rendering'], function (): void {
        register_sidebar([
            'id' => 'footer_sidebar',
            'name' => __('Footer sidebar'),
            'description' => __('Widgets in footer of page'),
        ]);

        register_sidebar([
            'id' => 'bottom_footer_sidebar',
            'name' => __('Bottom Footer sidebar'),
            'description' => __('Widgets in bottom footer'),
        ]);
    });

    if (is_plugin_active('ecommerce')) {
        if (method_exists(FlashSaleSupport::class, 'addShowSaleCountLeftSetting')) {
            FlashSale::addShowSaleCountLeftSetting();
        }

        EcommerceHelper::registerProductGalleryOptions();
        EcommerceHelper::registerProductVideo();
    }

    RvMedia::addSize('medium', 790, 510)
        ->addSize('small', 300, 300);

    add_action('init', function (): void {
        EmailHandler::addTemplateSettings(Theme::getThemeName(), [
            'name' => __('Theme emails'),
            'description' => __('Config email templates for theme'),
            'templates' => [
                'download_app' => [
                    'title' => __('Download apps'),
                    'description' => __('Send mail with links to download apps'),
                    'subject' => __('Download apps'),
                    'can_off' => true,
                ],
            ],
            'variables' => [],
        ], 'themes');
    }, 125);

    if (is_plugin_active('ads')) {
        AdsManager::registerLocation('top-slider-image-1', __('Top Slider Image 1 (deprecated)'))
            ->registerLocation('top-slider-image-2', __('Top Slider Image 2 (deprecated)'))
            ->registerLocation('product-sidebar', __('Product sidebar'));
    }
});

// ── Admin: restore scroll position after page reload ──────────────────────────
// Saves scrollY to sessionStorage before unload and restores it on load.
// Keyed by full path+query so each unique admin page/tab gets its own position.
if (is_in_admin()) {
    add_filter('core_layout_after_content', function (?string $html): string {
        $script = <<<'JS'
<script>
(function () {
    var key = 'bb_scroll_' + window.location.pathname + window.location.search;

    // Restore as early as possible to avoid visible jump
    var saved = sessionStorage.getItem(key);
    if (saved !== null) {
        // requestAnimationFrame lets the browser finish painting first
        requestAnimationFrame(function () {
            window.scrollTo(0, parseInt(saved, 10));
        });
    }

    // Save position just before the page unloads (reload, navigate away)
    window.addEventListener('beforeunload', function () {
        sessionStorage.setItem(key, window.scrollY);
    });
})();
</script>
JS;
        return ($html ?? '') . $script;
    }, 99);
}

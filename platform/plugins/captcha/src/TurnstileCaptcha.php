<?php

namespace Botble\Captcha;

use Botble\Captcha\Contracts\Captcha as CaptchaContract;
use Botble\Captcha\Events\CaptchaRendered;
use Botble\Captcha\Events\CaptchaRendering;
use Illuminate\Support\Facades\Http;

class TurnstileCaptcha extends CaptchaContract
{
    public const TURNSTILE_CLIENT_API_URL = 'https://challenges.cloudflare.com/turnstile/v0/api.js';

    public const TURNSTILE_VERIFY_API_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public const TURNSTILE_INPUT_NAME = 'cf-turnstile-response';

    protected bool $rendered = false;

    public function display(array $attributes = [], array $options = []): ?string
    {
        if (! $this->siteKey || ! $this->turnstileEnabled()) {
            return null;
        }

        $name = 'turnstile_' . md5(uniqid((string) rand(), true));

        $headContent = $this->headRender();
        $footerContent = $this->footerRender($name);

        CaptchaRendering::dispatch($attributes, $options, $headContent, $footerContent);

        add_filter(['theme-front-header', 'ecommerce_checkout_header'], function ($html) use ($headContent) {
            return $html . $headContent;
        }, 299);

        add_filter(['theme-front-footer', 'ecommerce_checkout_footer'], function (?string $html) use ($footerContent): string {
            return $html . $footerContent;
        }, 99);

        add_filter(BASE_FILTER_HEAD_LAYOUT_TEMPLATE, function ($html) use ($headContent) {
            return $html . $headContent;
        }, 299);

        add_filter(BASE_FILTER_FOOTER_LAYOUT_TEMPLATE, function (?string $html) use ($footerContent): string {
            return $html . $footerContent;
        }, 99);

        $this->rendered = true;

        $captchaContent = view('plugins/captcha::turnstile.html', [
            'name' => $name,
            'siteKey' => $this->siteKey,
            'theme' => setting('turnstile_theme', 'auto'),
        ])->render();

        if (request()->expectsJson()) {
            $captchaContent .= $footerContent;
        }

        return
            tap(
                $captchaContent,
                fn (string $rendered) => CaptchaRendered::dispatch($rendered)
            );
    }

    public function verify(string $response, ?string $clientIp = null, array $options = []): bool
    {
        if (! $this->turnstileEnabled()) {
            return true;
        }

        if (empty($response)) {
            return false;
        }

        $response = Http::asForm()
            ->withoutVerifying()
            ->post(self::TURNSTILE_VERIFY_API_URL, [
                'secret' => $this->secretKey,
                'response' => $response,
                'remoteip' => $clientIp,
            ]);

        return $response->json('success');
    }

    public function turnstileEnabled(): bool
    {
        if (! $this->siteKey || ! $this->secretKey) {
            return false;
        }

        return (bool) setting('enable_captcha') && setting('captcha_type') === 'turnstile';
    }

    public function isEnabled(): bool
    {
        return $this->turnstileEnabled();
    }

    public function reCaptchaEnabled(): bool
    {
        // Turnstile is not reCAPTCHA
        return false;
    }

    public function rules(): array
    {
        if (! $this->turnstileEnabled()) {
            return [];
        }

        return [self::TURNSTILE_INPUT_NAME => ['required', 'turnstile']];
    }

    public function captchaType(): string
    {
        return 'turnstile';
    }

    protected function headRender(): string
    {
        return view('plugins/captcha::turnstile.header-meta')->render();
    }

    protected function footerRender(string $name): string
    {
        $isRendered = $this->rendered;
        $url = self::TURNSTILE_CLIENT_API_URL;

        return view('plugins/captcha::turnstile.script', compact('url', 'isRendered', 'name'))->render();
    }
}

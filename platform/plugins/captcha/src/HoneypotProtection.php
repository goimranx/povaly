<?php

namespace Botble\Captcha;

class HoneypotProtection
{
    /**
     * Honeypot field name (looks legitimate to bots)
     */
    public const HONEYPOT_FIELD = 'contact_email_url';

    /**
     * Timestamp field for time-based validation
     */
    public const TIMESTAMP_FIELD = 'form_build_id';

    /**
     * Minimum time in seconds before form can be submitted (too fast = bot)
     * Set very low to allow autofill scenarios
     */
    public const MIN_SUBMIT_TIME = 0.8; // Allows autofill + human click

    /**
     * Maximum time in seconds before form expires (too slow = suspicious)
     */
    public const MAX_SUBMIT_TIME = 3600; // 1 hour

    /**
     * Super fast submission threshold (likely bot, no human interaction)
     */
    public const INSTANT_SUBMIT_THRESHOLD = 0.5; // Half second

    /**
     * Generate honeypot HTML for forms
     *
     * @return string
     */
    public static function generateHtml(): string
    {
        $timestamp = time();
        $token = self::generateToken($timestamp);

        return <<<HTML
<div class="form-group" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;" aria-hidden="true" tabindex="-1">
    <label for="contact_email_url">Leave this field empty</label>
    <input type="text" name="contact_email_url" id="contact_email_url" value="" autocomplete="off" tabindex="-1" />
</div>
<input type="hidden" name="form_build_id" value="{$token}" />
HTML;
    }

    /**
     * Validate honeypot fields
     *
     * @param array $data  Raw input data from the validator
     * @return bool
     */
    public static function validate(array $data): bool
    {
        $request = request();

        // Check if honeypot field is filled (bot trap)
        $honeypotValue = $data[self::HONEYPOT_FIELD] ?? null;
        if (! empty($honeypotValue)) {
            logger()->warning('Honeypot field filled', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
            ]);
            return false;
        }

        // Check timestamp token
        $token = $data[self::TIMESTAMP_FIELD] ?? null;
        if (! $token) {
            logger()->warning('Missing timestamp token', [
                'ip' => $request->ip(),
            ]);
            return false;
        }

        $timestamp = self::decodeToken($token);
        if (! $timestamp) {
            logger()->warning('Invalid timestamp token', [
                'ip' => $request->ip(),
            ]);
            return false;
        }

        $elapsed = time() - $timestamp;

        // Instant submission (< 0.5s) — automated script, no human interaction possible
        if ($elapsed < self::INSTANT_SUBMIT_THRESHOLD) {
            logger()->warning('Instant form submission detected (likely bot)', [
                'ip' => $request->ip(),
                'elapsed_seconds' => $elapsed,
                'user_agent' => $request->userAgent(),
            ]);
            return false;
        }

        // Gray area (0.5s–0.8s) — could be autofill + human click, so just log and allow
        if ($elapsed < self::MIN_SUBMIT_TIME) {
            logger()->info('Fast form submission (possibly autofill)', [
                'ip' => $request->ip(),
                'elapsed_seconds' => $elapsed,
                'user_agent' => $request->userAgent(),
            ]);
        }

        // Token expired (> 1 hour)
        if ($elapsed > self::MAX_SUBMIT_TIME) {
            logger()->warning('Form token expired', [
                'ip' => $request->ip(),
                'elapsed_seconds' => $elapsed,
            ]);
            return false;
        }

        return true;
    }

    /**
     * Generate secure token from timestamp
     *
     * @param int $timestamp
     * @return string
     */
    protected static function generateToken(int $timestamp): string
    {
        $data = base64_encode($timestamp);
        $hash = hash_hmac('sha256', $data, config('app.key'));
        return base64_encode($data . '|' . substr($hash, 0, 16));
    }

    /**
     * Decode and verify token
     *
     * @param string $token
     * @return int|null
     */
    protected static function decodeToken(string $token): ?int
    {
        try {
            $decoded = base64_decode($token);
            if (! $decoded || ! str_contains($decoded, '|')) {
                return null;
            }

            [$data, $hash] = explode('|', $decoded, 2);
            $timestamp = (int) base64_decode($data);

            // Verify hash
            $expectedHash = substr(hash_hmac('sha256', $data, config('app.key')), 0, 16);
            if (! hash_equals($expectedHash, $hash)) {
                return null;
            }

            return $timestamp;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get validation rules for honeypot
     *
     * @return array
     */
    public static function rules(): array
    {
        return [
            self::HONEYPOT_FIELD => ['max:0'], // Must be empty
            self::TIMESTAMP_FIELD => ['required', 'string'],
        ];
    }

    /**
     * Get validation messages
     *
     * @return array
     */
    public static function messages(): array
    {
        return [
            self::HONEYPOT_FIELD . '.max' => 'Invalid form submission detected.',
            self::TIMESTAMP_FIELD . '.required' => 'Form token is missing.',
        ];
    }

    /**
     * Check if honeypot is enabled
     *
     * @return bool
     */
    public static function isEnabled(): bool
    {
        // Disabled by default until properly configured
        // Admin can enable via Settings → Captcha → Enable Honeypot Protection
        return (bool) setting('enable_honeypot', false);
    }
}

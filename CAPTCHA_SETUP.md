# Captcha Integration Guide

This project now supports three types of CAPTCHA protection to defend against bots and automated abuse:

## Available CAPTCHA Types

### 1. **Cloudflare Turnstile** (Recommended)
**Why it's recommended:**
- User-friendly with minimal friction
- Privacy-first (no personal data collection)
- Free for unlimited use
- Clear visual feedback (no silent failures)
- Better user experience than traditional CAPTCHAs
- Automatic adaptive challenges

**Setup:**
1. Go to [Cloudflare Dashboard](https://dash.cloudflare.com/?to=/:account/turnstile)
2. Create a new Turnstile site
3. Copy the **Site Key** and **Secret Key**
4. In admin panel, go to **Settings → Captcha**
5. Enable Captcha
6. Select **Cloudflare Turnstile**
7. Paste your Site Key and Secret Key
8. Choose theme (Auto/Light/Dark)
9. Select which forms should use captcha

### 2. **reCAPTCHA v2 Checkbox**
**Best for:** Visible verification with Google's infrastructure

**Setup:**
1. Go to [Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin#list)
2. Register a new site with reCAPTCHA v2 "I'm not a robot" checkbox
3. Copy the **Site Key** and **Secret Key**
4. In admin panel, go to **Settings → Captcha**
5. Enable Captcha
6. Select **V2 (Verify requests with a challenge)**
7. Paste your Site Key and Secret Key
8. Select which forms should use captcha

### 3. **reCAPTCHA v3**
**Note:** Only recommended if you understand score-based validation. Can cause silent failures.

**Setup:**
1. Go to [Google reCAPTCHA Admin](https://www.google.com/recaptcha/admin#list)
2. Register a new site with reCAPTCHA v3
3. Copy the **Site Key** and **Secret Key**
4. In admin panel, go to **Settings → Captcha**
5. Enable Captcha
6. Select **V3 (Verify requests with a score)**
7. Set minimum score threshold (0.6 recommended)
8. Paste your Site Key and Secret Key

### 4. **Math CAPTCHA**
**Best for:** Simple fallback without third-party services

**Setup:**
1. In admin panel, go to **Settings → Captcha**
2. Enable Math CAPTCHA
3. Select which forms should use it

## Per-Form Configuration

After enabling a CAPTCHA type, you can choose which forms to protect:

- ✅ **Admin login form** (Highly recommended)
- ✅ **Admin forgot password form**
- ✅ **Admin reset password form**
- Additional forms registered by plugins (contact, registration, etc.)

## Progressive Enforcement Strategy

**Recommended approach** to minimize user friction while maintaining security:

1. **Baseline Protection:**
   - Enable rate limiting on all forms
   - Add honeypot fields (hidden fields that bots fill out)

2. **Primary CAPTCHA:**
   - Use **Cloudflare Turnstile** on login after 3 failed attempts
   - Use **Cloudflare Turnstile** on registration and contact forms (always)

3. **Fallback for Admin:**
   - Keep **reCAPTCHA v2 Checkbox** configured as backup
   - Can switch if Turnstile has issues

4. **Avoid:**
   - ❌ Don't use reCAPTCHA v3 on login (causes frustration with silent failures)
   - ❌ Don't show CAPTCHA on every form submission (only when needed)

## Testing Your Setup

1. Log out of admin panel
2. Try logging in with correct credentials
3. Verify captcha appears and functions properly
4. Test form submission with captcha verification

## Troubleshooting

### Captcha not showing
- Check that "Enable Captcha" is ON
- Verify Site Key and Secret Key are correct
- Check that the specific form has captcha enabled
- Clear browser cache and Laravel cache: `php artisan optimize:clear`

### Captcha shows but validation fails
- Verify Secret Key matches Site Key
- Check domain is registered in Turnstile/reCAPTCHA dashboard
- For localhost testing, add `localhost` to allowed domains

### Silent failures (form submits but says captcha failed)
- This commonly happens with reCAPTCHA v3
- Switch to Turnstile or reCAPTCHA v2 for better user experience

## Security Best Practices

1. **Use HTTPS** - Captcha requires secure connection
2. **Keep secrets secure** - Never commit `.env` with real keys
3. **Monitor failed attempts** - Check logs for patterns
4. **Layer your defense:**
   - Captcha + Rate limiting + Honeypot fields
   - Different captcha types for different threat levels
5. **Update regularly** - Keep captcha plugin updated

## Comparison Chart

| Feature | Turnstile | reCAPTCHA v2 | reCAPTCHA v3 | Math CAPTCHA |
|---------|-----------|--------------|--------------|--------------|
| User Friendly | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐⭐ |
| Bot Protection | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐ |
| Privacy | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| Free | ✅ Unlimited | ✅ 1M/month | ✅ 1M/month | ✅ Always |
| Setup Difficulty | Easy | Easy | Medium | Very Easy |
| Silent Failures | Never | Rare | Common | Never |

## Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Check browser console for JavaScript errors
- Verify network requests in DevTools

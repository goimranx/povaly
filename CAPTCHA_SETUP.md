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

### 5. **Honeypot Protection** (NEW - Automatic)
**Best for:** Silent bot detection without user interaction

**What it does:**
- Adds hidden fields that bots fill out but humans never see
- Validates form submission timing (too fast = bot)
- Logs suspicious activity for monitoring
- Works alongside any captcha type for layered defense

**Setup:**
1. In admin panel, go to **Settings → Captcha**
2. Toggle **"Enable Honeypot Protection"** to ON (enabled by default)
3. That's it! Works automatically on all forms

**How it works:**
```html
<!-- Hidden field (invisible to humans, visible to bots) -->
<input type="text" name="contact_email_url" style="display:none" />

<!-- Time-based token (prevents instant submissions) -->
<input type="hidden" name="form_build_id" value="encrypted_timestamp" />
```

**Bot Detection Logic:**
- ❌ Honeypot field filled → **Rejected** (bot trap)
- ❌ Form submitted < 0.5 seconds → **Rejected** (instant bot, no human interaction)
- ⚠️ Form submitted 0.5-0.8 seconds → **Logged** but **Allowed** (likely autofill)
- ✅ Form submitted 0.8+ seconds → **Accepted** (normal timing)
- ❌ Form token expired (> 1 hour) → **Rejected** (too slow)

**Why these timings?**
- **< 0.5s**: Impossible for human to load page + click submit (pure bot)
- **0.5-0.8s**: Browser autofill + human clicks submit (legitimate user)
- **0.8s+**: Human typed or used autofill and reviewed form (normal)
- **> 1 hour**: Security token expired, form must be reloaded

**Real-World Examples:**

| Scenario | Time | Result | Reason |
|----------|------|--------|--------|
| Automated bot script | 0.1s | ❌ **Blocked** | Too fast, no human interaction |
| Browser autofill + click submit | 0.6s | ✅ **Allowed** | Fast but humanly possible |
| User types slowly | 5s | ✅ **Allowed** | Normal human speed |
| User gets coffee mid-form | 10min | ✅ **Allowed** | Still within 1 hour |
| User left tab open overnight | 2hrs | ❌ **Blocked** | Token expired, reload form |

**Key Point:** We differentiate between "instant bot" (< 0.5s) and "fast human with autofill" (0.5-0.8s) to avoid false positives!

## Per-Form Configuration

After enabling a CAPTCHA type, you can choose which forms to protect:

- ✅ **Admin login form** (Highly recommended)
- ✅ **Admin forgot password form**
- ✅ **Admin reset password form**
- Additional forms registered by plugins (contact, registration, etc.)

## Progressive Enforcement Strategy

**Recommended approach** to minimize user friction while maintaining security:

1. **Layer 1: Honeypot (Always On)**
   - ✅ Enable Honeypot Protection (default: ON)
   - Catches 70-80% of basic bots
   - Zero user friction (completely invisible)
   - Logs suspicious activity

2. **Layer 2: Rate Limiting**
   - Limit login attempts per IP (built into Laravel)
   - Block after 5 failed attempts for 1 minute
   - Increase lockout time for repeat offenders

3. **Layer 3: Primary CAPTCHA**
   - Use **Cloudflare Turnstile** on:
     - Registration forms (always)
     - Contact forms (always)
     - Login after 3 failed attempts (progressive)

4. **Layer 4: Fallback for Admin**
   - Keep **reCAPTCHA v2 Checkbox** configured
   - Switch if Turnstile has issues
   - Use for admin login (always show)

5. **Avoid:**
   - ❌ Don't use reCAPTCHA v3 on login (silent failures)
   - ❌ Don't show CAPTCHA on every form (only when needed)
   - ❌ Don't disable honeypot (no reason to turn it off)

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
   - ✅ **Honeypot** (always on, catches basic bots)
   - ✅ **Turnstile** (visible verification, primary defense)
   - ✅ **Rate limiting** (prevents brute force)
   - ✅ **reCAPTCHA v2** (fallback option)
5. **Update regularly** - Keep captcha plugin updated
6. **Check logs** - Monitor honeypot catches: `storage/logs/laravel.log`

### Monitoring Honeypot Activity

Check Laravel logs to see honeypot catches:
```bash
tail -f storage/logs/laravel.log | grep "Honeypot"
```

You'll see entries like:
```
[warning] Honeypot field filled {"ip":"123.45.67.89","user_agent":"bot/1.0"}
[warning] Form submitted too fast {"ip":"123.45.67.89","elapsed_seconds":0.3}
```

**What to do with this data:**
- Block IPs with repeated violations
- Analyze user agents to identify bot patterns
- Adjust timing thresholds if needed
- Report to Cloudflare or hosting provider

## Comparison Chart

| Feature | Honeypot | Turnstile | reCAPTCHA v2 | reCAPTCHA v3 | Math CAPTCHA |
|---------|----------|-----------|--------------|--------------|--------------|
| User Friendly | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐ | ⭐⭐⭐⭐ |
| Bot Protection | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | ⭐⭐ |
| Privacy | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐ | ⭐⭐⭐⭐⭐ |
| User Visible | ❌ Hidden | ✅ Visible | ✅ Visible | ❌ Invisible | ✅ Visible |
| Free | ✅ Always | ✅ Unlimited | ✅ 1M/month | ✅ 1M/month | ✅ Always |
| Setup Difficulty | Very Easy | Easy | Easy | Medium | Very Easy |
| Silent Failures | Never | Never | Rare | Common | Never |
| Effectiveness Alone | Low | High | High | Medium | Low |
| **Best Used** | **+ Captcha** | **Primary** | **Fallback** | **Avoid Login** | **Basic** |

## Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Check browser console for JavaScript errors
- Verify network requests in DevTools

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

### Autofill users getting blocked?
**This shouldn't happen!** Our honeypot allows submissions 0.5s+ (autofill timing)

If it does happen:
```bash
# Check logs for the reason
grep "Honeypot\|Instant form" storage/logs/laravel.log
```

**Log entries explained:**
- `Instant form submission detected` (< 0.5s) → Real bot, correctly blocked
- `Fast form submission (possibly autofill)` (0.5-0.8s) → **Logged only, NOT blocked**
- No log entry (0.8s+) → Normal submission, no issue

**If legitimate users are blocked:**
1. Check the elapsed_seconds in logs
2. If needed, adjust timing in `platform/plugins/captcha/src/HoneypotProtection.php`:
   ```php
   public const INSTANT_SUBMIT_THRESHOLD = 0.3; // Lower = more permissive
   ```
3. Or temporarily disable: Settings → Captcha → Honeypot OFF

### False positives from honeypot?
Common triggers:
- ✅ **Honeypot field filled** → Real bot (correct block)
- ✅ **< 0.5s submission** → Automated script (correct block)  
- ℹ️ **0.5-0.8s submission** → Just logged, **NOT blocked** (autofill allowed)
- ❌ **Legitimate user blocked** → Check logs, adjust thresholds

**Remember:** Honeypot catches 70-80% of basic bots with zero user friction. False positives should be extremely rare with our smart timing logic.

# Automatic Deployment Setup

This project has **two ways** to deploy updates to production:

## Option 1: GitHub Webhook (Recommended - Automatic)

### Setup Steps:

1. **Upload deploy.php to server**
   - Use Hostinger File Manager
   - Upload `public/deploy.php` to `/home/u497292684/domains/povaly.com/public_html/deploy.php`

2. **Generate a secret key**
   ```bash
   # Run this locally to generate a random secret:
   php -r "echo bin2hex(random_bytes(32));"
   ```

3. **Edit deploy.php on server**
   - Open the file in Hostinger File Manager
   - Change line 18: Replace `'your-secret-key-here-change-this'` with your generated secret
   - Save the file

4. **Configure GitHub Webhook**
   - Go to: https://github.com/goimranx/povaly/settings/hooks
   - Click "Add webhook"
   - **Payload URL**: `https://povaly.com/deploy.php`
   - **Content type**: `application/json`
   - **Secret**: (paste the same secret from step 2)
   - **Which events?**: Select "Just the push event"
   - **Active**: ✓ Checked
   - Click "Add webhook"

5. **Test it**
   - Make any commit and push to GitHub
   - GitHub will automatically call your webhook
   - Changes will be pulled to server instantly
   - Check logs at: `/home/u497292684/domains/povaly.com/public_html/storage/logs/deploy.log`

### How it works:
- Every time you push to GitHub, GitHub sends a webhook to your server
- The `deploy.php` script runs automatically
- It pulls latest changes and clears Laravel caches
- No manual intervention needed

---

## Option 2: Admin Panel System Update (Manual)

This is already built-in to the project.

### Access:
- URL: https://povaly.com/pv-admin/system-update
- Or menu: **Platform Admin → Tools → System Update**

### Features:
- Pull from GitHub
- Update Composer dependencies
- Update NPM dependencies
- Clear all caches
- Run migrations
- Full deployment pipeline

### Note:
This requires ONE manual deployment first (to upload the SystemUpdateServiceProvider registration).

---

## Current Issue

**Problem**: Production server doesn't have latest files from GitHub
- `carousel-init.js` returns 404
- `config.php` missing carousel-init registration
- jQuery loading order issue

**Solution**: Use either deployment method above to sync server with GitHub

---

## Files to Deploy (Current Push):

Latest commits that need deployment:
```
0fd1c5d5 - Register SystemUpdateServiceProvider to enable GitHub auto-update feature
c2ce7799 - Use same carousel init approach as simple-slider plugin
180cfbe2 - Fix carousel by moving script/style tags outside container
404674d4 - Fix carousel blank spaces by removing script and style tags
6d069aa8 - Add carousel initialization script to fix owl-slider and tabs
```

Key files:
- `platform/themes/martfury/public/js/carousel-init.js` (NEW)
- `platform/themes/martfury/config.php` (UPDATED)
- `bootstrap/app.php` (UPDATED)

---

## Quick Fix (One-time Manual Deployment)

If you need to deploy NOW without setting up webhooks:

1. **Connect to server via SSH or Hostinger Terminal**
   ```bash
   cd /home/u497292684/domains/povaly.com/public_html
   git pull origin main
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

2. **Or use Hostinger File Manager**
   - Upload these 3 files manually:
     - `platform/themes/martfury/public/js/carousel-init.js`
     - `platform/themes/martfury/config.php`
     - `bootstrap/app.php`
   - Then clear cache via admin panel

After this one-time sync, set up automatic deployment above.

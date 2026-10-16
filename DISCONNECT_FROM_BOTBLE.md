# Complete Guide: Disconnect from Botble CMS and Make It Your Own

This guide will help you fork Botble CMS and make it a completely independent system under your control.

---

## Step 1: Disable Botble License System

### 1.1 Update `.env` file

Add these lines to your `.env` file:

```env
# Disable Botble License System
CMS_ENABLE_SYSTEM_UPDATER=false
DISABLE_LICENSE_ACTIVATION=true
```

### 1.2 Update `config/core/base/general.php`

Find this file and change:

```php
'enable_system_updater' => env('CMS_ENABLE_SYSTEM_UPDATER', true),
```

To:

```php
'enable_system_updater' => env('CMS_ENABLE_SYSTEM_UPDATER', false),
```

---

## Step 2: Remove License Verification Code

### 2.1 Comment out license checks in Service Providers

File: `platform/core/base/src/Providers/BaseServiceProvider.php`

Find and comment out any code related to:
- `Core::checkLicense()`
- `Core::validateLicense()`
- License verification middleware

### 2.2 Remove license validation from routes

File: `platform/core/base/routes/web.php`

Remove or comment out any middleware like:
- `'middleware' => 'license'`
- License check routes

---

## Step 3: Rebrand the CMS

### 3.1 Change App Name

In `.env`:
```env
APP_NAME="YourCompany CMS"
```

### 3.2 Update Admin Panel Branding

File: `config/core/base/general.php`

```php
'admin_title' => 'YourCompany Admin Panel',
'admin_logo' => 'path/to/your/logo.png',
```

### 3.3 Remove Botble Branding from Admin

Files to update:
- `platform/core/base/resources/views/layouts/master.blade.php`
- `platform/core/base/resources/views/layouts/partials/footer.blade.php`
- `platform/core/base/resources/views/layouts/partials/header.blade.php`

Replace "Botble CMS" with your brand name.

---

## Step 4: Disable Update Checks

### 4.1 Remove Update Check Component

File: `platform/core/dashboard/resources/views/list.blade.php`

Comment out or remove:
```blade
@if (config('core.base.general.enable_system_updater') && Auth::user()->isSuperUser())
    <v-check-for-updates ... >
    </v-check-for-updates>
@endif
```

### 4.2 Remove System Updater Menu Item

File: `platform/core/base/src/PanelSections/System/SystemPanelSection.php`

Comment out or remove the "System Updater" menu item.

---

## Step 5: Remove License Server Connections

### 5.1 Block External License API Calls

File: `platform/core/base/src/Supports/Core.php`

Find methods that make HTTP calls to:
- `https://license.botble.com`
- Any Botble API endpoints

Either:
- Return fake/empty responses
- Comment out the code
- Make them always return `true`

---

## Step 6: Create Your Own Update System

### 6.1 Create a Git-Based Deployment

1. **Initialize Git repository:**
```bash
git init
git add .
git commit -m "Initial commit - Forked from Botble CMS"
```

2. **Create remote repository** (GitHub/GitLab/Bitbucket)

3. **Push to remote:**
```bash
git remote add origin YOUR_REPO_URL
git push -u origin main
```

### 6.2 Create Deployment Script

Create file: `deploy.sh`

```bash
#!/bin/bash
echo "Starting deployment..."

# Pull latest changes
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install

# Compile assets
npm run prod

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Publish assets
php artisan vendor:publish --tag=public --force

echo "Deployment complete!"
```

Make it executable:
```bash
chmod +x deploy.sh
```

### 6.3 Create Admin Update Button

Add a custom update button in your admin panel that runs:
```bash
./deploy.sh
```

Or create a web-based updater that:
1. Pulls from Git
2. Runs migrations
3. Compiles assets
4. Clears cache

---

## Step 7: Remove Botble Attribution

### 7.1 Update Composer Package Name

File: `composer.json`

Change:
```json
{
    "name": "botble/martfury",
    "description": "Botble Martfury Theme",
    ...
}
```

To:
```json
{
    "name": "yourcompany/cms",
    "description": "YourCompany E-Commerce CMS",
    ...
}
```

### 7.2 Update Copyright Notices

Find and replace in all files:
- "Botble Technologies" → "Your Company Name"
- "© Botble" → "© Your Company"
- Botble license headers

### 7.3 Update Database Seeds and Demo Data

Remove or update any demo data that references Botble.

---

## Step 8: Security Hardening

### 8.1 Change Admin URL

In `.env`:
```env
ADMIN_DIR=your-secret-admin-path
```

### 8.2 Remove Debug Information

In `.env` for production:
```env
APP_DEBUG=false
APP_ENV=production
```

### 8.3 Generate New APP_KEY

```bash
php artisan key:generate
```

---

## Step 9: Create Your Own Documentation

1. Create `README.md` for your team
2. Document custom features
3. Create deployment guide
4. Setup instructions for new developers

---

## Step 10: Version Control Best Practices

### 10.1 Create `.gitignore` additions

Add to `.gitignore`:
```
# Botble license files
storage/.license
storage/framework/license-*

# Local environment
.env.local
.env.*.local

# Build artifacts
public/themes/*/js/*
public/themes/*/css/*
public/vendor/
```

### 10.2 Use Git Tags for Versioning

```bash
git tag -a v1.0.0 -m "First independent version"
git push origin v1.0.0
```

---

## Step 11: Create Your Own Admin Panel Customization

File: `platform/themes/martfury/views/layouts/admin.blade.php`

Create your own admin theme separate from Botble's.

---

## Verification Checklist

After completing all steps, verify:

- [ ] No license activation prompts appear
- [ ] System updater is hidden/disabled
- [ ] All Botble branding removed
- [ ] Admin panel shows your branding
- [ ] Git repository is set up
- [ ] Deployment script works
- [ ] No external calls to Botble servers
- [ ] Documentation is updated
- [ ] Team knows how to deploy updates

---

## Future Maintenance

### For Core Updates:
1. Make all changes in your Git repository
2. Commit with descriptive messages
3. Push to remote
4. Deploy using `deploy.sh` script

### For Theme Updates:
1. Edit files in `platform/themes/martfury/`
2. Run `npm run prod` to compile
3. Commit and deploy

### For Plugin Updates:
1. Edit files in `platform/plugins/`
2. Run migrations if needed
3. Commit and deploy

---

## Support & Community

Since you're now independent from Botble:
1. Build your own internal documentation
2. Train your team on the system
3. Consider hiring Laravel developers familiar with the codebase
4. Keep security patches up to date manually

---

## Legal Considerations

**Important:** Check your Botble CMS license agreement before:
- Removing attribution
- Rebranding the system
- Using it commercially

You may need to:
- Purchase a proper license
- Keep certain attributions
- Comply with open-source licenses of dependencies

---

## Conclusion

You now have a completely independent CMS system that:
✅ Doesn't connect to Botble servers
✅ Uses your own branding
✅ Has its own update mechanism
✅ Is under your full control
✅ Can be customized without restrictions

**Next Steps:**
1. Run through this guide step by step
2. Test everything in development first
3. Create backup before deploying to production
4. Set up automated backups
5. Document your custom features

---

**Need help with any step? Let me know which part you want to tackle first!**

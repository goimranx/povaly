# Complete Setup Guide - GitHub Auto-Update System

This guide will help you set up automatic updates from GitHub with a button in your admin panel, and completely disable Botble license checks.

---

## Part 1: Disable Botble License System (Already Done ✓)

### Step 1.1: Environment Variables
The `.env` file has been updated with:
```env
# Disable Botble License System
CMS_ENABLE_SYSTEM_UPDATER=false
DISABLE_LICENSE_ACTIVATION=true

# GitHub Update System
GITHUB_REPO_URL=https://github.com/yourusername/yourrepo
GITHUB_BRANCH=main
```

**Action Required:** Update `GITHUB_REPO_URL` and `GITHUB_BRANCH` with your actual repository details.

---

## Part 2: Initialize Git Repository (If Not Done)

### Step 2.1: Initialize Git
```bash
cd d:\Work Area\Development\Projects\povaly
git init
git add .
git commit -m "Initial commit - Sistema Povaly"
```

### Step 2.2: Add Remote Repository
```bash
# Replace with your actual GitHub repository URL
git remote add origin https://github.com/yourusername/povaly.git
git branch -M main
git push -u origin main
```

---

## Part 3: Register Service Provider

### Step 3.1: Open config/app.php
Find the `providers` array and add:

```php
'providers' => ServiceProvider::defaultProviders()->merge([
    // ... existing providers ...
    
    App\Providers\SystemUpdateServiceProvider::class,  // Add this line
])->toArray(),
```

### Step 3.2: Or Use Composer Autoload
If Botble uses auto-discovery, you can add to composer.json:

```json
{
    "extra": {
        "laravel": {
            "providers": [
                "App\\Providers\\SystemUpdateServiceProvider"
            ]
        }
    }
}
```

Then run:
```bash
composer dump-autoload
```

---

## Part 4: Clear Cache and Test

### Step 4.1: Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 4.2: Check Routes
```bash
php artisan route:list | findstr "system.update"
```

You should see:
```
GET|HEAD  pv-admin/system-update ........... system.update.index
POST      pv-admin/system-update/perform ... system.update.perform
GET|HEAD  pv-admin/system-update/check ..... system.update.check
GET|HEAD  pv-admin/system-update/version ... system.update.version
POST      pv-admin/system-update/clear-cache  system.update.clear-cache
```

---

## Part 5: Set Up Git Credentials (For Automation)

### Option A: HTTPS with Token (Recommended)

1. **Create a GitHub Personal Access Token:**
   - Go to GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
   - Click "Generate new token (classic)"
   - Give it a name like "Povaly Auto-Update"
   - Select scopes: `repo` (all)
   - Click "Generate token"
   - **Copy the token immediately** (you won't see it again)

2. **Configure Git to use the token:**
   ```bash
   # Set credential helper to cache
   git config credential.helper store
   
   # Or use Windows Credential Manager
   git config credential.helper wincred
   ```

3. **Update remote URL with token:**
   ```bash
   # Format: https://TOKEN@github.com/username/repo.git
   git remote set-url origin https://ghp_YourTokenHere@github.com/yourusername/povaly.git
   ```

### Option B: SSH Keys

1. **Generate SSH key:**
   ```bash
   ssh-keygen -t ed25519 -C "your-email@example.com"
   ```

2. **Add to GitHub:**
   - Copy the public key: `cat ~/.ssh/id_ed25519.pub`
   - Go to GitHub → Settings → SSH and GPG keys → New SSH key
   - Paste and save

3. **Update remote:**
   ```bash
   git remote set-url origin git@github.com:yourusername/povaly.git
   ```

---

## Part 6: Configure Web Server Permissions

The web server needs permission to execute Git and Composer commands.

### For Apache on Windows:
```bash
# No specific action needed if running as current user
```

### For Linux/Production:
```bash
# Give web server user git access
sudo usermod -a -G www-data $USER

# Set proper permissions
sudo chown -R www-data:www-data /var/www/html/povaly
sudo chmod -R 755 /var/www/html/povaly
sudo chmod -R 775 /var/www/html/povaly/storage
sudo chmod -R 775 /var/www/html/povaly/bootstrap/cache
```

---

## Part 7: Access the Update Panel

### Step 7.1: Login to Admin Panel
Go to: `http://povaly.com/pv-admin`

### Step 7.2: Navigate to System Update
After logging in, look for the menu:
```
Platform Admin → System → System Update
```

Or directly visit:
```
http://povaly.com/pv-admin/system-update
```

---

## Part 8: Using the Update System

### Available Buttons:

1. **Check for Updates**
   - Checks GitHub for new commits
   - Shows what has changed
   - Safe to use anytime

2. **Update Now**
   - Only enabled when updates are detected
   - Pulls changes and updates the system
   - Site goes into maintenance mode during update

3. **Force Update**
   - Updates even if no changes detected
   - Useful when:
     * You made manual changes and want to revert
     * Git is out of sync
     * You want to rebuild everything

4. **Clear Cache Only**
   - Clears all application caches
   - Doesn't pull from GitHub
   - Quick fix for cache issues

### Update Process:
1. Site enters maintenance mode
2. Pulls latest code from GitHub
3. Updates Composer dependencies
4. Updates NPM dependencies
5. Compiles assets (npm run prod)
6. Clears all caches
7. Runs database migrations
8. Publishes vendor assets
9. Optimizes application
10. Site exits maintenance mode

**Duration:** 2-5 minutes depending on server speed

---

## Part 9: Remove Botble License Notifications (Optional)

If you still see license prompts, add these files to disable them:

### Create: `app/Http/Middleware/DisableLicenseCheck.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableLicenseCheck
{
    public function handle(Request $request, Closure $next)
    {
        // Disable license checking
        config(['core.base.general.enable_system_updater' => false]);
        
        return $next($request);
    }
}
```

### Register in `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->append(\App\Http\Middleware\DisableLicenseCheck::class);
})
```

---

## Part 10: Troubleshooting

### Issue: Routes not found
**Solution:**
```bash
php artisan route:clear
php artisan cache:clear
composer dump-autoload
```

### Issue: Permission denied during update
**Solution:**
```bash
# On Linux
sudo chown -R www-data:www-data /var/www/html/povaly
sudo chmod -R 775 storage bootstrap/cache

# On Windows
# Run Command Prompt as Administrator
icacls "d:\Work Area\Development\Projects\povaly\storage" /grant Users:(OI)(CI)F /T
```

### Issue: Git authentication failed
**Solution:**
- Make sure your GitHub token has `repo` permissions
- Update the remote URL with the token:
  ```bash
  git remote set-url origin https://TOKEN@github.com/user/repo.git
  ```

### Issue: Composer/NPM not found
**Solution:**
- Make sure they're in PATH
- Or update controller to use full paths:
  ```php
  /usr/local/bin/composer install
  /usr/local/bin/npm install
  ```

### Issue: Update hangs
**Solution:**
- Check server PHP execution time
- Increase in `.env`:
  ```env
  MAX_EXECUTION_TIME=300
  ```
- Or php.ini:
  ```ini
  max_execution_time = 300
  ```

---

## Part 11: Best Practices

### Before Every Update:
1. ✅ Backup your database
2. ✅ Test in staging environment first
3. ✅ Check for breaking changes in commit log
4. ✅ Ensure you have recent backups

### Workflow:
```
Development → Commit → Push to GitHub → Production Update Button
```

### Scheduled Backups:
Set up automatic backups before allowing updates:

```bash
# Add to crontab (Linux)
0 2 * * * /usr/bin/php /var/www/html/povaly/artisan backup:run
```

---

## Part 12: Deployment Script (Alternative Method)

If you prefer SSH deployment:

### Create: `deploy.sh`

```bash
#!/bin/bash

echo "🚀 Starting Deployment..."

# Enable maintenance mode
php artisan down

# Pull latest changes
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install

# Build assets
npm run prod

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force

# Publish assets
php artisan vendor:publish --tag=public --force

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Disable maintenance mode
php artisan up

echo "✅ Deployment Complete!"
```

Make it executable:
```bash
chmod +x deploy.sh
```

---

## Part 13: Security Recommendations

1. **Restrict Access:**
   - Only allow superuser access to update panel
   - Use strong passwords
   - Enable 2FA if available

2. **GitHub Token Security:**
   - Never commit tokens to repository
   - Use environment variables
   - Rotate tokens periodically

3. **Backup Strategy:**
   - Daily automated backups
   - Store backups off-site
   - Test restore process

4. **Monitor Updates:**
   - Check logs after each update
   - Test critical functionality
   - Have rollback plan ready

---

## Summary Checklist

- [ ] Git repository initialized
- [ ] GitHub remote added
- [ ] Service provider registered
- [ ] Routes verified
- [ ] GitHub token configured
- [ ] Server permissions set
- [ ] Admin panel accessible
- [ ] Test update button works
- [ ] Backup system in place
- [ ] License checks disabled

---

## Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server logs
3. Enable debug mode temporarily: `APP_DEBUG=true`
4. Check GitHub repository access

---

**🎉 Congratulations! Your auto-update system is ready!**

You can now update your production site with a single click from the admin panel.

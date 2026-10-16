# Installation Checklist - GitHub Auto-Update System

Complete these steps in order. Check off each item as you finish.

---

## Phase 1: Git Repository Setup

### Step 1.1: Initialize Local Repository
```bash
cd "d:\Work Area\Development\Projects\povaly"
git init
git add .
git commit -m "Initial commit - Povaly System"
```
- [ ] Git repository initialized
- [ ] Files committed

### Step 1.2: Create GitHub Repository
1. Go to https://github.com/new
2. Repository name: `povaly` (or your choice)
3. Set to **Private** (recommended)
4. Don't initialize with README
5. Click "Create repository"

- [ ] GitHub repository created
- [ ] Repository URL copied

### Step 1.3: Connect Local to GitHub
```bash
# Replace with YOUR repository URL
git remote add origin https://github.com/YOUR_USERNAME/povaly.git
git branch -M main
git push -u origin main
```
- [ ] Remote origin added
- [ ] Code pushed to GitHub
- [ ] Can view code on GitHub website

---

## Phase 2: GitHub Token Configuration

### Step 2.1: Create Personal Access Token
1. Go to: https://github.com/settings/tokens
2. Click "Generate new token (classic)"
3. Token name: `Povaly Auto-Update`
4. Expiration: `No expiration` or `90 days`
5. Select scopes: ✅ **repo** (all checkboxes)
6. Click "Generate token"
7. **COPY THE TOKEN** immediately!

- [ ] Token created
- [ ] Token copied to safe place

### Step 2.2: Configure Git to Use Token
```bash
# Enable credential storage
git config credential.helper store

# Update remote URL with token
git remote set-url origin https://YOUR_TOKEN_HERE@github.com/YOUR_USERNAME/povaly.git

# Test authentication
git pull origin main
```
- [ ] Credential helper configured
- [ ] Remote URL updated with token
- [ ] Can pull without password prompt

---

## Phase 3: Environment Configuration

### Step 3.1: Update .env File
Open: `d:\Work Area\Development\Projects\povaly\.env`

Verify these lines exist (they should already):
```env
# Disable Botble License System
CMS_ENABLE_SYSTEM_UPDATER=false
DISABLE_LICENSE_ACTIVATION=true

# GitHub Update System
GITHUB_REPO_URL=https://github.com/yourusername/yourrepo
GITHUB_BRANCH=main
```

Update with YOUR values:
```env
GITHUB_REPO_URL=https://github.com/YOUR_USERNAME/povaly
GITHUB_BRANCH=main
```

- [ ] `.env` file updated
- [ ] Repository URL is correct
- [ ] Branch name is correct (usually `main` or `master`)

---

## Phase 4: Service Provider Registration

### Step 4.1: Check Auto-Discovery (Try This First)
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```
- [ ] Commands executed successfully

### Step 4.2: Manual Registration (If Auto-Discovery Doesn't Work)
Open: `config/app.php`

Find the `providers` array and add:
```php
'providers' => ServiceProvider::defaultProviders()->merge([
    // ... existing providers ...
    App\Providers\SystemUpdateServiceProvider::class,
])->toArray(),
```

- [ ] Service provider added to config (if needed)
- [ ] File saved

---

## Phase 5: Clear All Caches

### Step 5.1: Run Cache Clear Commands
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
composer dump-autoload
```
- [ ] All cache clear commands executed
- [ ] No errors reported

---

## Phase 6: Verification

### Step 6.1: Verify Routes
```bash
php artisan route:list | findstr "system.update"
```

Expected output:
```
GET|HEAD   pv-admin/system-update ............... system.update.index
POST       pv-admin/system-update/perform ....... system.update.perform
GET|HEAD   pv-admin/system-update/check ......... system.update.check
GET|HEAD   pv-admin/system-update/version ....... system.update.version
POST       pv-admin/system-update/clear-cache ... system.update.clear-cache
```

- [ ] Routes are listed
- [ ] All 5 routes are present

### Step 6.2: Verify File Permissions (Linux only)
```bash
# Skip if on Windows
sudo chown -R www-data:www-data /var/www/html/povaly
sudo chmod -R 775 storage bootstrap/cache
```
- [ ] File ownership correct (Linux)
- [ ] Permissions set (Linux)
- [ ] N/A (I'm on Windows)

### Step 6.3: Test Web Access
1. Open browser
2. Navigate to: `http://povaly.com/pv-admin`
3. Login with admin credentials

- [ ] Can access admin panel
- [ ] Successfully logged in

### Step 6.4: Access Update Panel
Navigate to: `http://povaly.com/pv-admin/system-update`

OR find in menu: **Platform Admin → System → System Update**

- [ ] Update panel page loads
- [ ] No errors displayed
- [ ] Version information loads

---

## Phase 7: Functional Testing

### Step 7.1: Test "Check for Updates"
1. Click **"Check for Updates"** button
2. Should show current version info
3. May say "No updates available" (normal for first time)

- [ ] Button works
- [ ] Shows version information
- [ ] No errors

### Step 7.2: Test "Clear Cache Only"
1. Click **"Clear Cache Only"** button
2. Should complete in 10-20 seconds
3. Should show success message

- [ ] Cache clear button works
- [ ] Completes successfully
- [ ] Site still loads normally

### Step 7.3: Test Update Functionality
```bash
# Make a small test change
echo "# Test Update" >> README.md
git add README.md
git commit -m "Test: System update verification"
git push origin main
```

Now in admin panel:
1. Click **"Check for Updates"**
2. Should show 1 new commit
3. Click **"Update Now"**
4. Watch progress (2-5 minutes)
5. Should complete successfully

- [ ] Test commit pushed to GitHub
- [ ] Update detected
- [ ] Update completed successfully
- [ ] Site loads normally after update

---

## Phase 8: License System Verification

### Step 8.1: Check for License Prompts
1. Browse admin panel
2. Check various pages
3. Should see NO license notifications

- [ ] No license activation prompts
- [ ] No license warning messages
- [ ] No Botble update notifications

### Step 8.2: Verify Configuration
```bash
php artisan config:show core.base.general.enable_system_updater
```
Expected output: `false`

- [ ] Shows `false` (license system disabled)

If still shows license prompts:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

- [ ] License prompts resolved

---

## Phase 9: Security & Backup

### Step 9.1: Document Credentials
Create a secure note with:
- GitHub repository URL
- GitHub token (encrypted/secure location)
- Admin panel URL
- Admin credentials

- [ ] Credentials documented securely
- [ ] Token stored safely

### Step 9.2: Set Up Backup System
1. Configure database backups
2. Test backup/restore process
3. Schedule automated backups

- [ ] Backup system configured
- [ ] Tested restore procedure
- [ ] Automated backups scheduled

### Step 9.3: Production Checklist
```env
# Verify in .env:
APP_ENV=production
APP_DEBUG=false
```

- [ ] Environment set to production
- [ ] Debug mode disabled
- [ ] Error logging configured

---

## Phase 10: Final Verification

### Step 10.1: Complete System Check
Run all these commands:
```bash
# Test Git
git status
git fetch origin main

# Test Laravel
php artisan config:show app.env
php artisan route:list | findstr "system.update"

# Test Composer
composer --version

# Test NPM
npm --version
```

- [ ] Git working
- [ ] Laravel working
- [ ] Composer installed
- [ ] NPM installed

### Step 10.2: Browser Testing
Test in admin panel:
1. Dashboard loads
2. System Update page loads
3. Check for updates works
4. All buttons are visible
5. No console errors (F12)

- [ ] Dashboard working
- [ ] Update panel accessible
- [ ] No JavaScript errors
- [ ] All features functional

---

## ✅ Installation Complete!

If all boxes are checked, your system is ready!

### Quick Reference:

**Update Panel URL:**
```
http://povaly.com/pv-admin/system-update
```

**Daily Workflow:**
```
1. Code locally
2. git commit -m "description"
3. git push origin main
4. Admin Panel → Check Updates
5. Update Now
```

**Emergency Rollback:**
```bash
git log --oneline -10
git reset --hard COMMIT_HASH
php artisan cache:clear
```

---

## 🆘 If Something Went Wrong

### Common Issues:

**Routes Not Found:**
```bash
php artisan route:clear
php artisan config:clear
composer dump-autoload
```

**Git Authentication Failed:**
```bash
git remote set-url origin https://TOKEN@github.com/USER/REPO.git
```

**Can't Access Update Panel:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:list | findstr "system.update"
```

**License Still Shows:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 📋 Maintenance Checklist (Monthly)

- [ ] Test update system
- [ ] Verify backups working
- [ ] Check disk space
- [ ] Review error logs
- [ ] Rotate GitHub token (every 90 days)
- [ ] Update documentation

---

**Installation Date:** _________________

**Installed By:** _________________

**GitHub Repository:** _________________

**Notes:**
_______________________________________
_______________________________________
_______________________________________

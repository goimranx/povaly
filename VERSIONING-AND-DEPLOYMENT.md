# Povaly Versioning & Deployment System

> **Automated version management and cache busting using Git tags**  
> **Last Updated:** October 23, 2026

---

## 🎯 Overview

Povaly uses **Git-based versioning** for automatic version tracking and cache busting:

- **Production (povaly.com)**: Uses Git **tags** (v1.0.0, v1.1.0, v1.2.0...)
- **Staging (stage.povaly.com)**: Uses Git **branch** + commit hash
- **Version automatically updates** when you deploy
- **Assets auto-cache-bust** using version numbers

---

## 🏷️ How It Works

### Production (main branch)

```bash
# When you deploy with a tag:
git tag -a v1.1.0 -m "Added reviews"
git push origin v1.1.0

# System automatically:
# 1. Reads Git tag: v1.1.0
# 2. Updates app version: v1.1.0
# 3. Cache busts assets: style.css?v=1.1.0
```

### Staging (stage branch)

```bash
# When you push to stage:
git push origin stage

# System automatically:
# 1. Reads branch + commit: stage-abc1234
# 2. Updates app version: stage-abc1234
# 3. Cache busts assets: style.css?v=abc1234
```

---

## 📝 Usage in Code

### Display Version

```php
// In controllers
use App\Helpers\VersionHelper;

$version = VersionHelper::getCurrentVersion(); // "v1.0.0" or "stage-abc1234"
$environment = VersionHelper::getEnvironment(); // "production" or "staging"
$isProduction = VersionHelper::isProduction(); // true/false
```

```blade
{{-- In Blade views --}}
<p>Version: {{ app_version() }}</p>
<p>Environment: {{ deployment_environment() }}</p>

@if(is_production_env())
    <span class="badge-production">Production</span>
@elseif(is_staging_env())
    <span class="badge-staging">Staging</span>
@endif
```

---

### Cache Busting Assets

**Method 1: Automatic (Recommended)**

```blade
{{-- Old way (no cache busting) --}}
<link href="{{ asset('css/style.css') }}" rel="stylesheet">
<script src="{{ asset('js/app.js') }}"></script>

{{-- New way (automatic cache busting) --}}
<link href="{{ asset_v('css/style.css') }}" rel="stylesheet">
<script src="{{ asset_v('js/app.js') }}"></script>

{{-- Generates: --}}
{{-- Production: style.css?v=1.0.0 --}}
{{-- Staging: style.css?v=abc1234 --}}
```

**Method 2: Manual**

```blade
<link href="{{ asset('css/style.css') }}?v={{ cache_bust() }}" rel="stylesheet">
<script src="{{ asset('js/app.js') }}?v={{ cache_bust() }}"></script>
```

**Method 3: In Theme Functions**

```php
// In theme functions or config
Theme::asset()->add('style', 'css/style.css?v=' . cache_bust());
Theme::asset()->add('script', 'js/app.js?v=' . cache_bust());
```

---

## 🚀 Deployment Workflow

### Production Deployment (povaly.com)

```bash
# 1. Merge to main
git checkout main
git merge stage
git push origin main

# 2. Create tag (REQUIRED!)
git tag -a v1.1.0 -m "v1.1.0 - Added product reviews

Added:
- Product review system
- Star ratings

Fixed:
- Cart calculation bug"

git push origin v1.1.0

# 3. Deploy (System Updater will use latest tag)
# Click "System Updater" in CMS
# OR SSH and run:
ssh povaly.com
cd /var/www/povaly
# System updater runs: git fetch --tags && git checkout v1.1.0

# 4. Version automatically updates to v1.1.0!
# 5. Assets automatically cache-bust: style.css?v=1.1.0
```

---

### Staging Deployment (stage.povaly.com)

```bash
# 1. Merge features to stage branch
git checkout stage
git merge feature/reviews
git push origin stage

# 2. Deploy (System Updater will use stage branch)
# Click "System Updater" in CMS
# OR SSH and run:
ssh stage.povaly.com
cd /var/www/stage
# System updater runs: git pull origin stage

# 3. Version automatically updates to stage-abc1234!
# 4. Assets automatically cache-bust: style.css?v=abc1234
```

---

## 🔧 System Updater

The custom System Updater now handles both production and staging differently:

### On Production (main branch):

1. Fetches all tags: `git fetch --tags`
2. Gets latest tag: `v1.1.0`
3. Checks out tag: `git checkout v1.1.0`
4. Updates dependencies
5. Publishes assets
6. Clears caches
7. Version automatically updates!

### On Staging (stage branch):

1. Fetches branch: `git fetch origin stage`
2. Resets to branch: `git reset --hard origin/stage`
3. Updates dependencies
4. Publishes assets
5. Clears caches
6. Version automatically updates!

---

## 📊 Version Helper API

### VersionHelper Class

```php
use App\Helpers\VersionHelper;

// Get current version
VersionHelper::getCurrentVersion(); // "v1.0.0" or "stage-abc1234"

// Get current branch
VersionHelper::getCurrentBranch(); // "main" or "stage"

// Get current commit hash
VersionHelper::getCurrentCommit(); // "abc1234"

// Get all Git tags
VersionHelper::getAllTags(); // ["v1.2.0", "v1.1.0", "v1.0.0"]

// Get cache bust version
VersionHelper::getCacheBustVersion(); // "1.0.0" or "abc1234"

// Get environment
VersionHelper::getEnvironment(); // "production", "staging", or "development"

// Check environment
VersionHelper::isProduction(); // true/false
VersionHelper::isStaging(); // true/false

// Get last update time
VersionHelper::getLastUpdateTime(); // "2026-10-23 14:30:00"

// Get tag information
VersionHelper::getTagInfo('v1.1.0'); // ['tag' => 'v1.1.0', 'message' => '...', 'date' => '...']

// Clear version cache (call after deployment)
VersionHelper::clearCache();
```

---

### Helper Functions

```php
// Global helper functions (available everywhere)

app_version(); // "v1.0.0" or "stage-abc1234"

cache_bust(); // "1.0.0" or "abc1234"

asset_v('css/style.css'); // "/css/style.css?v=1.0.0"

is_production_env(); // true/false

is_staging_env(); // true/false

deployment_environment(); // "production", "staging", or "development"
```

---

## 🎨 Update Your Theme

### Update Theme Assets

Find all asset references in your theme and add cache busting:

**Before:**
```blade
{{-- platform/themes/martfury/partials/header.blade.php --}}
<link href="{{ asset('themes/martfury/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('themes/martfury/css/custom.css') }}" rel="stylesheet">
```

**After:**
```blade
<link href="{{ asset_v('themes/martfury/css/style.css') }}" rel="stylesheet">
<link href="{{ asset_v('themes/martfury/css/custom.css') }}" rel="stylesheet">
```

**Before:**
```blade
{{-- platform/themes/martfury/partials/footer.blade.php --}}
<script src="{{ asset('themes/martfury/js/app.js') }}"></script>
<script src="{{ asset('themes/martfury/js/custom.js') }}"></script>
```

**After:**
```blade
<script src="{{ asset_v('themes/martfury/js/app.js') }}"></script>
<script src="{{ asset_v('themes/martfury/js/custom.js') }}"></script>
```

---

### Display Version in Admin

**Add to admin footer:**

```blade
{{-- platform/themes/martfury/partials/admin-footer.blade.php --}}
<div class="version-info">
    <span class="badge badge-{{ is_production_env() ? 'success' : 'warning' }}">
        {{ deployment_environment() }}
    </span>
    <span class="text-muted">Version: {{ app_version() }}</span>
</div>
```

---

## 🐛 Troubleshooting

### Version not updating after deployment?

```bash
# Clear version cache
php artisan cache:clear

# Or in code:
VersionHelper::clearCache();

# Verify Git
git describe --tags  # Should show latest tag on production
git rev-parse HEAD    # Should show current commit
```

### Assets not cache-busting?

1. Check if using `asset_v()` helper
2. Verify version is updating: `{{ cache_bust() }}`
3. Clear Laravel cache: `php artisan cache:clear`
4. Clear browser cache

### Wrong version showing?

```bash
# Check current branch
git branch

# Production should be on: main
# Staging should be on: stage

# If on wrong branch:
git checkout main  # For production
git checkout stage  # For staging
```

---

## 📋 Checklist: Adding Cache Busting

Use this checklist when adding cache busting to your theme:

```
□ Install VersionHelper and helpers
  - app/Helpers/VersionHelper.php ✓
  - app/Helpers/helpers.php ✓
  - composer.json autoload updated ✓

□ Run composer dump-autoload
  - composer dump-autoload

□ Update theme header
  - Change asset() to asset_v() for CSS
  - Test: View source, see ?v=1.0.0

□ Update theme footer  
  - Change asset() to asset_v() for JS
  - Test: View source, see ?v=1.0.0

□ Update admin templates
  - Add version display
  - Add environment badge

□ Test production
  - Deploy with tag
  - Verify version updates
  - Verify assets have ?v=1.0.0

□ Test staging
  - Deploy to stage
  - Verify version updates  
  - Verify assets have ?v=abc1234

□ Document for team
  - Update README
  - Train team on cache busting
```

---

## 🎯 Benefits

### Before (No Versioning)

```
❌ Manual version tracking
❌ Assets cached indefinitely
❌ Users see old CSS/JS after updates
❌ No way to know which version is live
❌ Hard to rollback
```

### After (Automated Versioning)

```
✅ Automatic version from Git tags
✅ Assets auto-cache-bust on deploy
✅ Users always see latest CSS/JS
✅ Clear version visible everywhere
✅ Easy rollback (checkout old tag)
✅ Production vs Staging clear
```

---

## 📚 Related Documentation

- **POVALY-GIT-WORKFLOW.md** - Complete Git workflow
- **VERSIONING.md** - Version numbering strategy
- **CHANGELOG.md** - Version history
- **README.md** - Project overview

---

## 🚀 Quick Reference

```bash
# Production: Tag-based
git tag -a v1.1.0 -m "message"
git push origin v1.1.0
# Deploy → System checks out v1.1.0

# Staging: Branch-based
git push origin stage
# Deploy → System pulls stage branch

# In code: Cache busting
<link href="{{ asset_v('css/style.css') }}" rel="stylesheet">
# Generates: style.css?v=1.0.0 (production) or style.css?v=abc1234 (staging)

# Check version
{{ app_version() }}          // v1.0.0 or stage-abc1234
{{ cache_bust() }}            // 1.0.0 or abc1234
{{ deployment_environment() }} // production, staging, or development
```

---

**Version system is now active!** Every deployment automatically updates the version and cache-busts all assets. 🎉


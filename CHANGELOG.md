# Changelog

All notable changes to Povaly will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.3] - 2026-10-30

### ✨ Added

- Product details page: Swatches now display in 5-column grid (changed from 6 columns)
- Action buttons (share, wishlist, compare) now use `global-color-border-strong` for consistent border styling

### 🐛 Fixed

- Fixed thumbnail-size images (150x150) displaying in main gallery after variation changes
- Fixed lens zoom box now properly hides when hovering over action buttons for cleaner interaction
- Fixed `restoreSelectedImage()` to use gallery's full-size URLs instead of thumbnail URLs
- Fixed main image and thumbnail `data-image` attributes to strip size suffixes (e.g., `-150x150`)
- Fixed product gallery thumbnail reordering logic after variation selection
- Updated pd-separator margins to 14px top/bottom (consistent spacing)

### 🧹 Cleanup

- Removed debug console.log statements from ecommerce JavaScript files
  - `change-product-swatches.js`
  - `pv-gallery.js`
  - `front-ecommerce.js`
  - `front-review.js`
  - `front-upsale-crosssale.js`

---

## [1.3.2] - 2026-10-26

### ✨ Added

- Amazon-style image lens zoom on PV gallery — hover over main product image to see a magnified zoom panel on the right
- Lens uses `global-color-link` dot grid pattern (polished halftone style)
- Zoom panel covers the info-wrapper area, full viewport height with 10px top/bottom gap
- Zoom panel border uses `global-color-border-strong`
- Lens size matches zoom panel aspect ratio for identical proportions

### 🐛 Fixed

- Double toast on add-to-cart from quick-shop panel (main.js now defers to front-ecommerce.js when `data-bb-toggle="product-form"` is present)

---

## [1.3.1] - 2026-10-25

### 🔀 Merged

- Merged `stage` into `main` — staging changes (bulletproof updater, SSE deployment, VersionHelper fixes) promoted to production

### 🔧 Changed

- Bumped Povaly version to `v1.3.1` (Git tag)

---

## [1.3.0] - 2026-10-25

### ✨ Added

- Bulletproof update system with nuclear asset publishing
- SSE-based real-time deployment progress streaming

### 🔧 Changed

- System updater now supports both tag-based (production) and branch-based (staging) deployment strategies
- Improved `VersionHelper` to correctly detect detached HEAD on tags for production env detection

### 🐛 Fixed

- Missing closing brace in `SystemUpdateController` class

---

## [1.0.0] - 2026-10-23

### 🎉 First Stable Release

This is the first production-ready release of Povaly, a customized e-commerce platform built on Botble CMS v1.41.11.

### ✨ Added

#### Core Features
- **Martfury Theme Integration** - Premium e-commerce theme with custom enhancements
- **Enhanced Product Cards** - Interactive quantity controls with +/- buttons
- **System Update Manager** - Real-time progress updates when pulling from GitHub
- **PV Gallery** - Improved product gallery with better image handling
- **Video Features** - Like and share functionality for video content

#### Admin Improvements
- Real-time system updater with streaming progress display
- Color-coded update messages (info, success, warning, error)
- Auto-scrolling progress output
- One-click asset publishing
- Cache clearing functionality

#### Custom Controllers
- `SystemUpdateController` - Automated GitHub deployment system
- `VideoLikeController` - Video engagement tracking
- `VideoShareController` - Social sharing for videos
- `CartCheckController` - Enhanced cart validation

#### Database
- Video likes tracking table
- Video shares tracking table
- Improved social links schema

### 🐛 Fixed

#### Critical Fixes
- **Main.js Restoration** - Restored missing 948 lines (was 968, now 1916 lines)
  - MartApp object completely restored
  - All core functions working
  - Cart/wishlist/compare buttons fixed
  - Go-to-top button fixed
  
- **Admin Sidebar** - Fixed collapsed sidebar dropdown hover
  - Submenus now show as flyouts when sidebar is collapsed
  - Proper hover behavior restored
  - All navigation working correctly

- **Sticky Header** - Removed custom code, uses theme option
  - Controlled via Appearance > Theme Options
  - Proper animation and transitions
  - Mobile-responsive behavior

- **Carousel** - Fixed product carousel initialization
  - Owl Carousel properly initialized
  - No more manual init needed
  - Smooth transitions

- **Admin UI Files** - Restored 1,084 corrupted files
  - `core-ui.js` - 142KB corrupted → 92KB original
  - `core.css` - 640KB corrupted → 567KB original
  - All admin interactions fixed

### 🔧 Changed
- Updated version to `1.41.11-povaly.1` in `platform/core/core.json`
- Improved asset publishing in SystemUpdateController
- Enhanced product card JavaScript with better event handling
- Optimized carousel initialization

### 📦 Dependencies
- Botble CMS: `v1.41.11`
- Laravel Framework: `^11.0`
- PHP: `>=8.3.0`
- Node.js: Used locally for asset compilation
- Bootstrap: `v5.3`
- jQuery: `v3.x`

### 🔐 Security
- CSRF protection on all forms
- Middleware stack properly configured
- Botble license check disabled (custom middleware)

### 📝 Documentation
- Added `DEVELOPMENT-WORKFLOW.md` - Complete development guide
- Added `VERSIONING.md` - Version management strategy
- Added `CHANGELOG.md` - This file

### 🚀 Deployment
The recommended deployment process:
```bash
git pull origin main
php artisan cms:theme:assets:publish martfury
php clear-cache.php
```

### ⚠️ Breaking Changes
None - This is the first release

### 🗑️ Deprecated
None

### 🔄 Migration Notes
First release - no migration needed

---

## Release Notes Format

For future releases, use this format:

```markdown
## [X.Y.Z] - YYYY-MM-DD

### Added
- New features

### Changed
- Changes in existing functionality

### Deprecated
- Soon-to-be removed features

### Removed
- Now removed features

### Fixed
- Bug fixes

### Security
- Security fixes
```

---

## Links
- [Unreleased Changes](https://github.com/goimranx/povaly/compare/v1.0.0...HEAD)
- [v1.0.0](https://github.com/goimranx/povaly/releases/tag/v1.0.0)
- [All Releases](https://github.com/goimranx/povaly/releases)

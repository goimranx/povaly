# Changelog

All notable changes to Povaly will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.10.0] - 2024-11-02

### ✨ New Features

- **Dynamic Product URL Support**: Cart functionality now works with ANY permalink setting from admin
  - Added `window.povalyConfig.productUrlPrefix` configuration in header
  - Backend automatically detects current permalink setting using `SlugHelper::getPrefix(Product::class)`
  - Helper functions dynamically extract product identifiers regardless of URL format (`/products/`, `/p/`, or any custom prefix)
  - No more hardcoded URL assumptions - fully adapts to Settings → Permalinks configuration

### 🐛 Fixed - Cart & UI Enhancements

- **Cart State Synchronization**: Fixed product card buttons not reverting after cart removal
  - Enhanced `syncCartIconStates()` to check ALL quantity controls and revert to "Add to Cart" if product not in cart
  - Fixed issue where removing item from header flyout cart didn't update product card button state
  - Now properly syncs both flyout cart icons AND product card buttons after any cart change
  
- **JavaScript Scope Fix**: Moved helper functions outside IIFE for global accessibility
  - `getProductUrlPrefix()`, `extractProductIdentifier()`, and `isProductUrl()` now globally accessible
  - Fixed "isProductUrl is not defined" error at line 644
  - Maintains backward compatibility while enabling proper function access across scopes

- **Toast Notifications**: Enabled success messages for all add-to-cart actions
  - Quick shop modal buttons (Add to cart / Order now) now show success toast
  - Product card enhanced buttons now show success toast
  - Changed `data-show-toast-on-success` from `"false"` to `"true"` in both contexts
  - Provides consistent user feedback across all cart interactions

### 🔧 Technical Improvements

- **All Product Instances Updated**: When removing from cart, ALL matching product cards update simultaneously
  - Uses jQuery selector to find all instances: `$('.ec-card__qty-control[data-id="' + productId + '"]')`
  - Ensures consistent state across duplicate products on same page (e.g., related products, search results)
  
- **Robust Cart Sync**: Multiple sync mechanisms for reliability
  - Event-based sync when `productId` is provided in `ecommerce.cart.removed` event
  - Fallback server-based sync via `syncCartIconStates()` when event data unavailable
  - Handles both scenarios: direct card removal and header flyout removal

### 📝 Notes

- This release removes all debug logging added in v1.3.10-v1.3.14
- Fully compatible with dynamic permalink settings - no code changes needed when changing URL structure
- All cart functionality now works seamlessly with product URLs, shopping cart operations, and UI state management

## [1.3.14] - 2024-11-02

### 🐛 Fixed - Cart URL Parsing

- **CRITICAL FIX**: Handle both `/p/` and `/products/` URL formats in cart item parsing
  - Cart was using `/p/demo-product` format but code only looked for `/products/` format
  - Added logic to extract product identifier from both URL formats
  - Fixes the issue where `productIdentifier` extraction failed causing `removeUrl: null`
- This explains why the selector fix in v1.3.13 wasn't enough - the URL parsing was also broken
- Both `removeFromCart()` and `syncCartState()` functions updated with proper URL handling

## [1.3.13] - 2024-11-02

### 🐛 Fixed - Cart Functionality

- **CRITICAL FIX**: Corrected jQuery selectors in `product-card-enhanced.js` for cart item parsing
  - Fixed product link selector to exclude remove button: `.ps-product__content > a:not(.ps-product__remove):not(.remove-cart-item)`
  - Previous selector was matching the remove link instead of product link (both are direct children of `.ps-product__content`)
  - Fixed remove link selector to work without requiring `[data-url]` attribute selector
  - This fix resolves the "removeUrl: null" issue preventing cart item removal
- **Root Cause**: The cart template has remove link as first child and product link as second child, but code was using `.first()` which selected wrong link
- Affects both production and staging servers (bug existed in both, just manifested differently based on test conditions)

## [1.3.12] - 2024-11-02

### 🐛 Fixed

- **System Updater**: Improved error handling in `checkUpdates()` method
  - Now uses cached/local tags if `git fetch --tags` fails (network issues, auth issues, etc.)
  - Added detailed error logging with exit codes and output
  - Added `fetch_status` field in response to indicate if using cached tags
  - Prevents "Failed to fetch tags from GitHub" error from blocking update checks
  - System can now check for updates even with temporary git connectivity issues

## [1.3.11] - 2024-11-02

### 🐛 Debug & Troubleshooting - Enhanced

- Enhanced debug logging in `product-card-enhanced.js` with detailed cart HTML parsing traces
- Added debug logs for cart item iteration showing:
  - Cart HTML length and structure validation
  - Number of `.ps-product--cart-mobile` items found
  - Product link discovery for each cart item
  - Product URL and identifier extraction
  - Product identifier matching logic (slug vs ID)
  - Remove link element discovery
  - Remove URL extraction results
- Enables pinpointing exact cart HTML parsing failures between stage and main servers
- Helps identify structural differences, selector mismatches, or timing issues

## [1.3.10] - 2024-11-02

### 🐛 Debug & Troubleshooting

- Added comprehensive console.log debug statements to `product-card-enhanced.js` for troubleshooting cart behavior differences between stage and main servers
- Debug logs trace execution flow through:
  - `transformToQuantityControl()` - button to quantity control transformation
  - `updateCartQuantity()` - cart quantity updates
  - `removeFromCart()` - cart item removal
  - `syncCartState()` - cart state synchronization
  - `ecommerce.cart.added` event listener
  - Quantity control button clicks and user interactions
- Logs include productId, productSlug, quantities, cart items, and AJAX responses
- Enables identification of where execution differs between identical codebases on different servers

## [1.3.9] - 2024-11-02

### ✨ Added - Mobile Product Page Enhancements

#### Mobile-Optimized Layout
- Created mobile-specific product page header with logo, store name, rating, and buy button above thumbnail
- Added reusable `product-details-section.blade.php` partial for consistent product info display
- Moved product details section below purchase area on mobile for better flow
- Full-width quantity selector on mobile (removed desktop margin override)

#### Mobile Upsell System
- Added simple mobile-only upsell section with product images + plus icons + "Buy all" button
- Created reusable product bundle modal component for both upsell and cross-sale
  - Modal slides up from bottom (75vh height) with smooth animations
  - Slide down animation when closing
  - Inline checkboxes with product cards in column layout
  - Sticky header and footer with shadow effects
  - Shows total price and saved amount (dynamic calculation)
  - Dynamic button text: "Add to Cart", "Add both to Cart", "Add X items to Cart"
- Locked state when parent product not in cart:
  - Images grayed out (50% opacity + grayscale filter)
  - Button shows lock icon with unlock message inside button
  - Auto-refreshes and unlocks via AJAX when parent added to cart
- Modal properly adds items to cart using same logic as desktop:
  - Sends `reference_product_for_upsale` parameter
  - Sequential product addition with success tracking
  - Updates cart UI and triggers events
  - Shows success message and closes with animation

#### Mobile UI Polish
- Removed bottom margin/padding/border from product header on mobile
- Simplified store logo to single 1px border with `global-color-primary-border`
- Updated review highlights to use standard `pd-rating` classes
- Container padding: 15px bottom for mobile sections
- Overlay background: `rgba(0, 0, 0, 0.3)` for modal
- Close button positioned above modal with no background, white icon color
- Footer layout: column-wise with 5px gap, price row horizontal

### 🐛 Fixed

- Fixed duplicate mobile upsell sections appearing after cart refresh by wrapping both desktop and mobile sections in single `data-upsale-bundle` container
- Fixed modal not opening when parent product not in cart (added disabled check)
- Fixed modal add-to-cart not working (now uses proper AJAX with CSRF token)
- Fixed saved amount and button text calculations (reuses desktop currency formatting logic)
- Removed orphaned CSS causing syntax errors in front-upsale-crosssale.scss

### 🎨 Changed

- Modal height increased from 50vh to 75vh for more item space
- Modal z-index: 10002
- Info gap reduced from 6px to 3px
- Footer gap reduced from 15px to 5px
- Hide original price and discount badge in modal items
- Buy all button: outline style with `global-color-primary-border` (no fill, no transform on click)

---

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

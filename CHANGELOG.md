# Changelog

All notable changes to Povaly will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.10.26] - 2024-11-12

### Fixed
- Product gallery: Fixed broken images in lightbox thumbnails
- Product gallery: Convert relative image paths to full storage URLs before passing to lightbox
- Product gallery: Lightbox now displays all images correctly with proper URLs

## [2.10.25] - 2024-11-12

### Fixed
- Product gallery: Fixed blank/white screen after mobile swipe
- Product gallery: Fixed overly aggressive cleanup that removed incoming image
- Product gallery: Incoming element now correctly excluded from preloaded cleanup

## [2.10.24] - 2024-11-12

### Fixed
- Product gallery: Fixed duplicate mobile dots (was creating 2 sets of dots)
- Product gallery: Fixed duplicate lens zoom elements (was creating 2 lens divs)
- Product gallery: Fixed stuck images after fast swipe or multi-finger gestures
- Product gallery: Fixed swipe animation not resetting properly when gesture is abandoned
- Product gallery: Fixed preloaded elements not being cleaned up after swipe completion
- Product gallery: Added proper cleanup of all preloaded slide elements

### Changed
- Product gallery: Added initialization guard to both DOMContentLoaded listeners
- Product gallery: Added `_lensInitialized` flag to prevent duplicate lens creation
- Product gallery: Improved touchend cleanup to reset element positions and remove orphaned elements
- Product gallery: Better handling of interrupted swipe gestures

## [2.10.23] - 2024-11-12

### Fixed
- Product gallery: Fixed duplicate mobile dots appearing after variation change
- Product gallery: Fixed mobile swipe showing wrong variation's images (flash effect)
- Product gallery: Removed loading spinner from image navigation (now only shows during variation change)
- Product gallery: Mobile slides now rebuild correctly when variation changes
- Product gallery: Prevent duplicate mobile initialization

### Changed
- Product gallery: Mobile swipe now works only with currently rendered variation's images
- Product gallery: Loading spinner only appears during variation change, not during swipe
- Product gallery: Mobile slides dynamically rebuild when thumbnails change

## [2.10.22] - 2024-11-12

### Fixed
- Product gallery: Restored Amazon-style lens zoom on image hover (desktop)
- Product gallery: Restored mobile swipe gestures for image navigation
- Product gallery: Restored mobile navigation dots with smooth animations
- Product gallery: Fixed lens zoom positioning relative to product info section
- Product gallery: Fixed mobile touch sensitivity and drag smoothness

### Added
- Product gallery: Complete lens zoom implementation with zoom panel
- Product gallery: Complete mobile swipe with preloaded elements for smooth transitions
- Product gallery: Mobile dots pagination with centered active indicator

## [2.10.21] - 2024-11-12

### Fixed
- Product gallery: Fixed loading spinner not hiding after image loads
- Product gallery: Fixed thumbnail hover not activating and showing in main image
- Product gallery: Fixed +N overlay click not opening lightbox
- Product gallery: Added missing rebindThumbnailEvents() method for proper event handler management
- Product gallery: Improved image loading feedback with proper spinner hide on load

### Changed
- Product gallery: Event handlers now properly rebind after thumbnail rebuild
- Product gallery: Loading spinner properly shows/hides during image transitions

## [2.10.20] - 2024-11-12

### Fixed
- Product gallery: Fixed typo in videoThumbs selector causing video thumbnails to not work
- Product gallery: Fixed thumbnail hover events not working after page load
- Product gallery: Fixed variation change not updating gallery thumbnails properly
- Product gallery: Added rebindThumbnailEvents() to properly re-attach event handlers after DOM rebuild
- Product gallery: Added rebuildThumbnailsFromAJAX() to handle AJAX image responses from variation changes
- Product gallery: Fixed hover preview to search in allProductImages correctly
- Product gallery: Main image now loads faster using AJAX imageUrls

### Changed
- Product gallery now properly rebuilds thumbnails when product variation changes
- Event handlers properly re-bind after dynamic thumbnail rebuild
- Improved performance with better event handler management

## [2.10.19] - 2024-11-12

### Fixed
- Fixed typo in videoThumbs selector causing video thumbnails to not work
- Fixed thumbnail hover events not working after page load
- Fixed variation change not updating gallery thumbnails
- Added rebindThumbnailEvents() to properly re-attach event handlers
- Added rebuildThumbnailsFromAJAX() to handle AJAX image responses
- Fixed hover preview to search in allProductImages correctly
- Main image now loads faster using AJAX imageUrls

### Changed
- Gallery now properly rebuilds thumbnails when variation changes
- Event handlers properly re-bind after dynamic thumbnail rebuild
- Improved performance with better event handler management

## [2.10.18] - 2024-11-12

### Fixed
- Fixed typo in videoThumbs selector causing video thumbnails to not work
- Fixed thumbnail hover events not working after page load
- Fixed variation change not updating gallery thumbnails
- Added rebindThumbnailEvents() to properly re-attach event handlers
- Added rebuildThumbnailsFromAJAX() to handle AJAX image responses
- Fixed hover preview to search in allProductImages correctly
- Main image now loads faster using AJAX imageUrls

### Changed
- Gallery now properly rebuilds thumbnails when variation changes
- Event handlers properly re-bind after dynamic thumbnail rebuild
- Improved performance with better event handler management

## [2.10.17] - 2024-11-12

### Fixed
- Fixed typo in videoThumbs selector causing video thumbnails to not work
- Fixed thumbnail hover events not working after page load
- Fixed variation change not updating gallery thumbnails
- Added rebindThumbnailEvents() to properly re-attach event handlers
- Added rebuildThumbnailsFromAJAX() to handle AJAX image responses
- Fixed hover preview to search in allProductImages correctly
- Main image now loads faster using AJAX imageUrls

### Changed
- Gallery now properly rebuilds thumbnails when variation changes
- Event handlers properly re-bind after dynamic thumbnail rebuild
- Improved performance with better event handler management

## [2.8.0] - 2024-11-11

### ✨ Feature - Honeypot Protection Re-enabled with Per-Form Settings

#### Honeypot Now Works Like reCAPTCHA
- **Per-form selective enabling** - Enable honeypot only on forms you want to protect
  - Contact Form, Newsletter, Testimonial, etc. can have different settings
  - Admin controls which forms get honeypot protection via checkboxes
  
- **Request-level tracking** - Only validates forms where honeypot field is actually rendered
  - No validation errors on admin forms without honeypot
  - Other forms work smoothly without overhead
  
- **Consistent admin UI** - Same pattern as reCAPTCHA v2/v3 and Math Captcha
  - Global on/off toggle
  - Per-form protection checkboxes
  - Clear labeling in settings

#### How It Works
1. Admin enables "Honeypot Protection" globally in Captcha Settings
2. Selects which forms should be protected (Contact Form, Newsletter, etc.)
3. Honeypot field auto-renders on selected forms only
4. Form submission timing is validated (blocks instant/too-slow submissions)
5. Empty honeypot field is verified (catches auto-filled bot submissions)
6. Admin panel unaffected (no forms checked by default)

#### Bot Protection Timeline
- **0-0.5s** → Blocked (instant submission = bot)
- **0.5-0.8s** → Logged only (autofill + human click scenario)
- **0.8s-1h** → Allowed (normal human submission)
- **1h+** → Blocked (token expired)

#### Benefits
- ✅ Protects contact/newsletter forms from automated bot submissions
- ✅ Works alongside Turnstile for multi-layer protection
- ✅ Zero impact on admin operations
- ✅ Granular per-form control
- ✅ Request-level validation (no false positives)

## [2.7.4] - 2024-11-11

### 🚨 EMERGENCY FIX - Honeypot Completely Disabled

#### Honeypot Feature Disabled
- **All honeypot code commented out**
  - Removed from core_request_rules filter
  - Removed from FormAbstract field injection
  - Feature will be redesigned in future version
  - Better to disable than have buggy functionality

#### Why?
- Honeypot causing persistent "Form token is missing" errors
- Even with disable-by-default, edge cases kept breaking things
- Cache issues preventing proper feature detection
- Admin operations continuously failing

#### Result
- ✅ Admin panel fully functional
- ✅ Visual builder working without errors
- ✅ All settings pages saveable
- ✅ AJAX requests no longer blocked
- ✅ Turnstile captcha still protecting frontend
- ✅ No more validation failures

#### Migration Path
- Honeypot feature temporarily unavailable
- Will be reimplemented with better design in future version
- Frontend still protected by Turnstile + rate limiting
- Admin still protected by authentication + CSRF

### 📝 Note
This is a temporary measure to restore full admin functionality.
The honeypot concept is valuable but the implementation had 
fundamental issues that couldn't be resolved with minor fixes.

## [2.7.3] - 2024-11-11

### 🐛 Critical Bug Fix

#### Honeypot Protection - Default Disabled
- **Changed honeypot default from enabled to disabled**
  - Prevents breaking admin operations on fresh installs
  - Resolves "Form token is missing" errors globally
  - Admin now must explicitly enable via Settings
  - No more false positives breaking valid operations

#### Technical Details
- `HoneypotProtection::isEnabled()` now returns `false` by default
- Setting key: `enable_honeypot` (must be explicitly set to 1/true)
- Admin can enable in: Settings → Captcha → Enable Honeypot Protection
- Frontend forms protected only when admin enables it

#### Impact
- ✅ Admin panel fully functional
- ✅ Visual builder works without errors
- ✅ Settings pages save correctly
- ✅ AJAX requests no longer blocked
- ✅ Honeypot still available as opt-in security layer

### 🚨 Root Cause
- Honeypot was enabled by default (`true`)
- Applied to ALL requests including admin
- Validation required fields that didn't exist in admin forms
- Led to cascade of "Form token is missing" errors

### 📝 Migration Path
If you want honeypot protection on frontend:
1. Go to Admin → Settings → Captcha
2. Enable "Honeypot Protection" toggle
3. Apply to desired frontend forms
4. Protects: login, contact, registration forms

## [2.7.2] - 2024-11-11

### 🐛 Bug Fixes

#### Honeypot Protection - Admin Route Detection
- **Strengthened admin route detection to prevent false positives**
  - Added multiple detection methods (4 checks instead of 1)
  - `$request->is('admin')` - Exact match
  - `$request->is('admin/*')` - Wildcard pattern  
  - `str_starts_with($request->path(), 'admin/')` - String prefix
  - `$request->segment(1) === 'admin'` - First URL segment
  - Ensures ALL admin URLs bypass honeypot validation
  
#### Technical Details
- Previous single check `$request->is('admin/*')` wasn't catching all cases
- Visual builder AJAX requests were still triggering validation
- Comprehensive check now covers all edge cases
- Admin panel fully functional without honeypot interference

### 📝 Notes
- Second hotfix for v2.7.0 honeypot feature
- Frontend forms remain fully protected
- No security impact - admin already protected by authentication

## [2.7.1] - 2024-11-11

### 🐛 Bug Fixes

#### Honeypot Protection
- **Fixed admin forms breaking with "Form token is missing" error**
  - Honeypot now only applies to frontend forms (`FormFront` instances)
  - Admin panel forms excluded (visual builder, AJAX requests)
  - Validation rules skip `admin/*` routes
  - Admin forms use existing CSRF protection, don't need honeypot

#### Technical Details
- Changed honeypot injection to check `$form instanceof FormFront`
- Added route check: `! $request->is('admin/*')` for validation rules
- CKEditor visual builder now works without errors
- All admin operations unaffected by honeypot

### 📝 Notes
- This is a hotfix for v2.7.0
- Frontend forms still protected by honeypot (login, contact, registration)
- No changes to security posture - admin already protected by authentication

## [2.7.0] - 2024-11-11

### ✨ New Features

#### 🔐 Advanced Bot Protection System
- **Cloudflare Turnstile Integration**
  - Privacy-first alternative to reCAPTCHA
  - User-friendly challenge (90% one-click verification)
  - Free unlimited use
  - No Google tracking or data collection
  - Auto/Light/Dark theme support
  - Admin UI for easy configuration
  
- **Honeypot Protection (Silent Bot Detection)**
  - Hidden fields that trap automated bots (70-80% catch rate)
  - Smart timing validation with autofill-safe logic
  - Three-tier detection: instant block (<0.5s), log fast (0.5-0.8s), allow normal (0.8s+)
  - HMAC-SHA256 signed timestamp tokens (1-hour expiry)
  - Zero user friction - completely invisible to legitimate users
  - Enabled by default with admin toggle
  
- **Multi-Layer Captcha System**
  - Support for 4 captcha types: Turnstile, reCAPTCHA v2, reCAPTCHA v3, Math Captcha
  - Per-form configuration (admin login, password reset, contact, registration)
  - Progressive enforcement strategy
  - Comprehensive admin settings UI with collapsible sections

#### 📚 Security Documentation
- **CAPTCHA_SETUP.md** - Complete setup guide
  - Step-by-step configuration for all captcha types
  - Comparison chart with recommendations
  - Troubleshooting section with autofill concerns addressed
  - Progressive enforcement strategy guide
  - Privacy protection details
  - Security monitoring instructions

### 🎨 UI/UX Enhancements

#### Promotional Hero Grid Shortcode
- **HTML Support in Content Fields**
  - Title, subtitle, and button text support HTML tags
  - Raw output without sanitization for creative freedom
  - Allows custom styling via inline span elements
  
- **Custom Typography Controls**
  - Left section: 44px title (desktop)
  - Middle top: 24px title
  - Middle bottom: 19px title
  - Right section: 29px title
  - Responsive scaling: tablet 30px, mobile 33px base with proportional adjustments
  
- **White Text Utility**
  - `<span>` wrapper automatically applies white color
  - Font-weight inheritance for all nested elements
  - Proper CSS specificity with `*, span, div` selectors

- **Responsive Optimizations**
  - Max-width changed from 70% to 93% for better mobile display
  - Content positioning adjustments for all screen sizes

#### Featured Banners Shortcode
- **HTML Support Added**
  - All text fields (title, subtitle, button) support HTML
  - Consistent with promotional hero grid behavior
  - Font-weight inheritance for nested elements
  
- **Positioning Improvements**
  - Fixed nested element position inheritance issues
  - Mobile positioning: 7px for better alignment
  - Separate position controls for parent and children

### 🎨 Theme Improvements

#### Cart Flyout
- **Icon Update** - Replaced linearicons close icon with Phosphor `ph-x-circle`
- Consistent iconography across theme

### 🔧 Technical Improvements

#### Captcha Plugin Architecture
- `TurnstileCaptcha.php` - New captcha class extending base contract
- `HoneypotProtection.php` - Standalone protection system
- `TurnstileField.php`, `HoneypotField.php` - Form field renderers
- Blade templates for Turnstile widget rendering (header, HTML, script)
- Extended `CaptchaServiceProvider` with Turnstile and honeypot support
- Updated `CaptchaSettingForm` with new fields and collapsibles
- Validation rules: `turnstile` and `honeypot` validators
- Request validation updated to accept Turnstile as captcha type

#### Security Logging
- Honeypot violations logged with IP, user agent, timing data
- Instant bot submissions tracked separately from autofill submissions
- Token expiration and validation failures logged for monitoring

### 🐛 Bug Fixes

#### Form Validation
- Fixed captcha toggle showing incorrect state when Turnstile is selected
- Fixed `enable_captcha` reading from settings instead of `reCaptchaEnabled()`
- Fixed captcha type selector to include Turnstile in allowed values
- Fixed honeypot validation to accept array data from validator

#### Shortcode Issues
- Fixed promotional hero grid HTML attributes being stripped
- Fixed featured banners font-weight not inheriting properly
- Fixed visual builder server errors with missing AlertField imports

### 🔄 Breaking Changes
None - All changes are backwards compatible

### 📦 Dependencies
- No new external dependencies (Turnstile uses CDN, honeypot is native PHP)

## [2.6.1] - 2024-12-21

### ✨ Review Section UX Improvements

#### 🎯 Smart Guest Review Messaging
- **Curiosity-driven FOMO text** for guest users
- When reviews exist: "Join Our Community" with social proof messaging
- When no reviews: "Curious What Others Think?" - creates information gap without revealing empty state
- **Psychological triggers**: exclusivity, social proof, curiosity gap
- Encourages sign-in through tactical messaging

#### 🎨 Review Sidebar Centering (No Reviews State)
- **Guest users**: centered layout when no reviews exist
- **Logged-in users (not met criteria)**: centered layout with "purchase required" message
- **Logged-in users (met criteria)**: centered layout with "write review" button
- Consistent centering with `align-items: center` across all states

#### 📱 Review Container Responsive Design
- **Column-reverse layout** when logged-in users have no reviews
- Padding/margin: 15px top/bottom
- Gap: 10px between elements
- Border: 1px solid top/bottom
- Centered alignment for better visual balance

#### 🎨 No-Review Message Styling
- Min-width: 720px (responsive: 100% on mobile)
- Background: subtle link color
- Border: 1px solid with 4px left accent
- Border-radius: 6px
- Padding: 10px 15px

#### 🔘 Button Styling Improvements
- **Write review button**: cart button colors, fixed 220px width
- **Sign-in button**: 320px desktop, 230px mobile
- Hover states with cart-bg-hover color
- All buttons maintain width on mobile (220px, not 100%)

#### 📱 Mobile/Tablet Responsive
- **Review sidebar shown at ≤991px** when no-review states active
- **Mobile header hidden at ≤991px** for no-review states
- **Mobile header shown at ≤991px** for default state (reviews exist)
- Proper visibility toggling across all viewport sizes
- Guest container: `align-items: center` for better centering

### 🔧 Technical Improvements
- Conditional CSS classes for different review states
- Uses `:has()` selector for parent container styling
- Forced display with `!important` where needed to override defaults
- SCSS compiled and optimized for production

## [2.6.0] - 2024-12-20

### ✨ Product Listing Page Redesign

#### 🎨 Filter System Enhancements
- **Mobile filter offcanvas** - slides from bottom with 75vh height
- **Sticky filter header** (z-index 11) with clean design
- **Visual attribute swatches** - fully clickable with pointer cursor
- **Rating filter stars** - styled with global primary color
- **"Department" label** replaces "Categories" with hidden icons
- **Filter spacing** - improved margins (8px items, 10px filters, 15px attributes)
- **Price range slider** - global link color with proper borders
- **Fixed scrolling** - removed overflow issues on filter content
- **Select2 dropdown** - works properly in mobile offcanvas

#### 📊 Layout & Display
- **Filter button styling** - matches layout switcher (normal/hover/active states)
- **Sort dropdown** - hidden ≤480px, shown in filter offcanvas
- **Products counter** - updates correctly via AJAX
- **List view** - thumbnail centered, min-height removed
- **"0 Product found" alert** - styled with link-subtle background

#### 🌟 Trending Products Section
- **Replaced** "Recommended Items" with "Most viewed products" shortcode
- **36 products limit** with 4-column desktop layout (1 mobile, 2 tablet)
- **Navigation arrows** - 30x30px, white semi-transparent, -10px positioning
- **No padding** on carousel, hidden "View All" button

#### 🔧 Technical Improvements
- **Quick-shop auto-close** after add-to-cart (500ms delay)
- **Enhanced product cards** used throughout listing
- **Compiled assets** optimized for production
- **Global color variables** used consistently

### 🐛 Bug Fixes
- Fixed horizontal overflow on mobile filter offcanvas
- Fixed products found counter not updating during filtering
- Fixed visual filter checkmark positioning (4px instead of 3px)
- Fixed label line-height (22px) for better alignment
- Fixed rating star fill targeting (only star path, not helper path)

## [2.7.0] - 2024-11-11

### ✨ New Features

#### 🔐 Advanced Bot Protection System
- **Cloudflare Turnstile Integration**
  - Privacy-first alternative to reCAPTCHA
  - User-friendly challenge (90% one-click verification)
  - Free unlimited use
  - No Google tracking or data collection
  - Auto/Light/Dark theme support
  - Admin UI for easy configuration
  
- **Honeypot Protection (Silent Bot Detection)**
  - Hidden fields that trap automated bots (70-80% catch rate)
  - Smart timing validation with autofill-safe logic
  - Three-tier detection: instant block (<0.5s), log fast (0.5-0.8s), allow normal (0.8s+)
  - HMAC-SHA256 signed timestamp tokens (1-hour expiry)
  - Zero user friction - completely invisible to legitimate users
  - Enabled by default with admin toggle
  
- **Multi-Layer Captcha System**
  - Support for 4 captcha types: Turnstile, reCAPTCHA v2, reCAPTCHA v3, Math Captcha
  - Per-form configuration (admin login, password reset, contact, registration)
  - Progressive enforcement strategy
  - Comprehensive admin settings UI with collapsible sections

#### 📚 Security Documentation
- **CAPTCHA_SETUP.md** - Complete setup guide
  - Step-by-step configuration for all captcha types
  - Comparison chart with recommendations
  - Troubleshooting section with autofill concerns addressed
  - Progressive enforcement strategy guide
  - Privacy protection details
  - Security monitoring instructions

### 🎨 UI/UX Enhancements

#### Promotional Hero Grid Shortcode
- **HTML Support in Content Fields**
  - Title, subtitle, and button text support HTML tags
  - Raw output without sanitization for creative freedom
  - Allows custom styling via inline span elements
  
- **Custom Typography Controls**
  - Left section: 44px title (desktop)
  - Middle top: 24px title
  - Middle bottom: 19px title
  - Right section: 29px title
  - Responsive scaling: tablet 30px, mobile 33px base with proportional adjustments
  
- **White Text Utility**
  - `<span>` wrapper automatically applies white color
  - Font-weight inheritance for all nested elements
  - Proper CSS specificity with `*, span, div` selectors

- **Responsive Optimizations**
  - Max-width changed from 70% to 93% for better mobile display
  - Content positioning adjustments for all screen sizes

#### Featured Banners Shortcode
- **HTML Support Added**
  - All text fields (title, subtitle, button) support HTML
  - Consistent with promotional hero grid behavior
  - Font-weight inheritance for nested elements
  
- **Positioning Improvements**
  - Fixed nested element position inheritance issues
  - Mobile positioning: 7px for better alignment
  - Separate position controls for parent and children

### 🎨 Theme Improvements

#### Cart Flyout
- **Icon Update** - Replaced linearicons close icon with Phosphor `ph-x-circle`
- Consistent iconography across theme

### 🔧 Technical Improvements

#### Captcha Plugin Architecture
- `TurnstileCaptcha.php` - New captcha class extending base contract
- `HoneypotProtection.php` - Standalone protection system
- `TurnstileField.php`, `HoneypotField.php` - Form field renderers
- Blade templates for Turnstile widget rendering (header, HTML, script)
- Extended `CaptchaServiceProvider` with Turnstile and honeypot support
- Updated `CaptchaSettingForm` with new fields and collapsibles
- Validation rules: `turnstile` and `honeypot` validators
- Request validation updated to accept Turnstile as captcha type

#### Security Logging
- Honeypot violations logged with IP, user agent, timing data
- Instant bot submissions tracked separately from autofill submissions
- Token expiration and validation failures logged for monitoring

### 🐛 Bug Fixes

#### Form Validation
- Fixed captcha toggle showing incorrect state when Turnstile is selected
- Fixed `enable_captcha` reading from settings instead of `reCaptchaEnabled()`
- Fixed captcha type selector to include Turnstile in allowed values
- Fixed honeypot validation to accept array data from validator

#### Shortcode Issues
- Fixed promotional hero grid HTML attributes being stripped
- Fixed featured banners font-weight not inheriting properly
- Fixed visual builder server errors with missing AlertField imports

### 🔄 Breaking Changes
None - All changes are backwards compatible

### 📦 Dependencies
- No new external dependencies (Turnstile uses CDN, honeypot is native PHP)

## [2.5.1] - 2024-11-07

### ✨ New Features

#### 📝 Typography Admin Controls
- **H1-H6 admin settings** in Appearance > Style > Global Styling
- Size controls for each heading (H1: 12-72px, H2: 12-60px, etc.)
- Weight controls: 300-800 (Light to Extra Bold)
- **Default sizes updated:**
  - H1: 36px (was 48px), weight: 700
  - H2: 30px (was 36px), weight: 700
  - H3: 24px, weight: 700
  - H4: 18px, weight: 700
  - H5: 16px (was 14px), weight: 600
  - H6: 14px (was 12px), weight: 600
- Dynamic CSS variables generation from theme options
- No code editing required for heading customization

#### 📱 Responsive CKEditor Indent Control
- **Mobile-first indent system** with hierarchy preservation
- Desktop: full indent (40px, 80px, 120px, 160px, 200px)
- Tablet (≤991px): reduced (30px, 60px, 90px, 120px, 150px)
- Mobile (≤767px): minimal with 5px increments (0, 10px, 15px, 20px, 25px)
- Maintains visual structure on small screens
- Better readability without cutting into narrow viewports

### 🎨 Technical Improvements
- Typography uses CSS variables (--heading-h1-size, --heading-h1-weight, etc.)
- SCSS updated to use CSS variables with fallbacks
- Admin controls dynamically inject CSS into page head
- Responsive indent applies to .ps-document, .ck-content, all content areas

## [2.5.0] - 2024-11-06

### ✨ New Features

#### 🔐 Authentication Pages Redesign
- **Minimal Amazon-style design** for login/register pages
- Transparent card background with clean borders
- Centered logo support with new `logo_black` theme option
- Custom checkbox styling with global color variables
- 50px border-radius buttons with theme colors
- No header/footer layout for auth pages (minimal.blade.php)
- Password reset pages use minimal layout
- Social login section with clean styling

#### 🎨 Product Filters Enhancement
- **Transparent background** (removed gray boxes)
- **Global color variables** for all filter elements
- Custom scrollbar with 500px max-height
- Filter titles: 14px, 600 weight, text-main color
- Filter items: text-body color
- Checkboxes: border-strong outline, link color when checked
- No padding, margins, or border-bottom on filter sections

#### 📦 Products Page Restructure
- **Main products shown first** (search/filter results priority)
- **Recommended items moved to bottom** of page
- **Featured brands carousel removed** (was empty/unused)
- Filter/sort bar moved to page top (full-width)
- Cleaner hierarchy and better UX flow

### 🎨 UI/UX Improvements

#### Design System Updates
- **Shortcode headings** changed from h3 to h2
- Desktop: 22px font-size, 700 weight
- Mobile: 20px font-size
- Updated: featured-products, smart-products, deal-and-offers, trending-products, featured-banners
- Homepage ps-container: 15px horizontal padding

#### Cache Busting Implementation
- Social login CSS uses `cache_bust()` helper
- Dynamic versioning: timestamp on staging, tag version on production
- Proper asset version management via VersionHelper

### 🐛 Bug Fixes

#### Filter System
- **Fixed scroll not working** (removed :has() selector)
- Custom scrollbar styling added
- Filter content scrolls properly with 500px max-height

#### Social Login
- **Removed hover transform** on social login buttons
- Buttons stay in place (no translateY jump)
- Only shadow effect on hover

### 🔧 Technical Improvements

#### Code Organization
- Auth page styles moved to `front-card.scss` (proper location)
- Removed duplicate styles from `custom.scss`
- Cleaner separation: plugin styles in plugin, theme overrides in theme
- All auth colors use CSS variables from header-meta

#### CSS Variables Usage
- `--global-color-text-main` for headings
- `--global-color-text-body` for regular text
- `--global-color-link` for links and active states
- `--global-color-link-hover` for hover states
- `--global-color-border-strong` for borders
- `--ec-btn-cart-bg/text/hover` for buttons

### 📝 Files Changed
- 68 files changed
- 3,755 additions
- 795 deletions

## [2.4.1] - 2024-11-05

### 🐛 Bug Fixes

#### Review Navigation Improvements
- **Photo viewer info link** now navigates directly to the specific review
- Added `data-review-id` tracking in photo viewer for accurate navigation
- Hash-based navigation for reviews on different pages (pagination support)
- Smooth scroll with 2-second highlight animation for target reviews

#### Link Behavior Updates
- **Product rating links** (pd-rating-link) now scroll to review section instead of opening tabs
- **Read reviews button** (pd-read-reviews-btn) scrolls to review section
- All review-related links use smooth scroll behavior for better UX

### 🔧 Technical Improvements
- Added `navigateToReview()` JavaScript function with pagination support
- Review ID tracking updates dynamically as user navigates photos
- Improved review element targeting with `#review-{id}` selectors

## [2.4] - 2024-11-05

### ✨ New Features

#### 🆕 Similar Items on New Arrival Section
- **Smart new arrival recommendations** showing newly launched products (within last 60 days)
- **Contextual matching** based on product name, tags, and categories
- Displays below review section on product pages
- Carousel layout with owl-slider integration
- Match score-based sorting with newest products first
- Clean product cards without extra actions/buttons
- Border-free design with no container padding

#### 👍 Review Helpful & Share Features
- **Helpful button** allowing users to mark reviews as helpful
- **Share functionality** for reviews with tracking
- Guest users can interact with reviews
- Real-time count updates via AJAX
- Session-based tracking to prevent duplicate votes
- Fixed 500 errors using existing CheckReviewConditionTrait

### 🎨 UI/UX Improvements

#### Review System Enhancements
- **Mobile responsive review interface** with proper offcanvas filters
- **Star rating filters** working correctly in mobile offcanvas sidebar
- Fixed dropdown filter synchronization issues
- Improved pagination UI (default 10 reviews per page)
- Better proportions for review guest wrapper (max-width 400px)
- Enhanced review sidebar layout (70% width, 80px gap)
- Review list displays correctly with proper error handling

#### Guest Sign-In Prompt
- **New guest prompt section** below new arrivals
- Centered content with personalized recommendations message
- "Sign in now" button with ec-cart button styling
- "Start here" registration link
- No bottom padding for clean section transition

#### Style Improvements
- Arrow navigation with 6px border-radius (not circular)
- Proper mobile dot sizing (6px inactive, 20px x 6px active pill)
- Consistent carousel styling across all product sections
- Fixed border-bottom styling across sections
- Removed duplicate CSS rules

### 🐛 Bug Fixes
- Fixed SQL error: "Unknown column 'reviews_avg_star' in 'HAVING'" in new arrival queries
- Removed incorrect review filters from new arrival product queries
- Fixed mobile offcanvas star filtering not working
- Resolved 500 errors when logged in users have no reviews
- Fixed review list showing blank despite having reviews
- Corrected checkReviewCondition logic using proper trait method

### 🔧 Technical Improvements
- Proper use of CheckReviewConditionTrait for review validation
- Cleaned up duplicate CSS in responsive breakpoints
- Added !important flags for style overrides where needed
- Improved AJAX error handling in review interactions
- Better SQL query optimization for new arrivals

## [2.3] - 2024-11-04

### ✨ Major New Features

#### 🖼️ Customer Photos Gallery
- **Interactive photo viewer** with large image display and navigation arrows
- **Paginated thumbnail grid** (24 items per page) with lazy loading
- **Smart navigation sync** between main viewer and thumbnails
- Auto-loads next page when navigating via arrows
- Displays customer ratings and names on each photo
- "Read Reviews" button linking to reviews tab
- Keyboard navigation support (arrow keys)
- Optimized thumbnail loading using RvMedia thumb images
- Responsive grid layout (12 columns desktop, 8 tablet, auto mobile)

#### 🎯 Similar Customers Recommendations
- **Collaborative filtering algorithm** showing products viewed by customers with similar browsing patterns
- **Contextual filtering** on product/category/brand pages using keyword matching
- Shows products OTHER customers viewed who also viewed SAME products as current visitor
- Excludes current user's browsing history from recommendations
- View count-based sorting for most popular recommendations
- Carousel layout with 6 items (desktop), responsive breakpoints
- Works for logged-in customers (database-based tracking)
- Guest users see recommendations from logged-in customers

#### 📜 Browsing History Section
- "Your browsing history" shortcode displaying recently viewed products
- Excludes current product from history display
- Session-based for guests, database-based for logged-in customers
- Grid layout with product cards

### 🎨 UI/UX Improvements

#### Product Page Layout
- **Semantic HTML**: Changed section titles from `<h3>` to `<h2>` tags
- **Consistent styling** across all sections (22px/700 weight, 20px mobile)
- Removed generic "Related Products" section (replaced with personalized recommendations)
- New section order:
  1. Product Information tabs
  2. Customer Photos Gallery (if photos exist)
  3. Your Browsing History
  4. Customers with Similar Interests Also Viewed

#### Cross-Sale Products Enhancements
- Improved modal styling and interactions
- Enhanced product variation selection
- Better bundle discount display
- Fixed checkbox states and disabled states

#### Product Details Section
- Typography improvements with global classes
- Enhanced mobile responsiveness
- Better rating display

### 🔧 Technical Improvements

#### Backend
- New shortcode: `similar-customers-recommendations` with full collaborative filtering logic
- Registered in `HookServiceProvider.php`
- Uses `ec_customer_recently_viewed_products` table for tracking
- Efficient query optimization with view count aggregation
- Proper eager loading of product relationships

#### Frontend Performance
- Lazy loading for customer photo thumbnails
- Paginated loading to keep page lightweight
- Thumbnail optimization using RvMedia thumb version
- Efficient page tracking (Set data structure)
- Only loads 24 thumbnails per page initially

#### Code Quality
- Fixed Review model relationship usage (`user` not `customer`)
- Uses `display_name` attribute for customer names
- Consistent with existing review system
- Proper null checks and fallbacks

### 📦 New Files Created
- `platform/themes/martfury/views/ecommerce/shortcodes/similar-customers-recommendations.blade.php`
- `platform/plugins/ecommerce/resources/views/themes/includes/top-rated-related.blade.php`
- `platform/plugins/ecommerce/resources/views/themes/includes/store-products.blade.php`
- `.kiro/GLOBAL_TYPOGRAPHY_CLASSES.md`

### 🔄 Modified Files
- Product page template with 540+ lines of new functionality
- Recently viewed products shortcode (title styling update)
- Cross-sale products styling and functionality
- Main.js with enhanced interactions
- Typography and product SCSS improvements

### 🐛 Bug Fixes
- Fixed `Call to undefined function get_product_reviews()` error
- Fixed `Call to undefined relationship [customer]` error on Review model
- Corrected SQL column reference (removed non-existent `created_at` ORDER BY)
- Fixed review image loading to match reviews tab approach

## [2.2] - 2024-11-02

### 🔧 Fixed - Admin Panel CSS/JS Issues

- **CRITICAL FIX**: Downgraded `@tabler/core` from v1.7.3 to v1.4.0
  - Resolved admin panel CSS/JS compatibility issues
  - Fixed CSS variables from modern `oklch()` colors back to hex colors with `--bb-` prefix
  - Reduced core.css file size from 688KB to 565KB (120KB reduction)
  - Restored proper `--bb-black`, `--bb-gray`, etc. variable naming convention
  - All admin panel styles now render correctly

### ✨ Improvements

- **Build System**: Enhanced CSS syntax fix automation
  - Automated fix for Tabler CSS output bugs (closing braces without newlines)
  - Fixed empty CSS variable declarations
  - Applied 44 syntax fixes automatically during production build
  
- **Version Update**: Core version bumped from 2.1 to 2.2
  - Updated `platform/core/core.json` version identifier
  - Cache busting for new release

### 📦 Technical Changes

- Fixed package dependency resolution in workspace
- Copied `@tabler/core` to root node_modules for proper Vite resolution
- Cleaned and optimized git repository (36,018 objects processed)
- All assets compiled and published to both `platform/` and `public/vendor/` directories

### 🚀 Deployment

- Merged stage branch to main with all fixes
- Production assets rebuilt and optimized
- All changes committed and pushed to main branch

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

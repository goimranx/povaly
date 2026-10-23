# Development Workflow

## How to Update Code & Deploy

This project uses a custom automated deployment system. Follow these steps:

### 1. Edit Code Locally
- Edit any files in `platform/` directory (plugins, themes, core, packages)
- Common locations:
  - **CSS/JS**: `platform/themes/martfury/assets/sass/` or `platform/plugins/ecommerce/resources/`
  - **PHP**: `platform/plugins/`, `platform/themes/`, `app/`
  - **Views**: `platform/themes/martfury/views/`, `platform/plugins/*/resources/views/`

### 2. Build Assets
```bash
npm run prod
```
This compiles SCSS → CSS and bundles JS from `resources/` to `public/` directories.

### 3. Commit & Push
```bash
git add .
git commit -m "Your message"
git push
```

### 4. Deploy to Server
**On the live site:**
1. Go to CMS Admin Panel
2. Click **"System Updater"** button (custom controller)
3. Done! The system automatically:
   - Pulls latest code from GitHub
   - Installs Composer dependencies
   - Publishes all compiled assets to `public/vendor/`
   - Clears all caches
   - Runs database migrations
   - Optimizes the application

### Important Notes

- **Server has Node.js v18** (too old for build tools) → Always build locally
- **`public/vendor/` is gitignored** → System Updater copies files there
- **No manual scripts needed** → Everything is automated
- **Compiled files ARE tracked in git** → `platform/*/public/` files are committed

### Custom Components

#### PV Gallery System
Custom product gallery without slick slider dependency:
- **CSS**: `platform/plugins/ecommerce/resources/css/pv-gallery.css`
- **JS**: `platform/plugins/ecommerce/resources/js/pv-gallery.js`
- **Template**: `platform/plugins/ecommerce/resources/views/themes/includes/product-gallery.blade.php`
- Uses `pv-` prefix for all classes

#### System Updater
Custom deployment controller:
- **Controller**: `app/Http/Controllers/SystemUpdateController.php`
- Handles git pull, asset publishing, cache clearing, migrations
- No npm build on server (pre-compiled files committed to git)

---

## Quick Reference

| Action | Command |
|--------|---------|
| Install dependencies | `npm install` |
| Build for production | `npm run prod` |
| Build for dev (watch) | `npm run dev` |
| Deploy to live | Click **System Updater** in CMS |

---

**Last Updated**: October 2026

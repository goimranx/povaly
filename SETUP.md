# Povaly - Fresh Installation Guide

## Prerequisites
- PHP 8.3+
- MySQL/MariaDB
- Composer
- Node.js & NPM (optional, for asset compilation)

## Installation Steps

### 1. Clone Repository
```bash
cd /home/u497292684/domains/povaly.com/public_html
git clone https://github.com/goimranx/povaly.git .
```

### 2. Environment Setup
```bash
cp .env.example .env
nano .env
```

Update these values in `.env`:
```
APP_URL=https://povaly.com
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 3. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Create Storage Symlink
```bash
php artisan storage:link
```

### 6. Create Theme Symlink
```bash
# This is CRITICAL for theme assets to load
ln -s /home/u497292684/domains/povaly.com/public_html/platform/themes/martfury/public /home/u497292684/domains/povaly.com/public_html/public/themes/martfury
```

### 7. Run Database Migrations
```bash
php artisan migrate --force
```

### 8. Import Database (if you have backup)
```bash
mysql -u your_user -p your_database < your_backup.sql
```

### 9. Set Permissions
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/app/public
```

### 10. Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

## Verification

Visit `https://povaly.com` and check:
- ✓ Homepage loads
- ✓ Products display with images
- ✓ Carousels work (no loading skeletons stuck)
- ✓ Add to cart works
- ✓ No JavaScript errors in browser console

## Troubleshooting

### Carousels Not Working
Check if theme symlink exists:
```bash
ls -la public/themes/martfury
```

Should show: `public/themes/martfury -> ../../platform/themes/martfury/public`

If not, recreate it:
```bash
ln -sf /home/u497292684/domains/povaly.com/public_html/platform/themes/martfury/public /home/u497292684/domains/povaly.com/public_html/public/themes/martfury
```

### 404 Errors
Clear and cache routes:
```bash
php artisan route:clear
php artisan route:cache
```

### Permission Errors
```bash
chmod -R 755 storage bootstrap/cache
chown -R your_user:your_user storage bootstrap/cache
```

## File Structure

```
povaly.com/
├── app/
├── bootstrap/
├── config/
├── database/
├── platform/
│   └── themes/
│       └── martfury/
│           ├── public/          ← Theme assets (source)
│           │   ├── css/
│           │   └── js/
│           │       └── carousel-init.js  ← Critical file
│           ├── partials/
│           │   ├── footer.blade.php
│           │   └── product-card-enhanced.blade.php
│           └── config.php
├── public/
│   ├── themes/
│   │   └── martfury/    ← SYMLINK to platform/themes/martfury/public
│   └── storage/         ← SYMLINK to storage/app/public
├── routes/
├── storage/
└── vendor/
```

## Important Files Modified

1. **platform/themes/martfury/public/js/carousel-init.js** - Owl Carousel initialization
2. **platform/themes/martfury/config.php** - Asset registration
3. **platform/themes/martfury/partials/footer.blade.php** - jQuery loading order fix
4. **bootstrap/app.php** - SystemUpdateServiceProvider registration
5. **app/Http/Controllers/SystemUpdateController.php** - Git reset before pull

## Update System

After initial setup, you can update via admin panel:
- URL: `https://povaly.com/pv-admin/system-update`
- Click "Update" button to pull latest from GitHub
- System will automatically pull changes, clear caches, and update

## Support

If you encounter issues:
1. Check browser console (F12) for JavaScript errors
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify file permissions
4. Ensure symlinks are created correctly

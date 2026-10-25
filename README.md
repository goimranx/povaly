# Povaly E-commerce Platform

> **Version:** 1.3.0  
> **Status:** ✅ Stable Release  
> **Built on:** Botble CMS v1.41.11  
> **License:** Commercial

---

## 🚀 About Povaly

Povaly is a custom e-commerce platform built on Botble CMS, featuring the premium Martfury theme with enhanced functionality for modern online retail.

### ✨ Key Features

- 🛒 **Enhanced Shopping Experience** - Interactive product cards with quantity controls
- 📦 **Martfury Theme** - Professional, mobile-responsive e-commerce design
- 🎥 **Video Integration** - Product videos with like and share functionality
- 🖼️ **PV Gallery** - Advanced product image gallery
- 🔄 **Auto-Deploy System** - One-click updates from GitHub with real-time progress
- 📊 **Analytics Ready** - Track user engagement and sales

---

## 📋 Requirements

- **PHP:** >= 8.3.0
- **MySQL:** >= 5.7 or MariaDB >= 10.2
- **Node.js:** >= 18.x (for local development)
- **Composer:** Latest version
- **Web Server:** Apache or Nginx

---

## 🛠️ Installation

### Fresh Installation

```bash
# Clone repository
git clone https://github.com/goimranx/povaly.git
cd povaly

# Install dependencies
composer install --no-dev --optimize-autoloader

# Copy environment file
cp .env.example .env

# Configure database in .env file
# DB_DATABASE=povaly
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Publish assets
php artisan cms:theme:assets:publish martfury

# Clear caches
php clear-cache.php
```

### Using Specific Version

```bash
# Checkout stable version
git checkout v1.0.0

# Install and setup
composer install --no-dev --optimize-autoloader
php artisan cms:theme:assets:publish martfury
php clear-cache.php
```

---

## 🔄 Updating

### Automatic Update (Recommended)

1. Login to admin panel
2. Go to **System Updater** (in admin menu)
3. Click **"Check for Updates"**
4. Click **"Update Now"**
5. Wait for automatic deployment

### Manual Update

```bash
# Pull latest changes
git fetch --tags
git checkout v1.0.0  # or latest version

# Update dependencies
composer install --no-dev --optimize-autoloader

# Publish assets
php artisan cms:theme:assets:publish martfury

# Run migrations (if any)
php artisan migrate --force

# Clear caches
php clear-cache.php
```

---

## 📦 Versioning

Povaly follows [Semantic Versioning](https://semver.org/):

- **v1.0.0** - Current stable release
- **v1.x.x** - Minor updates and patches
- **v2.0.0** - Major updates (future)

See [VERSIONING.md](VERSIONING.md) for details.

---

## 📝 Documentation

- [Development Workflow](DEVELOPMENT-WORKFLOW.md) - Complete development guide
- [Versioning Strategy](VERSIONING.md) - Version management
- [Changelog](CHANGELOG.md) - All changes and releases

---

## 🏗️ Project Structure

```
povaly/
├── app/                    # Custom application code
│   ├── Http/
│   │   └── Controllers/    # Custom controllers
│   └── Models/            # Custom models
├── platform/              # Botble CMS core
│   ├── core/             # Core system
│   ├── packages/         # CMS packages
│   ├── plugins/          # CMS plugins
│   └── themes/
│       └── martfury/     # Active theme
├── public/               # Public web root
│   ├── themes/          # Published theme assets
│   └── vendor/          # Published vendor assets
├── resources/           # Application resources
│   └── views/          # Custom views
├── routes/             # Custom routes
├── database/           # Database files
│   ├── migrations/    # Database migrations
│   └── seeders/      # Database seeders
└── storage/          # Application storage
```

---

## 🎨 Theme Customization

### Compiling Assets

```bash
# Install Node dependencies (first time only)
npm install

# Development build (with watch)
npm run dev

# Production build (optimized)
npm run prod
```

### Custom JavaScript

- Main theme JS: `platform/themes/martfury/assets/js/main.js`
- Product cards: `platform/themes/martfury/public/js/product-card-enhanced.js`

### Custom CSS

- Theme styles: `platform/themes/martfury/assets/sass/`
- Compiled: `platform/themes/martfury/public/css/`

---

## 🔐 Admin Panel

**URL:** `https://yoursite.com/pv-admin`

**Default Credentials:**
- Check `.env` file for admin credentials
- Change immediately after first login

### Admin Features

- 📊 Dashboard with analytics
- 🛍️ Product management
- 📦 Order processing
- 👥 Customer management
- 🎨 Theme customization
- 🔄 System updates
- 🗄️ Database management

---

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure proper database credentials
- [ ] Set up SSL certificate (HTTPS)
- [ ] Configure email settings
- [ ] Set up scheduled tasks (cron)
- [ ] Configure file permissions
- [ ] Set up backups (database + files)
- [ ] Test payment gateways
- [ ] Configure CDN (optional)

### Cron Jobs

Add to crontab:
```bash
* * * * * cd /path-to-povaly && php artisan schedule:run >> /dev/null 2>&1
```

### File Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🔧 Configuration

### Environment Variables

Key variables in `.env`:

```env
APP_NAME=Povaly
APP_ENV=production
APP_DEBUG=false
APP_URL=https://povaly.com

DB_DATABASE=povaly
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587

GITHUB_BRANCH=main
```

---

## 🐛 Troubleshooting

### Clear All Caches

```bash
php clear-cache.php
# or
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Reset Assets

```bash
php artisan cms:theme:assets:publish martfury --force
```

### Database Issues

```bash
# Fresh migration (⚠️ destroys data)
php artisan migrate:fresh --seed
```

---

## 📊 Current Version Info

**Version:** v1.0.0  
**Release Date:** October 23, 2026  
**Botble CMS:** v1.41.11  
**Custom Build:** 1.41.11-povaly.1  

### What's in v1.0.0?

✅ Stable release with all core features  
✅ Martfury theme fully integrated  
✅ Enhanced product cards  
✅ System update manager  
✅ All bugs fixed  
✅ Production ready  

See [CHANGELOG.md](CHANGELOG.md) for detailed changes.

---

## 🤝 Contributing

This is a private commercial project. For internal development:

1. Create feature branch: `git checkout -b feature/my-feature`
2. Make changes and commit
3. Push to GitHub: `git push origin feature/my-feature`
4. Create Pull Request to `develop` branch
5. After review, merge to `main`

---

## 📄 License

Proprietary - All rights reserved  
© 2026 Povaly

---

## 🔗 Links

- **Live Site:** https://povaly.com
- **Admin Panel:** https://povaly.com/pv-admin
- **GitHub Repo:** https://github.com/goimranx/povaly
- **Botble CMS:** https://botble.com

---

## 📧 Support

For issues or questions:
- Create an issue on GitHub
- Contact: support@povaly.com

---

**Built with ❤️ using Botble CMS**

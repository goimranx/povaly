#!/bin/bash
# Stage Deployment Script
# Run this after git pull on staging server

echo "🚀 Starting Stage Deployment..."

# Publish plugin assets (copies from platform/plugins/*/public/ to public/vendor/core/plugins/*)
echo "📦 Publishing plugin assets..."
php artisan vendor:publish --tag=cms-public --force

# Clear all caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan view:clear
php artisan config:clear

echo "✅ Deployment complete!"
echo ""
echo "🔍 Next steps:"
echo "1. Hard refresh browser (Ctrl+Shift+R or Ctrl+F5)"
echo "2. Check console for: [PV GALLERY] Separated thumbnails: {variation: X, other: Y, videos: Z}"
echo "3. Test variation clicks - thumbnails should reorder"

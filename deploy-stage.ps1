# Stage Deployment Script (PowerShell)
# Run this after git pull on staging server

Write-Host "🚀 Starting Stage Deployment..." -ForegroundColor Green

# Publish plugin assets (copies from platform/plugins/*/public/ to public/vendor/core/plugins/*)
Write-Host "📦 Publishing plugin assets..." -ForegroundColor Yellow
php artisan vendor:publish --tag=cms-public --force

# Clear all caches
Write-Host "🧹 Clearing caches..." -ForegroundColor Yellow
php artisan cache:clear
php artisan view:clear
php artisan config:clear

Write-Host "✅ Deployment complete!" -ForegroundColor Green
Write-Host ""
Write-Host "🔍 Next steps:" -ForegroundColor Cyan
Write-Host "1. Hard refresh browser (Ctrl+Shift+R or Ctrl+F5)"
Write-Host "2. Check console for: [PV GALLERY] Separated thumbnails: {variation: X, other: Y, videos: Z}"
Write-Host "3. Test variation clicks - thumbnails should reorder"

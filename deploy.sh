#!/bin/bash
# Post-deployment script
# Run this after pulling latest code to your server

echo "Starting deployment tasks..."

# Clear and rebuild view cache
echo "Clearing view cache..."
php artisan view:clear

echo "Rebuilding view cache..."
php artisan view:cache

# Clear other caches
echo "Clearing application cache..."
php artisan cache:clear

echo "Clearing config cache..."
php artisan config:clear

echo "Deployment complete!"

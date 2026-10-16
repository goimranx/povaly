@echo off
echo ========================================
echo Setting Up GitHub Auto-Update System
echo ========================================
echo.

echo [1/5] Clearing all caches...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan event:clear
echo Done!
echo.

echo [2/5] Dumping autoload...
composer dump-autoload
echo Done!
echo.

echo [3/5] Checking routes...
php artisan route:list | findstr "system.update"
echo.

echo [4/5] Testing Git connection...
git fetch origin main
if %errorlevel% == 0 (
    echo Git connection successful!
) else (
    echo WARNING: Git connection failed. You may need to configure credentials.
)
echo.

echo [5/5] Verifying files exist...
if exist "app\Http\Controllers\SystemUpdateController.php" (
    echo [OK] SystemUpdateController.php
) else (
    echo [MISSING] SystemUpdateController.php
)

if exist "app\Http\Middleware\DisableBotbleLicense.php" (
    echo [OK] DisableBotbleLicense.php
) else (
    echo [MISSING] DisableBotbleLicense.php
)

if exist "app\Providers\SystemUpdateServiceProvider.php" (
    echo [OK] SystemUpdateServiceProvider.php
) else (
    echo [MISSING] SystemUpdateServiceProvider.php
)

if exist "resources\views\system-update\index.blade.php" (
    echo [OK] system-update view
) else (
    echo [MISSING] system-update view
)

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Access your update panel at:
echo http://povaly.com/pv-admin/system-update
echo.
echo Menu: Platform Admin -^> System -^> System Update
echo.
pause

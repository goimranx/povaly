<?php
/**
 * Clear Blade view cache
 * Upload to root and access: https://povaly.com/stage/clear-view-cache.php
 * DELETE after use
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Clear view cache
Artisan::call('view:clear');
echo "✓ View cache cleared\n";

// Clear config cache
Artisan::call('config:clear');
echo "✓ Config cache cleared\n";

// Clear route cache
Artisan::call('route:clear');
echo "✓ Route cache cleared\n";

echo "\nAll caches cleared! Reload your page now.\n";

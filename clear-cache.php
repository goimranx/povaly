<?php
/**
 * Temporary cache clearing script
 * Upload this to your server root and visit it in browser: https://povaly.com/clear-cache.php
 * Delete this file after use for security
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "<h1>Clearing Laravel Cache...</h1>";

// Clear route cache
echo "<p>Clearing route cache...";
\Illuminate\Support\Facades\Artisan::call('route:clear');
echo " ✓</p>";

// Clear config cache
echo "<p>Clearing config cache...";
\Illuminate\Support\Facades\Artisan::call('config:clear');
echo " ✓</p>";

// Clear application cache
echo "<p>Clearing application cache...";
\Illuminate\Support\Facades\Artisan::call('cache:clear');
echo " ✓</p>";

// Clear view cache
echo "<p>Clearing view cache...";
\Illuminate\Support\Facades\Artisan::call('view:clear');
echo " ✓</p>";

// Clear compiled classes
echo "<p>Clearing compiled classes...";
\Illuminate\Support\Facades\Artisan::call('clear-compiled');
echo " ✓</p>";

// Optimize
echo "<p>Optimizing...";
\Illuminate\Support\Facades\Artisan::call('optimize:clear');
echo " ✓</p>";

echo "<h2 style='color: green;'>✓ All caches cleared successfully!</h2>";
echo "<p><strong>Important:</strong> Delete this file (clear-cache.php) immediately for security!</p>";

<?php
/**
 * Emergency Cache Clear Script
 * 
 * Upload this to your server root and visit: https://povaly.com/clear-cache.php
 * This will clear all Laravel caches without needing SSH access
 * 
 * DELETE THIS FILE after use for security!
 */

define('LARAVEL_START', microtime(true));

// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h1>Laravel Cache Clear</h1>";
echo "<pre>";

try {
    // Clear route cache
    echo "Clearing route cache...\n";
    Artisan::call('route:clear');
    echo Artisan::output();
    
    // Clear config cache
    echo "\nClearing config cache...\n";
    Artisan::call('config:clear');
    echo Artisan::output();
    
    // Clear view cache
    echo "\nClearing view cache...\n";
    Artisan::call('view:clear');
    echo Artisan::output();
    
    // Clear application cache
    echo "\nClearing application cache...\n";
    Artisan::call('cache:clear');
    echo Artisan::output();
    
    // Clear compiled
    echo "\nClearing compiled classes...\n";
    Artisan::call('clear-compiled');
    echo Artisan::output();
    
    // Optimize
    echo "\nOptimizing...\n";
    Artisan::call('optimize:clear');
    echo Artisan::output();
    
    echo "\n✅ All caches cleared successfully!\n";
    echo "\n🔒 IMPORTANT: DELETE THIS FILE (clear-cache.php) NOW for security!\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}

echo "</pre>";
echo "<hr>";
echo "<p><strong>Next steps:</strong></p>";
echo "<ol>";
echo "<li>DELETE this file (clear-cache.php) immediately</li>";
echo "<li>Test the endpoint: <a href='/ajax/smart-products-test'>/ajax/smart-products-test</a></li>";
echo "<li>Test smart products: <a href='/ajax/smart-products?page=1&per_page=6'>/ajax/smart-products?page=1&per_page=6</a></li>";
echo "</ol>";

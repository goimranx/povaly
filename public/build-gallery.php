<?php
/**
 * Manual build script - run this once to compile PV Gallery assets
 * Visit: https://povaly.com/build-gallery.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

set_time_limit(300); // 5 minutes
echo '<h2>🔧 Building PV Gallery Assets...</h2>';
echo '<pre>';

// Change to project root
$rootDir = dirname(__DIR__);
chdir($rootDir);

echo "Root directory: " . getcwd() . "\n\n";

// Check if exec is available
if (!function_exists('exec')) {
    echo "❌ ERROR: exec() function is disabled on this server\n\n";
    echo "You need to run this via SSH instead:\n";
    echo "cd $rootDir\n";
    echo "npm run prod\n\n";
    echo "Or ask your hosting provider to enable exec() function in PHP\n";
    exit;
}

// Check if npm exists
$npmCheck = [];
exec('which npm 2>&1', $npmCheck, $npmCode);
if ($npmCode !== 0) {
    echo "❌ ERROR: npm is not installed or not in PATH\n\n";
    echo "You need to run this via SSH instead:\n";
    echo "cd $rootDir\n";
    echo "npm run prod\n";
    exit;
}

echo "✅ npm found at: " . implode("\n", $npmCheck) . "\n\n";

// Run npm build
echo "Running npm run prod...\n";
echo str_repeat('-', 60) . "\n";
$output = [];
$returnCode = 0;
exec('cd ' . escapeshellarg($rootDir) . ' && npm run prod 2>&1', $output, $returnCode);

foreach ($output as $line) {
    echo htmlspecialchars($line) . "\n";
}

echo str_repeat('-', 60) . "\n\n";

if ($returnCode === 0) {
    echo '✅ Build completed successfully!';
    echo "\n\n";
} else {
    echo "⚠️  Build command returned code: $returnCode\n\n";
}

// Check if files exist
$filesToCheck = [
    'platform/plugins/ecommerce/public/css/pv-gallery.css',
    'platform/plugins/ecommerce/public/js/pv-gallery.js',
    'public/vendor/core/plugins/ecommerce/css/pv-gallery.css',
    'public/vendor/core/plugins/ecommerce/js/pv-gallery.js',
];

echo "Checking compiled files:\n";
foreach ($filesToCheck as $file) {
    $fullPath = $rootDir . '/' . $file;
    if (file_exists($fullPath)) {
        echo "✅ $file (" . number_format(filesize($fullPath)) . " bytes)\n";
    } else {
        echo "❌ $file (NOT FOUND)\n";
    }
}

echo '</pre>';
echo '<hr>';
echo '<p><strong>If build was successful:</strong> Hard refresh your browser (Ctrl+F5)</p>';
echo '<p><strong>If build failed:</strong> Run via SSH: <code>cd ' . htmlspecialchars($rootDir) . ' && npm run prod</code></p>';

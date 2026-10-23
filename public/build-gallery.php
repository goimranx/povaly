<?php
/**
 * Manual build script - run this once to compile PV Gallery assets
 * Visit: https://povaly.com/build-gallery.php
 */

set_time_limit(300); // 5 minutes
echo '<h2>🔧 Building PV Gallery Assets...</h2>';
echo '<pre>';

// Change to project root
chdir(dirname(__DIR__));

// Run npm build
echo "Running npm run prod...\n\n";
$output = [];
$returnCode = 0;
exec('npm run prod 2>&1', $output, $returnCode);

foreach ($output as $line) {
    echo htmlspecialchars($line) . "\n";
}

echo "\n";

if ($returnCode === 0) {
    echo '✅ Build completed successfully!';
    echo "\n\n";
    
    // Check if files exist
    $filesToCheck = [
        'platform/plugins/ecommerce/public/css/pv-gallery.css',
        'platform/plugins/ecommerce/public/js/pv-gallery.js',
        'public/vendor/core/plugins/ecommerce/css/pv-gallery.css',
        'public/vendor/core/plugins/ecommerce/js/pv-gallery.js',
    ];
    
    echo "Checking compiled files:\n";
    foreach ($filesToCheck as $file) {
        if (file_exists($file)) {
            echo "✅ $file (" . number_format(filesize($file)) . " bytes)\n";
        } else {
            echo "❌ $file (NOT FOUND)\n";
        }
    }
} else {
    echo "❌ Build failed with code: $returnCode\n";
    echo "\nYou may need to run manually via SSH:\n";
    echo "cd " . dirname(__DIR__) . "\n";
    echo "npm run prod\n";
}

echo '</pre>';
echo '<hr>';
echo '<p><strong>Next step:</strong> Hard refresh your browser (Ctrl+F5)</p>';

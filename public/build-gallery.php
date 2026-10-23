<?php
/**
 * Copy PV Gallery compiled files to vendor directory
 * Visit: https://povaly.com/build-gallery.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h2>🔧 Publishing PV Gallery Assets...</h2>';
echo '<pre>';

$rootDir = dirname(__DIR__);

$files = [
    [
        'source' => $rootDir . '/platform/plugins/ecommerce/public/css/pv-gallery.css',
        'dest' => $rootDir . '/public/vendor/core/plugins/ecommerce/css/pv-gallery.css',
    ],
    [
        'source' => $rootDir . '/platform/plugins/ecommerce/public/js/pv-gallery.js',
        'dest' => $rootDir . '/public/vendor/core/plugins/ecommerce/js/pv-gallery.js',
    ],
];

$success = 0;
$failed = 0;

foreach ($files as $file) {
    $source = $file['source'];
    $dest = $file['dest'];
    
    echo "Copying: " . basename($source) . "\n";
    
    if (!file_exists($source)) {
        echo "  ❌ Source not found: $source\n";
        $failed++;
        continue;
    }
    
    // Create destination directory if needed
    $destDir = dirname($dest);
    if (!is_dir($destDir)) {
        if (!mkdir($destDir, 0755, true)) {
            echo "  ❌ Failed to create directory: $destDir\n";
            $failed++;
            continue;
        }
    }
    
    // Copy file
    if (copy($source, $dest)) {
        echo "  ✅ Copied successfully (" . number_format(filesize($dest)) . " bytes)\n";
        $success++;
    } else {
        echo "  ❌ Copy failed\n";
        $failed++;
    }
}

echo "\n";
echo str_repeat('-', 60) . "\n";
echo "✅ Success: $success files\n";
echo "❌ Failed: $failed files\n";
echo str_repeat('-', 60) . "\n";

if ($success > 0) {
    echo "\n🎉 PV Gallery assets published successfully!\n";
    echo "Hard refresh your browser (Ctrl+F5) to see changes.\n";
}

echo '</pre>';

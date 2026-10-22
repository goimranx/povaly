<?php
/**
 * Update Gallery JavaScript
 * Run this file from browser: your-site.com/update-gallery-js.php
 * This copies the updated front-ecommerce.js to the vendor public directory
 */

$source = __DIR__ . '/platform/plugins/ecommerce/public/js/front-ecommerce.js';
$destination = __DIR__ . '/public/vendor/core/plugins/ecommerce/js/front-ecommerce.js';

// Check if source exists
if (!file_exists($source)) {
    die('❌ Source file not found: ' . $source);
}

// Check if destination directory exists
$destDir = dirname($destination);
if (!is_dir($destDir)) {
    if (!mkdir($destDir, 0755, true)) {
        die('❌ Could not create destination directory: ' . $destDir);
    }
}

// Copy the file
if (copy($source, $destination)) {
    echo '✅ Successfully copied front-ecommerce.js to vendor directory<br>';
    echo '📁 Source: ' . $source . '<br>';
    echo '📁 Destination: ' . $destination . '<br>';
    echo '📅 Modified: ' . date('Y-m-d H:i:s', filemtime($destination)) . '<br>';
    echo '<br><strong>✨ Done! Now clear your browser cache (Ctrl+F5) and reload the product page.</strong>';
} else {
    die('❌ Failed to copy file');
}

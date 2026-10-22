<?php
/**
 * Gallery JS Diagnostic Script
 * Run this: your-site.com/check-gallery-js.php
 */

echo '<h2>Gallery JS Diagnostic Check</h2>';
echo '<style>body{font-family:monospace;padding:20px;} .ok{color:green;} .error{color:red;}</style>';

// Check 1: Source file
$source = dirname(__DIR__) . '/platform/plugins/ecommerce/public/js/front-ecommerce.js';
echo '<h3>1. Source File Check</h3>';
if (file_exists($source)) {
    echo '<div class="ok">✅ Source exists: ' . $source . '</div>';
    echo 'Size: ' . number_format(filesize($source)) . ' bytes<br>';
    echo 'Modified: ' . date('Y-m-d H:i:s', filemtime($source)) . '<br>';
    
    // Check for our console log
    $content = file_get_contents($source);
    if (strpos($content, 'Gallery Thumbnails Init Start') !== false) {
        echo '<div class="ok">✅ Console logs FOUND in source</div>';
    } else {
        echo '<div class="error">❌ Console logs NOT FOUND in source</div>';
    }
    
    if (strpos($content, 'slidesToShow: 4') !== false) {
        echo '<div class="ok">✅ slidesToShow: 4 FOUND in source</div>';
    } else {
        echo '<div class="error">❌ slidesToShow: 4 NOT FOUND (still 6?)</div>';
    }
} else {
    echo '<div class="error">❌ Source NOT found</div>';
}

// Check 2: Vendor file (what's actually loaded)
$vendor = __DIR__ . '/vendor/core/plugins/ecommerce/js/front-ecommerce.js';
echo '<h3>2. Vendor File Check (Actually Loaded)</h3>';
if (file_exists($vendor)) {
    echo '<div class="ok">✅ Vendor file exists: ' . $vendor . '</div>';
    echo 'Size: ' . number_format(filesize($vendor)) . ' bytes<br>';
    echo 'Modified: ' . date('Y-m-d H:i:s', filemtime($vendor)) . '<br>';
    
    // Check for our console log
    $content = file_get_contents($vendor);
    if (strpos($content, 'Gallery Thumbnails Init Start') !== false) {
        echo '<div class="ok">✅ Console logs FOUND in vendor file</div>';
    } else {
        echo '<div class="error">❌ Console logs NOT FOUND in vendor file - THIS IS THE PROBLEM!</div>';
    }
    
    if (strpos($content, 'slidesToShow: 4') !== false) {
        echo '<div class="ok">✅ slidesToShow: 4 FOUND in vendor file</div>';
    } else {
        echo '<div class="error">❌ slidesToShow: 4 NOT FOUND in vendor file (still 6?)</div>';
    }
    
    // Show first 500 chars around slidesToShow
    if (preg_match('/slidesToShow:\s*(\d+)/', $content, $matches)) {
        echo '<br><strong>Found slidesToShow: ' . $matches[1] . '</strong><br>';
    }
} else {
    echo '<div class="error">❌ Vendor file NOT found</div>';
}

// Check 3: File comparison
echo '<h3>3. File Comparison</h3>';
if (file_exists($source) && file_exists($vendor)) {
    $sourceHash = md5_file($source);
    $vendorHash = md5_file($vendor);
    
    if ($sourceHash === $vendorHash) {
        echo '<div class="ok">✅ Files are IDENTICAL (hashes match)</div>';
    } else {
        echo '<div class="error">❌ Files are DIFFERENT - vendor needs update!</div>';
        echo 'Source hash: ' . $sourceHash . '<br>';
        echo 'Vendor hash: ' . $vendorHash . '<br>';
        echo '<br><strong>🔧 Fix: Run update-gallery-js.php to copy source to vendor</strong>';
    }
}

// Check 4: Resource file
$resource = dirname(__DIR__) . '/platform/plugins/ecommerce/resources/js/front-ecommerce.js';
echo '<h3>4. Resource File Check (Original Source)</h3>';
if (file_exists($resource)) {
    echo '<div class="ok">✅ Resource exists: ' . $resource . '</div>';
    echo 'Size: ' . number_format(filesize($resource)) . ' bytes<br>';
    echo 'Modified: ' . date('Y-m-d H:i:s', filemtime($resource)) . '<br>';
    
    $content = file_get_contents($resource);
    if (strpos($content, 'Gallery Thumbnails Init Start') !== false) {
        echo '<div class="ok">✅ Console logs FOUND in resource</div>';
    } else {
        echo '<div class="error">❌ Console logs NOT FOUND in resource</div>';
    }
}

echo '<hr>';
echo '<h3>Action Required:</h3>';
echo '<ol>';
echo '<li>If vendor file is missing console logs, visit: <a href="/update-gallery-js.php">update-gallery-js.php</a></li>';
echo '<li>Clear Laravel cache: <a href="/clear-cache.php">clear-cache.php</a></li>';
echo '<li>Hard refresh browser (Ctrl+F5)</li>';
echo '<li>Check browser console (F12)</li>';
echo '</ol>';

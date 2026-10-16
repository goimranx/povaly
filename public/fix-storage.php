<?php
/**
 * Fix Storage Symlink for Povaly
 * Upload this file to your public_html root and visit it in browser
 */

$publicStoragePath = __DIR__ . '/public/storage';
$appStoragePath = __DIR__ . '/storage/app/public';

echo "<h1>Povaly Storage Symlink Fix</h1>";

// Check if storage/app/public exists
if (!is_dir($appStoragePath)) {
    echo "<p style='color:red;'>❌ Error: storage/app/public folder does not exist!</p>";
    echo "<p>Path checked: $appStoragePath</p>";
    exit;
}

// Check if public/storage exists
if (file_exists($publicStoragePath)) {
    // Check if it's a symlink
    if (is_link($publicStoragePath)) {
        $target = readlink($publicStoragePath);
        echo "<p style='color:orange;'>⚠️ Symlink already exists</p>";
        echo "<p>Points to: $target</p>";
        
        // Check if it points to correct location
        if (realpath($publicStoragePath) === realpath($appStoragePath)) {
            echo "<p style='color:green;'>✅ Symlink is correct!</p>";
        } else {
            echo "<p style='color:red;'>❌ Symlink points to wrong location</p>";
            echo "<p>Removing old symlink...</p>";
            @unlink($publicStoragePath);
        }
    } else {
        // It's a directory, not a symlink
        echo "<p style='color:red;'>❌ public/storage is a directory, not a symlink</p>";
        echo "<p>This needs to be removed manually via File Manager</p>";
        echo "<p>Go to File Manager → public → Right-click 'storage' → Delete</p>";
        echo "<p>Then run this script again.</p>";
        exit;
    }
}

// Create symlink
if (!file_exists($publicStoragePath)) {
    echo "<p>Creating symlink...</p>";
    
    $relativePath = '../storage/app/public';
    
    if (symlink($relativePath, $publicStoragePath)) {
        echo "<p style='color:green;'>✅ SUCCESS! Symlink created</p>";
        echo "<p>public/storage → storage/app/public</p>";
    } else {
        echo "<p style='color:red;'>❌ Failed to create symlink</p>";
        echo "<p>Try running this command via SSH:</p>";
        echo "<pre>cd " . __DIR__ . "\nphp artisan storage:link</pre>";
    }
}

// Test the symlink
echo "<hr>";
echo "<h2>Testing...</h2>";

// Count files
$files = glob($appStoragePath . '/*/*');
$count = count($files);
echo "<p>Files in storage/app/public: <strong>$count</strong></p>";

// Test a sample image
$sampleImages = [
    'products/17-5.jpg',
    'general/logo.png',
    'brands/brand.png'
];

echo "<h3>Sample Image Tests:</h3>";
foreach ($sampleImages as $img) {
    $fullPath = $appStoragePath . '/' . $img;
    if (file_exists($fullPath)) {
        $webPath = '/storage/' . $img;
        echo "<p>✅ Found: <a href='$webPath' target='_blank'>$img</a></p>";
        echo "<img src='$webPath' style='max-width:200px;border:1px solid #ccc;' onerror=\"this.style.display='none'; this.nextSibling.style.display='block';\">";
        echo "<span style='color:red;display:none;'>Failed to load image</span><br><br>";
        break; // Just test first found image
    }
}

// List all folders
echo "<h3>Storage Folders:</h3>";
$folders = glob($appStoragePath . '/*', GLOB_ONLYDIR);
echo "<ul>";
foreach ($folders as $folder) {
    $name = basename($folder);
    $fileCount = count(glob($folder . '/*'));
    echo "<li>$name/ - $fileCount files</li>";
}
echo "</ul>";

echo "<hr>";
echo "<p><strong>Next Step:</strong> Go to <a href='/pv-admin/media'>Media Manager</a> to verify images appear</p>";
echo "<p><small>You can delete this file after confirming everything works.</small></p>";

<?php
/**
 * Version Detection Debug Script
 * Run this on the server to diagnose why updater isn't seeing v2.10.19
 * 
 * Usage: php check-version-debug.php
 */

echo "=== GIT VERSION DEBUG ===\n\n";

// Change to project root
chdir(__DIR__);
echo "Working Directory: " . getcwd() . "\n\n";

// 1. Check current HEAD
echo "1. CURRENT HEAD:\n";
exec('git rev-parse HEAD', $output, $code);
echo "   Commit: " . ($output[0] ?? 'unknown') . "\n";
echo "   Exit code: {$code}\n\n";
$output = [];

// 2. Check current branch
echo "2. CURRENT BRANCH:\n";
exec('git rev-parse --abbrev-ref HEAD', $output, $code);
echo "   Branch: " . ($output[0] ?? 'unknown') . "\n";
echo "   Exit code: {$code}\n\n";
$output = [];

// 3. Check exact tag on HEAD
echo "3. EXACT TAG ON HEAD:\n";
exec('git describe --exact-match --tags HEAD 2>&1', $output, $code);
echo "   Tag: " . ($output[0] ?? 'none') . "\n";
echo "   Exit code: {$code}\n\n";
$output = [];

// 4. Check latest tag
echo "4. LATEST TAG (--abbrev=0):\n";
exec('git describe --tags --abbrev=0 2>&1', $output, $code);
echo "   Tag: " . ($output[0] ?? 'unknown') . "\n";
echo "   Exit code: {$code}\n\n";
$output = [];

// 5. Fetch tags from remote
echo "5. FETCHING TAGS FROM REMOTE:\n";
exec('git fetch --tags 2>&1', $output, $code);
foreach ($output as $line) {
    echo "   {$line}\n";
}
echo "   Exit code: {$code}\n\n";
$output = [];

// 6. List all local tags (sorted)
echo "6. ALL LOCAL TAGS (sorted by version):\n";
exec('git tag --sort=-v:refname', $output, $code);
$tags = array_slice($output, 0, 10);
foreach ($tags as $i => $tag) {
    echo "   " . ($i + 1) . ". {$tag}\n";
}
echo "   Exit code: {$code}\n\n";
$output = [];

// 7. Check what commit v2.10.19 points to
echo "7. TAG v2.10.19 COMMIT:\n";
exec('git rev-parse v2.10.19 2>&1', $output, $code);
echo "   Commit: " . ($output[0] ?? 'not found') . "\n";
echo "   Exit code: {$code}\n\n";
$output = [];

// 8. Check remote tags
echo "8. REMOTE TAGS (v2.10.17+):\n";
exec('git ls-remote --tags origin 2>&1 | grep "v2.10.1[789]" | grep -v "\^{}"', $output, $code);
foreach ($output as $line) {
    echo "   {$line}\n";
}
echo "   Exit code: {$code}\n\n";
$output = [];

// 9. Check Laravel cache
echo "9. LARAVEL VERSION CACHE:\n";
if (file_exists('bootstrap/cache/config.php')) {
    echo "   Config cache exists\n";
} else {
    echo "   Config cache does NOT exist\n";
}
$cacheFile = 'storage/framework/cache/data';
if (is_dir($cacheFile)) {
    echo "   Cache directory exists\n";
    
    // Try to find app_version cache key
    $found = false;
    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($cacheFile)) as $file) {
        if ($file->isFile() && strpos(file_get_contents($file->getPathname()), 'app_version') !== false) {
            echo "   Found app_version cache: " . $file->getFilename() . "\n";
            $found = true;
            break;
        }
    }
    if (!$found) {
        echo "   app_version cache key not found\n";
    }
} else {
    echo "   Cache directory does NOT exist\n";
}

echo "\n=== DIAGNOSIS ===\n";
echo "Current HEAD commit should be: 3a3a3155\n";
echo "v2.10.19 tag should point to: 3a3a3155\n";
echo "If different, run: php artisan cache:clear\n";
echo "Then try System Updater again\n";

<?php
/**
 * GitHub API Direct File Download Deploy
 * No git commands - downloads files directly from GitHub
 */

// Configuration
define('SECRET_KEY', 'a23cd11be3895f3ee450d843745413a3854d99b4dfebe8a182d0ea4cbf44bf3f');
define('GITHUB_REPO', 'goimranx/povaly');
define('BRANCH', 'main');
define('BASE_DIR', '/home/u497292684/domains/povaly.com/public_html');
define('LOG_FILE', BASE_DIR . '/storage/logs/github-deploy.log');

// Files to download from GitHub
$filesToDeploy = [
    'platform/themes/martfury/public/js/carousel-init.js',
    'platform/themes/martfury/config.php',
    'platform/themes/martfury/partials/footer.blade.php',
    'bootstrap/app.php',
];

function log_message($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}\n";
    
    $logDir = dirname(LOG_FILE);
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    
    file_put_contents(LOG_FILE, $logMessage, FILE_APPEND);
    echo $logMessage;
    flush();
}

function verify_signature($payload, $signature) {
    if (empty($signature)) {
        return false;
    }
    
    $hash = 'sha256=' . hash_hmac('sha256', $payload, SECRET_KEY);
    return hash_equals($hash, $signature);
}

function download_from_github($filePath) {
    $url = "https://raw.githubusercontent.com/" . GITHUB_REPO . "/" . BRANCH . "/" . $filePath;
    
    log_message("Downloading: {$filePath}");
    log_message("  From: {$url}");
    
    $content = @file_get_contents($url);
    
    if ($content === false) {
        log_message("  ✗ Failed to download");
        return false;
    }
    
    $localPath = BASE_DIR . '/' . $filePath;
    $dir = dirname($localPath);
    
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    
    if (file_put_contents($localPath, $content) === false) {
        log_message("  ✗ Failed to write file");
        return false;
    }
    
    log_message("  ✓ Downloaded and saved successfully");
    return true;
}

function clear_caches() {
    log_message("\n--- Clearing Caches ---");
    
    // Clear OPcache
    if (function_exists('opcache_reset')) {
        opcache_reset();
        log_message("✓ OPcache cleared");
    }
    
    // Clear file caches
    $cacheFiles = [
        BASE_DIR . '/bootstrap/cache/packages.php',
        BASE_DIR . '/bootstrap/cache/services.php',
        BASE_DIR . '/bootstrap/cache/config.php',
        BASE_DIR . '/bootstrap/cache/routes-v7.php',
    ];
    
    foreach ($cacheFiles as $file) {
        if (file_exists($file)) {
            @unlink($file);
        }
    }
    
    log_message("✓ Laravel caches cleared");
}

// Main logic
try {
    log_message("========================================");
    log_message("GitHub Webhook - Direct Download Deploy");
    log_message("========================================");
    
    // Get payload
    $payload = file_get_contents('php://input');
    $signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
    
    // Verify signature
    if (!verify_signature($payload, $signature)) {
        log_message("✗ Invalid signature - webhook rejected");
        http_response_code(403);
        die('Invalid signature');
    }
    
    log_message("✓ Signature verified");
    
    // Parse payload
    $data = json_decode($payload, true);
    
    // Check branch
    if (!isset($data['ref']) || $data['ref'] !== 'refs/heads/' . BRANCH) {
        log_message("✗ Not a push to " . BRANCH . " branch - skipping");
        http_response_code(200);
        die('Not the target branch');
    }
    
    log_message("✓ Push to " . BRANCH . " branch detected");
    
    // Get commit info
    $commits = $data['commits'] ?? [];
    log_message("Commits: " . count($commits));
    
    foreach ($commits as $commit) {
        $message = $commit['message'] ?? 'No message';
        $author = $commit['author']['name'] ?? 'Unknown';
        log_message("  - {$message} (by {$author})");
    }
    
    // Download files from GitHub
    log_message("\n--- Downloading Files from GitHub ---");
    
    $success = true;
    foreach ($GLOBALS['filesToDeploy'] as $file) {
        if (!download_from_github($file)) {
            $success = false;
        }
    }
    
    if (!$success) {
        log_message("\n✗✗✗ DEPLOYMENT FAILED ✗✗✗");
        log_message("Some files failed to download");
        http_response_code(500);
        die('Deployment failed');
    }
    
    // Clear caches
    clear_caches();
    
    // Recreate theme symlink
    log_message("\n--- Recreating Theme Symlinks ---");
    $publicThemesDir = BASE_DIR . '/public/themes';
    $martfurySymlink = $publicThemesDir . '/martfury';
    $martfuryTarget = BASE_DIR . '/platform/themes/martfury/public';
    
    // Remove old symlink if exists
    if (is_link($martfurySymlink)) {
        @unlink($martfurySymlink);
        log_message("✓ Removed old symlink");
    }
    
    // Create directory if doesn't exist
    if (!is_dir($publicThemesDir)) {
        @mkdir($publicThemesDir, 0755, true);
    }
    
    // Create new symlink
    if (@symlink($martfuryTarget, $martfurySymlink)) {
        log_message("✓ Theme symlink recreated");
    } else {
        log_message("⚠ Could not create symlink (might need manual setup)");
    }
    
    log_message("\n========================================");
    log_message("✓✓✓ DEPLOYMENT SUCCESSFUL ✓✓✓");
    log_message("========================================\n");
    
    http_response_code(200);
    echo "Deployment successful!";
    
} catch (Exception $e) {
    log_message("\n✗✗✗ DEPLOYMENT FAILED ✗✗✗");
    log_message("Error: " . $e->getMessage());
    log_message("========================================\n");
    
    http_response_code(500);
    echo "Deployment failed: " . $e->getMessage();
}

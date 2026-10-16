<?php
/**
 * GitHub Webhook Auto-Deploy Script
 * 
 * This script automatically pulls latest changes when GitHub sends a webhook.
 * 
 * Setup:
 * 1. Upload this file to: /home/u497292684/domains/povaly.com/public_html/deploy.php
 * 2. Set file permissions: chmod 644 deploy.php
 * 3. Go to GitHub repo → Settings → Webhooks → Add webhook
 * 4. Payload URL: https://povaly.com/deploy.php
 * 5. Content type: application/json
 * 6. Secret: (generate a random string and put it below)
 * 7. Events: Just the push event
 * 8. Active: ✓
 */

// Configuration
define('SECRET_KEY', 'your-secret-key-here-change-this'); // CHANGE THIS!
define('BRANCH', 'main');
define('REPO_DIR', '/home/u497292684/domains/povaly.com/public_html');
define('LOG_FILE', REPO_DIR . '/storage/logs/deploy.log');

// Verify GitHub signature
function verify_signature($payload, $signature) {
    if (empty($signature)) {
        return false;
    }
    
    $hash = 'sha256=' . hash_hmac('sha256', $payload, SECRET_KEY);
    return hash_equals($hash, $signature);
}

// Log deployment activity
function log_message($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}\n";
    
    // Ensure log directory exists
    $logDir = dirname(LOG_FILE);
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    file_put_contents(LOG_FILE, $logMessage, FILE_APPEND);
    echo $logMessage;
}

// Execute shell command and log output
function execute_command($command) {
    log_message("Executing: {$command}");
    
    $output = [];
    $return_var = 0;
    exec($command . ' 2>&1', $output, $return_var);
    
    foreach ($output as $line) {
        log_message("  → {$line}");
    }
    
    if ($return_var !== 0) {
        log_message("  ✗ Command failed with exit code: {$return_var}");
        return false;
    }
    
    log_message("  ✓ Command successful");
    return true;
}

// Main deployment logic
try {
    log_message("========================================");
    log_message("GitHub Webhook Received");
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
    
    // Check if it's a push to the correct branch
    if (!isset($data['ref']) || $data['ref'] !== 'refs/heads/' . BRANCH) {
        log_message("✗ Not a push to " . BRANCH . " branch - skipping");
        http_response_code(200);
        die('Not the target branch');
    }
    
    log_message("✓ Push to " . BRANCH . " branch detected");
    
    // Get commit info
    $commits = $data['commits'] ?? [];
    $commitCount = count($commits);
    log_message("Commits: {$commitCount}");
    
    foreach ($commits as $commit) {
        $message = $commit['message'] ?? 'No message';
        $author = $commit['author']['name'] ?? 'Unknown';
        log_message("  - {$message} (by {$author})");
    }
    
    // Change to repository directory
    if (!is_dir(REPO_DIR)) {
        log_message("✗ Repository directory not found: " . REPO_DIR);
        http_response_code(500);
        die('Repository directory not found');
    }
    
    chdir(REPO_DIR);
    log_message("✓ Changed to repository directory");
    
    // Reset any local changes first
    log_message("\n--- Resetting local changes ---");
    execute_command('git reset --hard HEAD');
    execute_command('git clean -fd');
    log_message("✓ Local changes reset");
    
    // Pull latest changes
    log_message("\n--- Pulling from GitHub ---");
    if (!execute_command('git pull origin ' . BRANCH)) {
        log_message("✗ Git pull failed");
        http_response_code(500);
        die('Git pull failed');
    }
    
    // Clear Laravel caches
    log_message("\n--- Clearing Caches ---");
    execute_command('php artisan cache:clear');
    execute_command('php artisan config:clear');
    execute_command('php artisan route:clear');
    execute_command('php artisan view:clear');
    
    // Clear OPcache if available
    if (function_exists('opcache_reset')) {
        opcache_reset();
        log_message("✓ OPcache cleared");
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

<?php
/**
 * Auto-deploy webhook for GitHub
 *
 * Setup:
 * 1. Upload this file to your server root (where index.php is)
 * 2. In GitHub repo → Settings → Webhooks → Add webhook
 *    - Payload URL: https://povaly.com/deploy.php
 *    - Content type: application/json
 *    - Secret: (copy the SECRET below — change it to something random)
 *    - Events: Just the push event
 * 3. Change SECRET below to a strong random string
 * 4. Update REPO_PATH to your actual site path on the server
 */

define('SECRET',    'CHANGE_THIS_TO_A_RANDOM_SECRET');
define('REPO_PATH', '/home/your-user/public_html'); // ← change this

// Verify GitHub signature
$payload   = file_get_contents('php://input');
$signature = 'sha256=' . hash_hmac('sha256', $payload, SECRET);

if (!hash_equals($signature, $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '')) {
    http_response_code(403);
    die('Forbidden');
}

$data   = json_decode($payload, true);
$branch = $data['ref'] ?? '';

// Only deploy on push to main branch
if ($branch !== 'refs/heads/main') {
    die('Not main branch — skipped.');
}

$output = [];

// Pull latest code
exec('cd ' . REPO_PATH . ' && git pull origin main 2>&1', $output);

// Clear Laravel caches
exec('cd ' . REPO_PATH . ' && php artisan view:clear 2>&1', $output);
exec('cd ' . REPO_PATH . ' && php artisan cache:clear 2>&1', $output);
exec('cd ' . REPO_PATH . ' && php artisan config:clear 2>&1', $output);

echo implode("\n", $output);

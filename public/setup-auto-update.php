<?php
/**
 * One-time setup script - removes the old git hook that runs npm build
 * Visit: https://povaly.com/setup-auto-update.php
 */

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h2>🔧 Removing Old Auto-Build Hook...</h2>';
echo '<pre>';

$hookPath = dirname(__DIR__) . '/.git/hooks/post-merge';

if (file_exists($hookPath)) {
    if (unlink($hookPath)) {
        echo "✅ Old auto-build hook removed successfully!\n\n";
    } else {
        echo "❌ Failed to remove hook file\n\n";
    }
} else {
    echo "ℹ️  No hook file found (already removed or never created)\n\n";
}

echo "The System Updater now handles everything without npm build.\n";
echo "Assets are pre-compiled locally and just copied on the server.\n\n";

echo '</pre>';
echo '<hr>';
echo '<h3>✅ Setup Complete!</h3>';
echo '<p>Now just use the <strong>System Updater</strong> button in your CMS.</p>';
echo '<p>No more npm build errors!</p>';
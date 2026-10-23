<?php
/**
 * One-time setup script to enable auto-update of gallery JS
 * Run this once: https://povaly.com/setup-auto-update.php
 */

$hookPath = dirname(__DIR__) . '/.git/hooks/post-merge';
$hookContent = <<<'HOOK'
#!/bin/sh
# Auto-build and publish assets after git pull

echo "🔄 Building assets..."

# Run production build (compiles CSS/JS from resources to public)
npm run prod

echo "✅ Assets built and published automatically!"
echo "📦 All CSS/JS files are now up to date in vendor directory"

exit 0
HOOK;

// Create the hook file
if (file_put_contents($hookPath, $hookContent) !== false) {
    // Make it executable (Unix/Linux)
    @chmod($hookPath, 0755);
    
    echo '<h2>✅ Auto-update hook installed successfully!</h2>';
    echo '<p>The gallery JS will now auto-copy to vendor directory after every <code>git pull</code></p>';
    echo '<hr>';
    echo '<p><strong>Next steps:</strong></p>';
    echo '<ol>';
    echo '<li>Do a test: <code>git pull</code></li>';
    echo '<li>You should see: "🔄 Copying ecommerce JS to vendor directory..."</li>';
    echo '<li>From now on, just <code>git pull</code> and refresh browser - no manual update needed!</li>';
    echo '</ol>';
} else {
    echo '<h2>❌ Failed to create hook</h2>';
    echo '<p>Please check file permissions on .git/hooks directory</p>';
}

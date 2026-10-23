<?php
/**
 * One-time setup script to enable auto-update of gallery JS
 * Run this once: https://povaly.com/setup-auto-update.php
 */

$hookPath = dirname(__DIR__) . '/.git/hooks/post-merge';
$hookContent = <<<'HOOK'
#!/bin/sh
# Auto-copy ecommerce JS to vendor directory after git pull

echo "🔄 Copying ecommerce JS to vendor directory..."

SOURCE="platform/plugins/ecommerce/public/js/front-ecommerce.js"
DEST="public/vendor/core/plugins/ecommerce/js/front-ecommerce.js"

if [ -f "$SOURCE" ]; then
    mkdir -p "$(dirname "$DEST")"
    cp -f "$SOURCE" "$DEST"
    echo "✅ Ecommerce JS copied successfully"
else
    echo "⚠️  Source file not found: $SOURCE"
fi

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

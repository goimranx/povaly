<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Process;
use App\Helpers\VersionHelper;

/**
 * Custom System Updater for Povaly CMS
 * 
 * This controller handles automated deployments from GitHub.
 * 
 * WORKFLOW:
 * 1. Edit code locally in platform/ directory
 * 2. Build assets: npm run prod
 * 3. Commit and push to GitHub
 * 4. Click "System Updater" button in CMS
 * 5. Done! System auto-publishes assets and clears caches
 * 
 * NOTE: Server has Node v18 (too old), so assets are pre-compiled locally
 * and committed to git. Server just copies from platform/public/ to public/vendor/
 * 
 * See: DEVELOPMENT-WORKFLOW.md for full documentation
 */
class SystemUpdateController extends Controller
{
    /**
     * Display the system update page
     */
    public function index()
    {
        return view('system-update.index');
    }

    /**
     * Pull latest changes from GitHub and update the system
     * Uses Server-Sent Events (SSE) for real-time progress updates
     */
    public function update(Request $request)
    {
        return response()->stream(function () {
            // Helper function to send a message to the client
            $sendMessage = function ($message, $type = 'info') {
                echo "data: " . json_encode([
                    'type' => $type,
                    'message' => $message,
                    'timestamp' => now()->format('H:i:s')
                ]) . "\n\n";
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
                usleep(100000); // 100ms delay for better visual feedback
            };

            try {
                $returnCode = 0;

                // Step 1: Put the site in maintenance mode
                $sendMessage("=== Enabling Maintenance Mode ===", 'header');
                Artisan::call('down', ['--render' => 'errors::503']);
                $sendMessage("✓ Maintenance mode enabled", 'success');

                // Step 2: Determine deployment strategy based on branch
                $currentBranch = VersionHelper::getCurrentBranch();
                $isProduction = VersionHelper::isProduction(); // Use helper method which checks both branch and detached HEAD on tag
                
                if ($isProduction) {
                    // PRODUCTION: Deploy using Git tags
                    $sendMessage("\n=== Production Deployment (Tag-based) ===", 'header');
                    $sendMessage("Current branch: {$currentBranch}", 'info');
                    
                    // Fetch all tags
                    $sendMessage("\n→ Fetching tags from GitHub...", 'info');
                    $fetchOutput = $this->executeCommand('git fetch --tags', $returnCode);
                    foreach ($fetchOutput as $line) {
                        $sendMessage($line, 'info');
                    }
                    
                    if ($returnCode !== 0) {
                        throw new \Exception("Git fetch tags failed with code {$returnCode}");
                    }
                    
                    // Get latest tag
                    $tags = VersionHelper::getAllTags();
                    if (empty($tags)) {
                        throw new \Exception("No tags found! Production must use tags.");
                    }
                    
                    $latestTag = $tags[0];
                    $sendMessage("→ Latest tag: {$latestTag}", 'info');
                    
                    // Checkout the latest tag
                    $sendMessage("\n→ Deploying tag: {$latestTag}", 'info');
                    $checkoutOutput = $this->executeCommand("git checkout {$latestTag}", $returnCode);
                    foreach ($checkoutOutput as $line) {
                        $sendMessage($line, 'info');
                    }
                    
                    if ($returnCode !== 0) {
                        throw new \Exception("Git checkout tag failed with code {$returnCode}");
                    }
                    
                    $sendMessage("✓ Production deployed to tag: {$latestTag}", 'success');
                    
                } else {
                    // STAGING/DEVELOPMENT: Deploy using branch
                    $sendMessage("\n=== Staging Deployment (Branch-based) ===", 'header');
                    $sendMessage("Current branch: {$currentBranch}", 'info');
                    
                    // Fetch and pull latest from branch
                    $sendMessage("\n→ Fetching from GitHub...", 'info');
                    $fetchOutput = $this->executeCommand("git fetch origin {$currentBranch}", $returnCode);
                    foreach ($fetchOutput as $line) {
                        $sendMessage($line, 'info');
                    }
                    
                    if ($returnCode !== 0) {
                        throw new \Exception("Git fetch failed with code {$returnCode}");
                    }
                    
                    $sendMessage("\n→ Resetting to origin/{$currentBranch}...", 'info');
                    $resetOutput = $this->executeCommand("git reset --hard origin/{$currentBranch}", $returnCode);
                    foreach ($resetOutput as $line) {
                        $sendMessage($line, 'info');
                    }
                    
                    if ($returnCode !== 0) {
                        throw new \Exception("Git reset failed with code {$returnCode}");
                    }
                    
                    $sendMessage("✓ Staging synced with origin/{$currentBranch}", 'success');
                }
                
                // Clear version cache
                VersionHelper::clearCache();

                // Step 3: Install/Update Composer dependencies
                $sendMessage("\n=== Updating Composer Dependencies ===", 'header');
                $composerOutput = $this->executeCommand('composer install --no-dev --optimize-autoloader --no-interaction', $returnCode);
                foreach ($composerOutput as $line) {
                    if (!empty(trim($line))) {
                        $sendMessage($line, 'info');
                    }
                }

                // Step 4: Publish plugin assets
                $sendMessage("\n=== Publishing Plugin Assets to Vendor ===", 'header');
                $this->publishPluginAssets($sendMessage);
                
                // Step 4b: Publish CMS assets
                $sendMessage("\n=== Publishing CMS Assets ===", 'header');
                try {
                    Artisan::call('cms:publish:assets');
                    $sendMessage("✓ CMS assets published via artisan", 'success');
                } catch (\Exception $e) {
                    $sendMessage("⚠ CMS publish command skipped: " . $e->getMessage(), 'warning');
                }

                // Step 4c: Publish theme assets
                $sendMessage("\n=== Publishing Theme Assets ===", 'header');
                try {
                    Artisan::call('cms:theme:assets:publish', ['name' => 'martfury']);
                    $sendMessage("✓ Theme assets published via artisan", 'success');
                } catch (\Throwable $e) {
                    try {
                        $source = base_path('platform/themes/martfury/public');
                        $dest   = public_path('themes/martfury');
                        if (File::isDirectory($source)) {
                            File::ensureDirectoryExists($dest);
                            File::copyDirectory($source, $dest);
                            $sendMessage("✓ Theme assets published via direct copy", 'success');
                        } else {
                            $sendMessage("⚠ Theme source directory not found, skipping", 'warning');
                        }
                    } catch (\Throwable $e2) {
                        $sendMessage("⚠ Theme asset publish failed: " . $e2->getMessage(), 'warning');
                    }
                }

                // Step 5: Run database migrations
                $sendMessage("\n=== Running Database Migrations ===", 'header');
                try {
                    Artisan::call('migrate', ['--force' => true]);
                    $migrationOutput = Artisan::output();
                    if (!empty(trim($migrationOutput))) {
                        foreach (explode("\n", $migrationOutput) as $line) {
                            if (!empty(trim($line))) {
                                $sendMessage($line, 'info');
                            }
                        }
                    }
                    $sendMessage("✓ Migrations completed", 'success');
                } catch (\Exception $e) {
                    if (str_contains($e->getMessage(), 'already exists')) {
                        $sendMessage("⚠ Migrations skipped (tables already exist)", 'warning');
                    } else {
                        throw $e;
                    }
                }

                // Step 6: Publish vendor assets
                $sendMessage("\n=== Publishing Vendor Assets ===", 'header');
                Artisan::call('vendor:publish', ['--tag' => 'public', '--force' => true]);
                $sendMessage("✓ Vendor assets published", 'success');

                // Step 7: Clear all caches
                $sendMessage("\n=== Clearing All Caches ===", 'header');
                $this->clearAllCachesWithProgress($sendMessage);
                $sendMessage("✓ All caches cleared", 'success');

                // Step 8: Optimize application
                $sendMessage("\n=== Optimizing Application ===", 'header');
                $this->optimizeApplicationWithProgress($sendMessage);
                $sendMessage("✓ Application optimized", 'success');

                // Step 9: Take the site out of maintenance mode
                $sendMessage("\n=== Disabling Maintenance Mode ===", 'header');
                Artisan::call('up');
                $sendMessage("✓ Maintenance mode disabled", 'success');

                $sendMessage("\n========================================", 'success');
                $sendMessage("✓✓✓ UPDATE COMPLETED SUCCESSFULLY ✓✓✓", 'success');
                $sendMessage("========================================", 'success');

                // Send completion signal
                echo "data: " . json_encode(['type' => 'complete', 'success' => true]) . "\n\n";
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

            } catch (\Exception $e) {
                // Make sure to take the site out of maintenance mode on error
                Artisan::call('up');

                Log::error('System update failed: ' . $e->getMessage());

                $sendMessage("\n========================================", 'error');
                $sendMessage("❌ UPDATE FAILED!", 'error');
                $sendMessage("Error: " . $e->getMessage(), 'error');
                $sendMessage("========================================", 'error');

                // Send completion signal with error
                echo "data: " . json_encode([
                    'type' => 'complete',
                    'success' => false,
                    'error' => $e->getMessage()
                ]) . "\n\n";
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no', // Disable nginx buffering
            'Connection' => 'keep-alive',
        ]);
    }

    /**
     * Check for available updates from GitHub
     */
    public function checkUpdates()
    {
        try {
            $currentBranch = VersionHelper::getCurrentBranch();
            $isProduction = VersionHelper::isProduction(); // Use helper method which checks both branch and detached HEAD on tag
            
            if ($isProduction) {
                // Production: Check for new tags
                $output = $this->executeCommand('git fetch --tags', $returnCode);
                
                if ($returnCode !== 0) {
                    throw new \Exception("Failed to fetch tags from GitHub");
                }
                
                // Clear version cache to get fresh data
                VersionHelper::clearCache();
                
                $currentVersion = VersionHelper::getCurrentVersion();
                $tags = VersionHelper::getAllTags();
                $latestTag = $tags[0] ?? null;
                
                $hasUpdates = $latestTag && ($latestTag !== $currentVersion);
                
                $changesList = [];
                if ($hasUpdates) {
                    // Get tags between current and latest
                    $changesList = array_slice($tags, 0, array_search($currentVersion, $tags) ?: 3);
                }
                
                return response()->json([
                    'success' => true,
                    'environment' => 'production',
                    'deployment_method' => 'tag-based',
                    'has_updates' => $hasUpdates,
                    'current_version' => $currentVersion,
                    'latest_version' => $latestTag ?? $currentVersion,
                    'available_tags' => array_slice($tags, 0, 5),
                    'changes' => $changesList
                ]);
                
            } else {
                // Staging: Check for new commits
                $output = $this->executeCommand("git fetch origin {$currentBranch}", $returnCode);
                
                if ($returnCode !== 0) {
                    throw new \Exception("Failed to fetch from GitHub");
                }

                $localCommit = $this->executeCommand('git rev-parse HEAD', $returnCode)[0] ?? '';
                $remoteCommit = $this->executeCommand("git rev-parse origin/{$currentBranch}", $returnCode)[0] ?? '';

                $hasUpdates = trim($localCommit) !== trim($remoteCommit);
                
                $commitLog = [];
                if ($hasUpdates) {
                    $commitLog = $this->executeCommand("git log HEAD..origin/{$currentBranch} --oneline", $returnCode);
                }

                return response()->json([
                    'success' => true,
                    'environment' => 'staging',
                    'deployment_method' => 'branch-based',
                    'has_updates' => $hasUpdates,
                    'current_commit' => substr(trim($localCommit), 0, 7),
                    'latest_commit' => substr(trim($remoteCommit), 0, 7),
                    'branch' => $currentBranch,
                    'changes' => $commitLog
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check updates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all application caches
     */
    private function clearAllCaches()
    {
        // Clear application cache
        Artisan::call('cache:clear');
        
        // Clear config cache
        Artisan::call('config:clear');
        
        // Clear route cache
        Artisan::call('route:clear');
        
        // Clear view cache
        Artisan::call('view:clear');
        
        // Clear compiled classes
        Artisan::call('clear-compiled');
        
        // Clear event cache
        Artisan::call('event:clear');
        
        // Clear CMS cache (Botble specific)
        if (class_exists('\Botble\Base\Facades\BaseHelper')) {
            try {
                Artisan::call('cms:cache:clear');
            } catch (\Exception $e) {
                // Ignore if command doesn't exist
            }
        }

        // Clear file cache manually
        $cacheDirectories = [
            storage_path('framework/cache/data'),
            storage_path('framework/views'),
            storage_path('framework/sessions'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        foreach ($cacheDirectories as $directory) {
            if (File::exists($directory)) {
                $files = File::allFiles($directory);
                foreach ($files as $file) {
                    if ($file->getFilename() !== '.gitignore') {
                        try {
                            File::delete($file->getRealPath());
                        } catch (\Exception $e) {
                            // Continue even if some files can't be deleted
                        }
                    }
                }
            }
        }

        // Clear PHP OpCache if available
        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        // Clear APCu cache if available
        if (function_exists('apcu_clear_cache')) {
            apcu_clear_cache();
        }
    }

    /**
     * Clear all application caches with progress messages
     */
    private function clearAllCachesWithProgress(callable $sendMessage)
    {
        // Clear application cache
        $sendMessage("→ Clearing application cache...", 'info');
        Artisan::call('cache:clear');
        $sendMessage("✓ Application cache cleared", 'success');
        
        // Clear config cache
        $sendMessage("→ Clearing config cache...", 'info');
        Artisan::call('config:clear');
        $sendMessage("✓ Config cache cleared", 'success');
        
        // Clear route cache
        $sendMessage("→ Clearing route cache...", 'info');
        Artisan::call('route:clear');
        $sendMessage("✓ Route cache cleared", 'success');
        
        // Clear view cache
        $sendMessage("→ Clearing view cache...", 'info');
        Artisan::call('view:clear');
        $sendMessage("✓ View cache cleared", 'success');
        
        // Clear compiled classes
        $sendMessage("→ Clearing compiled classes...", 'info');
        Artisan::call('clear-compiled');
        $sendMessage("✓ Compiled classes cleared", 'success');
        
        // Clear event cache
        $sendMessage("→ Clearing event cache...", 'info');
        Artisan::call('event:clear');
        $sendMessage("✓ Event cache cleared", 'success');
        
        // Clear CMS cache (Botble specific)
        if (class_exists('\Botble\Base\Facades\BaseHelper')) {
            try {
                $sendMessage("→ Clearing CMS cache...", 'info');
                Artisan::call('cms:cache:clear');
                $sendMessage("✓ CMS cache cleared", 'success');
            } catch (\Exception $e) {
                $sendMessage("⚠ CMS cache not available", 'warning');
            }
        }

        // Clear file cache manually
        $sendMessage("→ Clearing file caches...", 'info');
        $cacheDirectories = [
            'framework/cache/data' => storage_path('framework/cache/data'),
            'framework/views' => storage_path('framework/views'),
            'framework/sessions' => storage_path('framework/sessions'),
            'bootstrap/cache' => base_path('bootstrap/cache'),
        ];

        $clearedCount = 0;
        foreach ($cacheDirectories as $name => $directory) {
            if (File::exists($directory)) {
                $files = File::allFiles($directory);
                foreach ($files as $file) {
                    if ($file->getFilename() !== '.gitignore') {
                        try {
                            File::delete($file->getRealPath());
                            $clearedCount++;
                        } catch (\Exception $e) {
                            // Continue even if some files can't be deleted
                        }
                    }
                }
            }
        }
        $sendMessage("✓ Cleared {$clearedCount} cache files", 'success');

        // Clear PHP OpCache if available
        if (function_exists('opcache_reset')) {
            $sendMessage("→ Resetting OpCache...", 'info');
            opcache_reset();
            $sendMessage("✓ OpCache reset", 'success');
        }

        // Clear APCu cache if available
        if (function_exists('apcu_clear_cache')) {
            $sendMessage("→ Clearing APCu cache...", 'info');
            apcu_clear_cache();
            $sendMessage("✓ APCu cache cleared", 'success');
        }
    }

    /**
     * Optimize the application
     */
    private function optimizeApplication()
    {
        // Cache config
        Artisan::call('config:cache');
        
        // Cache routes (only in production)
        if (app()->environment('production')) {
            Artisan::call('route:cache');
        }
        
        // Cache views
        Artisan::call('view:cache');
        
        // Optimize autoloader
        Artisan::call('optimize');
        
        // Cache events
        Artisan::call('event:cache');
    }

    /**
     * Optimize the application with progress messages
     */
    private function optimizeApplicationWithProgress(callable $sendMessage)
    {
        // Cache config
        $sendMessage("→ Caching configuration...", 'info');
        Artisan::call('config:cache');
        $sendMessage("✓ Configuration cached", 'success');
        
        // Cache routes (only in production)
        if (app()->environment('production')) {
            $sendMessage("→ Caching routes...", 'info');
            Artisan::call('route:cache');
            $sendMessage("✓ Routes cached", 'success');
        }
        
        // Cache views
        $sendMessage("→ Caching views...", 'info');
        Artisan::call('view:cache');
        $sendMessage("✓ Views cached", 'success');
        
        // Optimize autoloader
        $sendMessage("→ Optimizing autoloader...", 'info');
        Artisan::call('optimize');
        $sendMessage("✓ Autoloader optimized", 'success');
        
        // Cache events
        $sendMessage("→ Caching events...", 'info');
        Artisan::call('event:cache');
        $sendMessage("✓ Events cached", 'success');
    }

    /**
     * Execute a shell command and return output
     */
    private function executeCommand(string $command, &$returnCode = 0): array
    {
        // Use Laravel Process facade instead of exec()
        $result = Process::path(base_path())->run($command);
        
        $returnCode = $result->exitCode();
        
        // Combine stdout and stderr to capture all output
        $output = array_filter(explode("\n", $result->output() . "\n" . $result->errorOutput()));
        
        return $output;
    }

    /**
     * Publish ALL plugin/core compiled assets to public/vendor directory
     * This copies everything from platform directories to public/vendor
     */
    private function publishPluginAssets(callable $sendMessage)
    {
        $sources = [
            // Core
            'platform/core/base/public' => 'public/vendor/core/core/base',
            'platform/core/media/public' => 'public/vendor/core/core/media',
            'platform/core/table/public' => 'public/vendor/core/core/table',
            'platform/core/dashboard/public' => 'public/vendor/core/core/dashboard',
            'platform/core/setting/public' => 'public/vendor/core/core/setting',
            
            // Packages
            'platform/packages/menu/public' => 'public/vendor/core/packages/menu',
            'platform/packages/page/public' => 'public/vendor/core/packages/page',
            'platform/packages/plugin-management/public' => 'public/vendor/core/packages/plugin-management',
            'platform/packages/shortcode/public' => 'public/vendor/core/packages/shortcode',
            'platform/packages/theme/public' => 'public/vendor/core/packages/theme',
            'platform/packages/get-started/public' => 'public/vendor/core/packages/get-started',
            
            // Plugins
            'platform/plugins/ecommerce/public' => 'public/vendor/core/plugins/ecommerce',
            'platform/plugins/contact/public' => 'public/vendor/core/plugins/contact',
            'platform/plugins/newsletter/public' => 'public/vendor/core/plugins/newsletter',
            'platform/plugins/payment/public' => 'public/vendor/core/plugins/payment',
            'platform/plugins/marketplace/public' => 'public/vendor/core/plugins/marketplace',
            'platform/plugins/blog/public' => 'public/vendor/core/plugins/blog',
        ];

        $totalFiles = 0;
        $copiedFiles = 0;

        foreach ($sources as $source => $dest) {
            $sourcePath = base_path($source);
            $destPath = base_path($dest);
            
            if (!File::isDirectory($sourcePath)) {
                continue; // Skip if source doesn't exist
            }
            
            // Create destination directory
            File::ensureDirectoryExists($destPath);
            
            // Copy entire directory recursively
            try {
                File::copyDirectory($sourcePath, $destPath);
                
                // Count files
                $files = File::allFiles($destPath);
                $fileCount = count($files);
                $totalFiles += $fileCount;
                $copiedFiles += $fileCount;
                
                $pluginName = basename(dirname($source)) . '/' . basename(dirname(dirname($source)));
                $sendMessage("✓ Published {$pluginName}: {$fileCount} files", 'success');
                
            } catch (\Exception $e) {
                $sendMessage("✗ Failed to publish " . basename(dirname($source)), 'error');
            }
        }

        if ($copiedFiles > 0) {
            $sendMessage("✓ Total assets published: {$copiedFiles} files", 'success');
        } else {
            $sendMessage("⚠ No plugin assets found to publish", 'warning');
        }
    }

    /**
     * Clear cache only (without full update)
     */
    public function clearCacheOnly()
    {
        try {
            $output = [];
            
            $output[] = "=== Clearing All Caches ===";
            $this->clearAllCaches();
            $output[] = "✓ All caches cleared";
            
            $output[] = "\n=== Optimizing Application ===";
            $this->optimizeApplication();
            $output[] = "✓ Application optimized";
            
            $output[] = "\n✓✓✓ CACHE CLEARING COMPLETED ✓✓✓";

            return response()->json([
                'success' => true,
                'message' => 'Cache cleared successfully!',
                'output' => implode("\n", $output)
            ]);

        } catch (\Exception $e) {
            Log::error('Cache clearing failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Cache clearing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current version info
     */
    public function getVersion()
    {
        try {
            $version = VersionHelper::getCurrentVersion();
            $branch = VersionHelper::getCurrentBranch();
            $commit = VersionHelper::getCurrentCommit();
            $environment = VersionHelper::getEnvironment();
            $lastUpdate = VersionHelper::getLastUpdateTime();
            
            $response = [
                'success' => true,
                'version' => [
                    'current' => $version,
                    'branch' => $branch,
                    'commit' => $commit,
                    'environment' => $environment,
                    'last_update' => $lastUpdate,
                    'cache_bust' => VersionHelper::getCacheBustVersion(),
                ]
            ];
            
            // Add tag info if on production
            if (VersionHelper::isProduction()) {
                $response['version']['tag_info'] = VersionHelper::getTagInfo();
                $response['version']['all_tags'] = array_slice(VersionHelper::getAllTags(), 0, 10);
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get version: ' . $e->getMessage()
            ], 500);
        }
    }
}

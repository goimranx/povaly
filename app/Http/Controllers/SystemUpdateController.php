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

                    // Reset any local changes before checkout (server should never have local edits)
                    $sendMessage("→ Resetting local changes...", 'info');
                    $this->executeCommand('git reset --hard HEAD', $returnCode);

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

                // Step 4: Delete old assets (Nuclear approach)
                $sendMessage("\n=== Removing Old Assets (Nuclear Clean) ===", 'header');
                $this->deleteOldAssets($sendMessage);
                
                // Step 5: Publish ALL assets fresh
                $sendMessage("\n=== Publishing Fresh Assets ===", 'header');
                $this->publishAllAssetsFresh($sendMessage);
                
                // Step 6: Verify assets
                $sendMessage("\n=== Verifying Assets ===", 'header');
                try {
                    $this->verifyAssets($sendMessage);
                    $sendMessage("✓ All critical assets verified", 'success');
                } catch (\Exception $e) {
                    $sendMessage("⚠ Asset verification: " . $e->getMessage(), 'warning');
                }

                // Step 7: Run database migrations
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

                // Step 8: Nuclear cache clearing
                $sendMessage("\n=== Nuclear Cache Clearing ===", 'header');
                $this->nuclearCacheClear($sendMessage);
                $sendMessage("✓ All caches nuked", 'success');

                // Step 9: Rebuild caches
                $sendMessage("\n=== Rebuilding Caches ===", 'header');
                $this->optimizeApplicationWithProgress($sendMessage);
                $sendMessage("✓ Application optimized", 'success');

                // Step 10: Update cache bust version
                $sendMessage("\n=== Updating Asset Version ===", 'header');
                $this->updateAssetVersion($sendMessage);

                // Step 11: Take the site out of maintenance mode
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

    /**
     * NUCLEAR APPROACH: Delete old assets completely before copying new ones
     * This ensures no stale files remain that could cause caching issues
     */
    private function deleteOldAssets(callable $sendMessage)
    {
        $directories = [
            'public/vendor/core/core',
            'public/vendor/core/packages',
            'public/vendor/core/plugins',
            'public/themes/martfury',
        ];
        
        $deletedCount = 0;
        foreach ($directories as $dir) {
            $fullPath = base_path($dir);
            if (File::exists($fullPath)) {
                try {
                    $fileCount = count(File::allFiles($fullPath));
                    File::deleteDirectory($fullPath);
                    $deletedCount += $fileCount;
                    $sendMessage("✓ Deleted: {$dir} ({$fileCount} files)", 'success');
                } catch (\Exception $e) {
                    $sendMessage("⚠ Failed to delete {$dir}: " . $e->getMessage(), 'warning');
                }
            }
        }
        
        $sendMessage("✓ Total files deleted: {$deletedCount}", 'success');
    }

    /**
     * Publish ALL assets fresh using multiple strategies
     * 1. Laravel's vendor:publish system (cms-public tag)
     * 2. Direct copy for themes
     * 3. Verify all critical assets exist
     */
    private function publishAllAssetsFresh(callable $sendMessage)
    {
        // Strategy 1: Laravel vendor:publish system
        $sendMessage("→ Publishing via Laravel service providers (cms-public tag)...", 'info');
        try {
            Artisan::call('vendor:publish', ['--tag' => 'cms-public', '--force' => true]);
            $sendMessage("✓ CMS assets published", 'success');
        } catch (\Exception $e) {
            $sendMessage("⚠ vendor:publish failed: " . $e->getMessage(), 'warning');
        }
        
        // Strategy 2: Direct copy for platform modules
        $sendMessage("\n→ Publishing platform modules directly...", 'info');
        $this->publishPlatformModules($sendMessage);
        
        // Strategy 3: Theme assets
        $sendMessage("\n→ Publishing theme assets...", 'info');
        $this->publishThemeAssets($sendMessage);
        
        // Strategy 4: Additional vendor publish tags
        $sendMessage("\n→ Publishing additional vendor assets...", 'info');
        try {
            Artisan::call('vendor:publish', ['--tag' => 'public', '--force' => true]);
            $sendMessage("✓ Additional vendor assets published", 'success');
        } catch (\Exception $e) {
            $sendMessage("⚠ Additional publish skipped", 'warning');
        }
    }

    /**
     * Publish platform modules (core, packages, plugins)
     * Direct copy approach for maximum reliability
     */
    private function publishPlatformModules(callable $sendMessage)
    {
        $sources = [
            // Core modules
            'platform/core' => 'public/vendor/core/core',
            
            // Packages
            'platform/packages' => 'public/vendor/core/packages',
            
            // Plugins
            'platform/plugins' => 'public/vendor/core/plugins',
        ];

        $totalFiles = 0;

        foreach ($sources as $sourceBase => $destBase) {
            $sourcePath = base_path($sourceBase);
            
            if (!File::isDirectory($sourcePath)) {
                continue;
            }
            
            // Get all module directories
            $modules = File::directories($sourcePath);
            
            foreach ($modules as $modulePath) {
                $moduleName = basename($modulePath);
                $modulePublicPath = $modulePath . '/public';
                
                if (!File::isDirectory($modulePublicPath)) {
                    continue;
                }
                
                $destPath = base_path($destBase . '/' . $moduleName);
                
                try {
                    File::ensureDirectoryExists($destPath);
                    File::copyDirectory($modulePublicPath, $destPath);
                    
                    $fileCount = count(File::allFiles($destPath));
                    $totalFiles += $fileCount;
                    
                    $relativeSource = str_replace(base_path() . '/', '', $modulePath);
                    $sendMessage("✓ Published {$relativeSource}: {$fileCount} files", 'success');
                    
                } catch (\Exception $e) {
                    $sendMessage("✗ Failed to publish {$moduleName}: " . $e->getMessage(), 'error');
                }
            }
        }

        if ($totalFiles > 0) {
            $sendMessage("✓ Total platform assets published: {$totalFiles} files", 'success');
        }
    }

    /**
     * Publish theme assets
     */
    private function publishThemeAssets(callable $sendMessage)
    {
        try {
            Artisan::call('cms:theme:assets:publish', ['name' => 'martfury']);
            $sendMessage("✓ Theme assets published via artisan", 'success');
        } catch (\Throwable $e) {
            // Fallback to manual copy
            try {
                $source = base_path('platform/themes/martfury/public');
                $dest = public_path('themes/martfury');
                
                if (File::isDirectory($source)) {
                    File::ensureDirectoryExists($dest);
                    File::copyDirectory($source, $dest);
                    
                    $fileCount = count(File::allFiles($dest));
                    $sendMessage("✓ Theme assets published via direct copy: {$fileCount} files", 'success');
                } else {
                    $sendMessage("⚠ Theme source directory not found", 'warning');
                }
            } catch (\Throwable $e2) {
                $sendMessage("✗ Theme asset publish failed: " . $e2->getMessage(), 'error');
            }
        }
    }

    /**
     * Verify critical assets were published correctly
     */
    private function verifyAssets(callable $sendMessage)
    {
        $criticalFiles = [
            'public/vendor/core/packages/theme/js/toast.js',
            'public/vendor/core/plugins/ecommerce/js/front-ecommerce.js',
            'public/vendor/core/plugins/ecommerce/js/pv-gallery.js',
            'public/themes/martfury/js/main.js',
            'public/themes/martfury/js/product-card-enhanced.js',
            'public/themes/martfury/css/style.css',
        ];
        
        $verified = 0;
        $missing = 0;
        
        foreach ($criticalFiles as $file) {
            $fullPath = base_path($file);
            if (File::exists($fullPath)) {
                $size = File::size($fullPath);
                if ($size > 100) { // At least 100 bytes
                    $verified++;
                    $sendMessage("✓ Verified: {$file} ({$size} bytes)", 'success');
                } else {
                    $missing++;
                    $sendMessage("⚠ File too small: {$file} ({$size} bytes)", 'warning');
                }
            } else {
                $missing++;
                $sendMessage("⚠ Missing: {$file}", 'warning');
            }
        }
        
        $sendMessage("Verified: {$verified}/{" . count($criticalFiles) . "} critical assets", 'info');
        
        if ($missing > 0) {
            throw new \Exception("{$missing} critical asset(s) missing or corrupted");
        }
    }

    /**
     * NUCLEAR CACHE CLEARING
     * Clear ALL caches at every level: Laravel, PHP, File system
     */
    private function nuclearCacheClear(callable $sendMessage)
    {
        // 1. Laravel Artisan caches
        $commands = [
            'cache:clear' => 'Application cache',
            'config:clear' => 'Config cache',
            'route:clear' => 'Route cache',
            'view:clear' => 'View cache',
            'event:clear' => 'Event cache',
            'clear-compiled' => 'Compiled classes',
        ];
        
        foreach ($commands as $command => $label) {
            try {
                $sendMessage("→ Clearing {$label}...", 'info');
                Artisan::call($command);
                $sendMessage("✓ {$label} cleared", 'success');
            } catch (\Exception $e) {
                $sendMessage("⚠ {$label} clear failed", 'warning');
            }
        }
        
        // 2. CMS-specific cache
        try {
            if (class_exists('\Botble\Base\Facades\BaseHelper')) {
                $sendMessage("→ Clearing CMS cache...", 'info');
                Artisan::call('cms:cache:clear');
                $sendMessage("✓ CMS cache cleared", 'success');
            }
        } catch (\Exception $e) {
            $sendMessage("⚠ CMS cache not available", 'warning');
        }
        
        // 3. Physical file deletion
        $sendMessage("\n→ Deleting physical cache files...", 'info');
        $cacheDirectories = [
            'storage/framework/cache/data',
            'storage/framework/views',
            'storage/framework/sessions',
        ];
        
        $deletedFiles = 0;
        foreach ($cacheDirectories as $dir) {
            $fullPath = base_path($dir);
            if (File::exists($fullPath)) {
                try {
                    $files = File::allFiles($fullPath);
                    foreach ($files as $file) {
                        if ($file->getFilename() !== '.gitignore') {
                            File::delete($file->getRealPath());
                            $deletedFiles++;
                        }
                    }
                } catch (\Exception $e) {
                    // Continue even if some files can't be deleted
                }
            }
        }
        $sendMessage("✓ Deleted {$deletedFiles} cache files", 'success');
        
        // 4. Bootstrap cache files
        $sendMessage("→ Clearing bootstrap cache...", 'info');
        $bootstrapFiles = [
            'bootstrap/cache/config.php',
            'bootstrap/cache/routes-v7.php',
            'bootstrap/cache/events.php',
            'bootstrap/cache/packages.php',
            'bootstrap/cache/services.php',
        ];
        
        $deletedBootstrap = 0;
        foreach ($bootstrapFiles as $file) {
            $fullPath = base_path($file);
            if (File::exists($fullPath)) {
                try {
                    File::delete($fullPath);
                    $deletedBootstrap++;
                } catch (\Exception $e) {
                    // Continue
                }
            }
        }
        $sendMessage("✓ Deleted {$deletedBootstrap} bootstrap cache files", 'success');
        
        // 5. PHP OpCache
        if (function_exists('opcache_reset')) {
            $sendMessage("→ Resetting PHP OpCache...", 'info');
            try {
                opcache_reset();
                $sendMessage("✓ OpCache reset", 'success');
            } catch (\Exception $e) {
                $sendMessage("⚠ OpCache reset failed", 'warning');
            }
        }
        
        // 6. APCu cache
        if (function_exists('apcu_clear_cache')) {
            $sendMessage("→ Clearing APCu cache...", 'info');
            try {
                apcu_clear_cache();
                $sendMessage("✓ APCu cache cleared", 'success');
            } catch (\Exception $e) {
                $sendMessage("⚠ APCu clear failed", 'warning');
            }
        }
        
        // 7. Clear version cache
        VersionHelper::clearCache();
        $sendMessage("✓ Version cache cleared", 'success');
    }

    /**
     * Update asset version for cache busting
     * Stores current Git commit hash in config
     */
    private function updateAssetVersion(callable $sendMessage)
    {
        $version = VersionHelper::getCurrentCommit();
        $timestamp = now()->timestamp;
        
        // Store in cache
        Cache::forever('asset_version', $version);
        Cache::forever('asset_version_timestamp', $timestamp);
        
        $sendMessage("✓ Asset version updated: {$version}", 'success');
        $sendMessage("  This will force browsers to reload all assets", 'info');
    }
}

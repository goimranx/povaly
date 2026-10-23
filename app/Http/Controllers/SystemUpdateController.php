<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Process;

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
     */
    public function update(Request $request)
    {
        try {
            $output = [];
            $returnCode = 0;

            // Step 1: Put the site in maintenance mode
            $output[] = "=== Enabling Maintenance Mode ===";
            Artisan::call('down', ['--render' => 'errors::503']);
            $output[] = "✓ Maintenance mode enabled";

            // Step 2: Reset local changes and pull from GitHub
            $output[] = "\n=== Resetting Local Changes ===";
            $this->executeCommand('git reset --hard HEAD', $returnCode);
            $this->executeCommand('git clean -fd', $returnCode);
            $output[] = "✓ Local changes reset";
            
            $output[] = "\n=== Pulling from GitHub ===";
            $gitOutput = $this->executeCommand('git pull origin ' . env('GITHUB_BRANCH', 'main'), $returnCode);
            $output = array_merge($output, $gitOutput);
            
            if ($returnCode !== 0) {
                throw new \Exception("Git pull failed with code {$returnCode}");
            }

            // Step 3: Install/Update Composer dependencies
            $output[] = "\n=== Updating Composer Dependencies ===";
            $composerOutput = $this->executeCommand('composer install --no-dev --optimize-autoloader --no-interaction', $returnCode);
            $output = array_merge($output, $composerOutput);

            // Step 4: Publish ALL plugin assets to vendor directory
            // Note: Assets are pre-compiled locally and committed to git
            // Server just copies from platform/*/public/ to public/vendor/
            $output[] = "\n=== Publishing Plugin Assets to Vendor ===";
            $this->publishPluginAssets($output);

            // Step 5: Clear all caches
            $output[] = "\n=== Clearing All Caches ===";
            $this->clearAllCaches();
            $output[] = "✓ All caches cleared";

            // Step 6: Run database migrations (skip errors if tables already exist)
            $output[] = "\n=== Running Database Migrations ===";
            try {
                Artisan::call('migrate', ['--force' => true]);
                $migrationOutput = Artisan::output();
                if (!empty(trim($migrationOutput))) {
                    $output[] = $migrationOutput;
                }
                $output[] = "✓ Migrations completed";
            } catch (\Exception $e) {
                // If migration fails due to existing tables, just note it and continue
                if (str_contains($e->getMessage(), 'already exists')) {
                    $output[] = "⚠ Migrations skipped (tables already exist)";
                } else {
                    // Re-throw if it's a different error
                    throw $e;
                }
            }

            // Step 7: Publish assets
            $output[] = "\n=== Publishing Vendor Assets ===";
            Artisan::call('vendor:publish', ['--tag' => 'public', '--force' => true]);
            $output[] = "✓ Vendor assets published";

            // Step 7b: Publish theme assets (copies platform/themes/*/public/ → public/themes/)
            // Note: our custom CSS is already tracked in git so the pull handles it.
            // This step copies everything else (images, fonts, js) from the theme public folder.
            $output[] = "\n=== Publishing Theme Assets ===";
            try {
                Artisan::call('cms:theme:assets:publish');
                $output[] = "✓ Theme assets published via artisan";
            } catch (\Throwable $e) {
                // Fallback: copy manually if artisan command not registered
                try {
                    $source = base_path('platform/themes/martfury/public');
                    $dest   = public_path('themes/martfury');
                    if (File::isDirectory($source)) {
                        File::ensureDirectoryExists($dest);
                        File::copyDirectory($source, $dest);
                        $output[] = "✓ Theme assets published via direct copy";
                    } else {
                        $output[] = "⚠ Theme source directory not found, skipping";
                    }
                } catch (\Throwable $e2) {
                    // Non-fatal — git pull already updated tracked CSS/JS files
                    $output[] = "⚠ Theme asset publish skipped: " . $e2->getMessage();
                }
            }

            // Step 8: Optimize application
            $output[] = "\n=== Optimizing Application ===";
            $this->optimizeApplication();
            $output[] = "✓ Application optimized";

            // Step 9: Take the site out of maintenance mode
            $output[] = "\n=== Disabling Maintenance Mode ===";
            Artisan::call('up');
            $output[] = "✓ Maintenance mode disabled";

            $output[] = "\n========================================";
            $output[] = "✓✓✓ UPDATE COMPLETED SUCCESSFULLY ✓✓✓";
            $output[] = "========================================";

            return response()->json([
                'success' => true,
                'message' => 'System updated successfully!',
                'output' => implode("\n", $output)
            ]);

        } catch (\Exception $e) {
            // Make sure to take the site out of maintenance mode on error
            Artisan::call('up');

            Log::error('System update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Update failed: ' . $e->getMessage(),
                'output' => implode("\n", $output) . "\n\nERROR: " . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check for available updates from GitHub
     */
    public function checkUpdates()
    {
        try {
            $output = $this->executeCommand('git fetch origin ' . env('GITHUB_BRANCH', 'main'), $returnCode);
            
            if ($returnCode !== 0) {
                throw new \Exception("Failed to fetch from GitHub");
            }

            $localCommit = $this->executeCommand('git rev-parse HEAD', $returnCode)[0] ?? '';
            $remoteCommit = $this->executeCommand('git rev-parse origin/' . env('GITHUB_BRANCH', 'main'), $returnCode)[0] ?? '';

            $hasUpdates = trim($localCommit) !== trim($remoteCommit);
            
            $commitLog = [];
            if ($hasUpdates) {
                $commitLog = $this->executeCommand('git log HEAD..origin/' . env('GITHUB_BRANCH', 'main') . ' --oneline', $returnCode);
            }

            return response()->json([
                'success' => true,
                'has_updates' => $hasUpdates,
                'current_commit' => substr(trim($localCommit), 0, 7),
                'latest_commit' => substr(trim($remoteCommit), 0, 7),
                'changes' => $commitLog
            ]);

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
    private function publishPluginAssets(array &$output)
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
                $output[] = "✓ Published {$pluginName}: {$fileCount} files";
                
            } catch (\Exception $e) {
                $output[] = "✗ Failed to publish " . basename(dirname($source));
            }
        }

        if ($copiedFiles > 0) {
            $output[] = "✓ Total assets published: {$copiedFiles} files";
        } else {
            $output[] = "⚠ No plugin assets found to publish";
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
            $commit = $this->executeCommand('git rev-parse --short HEAD', $returnCode)[0] ?? 'Unknown';
            $branch = $this->executeCommand('git rev-parse --abbrev-ref HEAD', $returnCode)[0] ?? 'Unknown';
            $lastUpdate = $this->executeCommand('git log -1 --format=%cd --date=format:"%Y-%m-%d %H:%M:%S"', $returnCode)[0] ?? 'Unknown';

            return response()->json([
                'success' => true,
                'version' => [
                    'commit' => trim($commit),
                    'branch' => trim($branch),
                    'last_update' => trim($lastUpdate)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get version: ' . $e->getMessage()
            ], 500);
        }
    }
}

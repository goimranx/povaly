<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Process;

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

            // Step 4: Install/Update NPM dependencies
            $output[] = "\n=== Updating NPM Dependencies ===";
            $npmOutput = $this->executeCommand('npm install', $returnCode);
            $output = array_merge($output, $npmOutput);

            // Step 5: Compile assets
            $output[] = "\n=== Compiling Assets ===";
            $buildOutput = $this->executeCommand('npm run prod', $returnCode);
            $output = array_merge($output, $buildOutput);

            // Step 6: Clear all caches
            $output[] = "\n=== Clearing All Caches ===";
            $this->clearAllCaches();
            $output[] = "✓ All caches cleared";

            // Step 7: Run database migrations (skip errors if tables already exist)
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

            // Step 8: Publish assets
            $output[] = "\n=== Publishing Vendor Assets ===";
            Artisan::call('vendor:publish', ['--tag' => 'public', '--force' => true]);
            $output[] = "✓ Vendor assets published";

            // Step 8b: Publish theme assets (copies platform/themes/*/public/ → public/themes/)
            $output[] = "\n=== Publishing Theme Assets ===";
            Artisan::call('cms:theme:assets:publish');
            $output[] = "✓ Theme assets published";

            // Step 9: Optimize application
            $output[] = "\n=== Optimizing Application ===";
            $this->optimizeApplication();
            $output[] = "✓ Application optimized";

            // Step 10: Take the site out of maintenance mode
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
        
        // Split output by newlines and return as array
        $output = array_filter(explode("\n", $result->output()));
        
        return $output;
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

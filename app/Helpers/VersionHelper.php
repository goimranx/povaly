<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Process;

/**
 * Version Helper for Povaly
 * 
 * Manages version information from Git tags for production
 * and branch info for staging/development.
 * 
 * Usage:
 * - Production (povaly.com): Uses Git tags (v1.0.0, v1.1.0...)
 * - Staging (stage.povaly.com): Uses branch commits
 * 
 * Version is used for:
 * - Display in admin panel
 * - Cache busting for assets (CSS/JS)
 * - Deployment tracking
 */
class VersionHelper
{
    /**
     * Get current version from Git tags (production) or branch (staging)
     * 
     * @return string Version string (e.g., "v1.0.0" or "stage-abc1234")
     */
    public static function getCurrentVersion(): string
    {
        return Cache::remember('app_version', 3600, function () {
            try {
                // First, try to get exact tag if we're on one (detached HEAD on tag)
                $exactTag = Process::path(base_path())->run('git describe --exact-match --tags HEAD 2>/dev/null');
                
                if ($exactTag->successful()) {
                    $tag = trim($exactTag->output());
                    if (!empty($tag)) {
                        return $tag; // Return exact tag (e.g., v1.1.0)
                    }
                }
                
                // Check if we're on main branch (production)
                $branch = self::getCurrentBranch();
                
                if ($branch === 'main' || $branch === 'HEAD') {
                    // Production: Get latest Git tag
                    $result = Process::path(base_path())->run('git describe --tags --abbrev=0');
                    
                    if ($result->successful()) {
                        $tag = trim($result->output());
                        return !empty($tag) ? $tag : 'v1.0.0';
                    }
                    
                    return 'v1.0.0'; // Default if no tags
                }
                
                // Staging/Development: Use branch name + commit hash
                $commit = self::getCurrentCommit();
                return $branch . '-' . $commit;
                
            } catch (\Exception $e) {
                return 'unknown';
            }
        });
    }
    
    /**
     * Get current Git branch
     * 
     * @return string Branch name (e.g., "main", "stage", "develop")
     */
    public static function getCurrentBranch(): string
    {
        try {
            $result = Process::path(base_path())->run('git rev-parse --abbrev-ref HEAD');
            
            if ($result->successful()) {
                return trim($result->output());
            }
            
            return 'unknown';
        } catch (\Exception $e) {
            return 'unknown';
        }
    }
    
    /**
     * Get current commit hash (short)
     * 
     * @return string Short commit hash (e.g., "abc1234")
     */
    public static function getCurrentCommit(): string
    {
        try {
            $result = Process::path(base_path())->run('git rev-parse --short HEAD');
            
            if ($result->successful()) {
                return trim($result->output());
            }
            
            return 'unknown';
        } catch (\Exception $e) {
            return 'unknown';
        }
    }
    
    /**
     * Get all available Git tags
     * 
     * @return array List of tags (e.g., ["v1.0.0", "v1.1.0", "v1.2.0"])
     */
    public static function getAllTags(): array
    {
        try {
            $result = Process::path(base_path())->run('git tag --sort=-v:refname');
            
            if ($result->successful()) {
                $tags = array_filter(explode("\n", trim($result->output())));
                return array_values($tags);
            }
            
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get version for cache busting
     * Returns a short hash suitable for asset URLs
     * 
     * @return string Short version string for cache busting (e.g., "1.0.0" or "abc1234")
     */
    public static function getCacheBustVersion(): string
    {
        $version = self::getCurrentVersion();
        
        // Remove 'v' prefix if exists
        $version = ltrim($version, 'v');
        
        // If it contains a dash (stage-abc1234), get part after dash
        if (str_contains($version, '-')) {
            $parts = explode('-', $version);
            return end($parts);
        }
        
        return $version;
    }
    
    /**
     * Get deployment environment based on branch
     * 
     * @return string Environment name ("production", "staging", "development")
     */
    public static function getEnvironment(): string
    {
        $branch = self::getCurrentBranch();
        
        // Check if we're on a production tag (detached HEAD on tag)
        $exactTag = Process::path(base_path())->run('git describe --exact-match --tags HEAD 2>/dev/null');
        if ($exactTag->successful()) {
            return 'production'; // On a tag = production
        }
        
        return match ($branch) {
            'main', 'HEAD' => 'production',
            'stage' => 'staging',
            default => 'development',
        };
    }
    
    /**
     * Check if running on production (main branch)
     * 
     * @return bool True if on main branch
     */
    public static function isProduction(): bool
    {
        $branch = self::getCurrentBranch();
        
        // Check if on a production tag
        $exactTag = Process::path(base_path())->run('git describe --exact-match --tags HEAD 2>/dev/null');
        if ($exactTag->successful()) {
            return true; // On a tag = production
        }
        
        return $branch === 'main' || $branch === 'HEAD';
    }
    
    /**
     * Check if running on staging (stage branch)
     * 
     * @return bool True if on stage branch
     */
    public static function isStaging(): bool
    {
        return self::getCurrentBranch() === 'stage';
    }
    
    /**
     * Clear version cache
     * Call this after deploying a new version
     * 
     * @return void
     */
    public static function clearCache(): void
    {
        Cache::forget('app_version');
    }
    
    /**
     * Get last deployment/update time
     * 
     * @return string Formatted date/time
     */
    public static function getLastUpdateTime(): string
    {
        try {
            $result = Process::path(base_path())->run('git log -1 --format=%cd --date=format:"%Y-%m-%d %H:%M:%S"');
            
            if ($result->successful()) {
                return trim($result->output());
            }
            
            return 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
    
    /**
     * Get tag information (for production deployments)
     * 
     * @param string|null $tag Specific tag to get info for (null = current tag)
     * @return array Tag information
     */
    public static function getTagInfo(?string $tag = null): array
    {
        try {
            if ($tag === null) {
                $tag = self::getCurrentVersion();
            }
            
            // Get tag message
            $messageResult = Process::path(base_path())->run("git tag -l --format='%(contents)' {$tag}");
            $message = $messageResult->successful() ? trim($messageResult->output()) : '';
            
            // Get tag date
            $dateResult = Process::path(base_path())->run("git log -1 --format=%cd --date=format:'%Y-%m-%d %H:%M:%S' {$tag}");
            $date = $dateResult->successful() ? trim($dateResult->output()) : '';
            
            return [
                'tag' => $tag,
                'message' => $message,
                'date' => $date,
            ];
            
        } catch (\Exception $e) {
            return [
                'tag' => $tag ?? 'unknown',
                'message' => '',
                'date' => '',
            ];
        }
    }
}

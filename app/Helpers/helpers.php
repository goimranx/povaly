<?php

use App\Helpers\VersionHelper;

if (!function_exists('app_version')) {
    /**
     * Get current application version
     * 
     * @return string Version string (e.g., "v1.0.0" or "stage-abc1234")
     */
    function app_version(): string
    {
        return VersionHelper::getCurrentVersion();
    }
}

if (!function_exists('cache_bust')) {
    /**
     * Get version string for cache busting assets
     * 
     * Usage in views:
     * <link href="{{ asset('css/style.css') }}?v={{ cache_bust() }}" rel="stylesheet">
     * <script src="{{ asset('js/app.js') }}?v={{ cache_bust() }}"></script>
     * 
     * @return string Short version string for cache busting
     */
    function cache_bust(): string
    {
        return VersionHelper::getCacheBustVersion();
    }
}

if (!function_exists('asset_v')) {
    /**
     * Generate asset URL with automatic cache busting
     * 
     * Usage in views:
     * <link href="{{ asset_v('css/style.css') }}" rel="stylesheet">
     * <script src="{{ asset_v('js/app.js') }}"></script>
     * 
     * @param string $path Asset path
     * @return string Asset URL with version parameter
     */
    function asset_v(string $path): string
    {
        return asset($path) . '?v=' . cache_bust();
    }
}

if (!function_exists('is_production_env')) {
    /**
     * Check if running on production (main branch)
     * 
     * @return bool True if on main branch
     */
    function is_production_env(): bool
    {
        return VersionHelper::isProduction();
    }
}

if (!function_exists('is_staging_env')) {
    /**
     * Check if running on staging (stage branch)
     * 
     * @return bool True if on stage branch
     */
    function is_staging_env(): bool
    {
        return VersionHelper::isStaging();
    }
}

if (!function_exists('deployment_environment')) {
    /**
     * Get deployment environment name
     * 
     * @return string Environment name ("production", "staging", "development")
     */
    function deployment_environment(): string
    {
        return VersionHelper::getEnvironment();
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class SystemUpdateServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add menu item to admin panel under Tools menu
        if (class_exists('\Botble\Base\Facades\DashboardMenu')) {
            \Botble\Base\Facades\DashboardMenu::default()->beforeRetrieving(function () {
                \Botble\Base\Facades\DashboardMenu::make()
                    ->registerItem(
                        \Botble\Base\Supports\DashboardMenuItem::make()
                            ->id('cms-core-system-update')
                            ->priority(100)
                            ->parentId('cms-core-tools')
                            ->name('System Update')
                            ->icon('ti ti-refresh')
                            ->route('system.update.index')
                    );
            });
        }
    }
}

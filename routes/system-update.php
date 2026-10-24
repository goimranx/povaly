<?php

use App\Http\Controllers\SystemUpdateController;
use Illuminate\Support\Facades\Route;

// System Update Routes (Admin Only)
Route::prefix(config('core.base.general.admin_dir', 'admin'))->middleware(['web', 'core'])->group(function () {
    Route::prefix('system-update')->name('system.update.')->group(function () {
        Route::get('/', [SystemUpdateController::class, 'index'])->name('index');
        Route::post('/perform', [SystemUpdateController::class, 'update'])->name('perform');
        Route::get('/check', [SystemUpdateController::class, 'checkUpdates'])->name('check');
        Route::get('/version', [SystemUpdateController::class, 'getVersion'])->name('version');
        Route::post('/clear-cache', [SystemUpdateController::class, 'clearCacheOnly'])->name('clear-cache');
    });
});

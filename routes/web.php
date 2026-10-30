<?php

use App\Http\Controllers\CartCheckController;
use App\Http\Controllers\SystemUpdateController;
use App\Http\Controllers\VideoLikeController;
use App\Http\Controllers\VideoShareController;
use Illuminate\Support\Facades\Route;

// Video Likes API Routes
Route::prefix('api')->middleware(['web'])->group(function () {
    Route::get('/video-likes/{videoId}', [VideoLikeController::class, 'show'])->name('api.video-likes.show');
    Route::post('/video-likes', [VideoLikeController::class, 'store'])->name('api.video-likes.store');
    Route::delete('/video-likes/{videoId}', [VideoLikeController::class, 'destroy'])->name('api.video-likes.destroy');
    
    // Video Shares API Routes
    Route::get('/video-shares/{videoId}', [VideoShareController::class, 'show'])->name('api.video-shares.show');
    Route::post('/video-shares', [VideoShareController::class, 'store'])->name('api.video-shares.store');
    
    // Cart Check API Routes
    Route::get('/cart/check/{productId}', [CartCheckController::class, 'check'])->name('api.cart.check');
});

// System Update Routes (Admin Only)
Route::prefix(config('core.base.general.admin_dir', 'pv-admin'))->middleware(['web', 'core'])->group(function () {
    Route::prefix('system-update')->name('system.update.')->group(function () {
        Route::get('/', [SystemUpdateController::class, 'index'])->name('index')->middleware('auth');
        Route::post('/perform', [SystemUpdateController::class, 'update'])->name('perform')->middleware('auth');
        Route::get('/check', [SystemUpdateController::class, 'checkUpdates'])->name('check')->middleware('auth');
        Route::get('/version', [SystemUpdateController::class, 'getVersion'])->name('version')->middleware('auth');
        Route::post('/clear-cache', [SystemUpdateController::class, 'clearCacheOnly'])->name('clear-cache')->middleware('auth');
    });
});


// Location Session Routes
Route::post('/ajax/save-temporary-location', [App\Http\Controllers\LocationSessionController::class, 'saveTemporaryLocation'])
    ->name('ajax.save-temporary-location');

Route::get('/ajax/get-delivery-display', [App\Http\Controllers\LocationSessionController::class, 'getDeliveryDisplay'])
    ->name('ajax.get-delivery-display');

Route::post('/ajax/clear-temporary-location', [App\Http\Controllers\LocationSessionController::class, 'clearTemporaryLocation'])
    ->name('ajax.clear-temporary-location');

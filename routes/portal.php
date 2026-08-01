<?php

use App\Http\Controllers\Portal\PortalAuthController;
use App\Http\Controllers\Portal\PortalController;
use Illuminate\Support\Facades\Route;

/**
 * ملاحظة تسجيل: أضف هذا الملف داخل bootstrap/app.php (Laravel 11) ضمن withRouting():
 *
 *     ->withRouting(
 *         web: __DIR__.'/../routes/web.php',
 *         ...
 *         then: function () {
 *             Route::middleware('web')->group(base_path('routes/portal.php'));
 *         },
 *     )
 *
 * أو ببساطة أضف محتوى هذا الملف كـ require داخل routes/web.php نفسه إن كنت تفضّل مسارًا واحدًا فقط.
 */

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('login', [PortalAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [PortalAuthController::class, 'login']);
    Route::post('logout', [PortalAuthController::class, 'logout'])->name('logout');

    Route::middleware('auth:customer')->group(function () {
        Route::get('dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::post('orders/{order}/reorder', [PortalController::class, 'reorder'])->name('reorder');
    });
});

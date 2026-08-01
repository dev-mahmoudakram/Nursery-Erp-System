<?php

use App\Http\Controllers\Storefront\ReviewController;
use App\Http\Controllers\Storefront\StorefrontController;
use Illuminate\Support\Facades\Route;

/**
 * ملاحظة تسجيل: أضف هذا الملف بنفس طريقة routes/portal.php داخل bootstrap/app.php،
 * أو كـ require إضافي داخل routes/web.php. هذه المسارات عامة بالكامل (لا Guard ولا auth)
 * لأن زوار المتجر ضيوف حصرًا (Guest Checkout) - BR-B2C-02.
 */

Route::prefix('shop')->name('storefront.')->group(function () {
    Route::get('/', [StorefrontController::class, 'index'])->name('index');
    Route::get('/product/{product}', [StorefrontController::class, 'show'])->name('show');
    Route::post('/product/{product}/review', [ReviewController::class, 'store'])->name('review.store');

    Route::post('/cart/{product}/add', [StorefrontController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [StorefrontController::class, 'cart'])->name('cart');
    Route::delete('/cart/{cartItem}', [StorefrontController::class, 'removeFromCart'])->name('cart.remove');

    Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [StorefrontController::class, 'placeOrder'])->name('checkout.store');
    Route::get('/confirmation/{order}', [StorefrontController::class, 'confirmation'])->name('confirmation');
});

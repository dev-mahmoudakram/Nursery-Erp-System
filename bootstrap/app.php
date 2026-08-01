<?php

use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // مسارات بوابة B2B ومتجر B2C منفصلة عن web.php الإداري عمدًا (راجع README)
            Route::middleware('web')->group(base_path('routes/portal.php'));
            Route::middleware('web')->group(base_path('routes/storefront.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // تبديل اللغة على كل طلبات الواجهة (عربي/إنجليزي) - راجع README
        $middleware->web(append: [SetLocale::class]);

        // فحص الأدوار الفعلي (RBAC) - يُستخدم في الراوتس كـ ->middleware('role:admin,...')
        $middleware->alias(['role' => EnsureUserHasRole::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

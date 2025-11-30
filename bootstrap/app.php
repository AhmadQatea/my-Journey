<?php

use App\Http\Middleware\CheckIdentityVerified;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckUserType;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // تسجيل middleware الجديد
        $middleware->alias([
            'role' => CheckRole::class,
            'permission' => CheckPermission::class,
            'identity.verified' => CheckIdentityVerified::class,
            'user.type' => CheckUserType::class,
        ]);

        // groups middleware الـ
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        // إعادة توجيه المسؤولين غير المصرح لهم إلى صفحة تسجيل الدخول
        $exceptions->shouldRenderJsonWhen(function ($request, \Throwable $e) {
            if ($request->is('admin/*')) {
                return false;
            }

            return $request->expectsJson();
        });
    })->create();

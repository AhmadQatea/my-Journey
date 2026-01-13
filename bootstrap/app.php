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
            'two-factor' => \App\Http\Middleware\TwoFactorMiddleware::class,
        ]);

        // groups middleware الـ
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\SetLocale::class,
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

        // معالجة خطأ 419 (CSRF Token Mismatch) - يتم التعامل معه في VerifyCsrfToken middleware
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->is('admin/login') || $request->routeIs('admin.login')) {
                return redirect()->route('admin.login')
                    ->with('csrf_error', 'انتهت صلاحية الجلسة. يرجى المحاولة مرة أخرى.');
            }

            return null; // Let Laravel handle it normally for other routes
        });
    })->create();

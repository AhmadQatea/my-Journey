<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // إذا كان الطلب من صفحة تسجيل دخول الأدمن، أعد التوجيه مع رسالة خطأ
            if ($request->is('admin/login') || $request->routeIs('admin.login')) {
                return redirect()->route('admin.login')
                    ->with('csrf_error', 'انتهت صلاحية الجلسة. يرجى المحاولة مرة أخرى.');
            }

            // للطلبات الأخرى، أعد رمي الاستثناء للتعامل معه بشكل افتراضي
            throw $e;
        }
    }
}

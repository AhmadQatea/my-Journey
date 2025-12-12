<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckTwoFactorPermission
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->can('manage 2fa')) {
            abort(403, 'غير مسموح لك بالوصول إلى إعدادات المصادقة الثنائية');
        }

        return $next($request);
    }
}

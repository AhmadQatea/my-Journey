<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // محاولة الحصول على المستخدم من guard 'admin' أولاً، ثم guard الافتراضي
        $user = Auth::guard('admin')->user() ?? $request->user();

        if (! $user) {
            abort(403, 'غير مصرح بالوصول');
        }

        // تحميل role إذا لم يكن محملاً
        if (! $user->relationLoaded('role')) {
            $user->load('role');
        }

        // إذا كان المستخدم Super Admin، يسمح له بالوصول
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return $next($request);
        }

        // دعم pipe separator للأدوار المتعددة
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles = array_merge($allowedRoles, explode('|', $role));
        }

        // التحقق من أي دور من الأدوار المسموحة
        $hasRole = false;
        foreach ($allowedRoles as $role) {
            if ($user->hasRole(trim($role))) {
                $hasRole = true;
                break;
            }
        }

        if (! $hasRole) {
            abort(403, 'غير مصرح بالوصول');
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIdentityVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'يجب تسجيل الدخول أولاً');
        }

        // التحقق من البريد الإلكتروني
        if (! $user->email_verified_at) {
            abort(403, 'يجب التحقق من البريد الإلكتروني أولاً');
        }

        // التحقق من الهوية
        if (! $user->identity_verified) {
            abort(403, 'يجب التحقق من الهوية الشخصية أولاً. يرجى رفع صورة الهوية وانتظار الموافقة من المسؤول');
        }

        return $next($request);
    }
}

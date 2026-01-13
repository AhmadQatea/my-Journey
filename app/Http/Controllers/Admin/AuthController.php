<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            /** @var \App\Models\Admin $admin */
            $admin = Auth::guard('admin')->user();

            // تحميل role إذا لم يكن محملاً
            if (! $admin->relationLoaded('role')) {
                $admin->load('role');
            }

            $roleSlug = $admin->getRoleSlug();
            if ($roleSlug) {
                return redirect()->route('admin.dashboard', ['role' => $roleSlug]);
            }

            // إذا لم يكن هناك role، نعيد التوجيه إلى dashboard redirect
            return redirect()->route('admin.dashboard.redirect');
        }

        // التأكد من إنشاء جلسة جديدة وتحديث CSRF token
        $request->session()->regenerateToken();

        return view('admin.auth.login');
    }

    public function getCsrfToken(Request $request)
    {
        $request->session()->regenerateToken();

        return response()->json([
            'token' => csrf_token(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var \App\Models\Admin $admin */
            $admin = Auth::guard('admin')->user();

            // التحقق من أن المسؤول نشط
            if (! $admin->is_active) {
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('error', 'حسابك غير نشط. يرجى الاتصال بالمسؤول.');
            }

            // تحميل role إذا لم يكن محملاً
            if (! $admin->relationLoaded('role')) {
                $admin->load('role');
            }

            // إعادة التوجيه إلى dashboard مع role في الـ URL
            $roleSlug = $admin->getRoleSlug();

            if ($roleSlug) {
                // استخدام redirect مباشر بدلاً من intended لتجنب مشاكل الـ URL القديم
                return redirect()->route('admin.dashboard', ['role' => $roleSlug]);
            }

            // إذا لم يكن هناك role، نعيد التوجيه إلى dashboard redirect
            return redirect()->route('admin.dashboard.redirect');
        }

        throw ValidationException::withMessages([
            'email' => __('These credentials do not match our records.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

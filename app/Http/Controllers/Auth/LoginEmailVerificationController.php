<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use App\Notifications\PasswordResetCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class LoginEmailVerificationController extends Controller
{
    /**
     * إرسال كود التحقق إلى البريد الإلكتروني بعد تسجيل الدخول
     */
    public function sendLoginCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'لا يوجد حساب مرتبط بهذا البريد الإلكتروني.',
        ]);

        $email = $request->email;

        // حذف أي رموز قديمة لنفس البريد
        PasswordResetCode::where('email', $email)->where('purpose', 'login')->delete();

        // إنشاء رمز جديد (6 أرقام)
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // تخزين الرمز في قاعدة البيانات
        PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'purpose' => 'login',
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => Carbon::now(),
        ]);

        // إرسال البريد الإلكتروني مع الرمز
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->notify(new PasswordResetCodeNotification($code, 'تسجيل الدخول'));

            // تخزين البريد في الجلسة
            session(['login_email_verification' => $email]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني. الرمز صالح لمدة 10 دقائق.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء إرسال رمز التحقق.',
        ], 422);
    }

    /**
     * عرض نموذج إدخال كود التحقق لتسجيل الدخول
     */
    public function showVerifyForm()
    {
        if (!session('login_email_verification')) {
            return redirect()->route('login');
        }

        return view('auth.verify-login-code');
    }

    /**
     * التحقق من صحة كود التحقق لتسجيل الدخول
     */
    public function verifyLoginCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $email = session('login_email_verification');

        if (!$email) {
            return back()->withErrors(['code' => 'انتهت الجلسة. يرجى تسجيل الدخول من جديد.']);
        }

        // البحث عن الرمز
        $resetCode = PasswordResetCode::where('email', $email)
            ->where('code', $request->code)
            ->where('purpose', 'login')
            ->where('used', false)
            ->first();

        if (!$resetCode) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.']);
        }

        if ($resetCode->expires_at < now()) {
            return back()->withErrors(['code' => 'انتهت صلاحية رمز التحقق. يرجى طلب رمز جديد.']);
        }

        // تعيين الرمز كمستخدم
        $resetCode->markAsUsed();

        // تفعيل التحقق في الجلسة
        session(['login_email_verified' => true]);

        // الحصول على بيانات تسجيل الدخول من الجلسة
        $loginData = session('pending_login');

        if ($loginData) {
            // محاولة تسجيل الدخول
            $user = User::where('email', $email)->first();

            if ($user && Hash::check($loginData['password'], $user->password)) {
                Auth::login($user, $loginData['remember'] ?? false);

                // تنظيف الجلسة
                session()->forget(['login_email_verification', 'pending_login', 'login_email_verified']);

                // حذف جميع رموز هذا البريد
                PasswordResetCode::where('email', $email)->where('purpose', 'login')->delete();

                // التحقق من 2FA
                if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
                    session()->forget('2fa_verified');
                    return redirect()->route('two-factor.challenge');
                }

                return redirect()->route('dashboard')
                    ->with('success', 'تم تسجيل الدخول بنجاح!');
            }
        }

        return redirect()->route('login')
            ->with('error', 'حدث خطأ أثناء تسجيل الدخول. يرجى المحاولة مرة أخرى.');
    }

    /**
     * إعادة إرسال كود التحقق
     */
    public function resendLoginCode()
    {
        $email = session('login_email_verification');

        if (!$email) {
            return redirect()->route('login');
        }

        // حذف الرمز القديم
        PasswordResetCode::where('email', $email)->where('purpose', 'login')->delete();

        // إنشاء رمز جديد
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'purpose' => 'login',
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => Carbon::now(),
        ]);

        // إرسال البريد
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->notify(new PasswordResetCodeNotification($code, 'تسجيل الدخول'));
        }

        return back()->with('success', 'تم إعادة إرسال رمز التحقق.');
    }
}

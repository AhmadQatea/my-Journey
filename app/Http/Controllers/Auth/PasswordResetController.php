<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetCode;
use App\Notifications\PasswordResetCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class PasswordResetController extends Controller
{
    /**
     * عرض نموذج طلب كود التحقق
     */
    public function showCodeRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * إرسال كود التحقق إلى البريد الإلكتروني
     */
    public function sendResetCode(Request $request)
    {
        // التحقق من صحة البريد الإلكتروني
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'لا يوجد حساب مرتبط بهذا البريد الإلكتروني.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->email;

        // حذف أي رموز قديمة لنفس البريد
        PasswordResetCode::where('email', $email)->delete();

        // إنشاء رمز جديد (6 أرقام)
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // تخزين الرمز في قاعدة البيانات
        PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'purpose' => 'password_reset',
            'expires_at' => Carbon::now()->addMinutes(15), // صالح لمدة 15 دقيقة
            'created_at' => Carbon::now(),
        ]);

        // إرسال البريد الإلكتروني مع الرمز
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->notify(new PasswordResetCodeNotification($code));

            // تخزين البريد في الجلسة للمراحل التالية
            session(['password_reset_email' => $email]);

            return redirect()->route('password.code.verify')
                ->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني. الرمز صالح لمدة 15 دقيقة.');
        }

        return back()->with('error', 'حدث خطأ أثناء إرسال رمز التحقق.');
    }

    /**
     * عرض نموذج إدخال كود التحقق
     */
    public function showVerifyCodeForm()
    {
        if (!session('password_reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.verify-code', [
            'formAction' => route('password.code.verify'),
            'resendRoute' => route('password.code.resend'),
            'cancelRoute' => route('password.request'),
            'expiryText' => 'الرمز صالح لمدة 15 دقيقة فقط',
            'resendText' => 'إعادة إرسال الرمز',
            'cancelText' => 'إلغاء والعودة',
            'submitColor' => 'primary',
            'pageTitle' => 'التحقق من الهوية',
            'helperMessage' => 'تم إرسال رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني.',
        ]);
    }

    /**
     * التحقق من صحة كود التحقق
     */
    public function verifyResetCode(Request $request)
    {
        // تجميع الكود من الحقول المنفصلة إذا كان موجوداً
        $code = $request->input('code');

        if (!$code) {
            // محاولة تجميع الكود من الحقول المنفصلة
            $codeDigits = '';
            for ($i = 0; $i < 6; $i++) {
                $digit = $request->input("code_digit_{$i}");
                if ($digit) {
                    $codeDigits .= $digit;
                }
            }
            $code = $codeDigits;
        }

        $request->merge(['code' => $code]);

        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $email = session('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'انتهت الجلسة. يرجى البدء من جديد.');
        }

        // البحث عن الرمز
        $resetCode = PasswordResetCode::where('email', $email)
            ->where('code', $request->code)
            ->where('purpose', 'password_reset')
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

        // إنشاء token لتغيير كلمة المرور
        $token = Str::random(64);
        session(['password_reset_token' => $token]);

        return redirect()->route('password.reset.form')
            ->with('success', 'تم التحقق بنجاح. يمكنك الآن تعيين كلمة مرور جديدة.');
    }

    /**
     * إعادة إرسال كود التحقق
     */
    public function resendResetCode()
    {
        $email = session('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        // حذف الرمز القديم
        PasswordResetCode::where('email', $email)->delete();

        // إنشاء رمز جديد
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetCode::create([
            'email' => $email,
            'code' => $code,
            'purpose' => 'password_reset',
            'expires_at' => Carbon::now()->addMinutes(15),
            'created_at' => Carbon::now(),
        ]);

        // إرسال البريد
        $user = User::where('email', $email)->first();
        $user->notify(new PasswordResetCodeNotification($code));

        return back()->with('success', 'تم إعادة إرسال رمز التحقق.');
    }

    /**
     * عرض نموذج تعيين كلمة المرور الجديدة
     */
    public function showResetForm()
    {
        if (!session('password_reset_email') || !session('password_reset_token')) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.reset');
    }

    /**
     * معالجة تعيين كلمة المرور الجديدة
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $email = session('password_reset_email');
        $token = session('password_reset_token');

        if (!$email || !$token) {
            return redirect()->route('password.request')
                ->with('error', 'انتهت الجلسة. يرجى البدء من جديد.');
        }

        // البحث عن المستخدم
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->with('error', 'المستخدم غير موجود.');
        }

        // تغيير كلمة المرور
        $user->password = Hash::make($request->password);
        $user->save();

        // إرسال إشعار تغيير كلمة المرور
        $user->notify(new \App\Notifications\PasswordChangedNotification());

        // تنظيف الجلسة
        session()->forget(['password_reset_email', 'password_reset_token']);

        // حذف جميع رموز هذا البريد
        PasswordResetCode::where('email', $email)->delete();

        // تسجيل الدخول تلقائياً (اختياري)
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'تم تغيير كلمة المرور بنجاح!');
    }
}

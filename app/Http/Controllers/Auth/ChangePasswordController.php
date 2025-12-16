<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Notifications\PasswordResetCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{
    /**
     * عرض نموذج طلب كود التحقق لتغيير كلمة المرور
     */
    public function showRequestCodeForm()
    {
        return view('auth.passwords.request-change-code');
    }

    /**
     * إرسال كود التحقق لتغيير كلمة المرور
     */
    public function sendChangeCode(Request $request)
    {
        try {
            $user = Auth::user();

            if (! $user || ! $user->email) {
                Log::warning('Password change code request failed: User not found or no email', [
                    'user_id' => $user->id ?? null,
                ]);

                return back()->withErrors(['error' => 'المستخدم غير موجود أو لا يحتوي على بريد إلكتروني.']);
            }

            // حذف أي رموز قديمة لنفس الغرض
            PasswordResetCode::where('email', $user->email)
                ->where('purpose', 'password_change')
                ->delete();

            // إنشاء رمز جديد
            $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

            // تخزين الرمز
            PasswordResetCode::create([
                'email' => $user->email,
                'code' => $code,
                'purpose' => 'password_change',
                'expires_at' => Carbon::now()->addMinutes(10),
                'created_at' => Carbon::now(),
            ]);

            Log::info('Password change code created', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            try {
                // إرسال البريد
                $user->notify(new PasswordResetCodeNotification($code, 'تغيير كلمة المرور'));
                Log::info('Password change code notification sent', [
                    'user_id' => $user->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send password change code notification', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                // في بيئة التطوير، يمكن عرض الرمز مباشرة
                if (app()->environment('local')) {
                    // حفظ في الجلسة مع الرمز للعرض
                    session(['password_change_verified' => false]);
                    session(['password_change_code_sent' => true]);
                    session(['password_change_code' => $code]); // حفظ الرمز للعرض في بيئة التطوير

                    return redirect()->route('password.change.verify')
                        ->with('warning', 'فشل إرسال البريد الإلكتروني. في بيئة التطوير، يمكنك استخدام الرمز التالي: '.$code)
                        ->with('dev_code', $code);
                }

                // في بيئة الإنتاج، إرجاع خطأ
                return back()->withErrors([
                    'error' => 'فشل إرسال رمز التحقق. يرجى التحقق من إعدادات البريد الإلكتروني أو المحاولة مرة أخرى لاحقاً.',
                    'mail_error' => 'خطأ في إعدادات البريد الإلكتروني: '.$e->getMessage(),
                ]);
            }

            // حفظ في الجلسة
            session(['password_change_verified' => false]);
            session(['password_change_code_sent' => true]);

            return redirect()->route('password.change.verify')
                ->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.');
        } catch (\Exception $e) {
            Log::error('Password change code request error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء إرسال رمز التحقق. يرجى المحاولة مرة أخرى.']);
        }
    }

    /**
     * عرض نموذج إدخال كود التحقق لتغيير كلمة المرور
     */
    public function showVerifyChangeCodeForm()
    {
        if (! session('password_change_code_sent')) {
            return redirect()->route('password.change.request');
        }

        return view('auth.passwords.verify-code', [
            'formAction' => route('password.change.verify.post'),
            'resendRoute' => route('password.change.request'),
            'cancelRoute' => route('password.change.form'),
            'expiryText' => 'الرمز صالح لمدة 10 دقائق فقط',
            'resendText' => 'إعادة إرسال الرمز',
            'cancelText' => 'إلغاء والعودة',
            'submitColor' => 'warning',
            'pageTitle' => 'التحقق من الهوية',
            'helperMessage' => 'تم إرسال رمز تحقق مكون من 6 أرقام إلى بريدك الإلكتروني.',
        ]);
    }

    /**
     * التحقق من كود تغيير كلمة المرور
     */
    public function verifyChangeCode(Request $request)
    {
        // تجميع الكود من الحقول المنفصلة إذا كان موجوداً
        $code = $request->input('code');

        if (! $code) {
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

        $user = Auth::user();

        $resetCode = PasswordResetCode::where('email', $user->email)
            ->where('code', $request->code)
            ->where('purpose', 'password_change')
            ->where('used', false)
            ->first();

        if (! $resetCode) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح.']);
        }

        if ($resetCode->expires_at < now()) {
            return back()->withErrors(['code' => 'انتهت صلاحية رمز التحقق.']);
        }

        // تعيين الرمز كمستخدم
        $resetCode->markAsUsed();

        // تفعيل التغيير في الجلسة
        session(['password_change_verified' => true]);
        session(['password_change_code' => $request->code]);

        return redirect()->route('password.change.form')
            ->with('success', 'تم التحقق بنجاح. يمكنك الآن تغيير كلمة المرور.');
    }

    /**
     * عرض نموذج تغيير كلمة المرور بعد التحقق
     */
    public function showChangeForm()
    {
        // يمكن الوصول إلى هذه الصفحة بدون التحقق بالكود
        // إذا لم يتم التحقق بالكود، سيتم طلب كلمة المرور الحالية
        return view('auth.passwords.change');
    }

    /**
     * معالجة تغيير كلمة المرور
     */
    public function changePassword(Request $request)
    {
        // إذا تم التحقق بالكود، لا حاجة لكلمة المرور الحالية
        $isVerifiedByCode = session('password_change_verified');

        if (! $isVerifiedByCode) {
            // إذا لم يتم التحقق بالكود، يجب إدخال كلمة المرور الحالية
            $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
            ]);
        } else {
            // إذا تم التحقق بالكود، لا حاجة لكلمة المرور الحالية
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required',
            ]);
        }

        $user = Auth::user();

        // تغيير كلمة المرور
        $user->password = Hash::make($request->password);
        $user->save();

        // إرسال إشعار
        $user->notify(new \App\Notifications\PasswordChangedNotification);

        // تنظيف الجلسة
        session()->forget(['password_change_verified', 'password_change_code_sent', 'password_change_code']);

        // حذف الرموز
        PasswordResetCode::where('email', $user->email)->delete();

        return redirect()->route('dashboard')
            ->with('success', 'تم تغيير كلمة المرور بنجاح!');
    }
}

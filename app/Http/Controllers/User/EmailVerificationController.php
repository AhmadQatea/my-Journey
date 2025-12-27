<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Notifications\PasswordResetCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * إرسال كود التحقق من البريد الإلكتروني
     */
    public function sendVerificationCode(Request $request)
    {
        $user = Auth::user();

        // التحقق من أن البريد الإلكتروني لم يتم التحقق منه بالفعل
        if ($user->email_verified_at) {
            return back()->with('info', 'تم التحقق من بريدك الإلكتروني بالفعل');
        }

        // حذف أي رموز قديمة لنفس البريد
        PasswordResetCode::where('email', $user->email)
            ->where('purpose', 'email_verification')
            ->delete();

        // إنشاء رمز جديد (6 أرقام)
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // تخزين الرمز في قاعدة البيانات
        PasswordResetCode::create([
            'email' => $user->email,
            'code' => $code,
            'purpose' => 'email_verification',
            'expires_at' => Carbon::now()->addMinutes(15),
            'created_at' => Carbon::now(),
        ]);

        // إرسال البريد الإلكتروني مع الرمز
        $user->notify(new PasswordResetCodeNotification($code, 'التحقق من البريد الإلكتروني'));

        // تخزين البريد في الجلسة
        session(['email_verification_email' => $user->email]);

        return redirect()->route('email.verify')
            ->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني. الرمز صالح لمدة 15 دقيقة.');
    }

    /**
     * عرض نموذج إدخال كود التحقق
     */
    public function showVerifyForm()
    {
        $user = Auth::user();

        // إذا كان البريد موثقاً بالفعل
        if ($user->email_verified_at) {
            return redirect()->route('dashboard')
                ->with('info', 'تم التحقق من بريدك الإلكتروني بالفعل');
        }

        // التحقق من وجود جلسة التحقق
        if (! session('email_verification_email') || session('email_verification_email') !== $user->email) {
            return redirect()->route('dashboard')
                ->with('error', 'يجب إرسال كود التحقق أولاً');
        }

        return view('auth.verify-email');
    }

    /**
     * التحقق من صحة كود التحقق
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'يجب إدخال رمز التحقق',
            'code.size' => 'رمز التحقق يجب أن يكون 6 أرقام',
        ]);

        $user = Auth::user();
        $email = session('email_verification_email');

        if (! $email || $email !== $user->email) {
            return back()->withErrors(['code' => 'انتهت الجلسة. يرجى طلب رمز جديد.']);
        }

        // البحث عن الرمز
        $resetCode = PasswordResetCode::where('email', $email)
            ->where('code', $request->code)
            ->where('purpose', 'email_verification')
            ->where('used', false)
            ->first();

        if (! $resetCode) {
            return back()->withErrors(['code' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.']);
        }

        if ($resetCode->expires_at < now()) {
            return back()->withErrors(['code' => 'انتهت صلاحية رمز التحقق. يرجى طلب رمز جديد.']);
        }

        // تعيين الرمز كمستخدم
        $resetCode->markAsUsed();

        // تحديث البريد الإلكتروني كمتحقق منه
        $user->email_verified_at = now();
        $user->save();

        // إعادة تحميل المستخدم في الجلسة
        $freshUser = $user->fresh();
        Auth::setUser($freshUser);

        // تنظيف الجلسة
        session()->forget('email_verification_email');

        // إعادة التوجيه مع إعادة تحميل الصفحة
        return redirect()->route('identity-verification.create')
            ->with('success', 'تم التحقق من بريدك الإلكتروني بنجاح! يمكنك الآن رفع صورة الهوية.')
            ->with('email_verified', true);
    }

    /**
     * إعادة إرسال كود التحقق
     */
    public function resendCode()
    {
        $user = Auth::user();

        if ($user->email_verified_at) {
            return back()->with('info', 'تم التحقق من بريدك الإلكتروني بالفعل');
        }

        // حذف أي رموز قديمة
        PasswordResetCode::where('email', $user->email)
            ->where('purpose', 'email_verification')
            ->delete();

        // إنشاء رمز جديد
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetCode::create([
            'email' => $user->email,
            'code' => $code,
            'purpose' => 'email_verification',
            'expires_at' => Carbon::now()->addMinutes(15),
            'created_at' => Carbon::now(),
        ]);

        $user->notify(new PasswordResetCodeNotification($code, 'التحقق من البريد الإلكتروني'));

        return back()->with('success', 'تم إرسال رمز التحقق الجديد إلى بريدك الإلكتروني.');
    }
}

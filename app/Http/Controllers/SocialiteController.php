<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * توجيه المستخدم إلى صفحة مصادقة جوجل
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors('حدث خطأ في الاتصال بخدمة جوجل. يرجى المحاولة لاحقاً.');
        }
    }

    /**
     * معالجة الاستجابة القادمة من جوجل
     */
    public function handleGoogleCallback()
    {
        try {
            // الحصول على بيانات المستخدم من جوجل
            $googleUser = Socialite::driver('google')->user();

            // البحث عن مستخدم موجود بنفس البريد الإلكتروني
            $user = User::where('email', $googleUser->getEmail())->first();

            if (! $user) {
                // إنشاء مستخدم جديد
                $userRole = Role::where('name', 'user')->first();

                $user = User::create([
                    'full_name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => Hash::make(uniqid()), // كلمة مرور عشوائية
                    'role_id' => $userRole?->id,
                    'account_type' => 'visitor',
                ]);
            } else {
                // تحديث بيانات المستخدم الحالي برابط جوجل
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            // تسجيل دخول المستخدم
            Auth::login($user, true); // true يعني "تذكرني"

            // التحقق من تفعيل 2FA
            if ($user->two_factor_secret && $user->two_factor_confirmed_at) {
                // لا نضع 2fa_verified في session لأن المستخدم لم يتحقق بعد
                return redirect()->route('two-factor.challenge');
            }

            // توجيه المستخدم إلى لوحة التحكم
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            // تسجيل الخطأ للتصحيح
            Log::error('خطأ في تسجيل الدخول عبر جوجل: '.$e->getMessage());

            return redirect()->route('login')
                ->withErrors('حدث خطأ أثناء تسجيل الدخول عبر جوجل. يرجى المحاولة مرة أخرى.');
        }
    }

    /**
     * ربط حساب جوجل بحساب موجود (للمستخدمين المسجلين بالفعل)
     */
    public function linkGoogleAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        try {
            $googleUser = Socialite::driver('google')->user();
            $user = Auth::user();

            // التحقق مما إذا كان حساب جوجل مرتبطاً بحساب آخر
            $existingUser = User::where('google_id', $googleUser->getId())
                ->where('id', '!=', $user->id)
                ->first();

            if ($existingUser) {
                return back()->with('error', 'هذا الحساب مرتبط بحساب آخر.');
            }

            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            return back()->with('success', 'تم ربط حساب جوجل بنجاح');

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء ربط حساب جوجل: '.$e->getMessage());
        }
    }

    /**
     * فك ارتباط حساب جوجل
     */
    public function unlinkGoogleAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        $user->update([
            'google_id' => null,
        ]);

        return back()->with('success', 'تم فك ارتباط حساب جوجل');
    }
}

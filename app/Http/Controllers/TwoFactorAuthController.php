<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetCode;
use App\Notifications\PasswordResetCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use PragmaRX\Google2FALaravel\Facade as Google2FA;

class TwoFactorAuthController extends Controller
{
    /**
     * Show 2FA setup page
     */
    public function showSetupForm()
    {
        /** @var User $user */
        $user = Auth::user();

        // Generate secret key if not exists
        if (! $user->two_factor_secret) {
            $user->update([
                'two_factor_secret' => Google2FA::generateSecretKey(),
            ]);
        }

        // Generate QR Code using model method
        $qrCodeSvg = $user->twoFactorQrCodeSvg();

        return view('auth.two-factor-setup', compact('qrCodeSvg', 'user'));
    }

    /**
     * Enable 2FA
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $secret = $user->two_factor_secret;

        // Verify code
        $valid = Google2FA::verifyKey($secret, $request->code);

        if ($valid) {
            // Generate recovery codes
            $recoveryCodes = $this->generateRecoveryCodes();

            $user->update([
                'two_factor_confirmed_at' => now(),
                'two_factor_recovery_codes' => json_encode($recoveryCodes),
            ]);

            return redirect()->route('two-factor.recovery-codes')
                ->with('recoveryCodes', $recoveryCodes)
                ->with('status', 'تم تفعيل المصادقة الثنائية بنجاح!');
        }

        return back()->withErrors(['code' => 'رمز التحقق غير صحيح']);
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ]);

        return redirect()->route('profile.show')
            ->with('status', 'تم إلغاء تفعيل المصادقة الثنائية');
    }

    /**
     * Show recovery codes
     */
    public function showRecoveryCodes()
    {
        $recoveryCodes = json_decode(Auth::user()->two_factor_recovery_codes, true) ?? [];

        return view('auth.two-factor-recovery-codes', compact('recoveryCodes'));
    }

    /**
     * Generate new recovery codes
     */
    public function generateNewRecoveryCodes()
    {
        /** @var User $user */
        $user = Auth::user();
        $recoveryCodes = $this->generateRecoveryCodes();
        $user->update([
            'two_factor_recovery_codes' => json_encode($recoveryCodes),
        ]);

        return redirect()->route('two-factor.recovery-codes')
            ->with('recoveryCodes', $recoveryCodes)
            ->with('status', 'تم إنشاء أكواد استرجاع جديدة');
    }

    /**
     * Verify 2FA code during login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:6',
        ]);

        /** @var User $user */
        $user = $request->user();

        // Check if code is a recovery code first (recovery codes are longer, typically 10 chars)
        $isRecoveryCode = false;
        if (strlen($request->code) > 6 && $user->two_factor_recovery_codes) {
            $recoveryCodes = json_decode($user->two_factor_recovery_codes, true) ?? [];
            $codeIndex = array_search(strtoupper($request->code), array_map('strtoupper', $recoveryCodes));

            if ($codeIndex !== false) {
                $isRecoveryCode = true;
                // Remove used recovery code
                unset($recoveryCodes[$codeIndex]);
                $user->update([
                    'two_factor_recovery_codes' => json_encode(array_values($recoveryCodes)),
                ]);
            }
        }

        // If not a recovery code, verify as 2FA code (must be exactly 6 digits)
        $isValidCode = false;
        $isEmailCode = false;

        if (! $isRecoveryCode && strlen($request->code) === 6) {
            // Try Google2FA first
            $isValidCode = Google2FA::verifyKey($user->two_factor_secret, $request->code);

            // If not valid, try email code
            if (! $isValidCode) {
                $resetCode = PasswordResetCode::where('email', $user->email)
                    ->where('code', $request->code)
                    ->where('purpose', 'two_factor')
                    ->where('used', false)
                    ->first();

                if ($resetCode && $resetCode->expires_at > now()) {
                    $isEmailCode = true;
                    $resetCode->markAsUsed();
                    // حذف جميع رموز 2FA المنتهية
                    PasswordResetCode::where('email', $user->email)
                        ->where('purpose', 'two_factor')
                        ->where('expires_at', '<', now())
                        ->delete();
                }
            }
        }

        if ($isValidCode || $isRecoveryCode || $isEmailCode) {
            session(['2fa_verified' => true]);

            // Get intended URL from session or default to dashboard
            $intendedUrl = session('intended_url', route('dashboard'));
            session()->forget('intended_url');

            return redirect($intendedUrl);
        }

        return back()->withErrors(['code' => 'رمز التحقق غير صحيح']);
    }

    /**
     * Generate recovery codes
     */
    private function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(5)));
        }

        return $codes;
    }
    /**
     * إرسال كود التحقق عبر الإيميل للتحقق بخطوتين
     */
    public function sendEmailCode()
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user) {
            return back()->withErrors(['error' => 'يجب تسجيل الدخول أولاً']);
        }

        // حذف أي رموز قديمة
        PasswordResetCode::where('email', $user->email)
            ->where('purpose', 'two_factor')
            ->delete();

        // إنشاء رمز جديد
        $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // تخزين الرمز
        PasswordResetCode::create([
            'email' => $user->email,
            'code' => $code,
            'purpose' => 'two_factor',
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => Carbon::now(),
        ]);

        // إرسال البريد
        $user->notify(new PasswordResetCodeNotification($code, 'التحقق بخطوتين'));

        return back()->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.');
    }

    /**
     * التحقق من رمز 2FA لتغيير كلمة المرور
     */
    public function verifyForPasswordChange(Request $request)
    {
        $request->validate([
            'two_factor_code' => 'required|string|size:6',
        ]);

        $user = Auth::user();
        $secret = $user->two_factor_secret;

        $isValid = false;

        // Try Google2FA first
        if ($secret) {
            $isValid = app('pragmarx.google2fa')->verifyKey($secret, $request->two_factor_code);
        }

        // If not valid, try email code
        if (! $isValid) {
            $resetCode = PasswordResetCode::where('email', $user->email)
                ->where('code', $request->two_factor_code)
                ->where('purpose', 'two_factor')
                ->where('used', false)
                ->first();

            if ($resetCode && $resetCode->expires_at > now()) {
                $isValid = true;
                $resetCode->markAsUsed();
            }
        }

        if ($isValid) {
            // تخزين التحقق في الجلسة لمدة 10 دقائق
            session(['2fa_verified_for_password_change' => true]);
            session(['2fa_verified_time' => now()]);

            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'رمز التحقق غير صحيح',
        ], 422);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\PasswordResetCode;
use App\Models\User;
use App\Notifications\PasswordResetCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // If 2FA is already enabled and confirmed, redirect to dashboard
        if ($user->two_factor_confirmed_at) {
            return redirect()->route('dashboard')
                ->with('info', 'المصادقة الثنائية مفعلة بالفعل.');
        }

        // Check if password is verified in this session
        $passwordVerified = session('2fa_password_verified', false);

        $qrCodeSvg = null;

        // Only generate secret and QR code if password is verified
        if ($passwordVerified) {
            // Generate secret key if not exists
            if (! $user->two_factor_secret) {
                $user->update([
                    'two_factor_secret' => Google2FA::generateSecretKey(),
                ]);
                $user->refresh();
            }

            // Generate QR Code using model method
            $qrCodeSvg = $user->twoFactorQrCodeSvg();
        }

        return view('auth.two-factor-setup', compact('qrCodeSvg', 'user', 'passwordVerified'));
    }

    /**
     * Verify password before enabling 2FA
     */
    public function verifyPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|current_password',
        ], [
            'password.required' => 'الرجاء إدخال كلمة المرور',
            'password.current_password' => 'كلمة المرور غير صحيحة',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Generate secret key if not exists (do this immediately after password verification)
        if (! $user->two_factor_secret) {
            $user->update([
                'two_factor_secret' => Google2FA::generateSecretKey(),
            ]);
            $user->refresh();

            Log::info('2FA Secret Generated', [
                'user_id' => $user->id,
                'secret_length' => strlen($user->two_factor_secret),
            ]);
        }

        // Set session flag that password is verified
        session(['2fa_password_verified' => true]);
        session()->save();

        return redirect()->route('two-factor.setup')
            ->with('status', 'two-factor-authentication-enabled');
    }

    /**
     * Enable 2FA
     */
    public function enable(Request $request)
    {
        Log::info('2FA Enable Request Received', [
            'user_id' => Auth::id(),
            'has_code' => $request->has('code'),
            'code_length' => $request->has('code') ? strlen($request->code) : 0,
            'route' => $request->route()->getName(),
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();

            if (! $user) {
                Log::warning('2FA Enable: No authenticated user');

                return redirect()->route('login')->withErrors(['code' => 'يجب تسجيل الدخول أولاً']);
            }

            // Check if password is verified
            if (! session('2fa_password_verified', false)) {
                Log::warning('2FA Enable: Password not verified', ['user_id' => $user->id]);

                return redirect()->route('two-factor.setup')
                    ->withErrors(['password' => 'يجب التحقق من كلمة المرور أولاً']);
            }

            // If 2FA is already enabled and confirmed, redirect to dashboard
            if ($user->two_factor_confirmed_at) {
                Log::info('2FA Enable: Already enabled', ['user_id' => $user->id]);

                return redirect()->route('dashboard')
                    ->with('info', 'المصادقة الثنائية مفعلة بالفعل.');
            }

            // Validate input - allow flexible input (string with min 6 chars, we'll clean it)
            $validated = $request->validate([
                'code' => 'required|string|min:6',
            ], [
                'code.required' => 'الرجاء إدخال رمز التحقق',
                'code.min' => 'رمز التحقق يجب أن يكون 6 أرقام على الأقل',
            ]);

            $secret = $user->two_factor_secret;

            if (! $secret) {
                Log::error('2FA Enable: Secret not found', ['user_id' => $user->id]);

                return redirect()->route('two-factor.setup')
                    ->withErrors(['code' => 'المفتاح السري غير موجود. يرجى إعادة تحميل الصفحة']);
            }

            // Clean the code (remove any spaces or non-numeric characters)
            $code = preg_replace('/[^0-9]/', '', $request->code);

            if (strlen($code) !== 6 || ! ctype_digit($code)) {
                Log::warning('2FA Enable: Invalid code format', [
                    'user_id' => $user->id,
                    'code_length' => strlen($code),
                ]);

                return back()->withInput()->withErrors(['code' => 'رمز التحقق يجب أن يكون 6 أرقام فقط']);
            }

            // First, verify that the secret key is valid
            if (empty($secret) || strlen($secret) < 16) {
                Log::error('2FA Secret Invalid', [
                    'user_id' => $user->id,
                    'secret_exists' => ! empty($secret),
                    'secret_length' => $secret ? strlen($secret) : 0,
                ]);

                return back()->withInput()->withErrors(['code' => 'المفتاح السري غير صحيح. يرجى إعادة تحميل الصفحة']);
            }

            // Log before verification for debugging
            Log::info('2FA Code Verification Attempt', [
                'user_id' => $user->id,
                'code' => $code,
                'secret_length' => strlen($secret),
                'secret_preview' => substr($secret, 0, 8).'...',
            ]);

            // Use verifyKey with window tolerance (default is 4, which allows ±2 time steps = ±60 seconds)
            // This is the recommended way to verify 2FA codes
            // Try with default window first, then with larger window if needed
            $valid = Google2FA::verifyKey($secret, $code);

            Log::info('2FA Verification Result (default window)', [
                'user_id' => $user->id,
                'valid' => $valid,
            ]);

            // If not valid with default window, try with larger window (8 = ±4 time steps = ±120 seconds)
            if (! $valid) {
                $google2fa = app('pragmarx.google2fa');
                $valid = $google2fa->verifyKey($secret, $code, 8);

                Log::info('2FA Verification Result (large window)', [
                    'user_id' => $user->id,
                    'valid' => $valid,
                ]);
            }

            // If not valid, return error with logging for debugging
            if (! $valid) {
                Log::warning('2FA Code Verification Failed', [
                    'user_id' => $user->id,
                    'code' => $code,
                    'code_length' => strlen($code),
                    'secret_length' => strlen($secret),
                    'secret_preview' => substr($secret, 0, 4).'...',
                ]);

                return back()
                    ->withInput()
                    ->withErrors(['code' => 'رمز التحقق غير صحيح. تأكد من: 1) أنك قمت بمسح QR Code بشكل صحيح 2) أن الوقت على هاتفك متزامن (Settings > Date & Time > Automatic) 3) أنك تدخل الكود الحالي من التطبيق (يتغير كل 30 ثانية)']);
            }

            Log::info('2FA Code Verified Successfully', [
                'user_id' => $user->id,
            ]);

            // Generate recovery codes
            $recoveryCodes = $this->generateRecoveryCodes();

            // Update user with 2FA enabled FIRST
            $user->update([
                'two_factor_confirmed_at' => now(),
                'two_factor_recovery_codes' => json_encode($recoveryCodes),
            ]);

            // Refresh user instance to get updated data
            $user->refresh();

            // Set session flag AFTER updating database
            // This ensures the session is set after the database update
            session(['2fa_verified' => true]);
            session()->save(); // Force save session immediately

            Log::info('2FA Enabled Successfully', [
                'user_id' => $user->id,
                'two_factor_confirmed_at' => $user->two_factor_confirmed_at,
                '2fa_verified_session' => session('2fa_verified'),
            ]);

            // Clear password verification session
            session()->forget('2fa_password_verified');

            // Redirect to dashboard with success message
            // Use redirect()->intended() to respect any intended URL
            $redirectUrl = session('intended_url', route('dashboard'));
            session()->forget('intended_url');

            return redirect($redirectUrl)
                ->with('recoveryCodes', $recoveryCodes)
                ->with('success', 'تم تفعيل المصادقة الثنائية بنجاح! احفظ أكواد الاسترجاع في مكان آمن.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('2FA Enable Validation Error', [
                'user_id' => Auth::id(),
                'errors' => $e->errors(),
            ]);

            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error('2FA Enable Error: '.$e->getMessage(), [
                'user_id' => Auth::id(),
                'code' => $request->input('code'),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->withErrors(['code' => 'حدث خطأ أثناء تفعيل المصادقة الثنائية. يرجى المحاولة مرة أخرى.']);
        }
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
            // Set session flag and force save
            session(['2fa_verified' => true]);
            session()->save(); // Force save session immediately

            // Get intended URL from session or default to dashboard
            $intendedUrl = session('intended_url', route('dashboard'));
            session()->forget('intended_url');

            Log::info('2FA Verified Successfully', [
                'user_id' => $user->id,
                'intended_url' => $intendedUrl,
                '2fa_verified_session' => session('2fa_verified'),
            ]);

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

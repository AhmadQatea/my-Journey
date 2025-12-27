<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TwoFactorSetupController extends Controller
{
    /**
     * Show 2FA setup page using Fortify
     */
    public function showSetupForm()
    {
        $user = Auth::user();

        // If 2FA is already enabled and confirmed, redirect to dashboard
        if ($user->two_factor_confirmed_at) {
            return redirect()->route('dashboard')
                ->with('info', 'المصادقة الثنائية مفعلة بالفعل.');
        }

        // Get QR code SVG using Fortify method
        // Fortify generates the secret automatically when enabling 2FA
        // So we need to check if secret exists, if not, we'll show it after enabling
        $qrCodeSvg = null;
        if ($user->two_factor_secret) {
            $qrCodeSvg = $user->twoFactorQrCodeSvg();
        }

        return view('auth.two-factor-setup', compact('qrCodeSvg', 'user'));
    }

    /**
     * Show recovery codes
     * Reads directly from database to avoid Fortify encryption/decryption issues
     */
    public function showRecoveryCodes()
    {
        $user = Auth::user();
        $recoveryCodes = [];

        // Always read directly from database (stored as plain JSON)
        // This avoids "The payload is invalid" error from Fortify
        if ($user->two_factor_recovery_codes) {
            $decoded = json_decode($user->two_factor_recovery_codes, true);
            if (is_array($decoded)) {
                $recoveryCodes = $decoded;
            } elseif (is_string($user->two_factor_recovery_codes)) {
                // If it's a string, try to decode it
                $recoveryCodes = json_decode($user->two_factor_recovery_codes, true) ?? [];
            }
        }

        return view('auth.two-factor-recovery-codes', compact('recoveryCodes'));
    }
}

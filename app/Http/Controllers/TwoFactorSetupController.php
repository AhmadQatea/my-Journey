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
     * Show recovery codes using Fortify
     */
    public function showRecoveryCodes()
    {
        $user = Auth::user();

        // Get recovery codes using Fortify method
        // Fortify stores recovery codes in two_factor_recovery_codes as JSON
        $recoveryCodes = $user->recoveryCodes();

        // If recovery codes are stored as JSON string, decode them
        if (is_string($recoveryCodes)) {
            $recoveryCodes = json_decode($recoveryCodes, true) ?? [];
        }

        // If still empty, check if they exist in the database
        if (empty($recoveryCodes) && $user->two_factor_recovery_codes) {
            $recoveryCodes = json_decode($user->two_factor_recovery_codes, true) ?? [];
        }

        return view('auth.two-factor-recovery-codes', compact('recoveryCodes'));
    }
}

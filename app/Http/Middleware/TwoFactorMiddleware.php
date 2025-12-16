<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Skip 2FA check for 2FA routes themselves
        if ($request->routeIs('two-factor.*') ||
            $request->is('user/two-factor*') ||
            $request->is('two-factor-challenge')) {
            return $next($request);
        }

        // Skip 2FA check for password change routes (they have their own verification)
        if ($request->routeIs('password.change.*')) {
            return $next($request);
        }

        // Check if user has 2FA enabled and verified
        if ($user && $user->two_factor_secret && $user->two_factor_confirmed_at) {
            // Check if 2FA is verified in this session
            $isVerified = Session::get('2fa_verified', false);

            if (! $isVerified) {
                // Store intended URL for redirect after verification
                Session::put('intended_url', $request->url());
                Session::save(); // Force save session

                // Redirect to 2FA challenge (Fortify handles this route)
                return redirect('/two-factor-challenge');
            }
        }

        return $next($request);
    }
}

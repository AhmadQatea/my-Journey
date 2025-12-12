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
        if ($request->routeIs('two-factor.*')) {
            return $next($request);
        }

        // Check if user has 2FA enabled and verified
        if ($user && $user->two_factor_secret && $user->two_factor_confirmed_at) {
            // Check if 2FA is verified in this session
            if (! Session::get('2fa_verified')) {
                // Store intended URL for redirect after verification
                Session::put('intended_url', $request->url());

                // Redirect to 2FA challenge
                return redirect()->route('two-factor.challenge');
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        if (! $request->user() || $request->user()->account_type !== $type) {
            abort(403, 'غير مصرح بالوصول');
        }

        return $next($request);
    }
}

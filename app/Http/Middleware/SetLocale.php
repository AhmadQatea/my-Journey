<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // الحصول على اللغة من Session أو من الـ URL parameter أو الافتراضية
        $locale = $request->get('lang')
            ?? Session::get('locale')
            ?? config('app.locale', 'ar');

        // التحقق من أن اللغة مدعومة
        if (! in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }

        // تعيين اللغة
        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }
}

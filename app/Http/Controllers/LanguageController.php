<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * تبديل اللغة
     */
    public function switch(Request $request, string $locale): \Illuminate\Http\RedirectResponse
    {
        // التحقق من أن اللغة مدعومة
        if (! in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }

        // حفظ اللغة في Session
        Session::put('locale', $locale);
        App::setLocale($locale);

        // إرجاع المستخدم للصفحة السابقة أو الصفحة الرئيسية
        return Redirect::back();
    }
}

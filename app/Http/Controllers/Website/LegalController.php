<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class LegalController extends Controller
{
    /**
     * عرض صفحة الشروط والأحكام
     */
    public function terms(): View
    {
        $siteSettings = SiteSetting::getSettings();

        return view('website.pages.legal.terms', [
            'title' => 'الشروط والأحكام',
            'content' => $siteSettings->terms_and_conditions,
        ]);
    }

    /**
     * عرض صفحة سياسة الخصوصية
     */
    public function privacy(): View
    {
        $siteSettings = SiteSetting::getSettings();

        return view('website.pages.legal.privacy', [
            'title' => 'سياسة الخصوصية',
            'content' => $siteSettings->privacy_policy,
        ]);
    }

    /**
     * عرض صفحة سياسة ملفات تعريف الارتباط
     */
    public function cookies(): View
    {
        $siteSettings = SiteSetting::getSettings();

        return view('website.pages.legal.cookies', [
            'title' => 'سياسة ملفات تعريف الارتباط',
            'content' => $siteSettings->cookie_policy,
        ]);
    }

    /**
     * API endpoint لجلب المحتوى عبر JavaScript
     */
    public function getContent(string $type)
    {
        $siteSettings = SiteSetting::getSettings();

        $contentMap = [
            'terms' => $siteSettings->terms_and_conditions,
            'privacy' => $siteSettings->privacy_policy,
            'cookies' => $siteSettings->cookie_policy,
        ];

        if (! isset($contentMap[$type])) {
            return response()->json(['error' => 'نوع المحتوى غير صحيح'], 404);
        }

        $titles = [
            'terms' => 'الشروط والأحكام',
            'privacy' => 'سياسة الخصوصية',
            'cookies' => 'سياسة ملفات تعريف الارتباط',
        ];

        return response()->json([
            'title' => $titles[$type],
            'content' => $contentMap[$type],
        ]);
    }
}

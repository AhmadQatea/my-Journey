<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SiteController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! Gate::allows('site-manager')) {
                abort(403, 'غير مصرح لك بالوصول إلى هذه الصفحة');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = SiteSetting::getSettings();

        return view('admin.site.index', compact('settings'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $settings = SiteSetting::getSettings();

        return view('admin.site.edit', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // وصف مختصر عن الموقع (يُستخدم في صفحة about)
            'about_story' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'contact_address' => ['nullable', 'string', 'max:500'],
            'working_hours' => ['nullable', 'string'],
            'terms_and_conditions' => ['nullable', 'string'],
            'privacy_policy' => ['nullable', 'string'],
            'cookie_policy' => ['nullable', 'string'],
            'social_facebook' => ['nullable', 'url'],
            'social_twitter' => ['nullable', 'url'],
            'social_instagram' => ['nullable', 'url'],
            'social_youtube' => ['nullable', 'url'],
            'social_linkedin' => ['nullable', 'url'],
            'social_whatsapp' => ['nullable', 'string', 'max:255'],
        ]);

        // تحويل working_hours إلى JSON إذا كان نصاً
        if (isset($validated['working_hours']) && is_string($validated['working_hours'])) {
            $validated['working_hours'] = json_decode($validated['working_hours'], true) ?? $validated['working_hours'];
        }

        $settings = SiteSetting::getSettings();
        $settings->update($validated);

        return redirect()->route('admin.site.index')
            ->with('success', 'تم تحديث إعدادات الموقع بنجاح.');
    }
}

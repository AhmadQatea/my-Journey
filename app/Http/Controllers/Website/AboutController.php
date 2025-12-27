<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $admins = Admin::query()
            ->where('is_active', true)
            ->with('role')
            ->orderByDesc('is_super_admin')
            ->orderBy('name')
            ->get();

        $siteSettings = SiteSetting::getSettings();

        return view('website.pages.about', compact('admins', 'siteSettings'));
    }
}

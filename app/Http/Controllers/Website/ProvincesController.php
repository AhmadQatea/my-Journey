<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Governorate;
use App\Models\TouristSpot;

class ProvincesController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        // جلب جميع المحافظات مع عدد الرحلات والأماكن السياحية
        $governorates = Governorate::withCount([
            'trips' => function ($query) {
                $query->whereIn('status', ['مقبولة', 'قيد التفعيل']);
            },
            'touristSpots',
        ])
            ->orderByDesc('trips_count')
            ->paginate(9);

        // جلب جميع الأماكن السياحية
        $touristSpots = TouristSpot::with('governorate')
            ->latest()
            ->get();

        // جلب الفئات الحقيقية للأماكن السياحية (العدّ يتم عبر الميثود في الـ Model)
        $categories = Category::all();

        return view('website.pages.provinces', compact('governorates', 'touristSpots', 'categories'));
    }

    public function show(Governorate $governorate): \Illuminate\Contracts\View\View
    {
        // جلب المحافظة مع الأماكن السياحية والرحلات
        $governorate->load([
            'touristSpots' => function ($query) {
                $query->latest();
            },
            'trips' => function ($query) {
                $query->whereIn('status', ['مقبولة', 'قيد التفعيل'])
                    ->latest()
                    ->limit(6);
            },
        ]);

        // إحصائيات المحافظة
        $governorate->places_count = $governorate->touristSpots->count();
        $governorate->trips_count = $governorate->trips->count();

        // جلب الأماكن السياحية مع الفئات
        $touristSpots = $governorate->touristSpots()->with('governorate')->latest()->get();

        // جلب الرحلات الخاصة بالمحافظة
        $trips = $governorate->trips()
            ->whereIn('status', ['مقبولة', 'قيد التفعيل'])
            ->with(['governorate', 'departureGovernorate'])
            ->latest()
            ->limit(6)
            ->get();

        return view('website.pages.show-provinces', compact('governorate', 'touristSpots', 'trips'));
    }
}

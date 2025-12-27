<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Governorate;
use App\Models\TouristSpot;
use App\Models\Trip;

class HomeController extends Controller
{
    public function index(): \Illuminate\Contracts\View\View
    {
        // جلب الرحلات المميزة أولاً، وإذا لم توجد، جلب الرحلات المقبولة أو قيد التفعيل
        $featuredTrips = Trip::whereIn('status', ['مقبولة', 'قيد التفعيل'])
            ->with(['governorate', 'departureGovernorate'])
            ->orderBy('is_featured', 'desc') // المميزة أولاً
            ->latest()
            ->limit(6)
            ->get();

        // جلب جميع المحافظات مع عدد الرحلات والأماكن السياحية
        $governorates = Governorate::withCount([
            'trips' => function ($query) {
                $query->whereIn('status', ['مقبولة', 'قيد التفعيل']);
            },
            'touristSpots',
        ])
            ->orderBy('trips_count', 'desc')
            ->get();

        // جلب جميع الأماكن السياحية
        $featuredTouristSpots = TouristSpot::with('governorate')
            ->latest()
            ->get();

        // إحصائيات
        $stats = [
            'governorates_count' => Governorate::whereHas('trips', function ($query) {
                $query->whereIn('status', ['مقبولة', 'قيد التفعيل']);
            })->count(),
            'trips_count' => Trip::whereIn('status', ['مقبولة', 'قيد التفعيل'])->count(),
            'travelers_count' => Booking::where('status', 'مؤكدة')->distinct('user_id')->count('user_id'),
            'average_rating' => 4.9, // يمكن حسابها من التقييمات في المستقبل
        ];

        return view('website.pages.home', compact(
            'featuredTrips',
            'governorates',
            'featuredTouristSpots',
            'stats'
        ));
    }
}

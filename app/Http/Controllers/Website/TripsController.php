<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Governorate;
use App\Models\Offer;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripsController extends Controller
{
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        // جلب المحافظات للفلتر
        $governorates = Governorate::orderBy('name')->get();

        // جلب الفئات للفلتر
        $categories = Category::orderBy('name')->get();

        // جلب العروض الخاصة (المفعلة فقط)
        // نتحقق من أن العرض مفعل وأن تاريخه صالح (لم ينته بعد)
        // ملاحظة: نسمح بالعروض التي لم تبدأ بعد أيضاً (start_date في المستقبل)
        $offers = Offer::where('status', 'مفعل')
            ->whereDate('end_date', '>=', now()->toDateString())
            ->whereHas('trip', function ($query) {
                $query->whereIn('status', ['مقبولة', 'قيد التفعيل']);
            })
            ->with(['trip' => function ($query) {
                $query->with(['governorate', 'departureGovernorate']);
            }])
            ->latest()
            ->limit(10)
            ->get();

        // جلب الرحلات مع الفلاتر
        $tripsQuery = Trip::whereIn('status', ['مقبولة', 'قيد التفعيل'])
            ->with(['governorate', 'departureGovernorate']);

        // فلتر حسب المحافظة
        if ($request->filled('province')) {
            $tripsQuery->where('governorate_id', $request->province);
        }

        // فلتر حسب نوع الرحلة (trip_types)
        if ($request->filled('type')) {
            $tripsQuery->whereJsonContains('trip_types', $request->type);
        }

        // فلتر حسب السعر
        if ($request->filled('price')) {
            $priceRange = $request->price;
            if ($priceRange === '0-5000') {
                $tripsQuery->where('price', '<=', 5000);
            } elseif ($priceRange === '5000-10000') {
                $tripsQuery->whereBetween('price', [5000, 10000]);
            } elseif ($priceRange === '10000-20000') {
                $tripsQuery->whereBetween('price', [10000, 20000]);
            } elseif ($priceRange === '20000+') {
                $tripsQuery->where('price', '>', 20000);
            }
        }

        // فلتر حسب المدة
        if ($request->filled('duration')) {
            $duration = $request->duration;
            if ($duration === '1-3') {
                $tripsQuery->whereBetween('duration_hours', [1, 3]);
            } elseif ($duration === '3-6') {
                $tripsQuery->whereBetween('duration_hours', [3, 6]);
            } elseif ($duration === '6-12') {
                $tripsQuery->whereBetween('duration_hours', [6, 12]);
            } elseif ($duration === '12+') {
                $tripsQuery->where('duration_hours', '>', 12);
            }
        }

        // جلب الرحلات مع pagination
        $trips = $tripsQuery->latest()->paginate(12)->withQueryString();

        // جلب إحصائيات الفئات
        // جلب جميع الرحلات المقبولة مرة واحدة لتحسين الأداء
        $allTrips = Trip::whereIn('status', ['مقبولة', 'قيد التفعيل'])
            ->whereNotNull('category_ids')
            ->get();

        $categoryStats = [];
        foreach ($categories as $category) {
            $count = 0;
            foreach ($allTrips as $trip) {
                $categoryIds = $trip->category_ids ?? [];
                // التحقق من وجود category_id في المصفوفة (كرقم أو كسلسلة)
                if (in_array($category->id, $categoryIds) || in_array((string) $category->id, $categoryIds)) {
                    $count++;
                }
            }
            $categoryStats[$category->id] = $count;
        }

        return view('website.pages.tripsandoffer', compact(
            'governorates',
            'categories',
            'offers',
            'trips',
            'categoryStats'
        ));
    }

    public function show(Trip $trip): \Illuminate\Contracts\View\View
    {
        // التحقق من أن الرحلة مقبولة أو قيد التفعيل
        if (! in_array($trip->status, ['مقبولة', 'قيد التفعيل'])) {
            abort(404, 'الرحلة غير متاحة');
        }

        // زيادة عدد المشاهدات
        $trip->increment('views_count');

        // تحميل العلاقات
        $trip->load([
            'governorate',
            'departureGovernorate',
        ]);

        // جلب الأماكن السياحية المضمنة في الرحلة
        $touristSpots = collect();
        if ($trip->included_places) {
            $placeIds = array_filter($trip->included_places, 'is_numeric');
            if (! empty($placeIds)) {
                $touristSpots = \App\Models\TouristSpot::whereIn('id', $placeIds)
                    ->with('governorate')
                    ->get();
            }
        }

        // جلب المحافظات التي سنمر بها
        $passingGovernorates = collect();
        if ($trip->passing_governorates) {
            $governorateIds = array_filter($trip->passing_governorates, 'is_numeric');
            if (! empty($governorateIds)) {
                $passingGovernorates = \App\Models\Governorate::whereIn('id', $governorateIds)->get();
            }
        }

        // جلب عروض خاصة بهذه الرحلة
        $offers = \App\Models\Offer::where('trip_id', $trip->id)
            ->where('status', 'مفعل')
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->latest()
            ->get();

        return view('website.pages.trip-show', compact(
            'trip',
            'touristSpots',
            'passingGovernorates',
            'offers'
        ));
    }
}

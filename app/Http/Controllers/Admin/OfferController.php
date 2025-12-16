<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OfferRequest;
use App\Models\Governorate;
use App\Models\Offer;
use App\Models\Trip;
use Illuminate\Http\Request;

class OfferController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Offer::with(['trip.governorate', 'trip.departureGovernorate', 'creator']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الرحلة
        if ($request->filled('trip_id')) {
            $query->where('trip_id', $request->trip_id);
        }

        // البحث
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        $offers = $query->latest()->paginate(15)->withQueryString();
        $trips = Trip::where('status', 'مقبولة')->orWhere('status', 'قيد التفعيل')->get();

        // إحصائيات
        $stats = [
            'total' => Offer::count(),
            'active' => Offer::where('status', 'مفعل')->count(),
            'expired' => Offer::where('status', 'منتهي')->count(),
        ];

        return view('admin.deals.index', compact('offers', 'trips', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trips = Trip::where('status', 'مقبولة')->orWhere('status', 'قيد التفعيل')->with(['governorate', 'departureGovernorate'])->get();
        $governorates = Governorate::all();

        return view('admin.deals.create', compact('trips', 'governorates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OfferRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        // معالجة الأماكن المضمنة - تصفية القيم الفارغة
        if ($request->filled('custom_included_places')) {
            $places = array_filter($request->custom_included_places, function ($value) {
                return ! empty($value) && is_numeric($value);
            });
            $data['custom_included_places'] = ! empty($places) ? array_values($places) : null;
        } else {
            $data['custom_included_places'] = null;
        }

        // معالجة الميزات - تصفية القيم الفارغة
        if ($request->filled('custom_features')) {
            $features = array_filter($request->custom_features, function ($value) {
                return ! empty(trim($value));
            });
            $data['custom_features'] = ! empty($features) ? array_values($features) : null;
        } else {
            $data['custom_features'] = null;
        }

        $offer = Offer::create($data);

        return redirect()->route('admin.deals.index')
            ->with('success', 'تم إنشاء العرض بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Offer $deal)
    {
        $deal->load(['trip.governorate', 'trip.departureGovernorate', 'creator', 'customDepartureGovernorate']);

        return view('admin.deals.show', compact('deal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offer $deal)
    {
        $trips = Trip::where('status', 'مقبولة')->orWhere('status', 'قيد التفعيل')->with(['governorate', 'departureGovernorate'])->get();
        $governorates = Governorate::all();
        $deal->load(['trip.governorate', 'trip.departureGovernorate', 'customDepartureGovernorate']);

        return view('admin.deals.edit', compact('deal', 'trips', 'governorates'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OfferRequest $request, Offer $deal)
    {
        $data = $request->validated();

        // معالجة الأماكن المضمنة - تصفية القيم الفارغة
        if ($request->filled('custom_included_places')) {
            $places = array_filter($request->custom_included_places, function ($value) {
                return ! empty($value) && is_numeric($value);
            });
            $data['custom_included_places'] = ! empty($places) ? array_values($places) : null;
        } else {
            $data['custom_included_places'] = null;
        }

        // معالجة الميزات - تصفية القيم الفارغة
        if ($request->filled('custom_features')) {
            $features = array_filter($request->custom_features, function ($value) {
                return ! empty(trim($value));
            });
            $data['custom_features'] = ! empty($features) ? array_values($features) : null;
        } else {
            $data['custom_features'] = null;
        }

        $deal->update($data);

        return redirect()->route('admin.deals.index')
            ->with('success', 'تم تحديث العرض بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $deal)
    {
        $deal->delete();

        return redirect()->route('admin.deals.index')
            ->with('success', 'تم حذف العرض بنجاح.');
    }

    /**
     * تغيير حالة العرض
     */
    public function changeStatus(Request $request, Offer $deal)
    {
        $request->validate([
            'status' => 'required|in:مفعل,منتهي',
        ]);

        $deal->update(['status' => $request->status]);

        return redirect()->route('admin.deals.show', $deal)
            ->with('success', 'تم تحديث حالة العرض بنجاح.');
    }

    /**
     * جلب تفاصيل الرحلة (AJAX)
     */
    public function getTripDetails(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
        ]);

        $trip = Trip::with(['governorate', 'departureGovernorate'])->findOrFail($request->trip_id);

        return response()->json([
            'success' => true,
            'trip' => [
                'id' => $trip->id,
                'title' => $trip->title,
                'price' => $trip->price,
                'included_places' => $trip->included_places ?? [],
                'features' => $trip->features ?? [],
                'start_time' => $trip->start_time ? \Carbon\Carbon::parse($trip->start_time)->format('H:i') : null,
                'duration_hours' => $trip->duration_hours,
                'max_persons' => $trip->max_persons,
                'meeting_point' => $trip->meeting_point,
                'departure_governorate_id' => $trip->departure_governorate_id,
                'governorate_id' => $trip->governorate_id,
                'passing_governorates' => $trip->passing_governorates ?? [],
            ],
        ]);
    }
}

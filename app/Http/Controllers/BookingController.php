<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Offer;
use App\Models\Trip;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('trip')
            ->latest()
            ->paginate(10);

        return view('website.user.booking.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        if (! Auth::user()->identity_verified) {
            return redirect()->route('identity-verification.create')
                ->with('error', 'يجب توثيق هويتك أولاً قبل الحجز');
        }

        // جلب جميع الرحلات المتاحة (مقبولة أو قيد التفعيل)
        // إزالة شرط start_date لجلب جميع الرحلات المتاحة
        $trips = Trip::whereIn('status', ['مقبولة', 'قيد التفعيل'])
            ->with('governorate')
            ->latest()
            ->get();

        // جلب جميع العروض المفعلة التي لم تنتهي
        $offers = Offer::where('status', 'مفعل')
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->whereHas('trip', function ($q) {
                $q->whereIn('status', ['مقبولة', 'قيد التفعيل']);
            })
            ->with(['trip.governorate', 'customDepartureGovernorate'])
            ->latest()
            ->get();

        // معلومات تصحيح
        \Log::info('Booking Create - Data Count', [
            'trips_count' => $trips->count(),
            'offers_count' => $offers->count(),
            'total_items' => $trips->count() + $offers->count(),
            'trips_ids' => $trips->pluck('id')->toArray(),
            'offers_ids' => $offers->pluck('id')->toArray(),
        ]);

        // دمج الرحلات والعروض في قائمة واحدة
        $allItems = collect();

        // إضافة الرحلات
        foreach ($trips as $trip) {
            $tripTypes = is_array($trip->trip_types) ? $trip->trip_types : [];
            $primaryType = ! empty($tripTypes) ? $tripTypes[0] : 'سياحية';

            $allItems->push([
                'id' => $trip->id,
                'type' => 'trip',
                'title' => $trip->title,
                'description' => $trip->description,
                'trip_type' => $primaryType,
                'trip_types' => $tripTypes,
                'price' => (float) $trip->price,
                'duration' => $trip->duration_hours ?? 0,
                'max_capacity' => $trip->max_persons ?? 0,
                'province' => $trip->governorate->name ?? '',
                'images' => $trip->images ?? [],
                'model' => $trip,
            ]);
        }

        // إضافة العروض
        foreach ($offers as $offer) {
            $trip = $offer->trip;
            $tripTypes = is_array($trip->trip_types) ? $trip->trip_types : [];
            $primaryType = ! empty($tripTypes) ? $tripTypes[0] : 'سياحية';

            $allItems->push([
                'id' => 'offer_'.$offer->id,
                'type' => 'offer',
                'offer_id' => $offer->id,
                'trip_id' => $trip->id,
                'title' => $offer->title,
                'description' => $offer->description,
                'trip_type' => $primaryType,
                'trip_types' => $tripTypes,
                'price' => (float) $offer->getFinalPrice(),
                'original_price' => (float) $trip->price,
                'discount_percentage' => (float) $offer->discount_percentage,
                'duration' => $offer->getDurationHours(),
                'max_capacity' => $offer->getMaxPersons(),
                'province' => $trip->governorate->name ?? '',
                'images' => $trip->images ?? [],
                'model' => $offer,
            ]);
        }

        // ترتيب حسب التاريخ
        $allItems = $allItems->sortByDesc(function ($item) {
            return $item['model']->created_at ?? now();
        })->values();

        // تحضير بيانات للجافاسكريبت
        $tripsData = $allItems->map(function ($item) {
            return [
                'id' => (string) $item['id'], // تحويل إلى سلسلة لضمان المقارنة الصحيحة
                'type' => $item['type'],
                'trip_id' => $item['trip_id'] ?? $item['id'],
                'title' => $item['title'],
                'description' => $item['description'],
                'trip_type' => $item['trip_type'],
                'trip_types' => $item['trip_types'] ?? [],
                'price' => $item['price'],
                'original_price' => $item['original_price'] ?? $item['price'],
                'discount_percentage' => $item['discount_percentage'] ?? 0,
                'duration' => $item['duration'],
                'max_capacity' => $item['max_capacity'],
                'province' => $item['province'],
                'images' => $item['images'],
            ];
        })->values();

        // جلب المحافظات
        $provinces = \App\Models\Governorate::all();

        // معاملات URL للانتقال المباشر إلى رحلة/عرض محدد
        $selectedTripId = $request->query('trip_id');
        $selectedOfferId = $request->query('offer_id');

        return view('website.user.booking.create', compact(
            'allItems',
            'provinces',
            'tripsData',
            'trips',
            'offers',
            'selectedTripId',
            'selectedOfferId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'trip_id' => ['required', 'exists:trips,id'],
            'guest_count' => ['required', 'integer', 'min:1'],
            'booking_date' => ['required', 'date', 'after:today'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ]);

        $trip = Trip::findOrFail($request->trip_id);

        // التحقق من الحد الأقصى للأشخاص
        if ($request->guest_count > $trip->max_persons) {
            return back()->withErrors(['guest_count' => 'عدد الأشخاص يتجاوز الحد الأقصى للرحلة'])->withInput();
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'trip_id' => $trip->id,
            'guest_count' => $request->guest_count,
            'booking_date' => $request->booking_date,
            'total_price' => $trip->price * $request->guest_count,
            'special_requests' => $request->special_requests,
            'status' => 'معلقة',
        ]);

        // زيادة عدد حجوزات المستخدم
        /** @var User $user */
        $user = Auth::user();
        $user->incrementBookings();

        // إرسال إشعار للمسؤولين
        NotificationService::notifyNewBooking($booking);

        return redirect()->route('my-bookings')
            ->with('success', 'تم إنشاء الحجز بنجاح، بانتظار التأكيد من المسؤول');
    }

    public function show(Booking $booking)
    {
        // التحقق من أن الحجز يخص المستخدم الحالي
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بالوصول إلى هذا الحجز');
        }

        $booking->load([
            'trip.governorate',
            'trip.departureGovernorate',
            'user',
        ]);

        // جلب الأماكن السياحية المضمنة في الرحلة
        $touristSpots = collect();
        if ($booking->trip && $booking->trip->included_places) {
            $placeIds = array_filter($booking->trip->included_places, 'is_numeric');
            if (! empty($placeIds)) {
                $touristSpots = \App\Models\TouristSpot::whereIn('id', $placeIds)
                    ->with('governorate')
                    ->get();
            }
        }

        // جلب المحافظات التي سنمر بها
        $passingGovernorates = collect();
        if ($booking->trip && $booking->trip->passing_governorates) {
            $governorateIds = array_filter($booking->trip->passing_governorates, 'is_numeric');
            if (! empty($governorateIds)) {
                $passingGovernorates = \App\Models\Governorate::whereIn('id', $governorateIds)->get();
            }
        }

        return view('website.user.booking.show', compact('booking', 'touristSpots', 'passingGovernorates'));
    }

    public function edit(Booking $booking)
    {
        // التحقق من أن الحجز يخص المستخدم الحالي
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا الحجز');
        }

        // فقط الحجوزات المعلقة يمكن تعديلها
        if ($booking->status !== 'معلقة') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'لا يمكن تعديل الحجز المؤكد أو المرفوض');
        }

        $booking->load('trip');

        return view('website.user.booking.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        // التحقق من أن الحجز يخص المستخدم الحالي
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا الحجز');
        }

        // فقط الحجوزات المعلقة يمكن تعديلها
        if ($booking->status !== 'معلقة') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'لا يمكن تعديل الحجز المؤكد أو المرفوض');
        }

        $booking->load('trip');

        $request->validate([
            'guest_count' => ['required', 'integer', 'min:1', 'max:'.$booking->trip->max_persons],
            'booking_date' => ['required', 'date', 'after:today'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ]);

        $booking->update([
            'guest_count' => $request->guest_count,
            'booking_date' => $request->booking_date,
            'total_price' => $booking->trip->price * $request->guest_count,
            'special_requests' => $request->special_requests,
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'تم تحديث الحجز بنجاح');
    }

    public function destroy(Booking $booking)
    {
        // التحقق من أن الحجز يخص المستخدم الحالي
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بحذف هذا الحجز');
        }

        // فقط الحجوزات المعلقة يمكن حذفها
        if ($booking->status !== 'معلقة') {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'لا يمكن حذف الحجز المؤكد أو المرفوض');
        }

        $booking->delete();

        return redirect()->route('my-bookings')
            ->with('success', 'تم حذف الحجز بنجاح');
    }
}

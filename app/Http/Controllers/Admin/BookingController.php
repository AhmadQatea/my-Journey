<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Offer;
use App\Models\Trip;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends AdminController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'trip.governorate']);

        // Filtering
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('full_name', 'like', '%'.$request->search.'%')
                        ->orWhere('email', 'like', '%'.$request->search.'%')
                        ->orWhere('phone', 'like', '%'.$request->search.'%');
                })
                    ->orWhereHas('trip', function ($tripQuery) use ($request) {
                        $tripQuery->where('title', 'like', '%'.$request->search.'%');
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('trip_id')) {
            $query->where('trip_id', $request->trip_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'معلقة')->count(),
            'confirmed' => Booking::where('status', 'مؤكدة')->count(),
            'rejected' => Booking::where('status', 'مرفوضة')->count(),
            'cancelled' => Booking::where('status', 'ملغاة')->count(),
        ];

        $trips = Trip::where('status', 'مقبولة')->orWhere('status', 'قيد التفعيل')->get();
        $users = User::all();

        return view('admin.bookings.index', compact('bookings', 'stats', 'trips', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $trips = Trip::where('status', 'مقبولة')->orWhere('status', 'قيد التفعيل')->with('governorate')->get();

        // جلب العروض المفعلة والمرتبطة برحلات مقبولة أو قيد التفعيل
        $offers = Offer::where('status', 'مفعل')
            ->whereHas('trip', function ($query) {
                $query->whereIn('status', ['مقبولة', 'قيد التفعيل']);
            })
            ->with(['trip.governorate'])
            ->get();

        // تسجيل للتحقق (يمكن حذفه لاحقاً)
        Log::info('Booking Create - Offers Count', [
            'total_offers' => $offers->count(),
            'offers' => $offers->map(function ($offer) {
                return [
                    'id' => $offer->id,
                    'title' => $offer->title,
                    'status' => $offer->status,
                    'start_date' => $offer->start_date?->format('Y-m-d'),
                    'end_date' => $offer->end_date?->format('Y-m-d'),
                    'trip_id' => $offer->trip_id,
                    'trip_status' => $offer->trip?->status,
                ];
            })->toArray(),
        ]);

        $users = User::all();

        return view('admin.bookings.create', compact('trips', 'offers', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookingRequest $request)
    {
        $data = $request->validated();

        // إذا كان الحجز من المسؤول، يكون مؤكد مباشرة
        $data['status'] = 'مؤكدة';
        $data['created_by_admin'] = true;
        $data['created_by_admin_id'] = Auth::id();
        $data['confirmed_by_admin_id'] = Auth::id(); // عند الإنشاء من المسؤول، يعتبر مؤكد تلقائياً

        $booking = Booking::create($data);

        // إرسال إشعار للمسؤول الكبير
        NotificationService::notifyAdminAction(
            Auth::guard('admin')->user(),
            'create',
            'booking',
            $booking->id,
            route('admin.bookings.show', $booking)
        );

        // إرسال إشعار للمستخدم بأنه تم إنشاء حجز له
        if ($booking->user_id) {
            NotificationService::notifyBookingConfirmed($booking);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'تم إنشاء الحجز بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['user', 'trip.governorate', 'trip.departureGovernorate', 'adminCreator', 'adminConfirmer']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $trips = Trip::where('status', 'مقبولة')->orWhere('status', 'قيد التفعيل')->with('governorate')->get();
        $offers = Offer::where('status', 'مفعل')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->with(['trip.governorate'])
            ->get();
        $users = User::all();

        return view('admin.bookings.edit', compact('booking', 'trips', 'offers', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookingRequest $request, Booking $booking)
    {
        $data = $request->validated();

        // إذا تم رفض الحجز، يجب إضافة سبب الرفض
        if ($data['status'] === 'مرفوضة' && empty($data['rejection_reason'])) {
            return redirect()->back()
                ->withErrors(['rejection_reason' => 'سبب الرفض مطلوب عند رفض الحجز.'])
                ->withInput();
        }

        $oldData = $booking->toArray();
        $booking->update($data);

        // إرسال إشعار للمسؤول الكبير
        NotificationService::notifyAdminAction(
            Auth::guard('admin')->user(),
            'update',
            'booking',
            $booking->id,
            route('admin.bookings.show', $booking)
        );

        // إرسال إشعار للمستخدم عند التغييرات
        if ($booking->user_id) {
            $changes = [];
            if (isset($data['guest_count']) && $oldData['guest_count'] != $data['guest_count']) {
                $changes['guest_count'] = $data['guest_count'];
            }
            if (isset($data['booking_date']) && $oldData['booking_date'] != $data['booking_date']) {
                $changes['booking_date'] = $data['booking_date'];
            }
            if (isset($data['total_price']) && $oldData['total_price'] != $data['total_price']) {
                $changes['total_price'] = $data['total_price'];
            }

            if (! empty($changes)) {
                NotificationService::notifyBookingUpdated($booking, $changes);
            }
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'تم تحديث الحجز بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $bookingId = $booking->id;
        $booking->delete();

        // إرسال إشعار للمسؤول الكبير
        NotificationService::notifyAdminAction(
            Auth::guard('admin')->user(),
            'delete',
            'booking',
            $bookingId,
            route('admin.bookings.index')
        );

        return redirect()->route('admin.bookings.index')
            ->with('success', 'تم حذف الحجز بنجاح.');
    }

    /**
     * تحديث حالة الحجز
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:معلقة,مؤكدة,مرفوضة,ملغاة',
            'rejection_reason' => 'nullable|string|max:500|required_if:status,مرفوضة',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'مرفوضة' && $request->filled('rejection_reason')) {
            $data['rejection_reason'] = $request->rejection_reason;
            $data['confirmed_by_admin_id'] = null; // إلغاء التأكيد عند الرفض
        } else {
            $data['rejection_reason'] = null;
        }

        // إذا تم التأكيد، سجل المسؤول الذي أكد
        if ($request->status === 'مؤكدة' && ! $booking->confirmed_by_admin_id) {
            $data['confirmed_by_admin_id'] = Auth::id();
        }

        $oldStatus = $booking->status;
        $oldData = $booking->toArray();
        $booking->update($data);
        $booking->refresh();

        // إرسال إشعار للمستخدم عند تغيير الحالة
        if ($oldStatus !== $request->status && $booking->user_id) {
            if ($request->status === 'مؤكدة') {
                NotificationService::notifyBookingConfirmed($booking);
            } elseif ($request->status === 'مرفوضة') {
                NotificationService::notifyBookingRejected($booking, $request->rejection_reason ?? null);
            }
        }

        return redirect()->back()
            ->with('success', 'تم تحديث حالة الحجز بنجاح.');
    }
}

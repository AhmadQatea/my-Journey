<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Offer;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $offers = Offer::where('status', 'مفعل')
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->with(['trip.governorate'])
            ->get();
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

        $booking->update($data);

        return redirect()->route('admin.bookings.index')
            ->with('success', 'تم تحديث الحجز بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();

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

        $booking->update($data);

        return redirect()->back()
            ->with('success', 'تم تحديث حالة الحجز بنجاح.');
    }
}

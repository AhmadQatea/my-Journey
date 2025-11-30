<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
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

        return view('bookings.index', compact('bookings'));
    }

    public function create(Trip $trip)
    {
        if (! Auth::user()->identity_verified) {
            return redirect()->route('profile.edit')
                ->with('error', 'يجب توثيق هويتك أولاً قبل الحجز');
        }

        return view('bookings.create', compact('trip'));
    }

    public function store(Request $request, Trip $trip)
    {
        $request->validate([
            'guest_count' => ['required', 'integer', 'min:1', 'max:'.$trip->max_persons],
            'booking_date' => ['required', 'date', 'after:today'],
            'special_requests' => ['nullable', 'string', 'max:500'],
        ]);

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

        return redirect()->route('my-bookings')
            ->with('success', 'تم إنشاء الحجز بنجاح، بانتظار التأكيد من المسؤول');
    }
}

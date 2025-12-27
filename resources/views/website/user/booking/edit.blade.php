@extends('website.user.layouts.user')

@section('title', 'تعديل الحجز - MyJourney')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/booking.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div class="header-content">
        <h2 class="page-title">تعديل الحجز</h2>
        <p class="page-subtitle">تحديث معلومات حجزك</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-new-trip">
            <i class="fas fa-arrow-right"></i>
            <span>العودة</span>
        </a>
    </div>
</div>

<div class="booking-edit-form">
    <form action="{{ route('bookings.update', $booking) }}" method="POST" id="booking-form">
        @csrf
        @method('PUT')

        <div class="form-section">
            <div class="form-header">
                <h3>معلومات الرحلة</h3>
            </div>
            <div class="trip-info">
                <h4>{{ $booking->trip->title ?? 'رحلة محذوفة' }}</h4>
                <p>{{ $booking->trip->description ?? '' }}</p>
                <div class="trip-details">
                    <span><i class="fas fa-money-bill-wave"></i> السعر: {{ number_format($booking->trip->price ?? 0) }} ل.س</span>
                    <span><i class="fas fa-users"></i> الحد الأقصى: {{ $booking->trip->max_persons ?? 0 }} شخص</span>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-group">
                <label for="booking_date">
                    <i class="fas fa-calendar-day"></i>
                    تاريخ الرحلة
                </label>
                <input type="date" id="booking_date" name="booking_date"
                       class="form-input"
                       value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}"
                       required
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                <div class="form-hint">اختر تاريخاً بعد اليوم</div>
            </div>

            <div class="form-group">
                <label for="guest_count">
                    <i class="fas fa-user-friends"></i>
                    عدد الأشخاص
                </label>
                <div class="guest-counter">
                    <button type="button" class="counter-btn minus-btn" id="guest-minus">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" id="guest_count" name="guest_count"
                           class="counter-input"
                           value="{{ old('guest_count', $booking->guest_count) }}"
                           min="1"
                           max="{{ $booking->trip->max_persons ?? 20 }}"
                           required readonly>
                    <button type="button" class="counter-btn plus-btn" id="guest-plus">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <div class="form-hint">الحد الأقصى {{ $booking->trip->max_persons ?? 20 }} شخص</div>
            </div>

            <div class="form-group">
                <label for="special_requests">
                    <i class="fas fa-comment-alt"></i>
                    طلبات خاصة
                </label>
                <textarea id="special_requests" name="special_requests"
                          class="form-textarea"
                          placeholder="أي طلبات خاصة أو احتياجات إضافية..."
                          rows="3">{{ old('special_requests', $booking->special_requests) }}</textarea>
            </div>
        </div>

        <div class="form-section">
            <div class="price-summary">
                <div class="summary-item">
                    <span>سعر الفرد:</span>
                    <span>{{ number_format($booking->trip->price ?? 0) }} ل.س</span>
                </div>
                <div class="summary-item">
                    <span>عدد الأشخاص:</span>
                    <span id="summary-guest-count">{{ $booking->guest_count }}</span>
                </div>
                <div class="summary-item total">
                    <span>المبلغ الإجمالي:</span>
                    <span id="summary-total-price">{{ number_format($booking->total_price) }} ل.س</span>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-submit">
                <i class="fas fa-save"></i>
                حفظ التعديلات
            </button>
            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-cancel">
                إلغاء
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const tripPrice = {{ $booking->trip->price ?? 0 }};
    const maxPersons = {{ $booking->trip->max_persons ?? 20 }};
    const guestCountInput = document.getElementById('guest_count');
    const guestMinusBtn = document.getElementById('guest-minus');
    const guestPlusBtn = document.getElementById('guest-plus');
    const summaryGuestCount = document.getElementById('summary-guest-count');
    const summaryTotalPrice = document.getElementById('summary-total-price');

    function updateTotalPrice() {
        const guestCount = parseInt(guestCountInput.value);
        const totalPrice = tripPrice * guestCount;
        summaryGuestCount.textContent = guestCount;
        summaryTotalPrice.textContent = totalPrice.toLocaleString() + ' ل.س';
    }

    guestPlusBtn.addEventListener('click', function() {
        let value = parseInt(guestCountInput.value);
        if (value < maxPersons) {
            guestCountInput.value = value + 1;
            updateTotalPrice();
        }
    });

    guestMinusBtn.addEventListener('click', function() {
        let value = parseInt(guestCountInput.value);
        if (value > 1) {
            guestCountInput.value = value - 1;
            updateTotalPrice();
        }
    });

    guestCountInput.addEventListener('change', updateTotalPrice);
</script>
@endpush


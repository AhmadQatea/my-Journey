<x-admin.edit-form
    title="تعديل الحجز"
    :page-title="'تعديل الحجز: #' . $booking->id"
    :action="route('admin.bookings.update', $booking)"
    :model="$booking"
    :back-route="route('admin.bookings.index')"
    submit-text="حفظ التعديلات"
    layout="grid"
>
    <!-- Basic Information -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">المعلومات الأساسية</h3>
        </div>
        <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">المستخدم *</label>
                                <select name="user_id"
                                        id="user_id"
                                        class="form-control form-select @error('user_id') is-invalid @enderror"
                                        required>
                                    <option value="">اختر المستخدم</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $booking->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->full_name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">الرحلة أو العرض *</label>
                                <select name="trip_id"
                                        id="trip_id"
                                        class="form-control form-select @error('trip_id') is-invalid @enderror"
                                        required
                                        onchange="calculatePrice()">
                                    <option value="">اختر الرحلة أو العرض</option>
                                    @if($trips->count() > 0)
                                        <optgroup label="الرحلات">
                                            @foreach($trips as $trip)
                                                <option value="{{ $trip->id }}"
                                                        data-price="{{ $trip->price }}"
                                                        data-type="trip"
                                                        {{ old('trip_id', $booking->trip_id) == $trip->id ? 'selected' : '' }}>
                                                    {{ $trip->title }} - {{ $trip->governorate->name }} ({{ number_format($trip->price, 0) }} ل.س)
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                    @if($offers->count() > 0)
                                        <optgroup label="العروض الخاصة">
                                            @foreach($offers as $offer)
                                                @php
                                                    $finalPrice = $offer->getFinalPrice();
                                                @endphp
                                                <option value="{{ $offer->trip_id }}"
                                                        data-price="{{ $finalPrice }}"
                                                        data-type="offer"
                                                        data-offer-id="{{ $offer->id }}"
                                                        {{ old('trip_id', $booking->trip_id) == $offer->trip_id ? 'selected' : '' }}>
                                                    {{ $offer->title }} - {{ $offer->trip->governorate->name ?? 'N/A' }} 
                                                    ({{ number_format($finalPrice, 0) }} ل.س)
                                                    @if($offer->discount_percentage > 0)
                                                        - خصم {{ $offer->discount_percentage }}%
                                                    @endif
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                </select>
                                @error('trip_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">عدد الضيوف *</label>
                                <input type="number"
                                       name="guest_count"
                                       id="guest_count"
                                       class="form-control @error('guest_count') is-invalid @enderror"
                                       value="{{ old('guest_count', $booking->guest_count) }}"
                                       min="1"
                                       max="100"
                                       required
                                       onchange="calculatePrice()">
                                @error('guest_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">تاريخ الحجز *</label>
                                <input type="date"
                                       name="booking_date"
                                       class="form-control @error('booking_date') is-invalid @enderror"
                                       value="{{ old('booking_date', $booking->booking_date->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('booking_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">السعر الإجمالي *</label>
                            <input type="number"
                                   name="total_price"
                                   id="total_price"
                                   class="form-control @error('total_price') is-invalid @enderror"
                                   value="{{ old('total_price', $booking->total_price) }}"
                                   step="0.01"
                                   min="0"
                                   required>
                            @error('total_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">حالة الحجز *</label>
                            <select name="status"
                                    id="status"
                                    class="form-control form-select @error('status') is-invalid @enderror"
                                    required
                                    onchange="toggleRejectionReason()">
                                <option value="معلقة" {{ old('status', $booking->status) == 'معلقة' ? 'selected' : '' }}>معلقة</option>
                                <option value="مؤكدة" {{ old('status', $booking->status) == 'مؤكدة' ? 'selected' : '' }}>مؤكدة</option>
                                <option value="مرفوضة" {{ old('status', $booking->status) == 'مرفوضة' ? 'selected' : '' }}>مرفوضة</option>
                                <option value="ملغاة" {{ old('status', $booking->status) == 'ملغاة' ? 'selected' : '' }}>ملغاة</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" id="rejectionReasonGroup" style="display: {{ old('status', $booking->status) == 'مرفوضة' ? 'block' : 'none' }};">
                            <label class="form-label">سبب الرفض *</label>
                            <textarea name="rejection_reason"
                                      class="form-control @error('rejection_reason') is-invalid @enderror"
                                      rows="3"
                                      placeholder="أدخل سبب رفض الحجز...">{{ old('rejection_reason', $booking->rejection_reason) }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">الطلبات الخاصة</label>
                            <textarea name="special_requests"
                                      class="form-control @error('special_requests') is-invalid @enderror"
                                      rows="3"
                                      placeholder="أدخل أي طلبات خاصة من المستخدم...">{{ old('special_requests', $booking->special_requests) }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">ملاحظات المسؤول</label>
                            <textarea name="admin_notes"
                                      class="form-control @error('admin_notes') is-invalid @enderror"
                                      rows="3"
                                      placeholder="أدخل أي ملاحظات خاصة بالمسؤول...">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
        </div>
    </div>
</x-admin.edit-form>

@push('scripts')
<script>
    function calculatePrice() {
        const tripSelect = document.getElementById('trip_id');
        const guestCountInput = document.getElementById('guest_count');
        const totalPriceInput = document.getElementById('total_price');

        if (tripSelect.value && guestCountInput.value) {
            const tripPrice = parseFloat(tripSelect.options[tripSelect.selectedIndex].getAttribute('data-price')) || 0;
            const guestCount = parseInt(guestCountInput.value) || 1;
            const totalPrice = tripPrice * guestCount;
            totalPriceInput.value = totalPrice.toFixed(2);
        }
    }

    function toggleRejectionReason() {
        const statusSelect = document.getElementById('status');
        const rejectionReasonGroup = document.getElementById('rejectionReasonGroup');
        
        if (statusSelect.value === 'مرفوضة') {
            rejectionReasonGroup.style.display = 'block';
            rejectionReasonGroup.querySelector('textarea').required = true;
        } else {
            rejectionReasonGroup.style.display = 'none';
            rejectionReasonGroup.querySelector('textarea').required = false;
        }
    }

    // Calculate on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculatePrice();
        toggleRejectionReason();
    });
</script>
@endpush


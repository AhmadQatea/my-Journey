<x-admin.create-form
    title="إضافة حجز جديد"
    :action="route('admin.bookings.store')"
    :back-route="route('admin.bookings.index')"
    submit-text="حفظ الحجز"
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
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
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
                                                        {{ old('trip_id') == $trip->id ? 'selected' : '' }}>
                                                    {{ $trip->title }} - {{ $trip->governorate->name }} ({{ number_format($trip->price, 0) }} ل.س)
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                    @if($offers && $offers->count() > 0)
                                        <optgroup label="العروض الخاصة">
                                            @foreach($offers as $offer)
                                                @php
                                                    $finalPrice = $offer->getFinalPrice();
                                                @endphp
                                                <option value="{{ $offer->trip_id }}"
                                                        data-price="{{ $finalPrice }}"
                                                        data-type="offer"
                                                        data-offer-id="{{ $offer->id }}"
                                                        {{ old('trip_id') == $offer->trip_id ? 'selected' : '' }}>
                                                    {{ $offer->title }} - {{ $offer->trip->governorate->name ?? 'N/A' }}
                                                    ({{ number_format($finalPrice, 0) }} ل.س)
                                                    @if($offer->discount_percentage > 0)
                                                        - خصم {{ $offer->discount_percentage }}%
                                                    @endif
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        {{-- Debug: عرض معلومات العروض --}}
                                        @if(config('app.debug'))
                                            <!-- Debug: Offers count = {{ $offers->count() ?? 0 }} -->
                                        @endif
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
                                       value="{{ old('guest_count', 1) }}"
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
                                       value="{{ old('booking_date', date('Y-m-d')) }}"
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
                                   value="{{ old('total_price', 0) }}"
                                   step="0.01"
                                   min="0"
                                   required
                                   readonly>
                            @error('total_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-info-circle ml-1"></i>
                                سيتم حساب السعر تلقائياً بناءً على سعر الرحلة وعدد الضيوف
                            </p>
                        </div>

                        <div class="form-group">
                            <label class="form-label">الطلبات الخاصة</label>
                            <textarea name="special_requests"
                                      class="form-control @error('special_requests') is-invalid @enderror"
                                      rows="3"
                                      placeholder="أدخل أي طلبات خاصة من المستخدم...">{{ old('special_requests') }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">ملاحظات المسؤول</label>
                            <textarea name="admin_notes"
                                      class="form-control @error('admin_notes') is-invalid @enderror"
                                      rows="3"
                                      placeholder="أدخل أي ملاحظات خاصة بالمسؤول...">{{ old('admin_notes') }}</textarea>
                            @error('admin_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
        </div>
    </div>

    <x-slot name="sidebar">
        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">معلومات</h3>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle ml-1"></i>
                    <p class="text-sm">
                        الحجوزات التي ينشئها المسؤولون تكون <strong>مؤكدة</strong> تلقائياً.
                    </p>
                    <p class="text-sm mt-2">
                        يمكنك اختيار رحلة أو <strong>عرض خاص</strong> من القائمة.
                    </p>
                </div>
            </div>
        </div>
    </x-slot>
</x-admin.create-form>

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
        } else {
            totalPriceInput.value = '0.00';
        }
    }

    // Calculate on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculatePrice();
    });
</script>
@endpush


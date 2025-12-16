{{-- resources/views/admin/deals/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'تعديل العرض: ' . $deal->title)
@section('page-title', 'تعديل العرض: ' . $deal->title)

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">تعديل العرض</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $deal->title }}</p>
        </div>
        <a href="{{ route('admin.deals.index') }}"
           class="btn btn-outline inline-flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            <span>رجوع للقائمة</span>
        </a>
    </div>

    <form action="{{ route('admin.deals.update', $deal) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">المعلومات الأساسية</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">عنوان العرض *</label>
                            <input type="text"
                                   name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $deal->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">وصف العرض *</label>
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="5"
                                      required>{{ old('description', $deal->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">الرحلة المرتبطة</label>
                            <input type="text"
                                   class="form-control"
                                   value="{{ $deal->trip->title }} - {{ $deal->trip->governorate->name }}"
                                   disabled>
                            <input type="hidden" name="trip_id" value="{{ $deal->trip_id }}">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-info-circle ml-1"></i>
                                لا يمكن تغيير الرحلة المرتبطة بعد الإنشاء
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Offer Details -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">تفاصيل العرض</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">نسبة الخصم (%) *</label>
                                <input type="number"
                                       name="discount_percentage"
                                       id="discount_percentage"
                                       class="form-control @error('discount_percentage') is-invalid @enderror"
                                       value="{{ old('discount_percentage', $deal->discount_percentage) }}"
                                       min="0"
                                       max="100"
                                       step="0.01"
                                       required
                                       onchange="calculateFinalPrice()">
                                @error('discount_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">السعر المخصص (ل.س) <span class="text-xs text-gray-500">(اختياري)</span></label>
                                <input type="number"
                                       name="custom_price"
                                       id="custom_price"
                                       class="form-control @error('custom_price') is-invalid @enderror"
                                       value="{{ old('custom_price', $deal->custom_price) }}"
                                       min="0"
                                       step="0.01"
                                       onchange="calculateFinalPrice()">
                                @error('custom_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    <i class="fas fa-info-circle ml-1"></i>
                                    إذا تركت فارغاً، سيتم حساب السعر بناءً على خصم الرحلة
                                </p>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle ml-1"></i>
                            <span id="priceInfo">
                                السعر النهائي: {{ number_format($deal->getFinalPrice(), 0) }} ل.س
                                @if($deal->custom_price)
                                    (سعر مخصص)
                                @else
                                    (سعر الرحلة: {{ number_format($deal->trip->price, 0) }} ل.س - خصم: {{ $deal->discount_percentage }}%)
                                @endif
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">تاريخ البدء *</label>
                                <input type="date"
                                       name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', $deal->start_date->format('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">تاريخ الانتهاء *</label>
                                <input type="date"
                                       name="end_date"
                                       class="form-control @error('end_date') is-invalid @enderror"
                                       value="{{ old('end_date', $deal->end_date->format('Y-m-d')) }}"
                                       required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">حالة العرض *</label>
                            <select name="status"
                                    class="form-control form-select @error('status') is-invalid @enderror"
                                    required>
                                <option value="مفعل" {{ old('status', $deal->status) == 'مفعل' ? 'selected' : '' }}>مفعل</option>
                                <option value="منتهي" {{ old('status', $deal->status) == 'منتهي' ? 'selected' : '' }}>منتهي</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Customizations (Optional) -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">التخصيصات (اختياري)</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">يمكنك تخصيص بعض تفاصيل الرحلة للعرض</p>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle ml-1"></i>
                            <span>إذا تركت هذه الحقول فارغة، سيتم استخدام القيم من الرحلة الأصلية</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">وقت البدء المخصص</label>
                                <input type="time"
                                       name="custom_start_time"
                                       class="form-control @error('custom_start_time') is-invalid @enderror"
                                       value="{{ old('custom_start_time', $deal->custom_start_time ? \Carbon\Carbon::parse($deal->custom_start_time)->format('H:i') : '') }}">
                                @error('custom_start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">المدة المخصصة (ساعة)</label>
                                <input type="number"
                                       name="custom_duration_hours"
                                       class="form-control @error('custom_duration_hours') is-invalid @enderror"
                                       value="{{ old('custom_duration_hours', $deal->custom_duration_hours) }}"
                                       min="1">
                                @error('custom_duration_hours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">العدد الأقصى المخصص</label>
                                <input type="number"
                                       name="custom_max_persons"
                                       class="form-control @error('custom_max_persons') is-invalid @enderror"
                                       value="{{ old('custom_max_persons', $deal->custom_max_persons) }}"
                                       min="1"
                                       max="100">
                                @error('custom_max_persons')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">محافظة الانطلاق المخصصة</label>
                                <select name="custom_departure_governorate_id"
                                        class="form-control form-select @error('custom_departure_governorate_id') is-invalid @enderror">
                                    <option value="">استخدام قيمة الرحلة</option>
                                    @foreach($governorates as $gov)
                                        <option value="{{ $gov->id }}"
                                                {{ old('custom_departure_governorate_id', $deal->custom_departure_governorate_id) == $gov->id ? 'selected' : '' }}>
                                            {{ $gov->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('custom_departure_governorate_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">نقطة اللقاء المخصصة</label>
                            <textarea name="custom_meeting_point"
                                      class="form-control @error('custom_meeting_point') is-invalid @enderror"
                                      rows="3">{{ old('custom_meeting_point', $deal->custom_meeting_point) }}</textarea>
                            @error('custom_meeting_point')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Custom Included Places -->
                        <div class="form-group">
                            <label class="form-label">الأماكن المضمنة المخصصة</label>
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle ml-1"></i>
                                <span>الأماكن السياحية المتاحة من محافظات الرحلة</span>
                            </div>
                            <div id="customIncludedPlacesContainer" class="space-y-2">
                                @php
                                    $currentPlaces = old('custom_included_places', $deal->custom_included_places ?? []);
                                    if (empty($currentPlaces)) {
                                        $currentPlaces = [''];
                                    }
                                @endphp
                                @foreach($currentPlaces as $index => $placeId)
                                <div class="flex gap-2">
                                    <select name="custom_included_places[]"
                                            class="form-control form-select custom-included-place-select"
                                            data-selected-value="{{ $placeId }}">
                                        <option value="">اختر مكان سياحي</option>
                                    </select>
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            onclick="removeCustomPlace(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button"
                                    class="btn btn-outline btn-sm mt-3"
                                    onclick="addCustomPlace()">
                                <i class="fas fa-plus ml-1"></i>
                                إضافة مكان
                            </button>
                            @error('custom_included_places')
                                <div class="invalid-feedback mt-2">{{ $message }}</div>
                            @enderror
                            @error('custom_included_places.*')
                                <div class="invalid-feedback mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Custom Features -->
                        <div class="form-group">
                            <label class="form-label">الميزات المخصصة</label>
                            <div id="customFeaturesContainer" class="space-y-2">
                                @php
                                    $currentFeatures = old('custom_features', $deal->custom_features ?? []);
                                    if (empty($currentFeatures)) {
                                        $currentFeatures = [''];
                                    }
                                @endphp
                                @foreach($currentFeatures as $feature)
                                <div class="flex gap-2">
                                    <input type="text"
                                           name="custom_features[]"
                                           class="form-control"
                                           value="{{ $feature }}"
                                           placeholder="أدخل ميزة">
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            onclick="removeCustomFeature(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button"
                                    class="btn btn-outline btn-sm mt-3"
                                    onclick="addCustomFeature()">
                                <i class="fas fa-plus ml-1"></i>
                                إضافة ميزة
                            </button>
                            @error('custom_features')
                                <div class="invalid-feedback mt-2">{{ $message }}</div>
                            @enderror
                            @error('custom_features.*')
                                <div class="invalid-feedback mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body p-4">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-save ml-1"></i>
                            حفظ التعديلات
                        </button>
                    </div>
                </div>

                <!-- Trip Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">معلومات الرحلة المرتبطة</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="space-y-2 text-sm">
                            <div><strong>العنوان:</strong> {{ $deal->trip->title }}</div>
                            <div><strong>المحافظة:</strong> {{ $deal->trip->governorate->name }}</div>
                            <div><strong>السعر الأصلي:</strong> {{ number_format($deal->trip->price, 0) }} ل.س</div>
                            <div><strong>المدة:</strong> {{ $deal->trip->duration_hours }} ساعة</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
let tripTouristSpots = [];

// تحميل الأماكن السياحية عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    const trip = @json($deal->trip);
    const governorateId = trip.governorate_id;
    const passingGovernorates = trip.passing_governorates || [];

    loadTouristSpots(governorateId, passingGovernorates);
    calculateFinalPrice();
});

// تحميل الأماكن السياحية
function loadTouristSpots(governorateId, passingGovernorates) {
    const governorateIds = [governorateId];
    if (passingGovernorates && passingGovernorates.length > 0) {
        governorateIds.push(...passingGovernorates);
    }

    const params = new URLSearchParams();
    governorateIds.forEach(id => params.append('governorate_ids[]', id));
    params.append('main_governorate_id', governorateId);

    fetch('{{ route("admin.trips.tourist-spots.by-governorates") }}?' + params.toString(), {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            tripTouristSpots = data.tourist_spots;
            updateCustomIncludedPlacesSelects();
        }
    })
    .catch(error => {
        console.error('Error loading tourist spots:', error);
    });
}

// تحديث select boxes للأماكن المخصصة
function updateCustomIncludedPlacesSelects() {
    const options = tripTouristSpots.map(spot =>
        `<option value="${spot.id}">${spot.name} (${spot.governorate_name})</option>`
    ).join('');

    document.querySelectorAll('.custom-included-place-select').forEach(select => {
        const selectedValue = select.getAttribute('data-selected-value');
        const currentValue = selectedValue || select.value;
        select.innerHTML = '<option value="">اختر مكان سياحي</option>' + options;
        if (currentValue) {
            select.value = currentValue;
        }
        select.removeAttribute('data-selected-value');
    });
}

// حساب السعر النهائي
function calculateFinalPrice() {
    const customPrice = parseFloat(document.getElementById('custom_price').value) || 0;
    const discountPercentage = parseFloat(document.getElementById('discount_percentage').value) || 0;
    const tripPrice = {{ $deal->trip->price }};

    if (customPrice > 0) {
        document.getElementById('priceInfo').innerHTML = `
            <strong>السعر النهائي:</strong> ${customPrice.toLocaleString()} ل.س (سعر مخصص)
        `;
    } else {
        const discount = (tripPrice * discountPercentage) / 100;
        const finalPrice = tripPrice - discount;
        document.getElementById('priceInfo').innerHTML = `
            <strong>السعر النهائي:</strong> ${finalPrice.toLocaleString()} ل.س
            <br>
            <small>سعر الرحلة: ${tripPrice.toLocaleString()} ل.س - خصم: ${discountPercentage}%</small>
        `;
    }
}

// إدارة الأماكن المخصصة
function addCustomPlace() {
    const container = document.getElementById('customIncludedPlacesContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2';

    const firstSelect = container.querySelector('.custom-included-place-select');
    const options = firstSelect ? firstSelect.innerHTML : '<option value="">اختر مكان سياحي</option>';

    div.innerHTML = `
        <select name="custom_included_places[]" class="form-control form-select custom-included-place-select">
            ${options}
        </select>
        <button type="button" class="btn btn-danger btn-sm" onclick="removeCustomPlace(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeCustomPlace(button) {
    const container = document.getElementById('customIncludedPlacesContainer');
    if (container.children.length > 1) {
        button.closest('.flex').remove();
    } else {
        alert('يجب أن يكون هناك مكان واحد على الأقل');
    }
}

// إدارة الميزات المخصصة
function addCustomFeature() {
    const container = document.getElementById('customFeaturesContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2';

    div.innerHTML = `
        <input type="text" name="custom_features[]" class="form-control" placeholder="أدخل ميزة">
        <button type="button" class="btn btn-danger btn-sm" onclick="removeCustomFeature(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeCustomFeature(button) {
    const container = document.getElementById('customFeaturesContainer');
    if (container.children.length > 1) {
        button.closest('.flex').remove();
    }
}
</script>
@endpush


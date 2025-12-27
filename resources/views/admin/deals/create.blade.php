<x-admin.create-form
    title="إضافة عرض جديد"
    :action="route('admin.deals.store')"
    :back-route="route('admin.deals.index')"
    submit-text="حفظ العرض"
    layout="grid"
>
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
                                   value="{{ old('title') }}"
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
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">اختر الرحلة *</label>
                            <select name="trip_id"
                                    id="trip_id"
                                    class="form-control form-select @error('trip_id') is-invalid @enderror"
                                    required
                                    onchange="loadTripDetails()">
                                <option value="">اختر رحلة</option>
                                @foreach($trips as $trip)
                                    <option value="{{ $trip->id }}"
                                            data-governorate-id="{{ $trip->governorate_id }}"
                                            data-passing-governorates="{{ json_encode($trip->passing_governorates ?? []) }}"
                                            {{ old('trip_id') == $trip->id ? 'selected' : '' }}>
                                        {{ $trip->title }} - {{ $trip->governorate->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trip_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-info-circle ml-1"></i>
                                سيتم استخدام بيانات الرحلة المختارة كقيم افتراضية
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
                                       value="{{ old('discount_percentage') }}"
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
                                       value="{{ old('custom_price') }}"
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
                            <span id="priceInfo">السعر النهائي سيظهر هنا بعد اختيار الرحلة</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">تاريخ البدء *</label>
                                <input type="date"
                                       name="start_date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       value="{{ old('start_date', date('Y-m-d')) }}"
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
                                       value="{{ old('end_date') }}"
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
                                <option value="مفعل" {{ old('status', 'مفعل') == 'مفعل' ? 'selected' : '' }}>مفعل</option>
                                <option value="منتهي" {{ old('status') == 'منتهي' ? 'selected' : '' }}>منتهي</option>
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
                                       value="{{ old('custom_start_time') }}">
                                @error('custom_start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">المدة المخصصة (ساعة)</label>
                                <input type="number"
                                       name="custom_duration_hours"
                                       class="form-control @error('custom_duration_hours') is-invalid @enderror"
                                       value="{{ old('custom_duration_hours') }}"
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
                                       value="{{ old('custom_max_persons') }}"
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
                                                {{ old('custom_departure_governorate_id') == $gov->id ? 'selected' : '' }}>
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
                                      rows="3">{{ old('custom_meeting_point') }}</textarea>
                            @error('custom_meeting_point')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Custom Included Places -->
                        <div class="form-group">
                            <label class="form-label">الأماكن المضمنة المخصصة</label>
                            <div class="alert alert-info mb-4" id="placesInfoAlert">
                                <i class="fas fa-info-circle ml-1"></i>
                                <span id="placesInfoText">سيتم تحميل الأماكن السياحية بعد اختيار الرحلة</span>
                            </div>
                            <div id="customIncludedPlacesContainer" class="space-y-2">
                                <div class="flex gap-2">
                                    <select name="custom_included_places[]"
                                            class="form-control form-select custom-included-place-select">
                                        <option value="">اختر مكان سياحي</option>
                                    </select>
                                    <button type="button"
                                            class="btn btn-danger btn-sm"
                                            onclick="removeCustomPlace(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
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
                                @if(old('custom_features'))
                                    @foreach(old('custom_features') as $feature)
                                    <div class="flex gap-2">
                                        <input type="text"
                                               name="custom_features[]"
                                               class="form-control"
                                               value="{{ $feature }}">
                                        <button type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="removeCustomFeature(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="flex gap-2">
                                        <input type="text"
                                               name="custom_features[]"
                                               class="form-control"
                                               placeholder="أدخل ميزة">
                                        <button type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="removeCustomFeature(this)">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endif
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

    <x-slot name="sidebar">
        <!-- Trip Info Preview -->
        <div class="card" id="tripInfoPreview" style="display: none;">
            <div class="card-header">
                <h3 class="card-title">معلومات الرحلة المختارة</h3>
            </div>
            <div class="card-body p-4">
                <div id="tripInfoContent">
                    <!-- سيتم ملؤها بـ JavaScript -->
                </div>
            </div>
        </div>
    </x-slot>
</x-admin.create-form>

@push('scripts')
<script>
let selectedTrip = null;
let tripTouristSpots = [];

// تحميل تفاصيل الرحلة
function loadTripDetails() {
    const tripId = document.getElementById('trip_id').value;
    if (!tripId) {
        document.getElementById('tripInfoPreview').style.display = 'none';
        // إفراغ الأماكن والميزات
        clearCustomIncludedPlaces();
        clearCustomFeatures();
        return;
    }

    const selectedOption = document.getElementById('trip_id').options[document.getElementById('trip_id').selectedIndex];
    const governorateId = selectedOption.getAttribute('data-governorate-id');
    const passingGovernorates = JSON.parse(selectedOption.getAttribute('data-passing-governorates') || '[]');

    // جلب الأماكن السياحية أولاً
    loadTouristSpots(governorateId, passingGovernorates).then(() => {
        // بعد تحميل الأماكن السياحية، جلب تفاصيل الرحلة
        return fetch('{{ route("admin.deals.get-trip-details") }}?trip_id=' + tripId, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.trip) {
            selectedTrip = data.trip;

            // ملء الأماكن المضمنة من الرحلة (بعد تحميل الأماكن السياحية)
            if (data.trip.included_places && data.trip.included_places.length > 0) {
                loadTripIncludedPlaces(data.trip.included_places);
            } else {
                // إذا لم تكن هناك أماكن، أضف واحداً فارغاً
                const container = document.getElementById('customIncludedPlacesContainer');
                if (container && container.children.length === 0) {
                    addCustomPlace();
                }
            }

            // ملء الميزات من الرحلة
            if (data.trip.features && data.trip.features.length > 0) {
                loadTripFeatures(data.trip.features);
            } else {
                // إذا لم تكن هناك ميزات، أضف واحداً فارغاً
                const container = document.getElementById('customFeaturesContainer');
                if (container && container.children.length === 0) {
                    addCustomFeature();
                }
            }

            // تحديث السعر
            calculateFinalPrice();

            // عرض معلومات الرحلة
            showTripInfo(data.trip);
        }
    })
    .catch(error => {
        console.error('Error loading trip details:', error);
    });
}

// ملء الأماكن المضمنة من الرحلة
function loadTripIncludedPlaces(places) {
    const container = document.getElementById('customIncludedPlacesContainer');
    const infoText = document.getElementById('placesInfoText');

    if (!places || places.length === 0) {
        container.innerHTML = '';
        addCustomPlace();
        if (infoText) {
            infoText.textContent = 'لا توجد أماكن محددة في الرحلة. يمكنك إضافة أماكن مخصصة';
        }
        return;
    }

    container.innerHTML = '';

    if (infoText) {
        infoText.textContent = `تم تحميل ${places.length} مكان من الرحلة. يمكنك تعديلها أو إضافة أماكن جديدة`;
    }

    places.forEach((placeId) => {
        const div = document.createElement('div');
        div.className = 'flex gap-2';
        div.innerHTML = `
            <select name="custom_included_places[]" class="form-control form-select custom-included-place-select" data-place-id="${placeId}">
                <option value="">اختر مكان سياحي</option>
            </select>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeCustomPlace(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    });

    // تحديث select boxes بعد تحميل الأماكن السياحية
    function setPlaceValues() {
        if (tripTouristSpots.length > 0) {
            updateCustomIncludedPlacesSelects();
            // تعيين القيم بعد التحديث
            setTimeout(() => {
                places.forEach(placeId => {
                    document.querySelectorAll('.custom-included-place-select').forEach(select => {
                        const dataPlaceId = select.getAttribute('data-place-id');
                        if (dataPlaceId == placeId) {
                            if (tripTouristSpots.some(spot => spot.id == placeId)) {
                                select.value = placeId;
                            }
                            select.removeAttribute('data-place-id');
                        }
                    });
                });
            }, 50);
        } else {
            // إذا لم يتم تحميل الأماكن السياحية بعد، انتظر قليلاً
            setTimeout(setPlaceValues, 200);
        }
    }

    setPlaceValues();
}

// ملء الميزات من الرحلة
function loadTripFeatures(features) {
    const container = document.getElementById('customFeaturesContainer');

    if (!features || features.length === 0) {
        container.innerHTML = '';
        addCustomFeature();
        return;
    }

    container.innerHTML = '';

    features.forEach(feature => {
        if (feature && feature.trim()) {
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            // استخدام escapeHTML لتجنب مشاكل XSS
            const escapedFeature = feature.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
            div.innerHTML = `
                <input type="text" name="custom_features[]" class="form-control" value="${escapedFeature}" placeholder="أدخل ميزة">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeCustomFeature(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(div);
        }
    });

    // إذا لم تكن هناك ميزات بعد التصفية، أضف واحداً فارغاً
    if (container.children.length === 0) {
        addCustomFeature();
    }
}

// إفراغ الأماكن المضمنة
function clearCustomIncludedPlaces() {
    const container = document.getElementById('customIncludedPlacesContainer');
    container.innerHTML = '';
    addCustomPlace();
}

// إفراغ الميزات
function clearCustomFeatures() {
    const container = document.getElementById('customFeaturesContainer');
    container.innerHTML = '';
    addCustomFeature();
}

// تحديث خيارات select
function updateSelectOptions(select) {
    const options = tripTouristSpots.map(spot =>
        `<option value="${spot.id}">${spot.name} (${spot.governorate_name})</option>`
    ).join('');
    const currentValue = select.value;
    select.innerHTML = '<option value="">اختر مكان سياحي</option>' + options;
    if (currentValue) {
        select.value = currentValue;
    }
}

// عرض معلومات الرحلة
function showTripInfo(trip) {
    const preview = document.getElementById('tripInfoPreview');
    const content = document.getElementById('tripInfoContent');

    if (preview && content) {
        content.innerHTML = `
            <div class="space-y-2 text-sm">
                <div><strong>السعر:</strong> ${parseFloat(trip.price).toLocaleString()} ل.س</div>
                <div><strong>المدة:</strong> ${trip.duration_hours} ساعة</div>
                <div><strong>العدد الأقصى:</strong> ${trip.max_persons} شخص</div>
                ${trip.start_time ? `<div><strong>وقت البدء:</strong> ${trip.start_time}</div>` : ''}
            </div>
        `;
        preview.style.display = 'block';
    }
}

// تحميل الأماكن السياحية
function loadTouristSpots(governorateId, passingGovernorates) {
    const governorateIds = [governorateId];
    if (passingGovernorates && passingGovernorates.length > 0) {
        governorateIds.push(...passingGovernorates);
    }

    const params = new URLSearchParams();
    governorateIds.forEach(id => params.append('governorate_ids[]', id));
    params.append('main_governorate_id', governorateId);

    return fetch('{{ route("admin.trips.tourist-spots.by-governorates") }}?' + params.toString(), {
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
            return data;
        }
        return { success: false };
    })
    .catch(error => {
        console.error('Error loading tourist spots:', error);
        return { success: false };
    });
}

// تحديث select boxes للأماكن المخصصة
function updateCustomIncludedPlacesSelects() {
    if (tripTouristSpots.length === 0) {
        return;
    }

    const options = tripTouristSpots.map(spot =>
        `<option value="${spot.id}">${spot.name} (${spot.governorate_name})</option>`
    ).join('');

    document.querySelectorAll('.custom-included-place-select').forEach(select => {
        const currentValue = select.value;
        select.innerHTML = '<option value="">اختر مكان سياحي</option>' + options;
        if (currentValue) {
            select.value = currentValue;
        }
    });
}

// حساب السعر النهائي
function calculateFinalPrice() {
    const customPrice = parseFloat(document.getElementById('custom_price').value) || 0;
    const discountPercentage = parseFloat(document.getElementById('discount_percentage').value) || 0;
    const tripId = document.getElementById('trip_id').value;

    if (!tripId) {
        document.getElementById('priceInfo').textContent = 'السعر النهائي سيظهر هنا بعد اختيار الرحلة';
        return;
    }

    if (customPrice > 0) {
        document.getElementById('priceInfo').innerHTML = `
            <strong>السعر النهائي:</strong> ${customPrice.toLocaleString()} ل.س (سعر مخصص)
        `;
    } else if (selectedTrip && selectedTrip.price) {
        const tripPrice = parseFloat(selectedTrip.price);
        const discount = (tripPrice * discountPercentage) / 100;
        const finalPrice = tripPrice - discount;
        document.getElementById('priceInfo').innerHTML = `
            <strong>السعر النهائي:</strong> ${finalPrice.toLocaleString()} ل.س
            <br>
            <small class="text-gray-500">سعر الرحلة: ${tripPrice.toLocaleString()} ل.س - خصم: ${discountPercentage}%</small>
        `;
    } else {
        document.getElementById('priceInfo').innerHTML = `
            <strong>السعر النهائي:</strong> سيتم حسابه بناءً على سعر الرحلة وخصم ${discountPercentage}%
        `;
    }
}

// إدارة الأماكن المخصصة
function addCustomPlace() {
    const container = document.getElementById('customIncludedPlacesContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2';

    const firstSelect = container.querySelector('.custom-included-place-select');
    let options = '<option value="">اختر مكان سياحي</option>';

    if (firstSelect && tripTouristSpots.length > 0) {
        options = tripTouristSpots.map(spot =>
            `<option value="${spot.id}">${spot.name} (${spot.governorate_name})</option>`
        ).join('');
        options = '<option value="">اختر مكان سياحي</option>' + options;
    }

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
    }
    // السماح بحذف جميع الأماكن - اختياري
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

// تحميل البيانات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('trip_id').value) {
        loadTripDetails();
    }
});
</script>
@endpush


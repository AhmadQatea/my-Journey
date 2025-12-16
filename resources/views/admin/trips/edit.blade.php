{{-- resources/views/admin/trips/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'تعديل الرحلة: ' . $trip->title)
@section('page-title', 'تعديل الرحلة: ' . $trip->title)

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">تعديل الرحلة</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $trip->title }}</p>
        </div>
        <a href="{{ route('admin.trips.index') }}" class="btn btn-outline inline-flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            <span>رجوع للقائمة</span>
        </a>
    </div>

    <form action="{{ route('admin.trips.update', $trip) }}" method="POST" enctype="multipart/form-data">
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
                            <label class="form-label">عنوان الرحلة *</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $trip->title) }}" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">وصف الرحلة *</label>
                            <textarea id="descriptionEditor" name="description"
                                class="form-control @error('description') is-invalid @enderror" rows="10"
                                required>{{ old('description', $trip->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Trip Details -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">تفاصيل الرحلة</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">الانطلاق من (المحافظة) *</label>
                                <select name="departure_governorate_id" id="departure_governorate_id"
                                    class="form-control form-select @error('departure_governorate_id') is-invalid @enderror"
                                    required>
                                    <option value="">اختر محافظة الانطلاق</option>
                                    @foreach($governorates as $gov)
                                    <option value="{{ $gov->id }}" {{ old('departure_governorate_id', $trip->
                                        departure_governorate_id) == $gov->id ? 'selected' : '' }}>
                                        {{ $gov->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('departure_governorate_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">المحافظة الرئيسية *</label>
                                <select name="governorate_id"
                                    class="form-control form-select @error('governorate_id') is-invalid @enderror"
                                    required>
                                    <option value="">اختر المحافظة</option>
                                    @foreach($governorates as $gov)
                                    <option value="{{ $gov->id }}" {{ old('governorate_id', $trip->governorate_id) ==
                                        $gov->id ? 'selected' : '' }}>
                                        {{ $gov->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('governorate_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">التصنيفات *</label>
                                <div class="grid grid-cols-2 gap-3 mt-2">
                                    @php
                                    $currentCategoryIds = old('category_ids', $trip->category_ids ?? []);
                                    @endphp
                                    @foreach($categories as $category)
                                    <label
                                        class="flex items-center gap-2 p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-emerald-500 dark:hover:border-emerald-500 transition-all duration-300 {{ in_array($category->id, $currentCategoryIds) ? 'border-emerald-500 dark:border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : '' }}">
                                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                            class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                            {{ in_array($category->id, $currentCategoryIds) ? 'checked' : '' }}
                                        onchange="this.closest('label').classList.toggle('border-emerald-500',
                                        this.checked); this.closest('label').classList.toggle('dark:border-emerald-500',
                                        this.checked); this.closest('label').classList.toggle('bg-emerald-50',
                                        this.checked); this.closest('label').classList.toggle('dark:bg-emerald-900/20',
                                        this.checked);">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-500">{{
                                            $category->name }}</span>
                                    </label>
                                    @endforeach
                                </div>
                                @if($categories->count() == 0)
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle ml-1"></i>
                                    لا توجد فئات متاحة. يرجى <a href="{{ route('admin.categories.index') }}"
                                        class="underline">إضافة فئات</a> أولاً.
                                </div>
                                @endif
                                @error('category_ids')
                                <div class="invalid-feedback mt-2">{{ $message }}</div>
                                @enderror
                                @error('category_ids.*')
                                <div class="invalid-feedback mt-2">{{ $message }}</div>
                                @enderror
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    <i class="fas fa-info-circle ml-1"></i>
                                    يمكنك اختيار أكثر من فئة للرحلة
                                </p>
                            </div>

                            <div class="form-group">
                                <label class="form-label">نوع الرحلة *</label>
                                <select name="trip_type" id="trip_type"
                                    class="form-control form-select @error('trip_type') is-invalid @enderror" required>
                                    @foreach($types as $type)
                                    <option value="{{ $type }}" {{ old('trip_type', $trip->trip_type) == $type ?
                                        'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('trip_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <!-- Passing Governorates (يظهر عند اختيار "عدة محافظات") -->
                        <div id="passingGovernoratesGroup"
                            class="form-group {{ old('trip_type', $trip->trip_type) == 'عدة محافظات' ? '' : 'hidden' }}">
                            <label class="form-label">المحافظات التي سنمر بها <span
                                    class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                                @php
                                $oldPassingGovernorates = old('passing_governorates', $trip->passing_governorates ??
                                []);
                                @endphp
                                @foreach($governorates as $gov)
                                <label
                                    class="flex items-center gap-2 p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-emerald-500 dark:hover:border-emerald-500 transition-all duration-300 {{ in_array($gov->id, $oldPassingGovernorates) ? 'border-emerald-500 dark:border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : '' }}">
                                    <input type="checkbox" name="passing_governorates[]" value="{{ $gov->id }}"
                                        class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        {{ in_array($gov->id, $oldPassingGovernorates) ? 'checked' : '' }}
                                    onchange="this.closest('label').classList.toggle('border-emerald-500',
                                    this.checked); this.closest('label').classList.toggle('dark:border-emerald-500',
                                    this.checked); this.closest('label').classList.toggle('bg-emerald-50',
                                    this.checked); this.closest('label').classList.toggle('dark:bg-emerald-900/20',
                                    this.checked);">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-500">{{ $gov->name
                                        }}</span>
                                </label>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                <i class="fas fa-info-circle ml-1"></i>
                                اختر محافظة واحدة على الأقل من المحافظات التي ستمر بها الرحلة
                            </p>
                            @error('passing_governorates')
                            <div class="invalid-feedback mt-2">{{ $message }}</div>
                            @enderror
                            @error('passing_governorates.*')
                            <div class="invalid-feedback mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">المدة (ساعة) *</label>
                                <input type="number" name="duration_hours"
                                    class="form-control @error('duration_hours') is-invalid @enderror"
                                    value="{{ old('duration_hours', $trip->duration_hours) }}" min="1" required>
                                @error('duration_hours')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">الجدول الزمني</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">تاريخ البدء *</label>
                                <input type="date" name="start_date"
                                    class="form-control @error('start_date') is-invalid @enderror"
                                    value="{{ old('start_date', $trip->start_date?->format('Y-m-d')) }}" required>
                                @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">وقت البدء *</label>
                                <input type="time" name="start_time"
                                    class="form-control @error('start_time') is-invalid @enderror"
                                    value="{{ old('start_time', $trip->start_time?->format('H:i')) }}" required>
                                @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">نقطة اللقاء *</label>
                            <textarea name="meeting_point"
                                class="form-control @error('meeting_point') is-invalid @enderror" rows="3"
                                required>{{ old('meeting_point', $trip->meeting_point) }}</textarea>
                            @error('meeting_point')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Pricing & Capacity -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">السعر والسعة</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="form-group">
                                <label class="form-label">السعر (ل.س) *</label>
                                <input type="number" name="price"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price', $trip->price) }}" step="0.01" min="0" required>
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">العدد الأقصى *</label>
                                <input type="number" name="max_persons"
                                    class="form-control @error('max_persons') is-invalid @enderror"
                                    value="{{ old('max_persons', $trip->max_persons) }}" min="1" max="100" required>
                                @error('max_persons')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">المقاعد المتاحة *</label>
                                <input type="number" name="available_seats"
                                    class="form-control @error('available_seats') is-invalid @enderror"
                                    value="{{ old('available_seats', $trip->available_seats) }}" min="1" required>
                                @error('available_seats')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Included Places -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">الأماكن المضمنة *</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle ml-1"></i>
                            <span>يجب اختيار المحافظات أولاً (المحافظة الرئيسية أو المحافظات التي سنمر بها) لعرض الأماكن
                                السياحية المتاحة</span>
                        </div>
                        <div id="includedPlacesContainer" class="space-y-2">
                            @php
                            $oldIncludedPlaces = old('included_places', $trip->included_places ?? []);
                            // تحويل من أسماء إلى IDs إذا كانت أسماء
                            $includedPlaceIds = [];
                            foreach ($oldIncludedPlaces as $place) {
                            if (is_numeric($place)) {
                            $includedPlaceIds[] = $place;
                            } else {
                            // إذا كان نصاً، نحاول البحث عن المكان السياحي
                            $spot = \App\Models\TouristSpot::where('name', $place)->first();
                            if ($spot) {
                            $includedPlaceIds[] = $spot->id;
                            }
                            }
                            }
                            @endphp
                            @if(count($includedPlaceIds) > 0)
                            @foreach($includedPlaceIds as $placeId)
                            <div class="flex gap-2">
                                <select name="included_places[]" class="form-control form-select included-place-select"
                                    data-selected-value="{{ $placeId }}" required>
                                    <option value="">اختر مكان سياحي</option>
                                </select>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removePlace(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                            @else
                            <div class="flex gap-2">
                                <select name="included_places[]" class="form-control form-select included-place-select"
                                    required>
                                    <option value="">اختر مكان سياحي</option>
                                </select>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removePlace(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-outline btn-sm mt-3" onclick="addPlace()">
                            <i class="fas fa-plus ml-1"></i>
                            إضافة مكان
                        </button>
                    </div>
                </div>

                <!-- Features -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">مميزات الرحلة</h3>
                    </div>
                    <div class="card-body">
                        <div id="featuresContainer" class="space-y-2">
                            @foreach(old('features', $trip->features ?? []) as $index => $feature)
                            <div class="flex gap-2">
                                <input type="text" name="features[]" class="form-control" value="{{ $feature }}"
                                    placeholder="ميزة الرحلة">
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeFeature(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline btn-sm mt-3" onclick="addFeature()">
                            <i class="fas fa-plus ml-1"></i>
                            إضافة ميزة
                        </button>
                    </div>
                </div>

                <!-- Requirements -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">متطلبات الرحلة</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <textarea name="requirements"
                                class="form-control @error('requirements') is-invalid @enderror" rows="4"
                                placeholder="متطلبات الرحلة (اختياري)">{{ old('requirements', $trip->requirements) }}</textarea>
                            @error('requirements')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">صور الرحلة</h3>
                    </div>
                    <div class="card-body">
                        @if($trip->images && count($trip->images) > 0)
                        <div class="grid grid-cols-4 gap-3 mb-4">
                            @foreach($trip->images as $index => $image)
                            <div class="relative">
                                <img src="{{ Storage::url($image) }}" alt="صورة {{ $index + 1 }}"
                                    class="w-full h-24 object-cover rounded-lg">
                                <button type="button"
                                    class="absolute top-1 right-1 btn btn-danger btn-sm !p-1 !w-6 !h-6"
                                    onclick="removeExistingImage(this, {{ $index }})">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                                <input type="hidden" name="existing_images[]" value="{{ $image }}">
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="form-label">إضافة صور جديدة</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                            <p class="text-xs text-gray-500 mt-1">يمكن رفع حتى 5 صور</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Featured -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">الإعدادات</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_featured" value="1" class="rounded" {{
                                    old('is_featured', $trip->is_featured) ? 'checked' : '' }}>
                                <span>رحلة مميزة</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body space-y-3">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-save ml-1"></i>
                            حفظ التعديلات
                        </button>
                        <a href="{{ route('admin.trips.index') }}" class="btn btn-outline w-full">
                            <i class="fas fa-times ml-1"></i>
                            إلغاء
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    // Initialize CKEditor
document.addEventListener('DOMContentLoaded', function() {
    CKEDITOR.replace('descriptionEditor', {
        toolbar: [
            { name: 'document', items: ['Source'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
            '/',
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
            '/',
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
        ],
        height: 300
    });

    // Show/hide passing governorates based on trip_type
    // Load tourist spots when governorates change
    const tripTypeSelect = document.getElementById('trip_type');
    const passingGovernoratesGroup = document.getElementById('passingGovernoratesGroup');
    const governorateSelect = document.querySelector('select[name="governorate_id"]');

    if (tripTypeSelect && passingGovernoratesGroup) {
        let isInitialLoad = true;

        function togglePassingGovernorates() {
            if (tripTypeSelect.value === 'عدة محافظات') {
                passingGovernoratesGroup.classList.remove('hidden');
                // لا نضيف required على checkboxes - سيتم التحقق في backend
            } else {
                passingGovernoratesGroup.classList.add('hidden');
                // إلغاء التحديد فقط إذا لم يكن تحميلاً أولياً (أي عند تغيير المستخدم)
                if (!isInitialLoad) {
                    passingGovernoratesGroup.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                        checkbox.checked = false;
                        // تحديث الـ label
                        const label = checkbox.closest('label');
                        label.classList.remove('border-emerald-500', 'dark:border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
                    });
                }
            }
            // تحديث الأماكن السياحية بعد تغيير المحافظات
            setTimeout(loadTouristSpots, 200);
        }

        tripTypeSelect.addEventListener('change', function() {
            isInitialLoad = false;
            togglePassingGovernorates();
        });

        // Check on page load - فقط إظهار/إخفاء، بدون إلغاء التحديد
        if (tripTypeSelect.value === 'عدة محافظات') {
            passingGovernoratesGroup.classList.remove('hidden');
        } else {
            passingGovernoratesGroup.classList.add('hidden');
        }
        isInitialLoad = false;
    }

    // حفظ القيم المختارة حالياً - حفظ مع index لكل select
    const currentSelectedPlaces = new Map();
    document.querySelectorAll('.included-place-select').forEach((select, index) => {
        const selectedValue = select.getAttribute('data-selected-value');
        if (selectedValue) {
            currentSelectedPlaces.set(index, selectedValue);
            console.log('Saved place ID', selectedValue, 'at index', index);
        }
    });

    function loadTouristSpots() {
        const governorateIds = [];

        // إضافة المحافظة الرئيسية
        if (governorateSelect && governorateSelect.value) {
            governorateIds.push(governorateSelect.value);
        }

        // إضافة المحافظات التي سنمر بها (من checkboxes)
        // التحقق من المحافظات المختارة حتى لو كانت المجموعة مخفية
        if (passingGovernoratesGroup) {
            // التحقق من جميع checkboxes المختارة، حتى لو كانت المجموعة مخفية
            passingGovernoratesGroup.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
                if (checkbox.value && !governorateIds.includes(checkbox.value)) {
                    governorateIds.push(checkbox.value);
                }
            });
        }

        console.log('Loading tourist spots for governorates:', governorateIds);
        console.log('Current selected places before loading:', Array.from(currentSelectedPlaces.entries()));

        if (governorateIds.length === 0) {
            console.warn('No governorates selected, clearing select boxes');
            // إفراغ جميع select boxes
            document.querySelectorAll('.included-place-select').forEach(select => {
                select.innerHTML = '<option value="">اختر مكان سياحي</option>';
            });
            return;
        }

        // جلب الأماكن السياحية
        const params = new URLSearchParams();
        governorateIds.forEach(id => params.append('governorate_ids[]', id));
        if (governorateSelect?.value) {
            params.append('main_governorate_id', governorateSelect.value);
        }

        const url = '{{ route("admin.trips.tourist-spots.by-governorates") }}?' + params.toString();
        console.log('Fetching from:', url);

        fetch(url, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Tourist spots data:', data);
            if (data.success) {
                const options = data.tourist_spots.map(spot =>
                    `<option value="${spot.id}" data-governorate="${spot.governorate_name}">${spot.name} (${spot.governorate_name})</option>`
                ).join('');

                // تحديث جميع select boxes
                document.querySelectorAll('.included-place-select').forEach((select, index) => {
                    // الحصول على القيمة المختارة مسبقاً
                    const selectedValue = select.getAttribute('data-selected-value');
                    const savedValue = currentSelectedPlaces.get(index);
                    const currentValue = selectedValue || savedValue || select.value;

                    console.log('Updating select at index', index, 'with value', currentValue);

                    select.innerHTML = '<option value="">اختر مكان سياحي</option>' + options;

                    if (currentValue) {
                        // التحقق من أن القيمة موجودة في الخيارات قبل تعيينها
                        const optionExists = Array.from(select.options).some(opt => opt.value == currentValue);
                        if (optionExists) {
                            select.value = currentValue;
                            console.log('Successfully set value', currentValue, 'for select at index', index);
                        } else {
                            console.warn('Place ID', currentValue, 'not found in available options for governorates:', governorateIds);
                            console.warn('Available options:', Array.from(select.options).map(opt => opt.value));
                        }
                        // إزالة data attribute بعد التحديث
                        select.removeAttribute('data-selected-value');
                        // إزالة من Map بعد التحديث
                        currentSelectedPlaces.delete(index);
                    }
                });
                console.log('Updated', document.querySelectorAll('.included-place-select').length, 'select boxes with', data.tourist_spots.length, 'tourist spots');
            } else {
                console.error('API returned success: false');
            }
        })
        .catch(error => {
            console.error('Error loading tourist spots:', error);
        });
    }

    // إضافة event listeners للمحافظة الرئيسية
    if (governorateSelect) {
        governorateSelect.addEventListener('change', function() {
            console.log('Governorate changed to:', this.value);
            setTimeout(loadTouristSpots, 100);
        });
    }

    // إضافة event listeners للـ checkboxes (للمحافظات التي سنمر بها)
    // استخدام event delegation للتعامل مع العناصر الديناميكية
    if (passingGovernoratesGroup) {
        passingGovernoratesGroup.addEventListener('change', function(e) {
            if (e.target.type === 'checkbox') {
                console.log('Passing governorate checkbox changed:', e.target.value, e.target.checked);
                setTimeout(loadTouristSpots, 100);
            }
        });
    }

    // دالة لتحديث الأماكن السياحية (يمكن استدعاؤها من checkboxes)
    window.updateTouristSpots = loadTouristSpots;

    // تحميل الأماكن عند تحميل الصفحة (بعد تأخير بسيط للتأكد من تحميل جميع العناصر)
    // استخدام تأخير أطول لضمان تحميل جميع العناصر بما في ذلك checkboxes
    setTimeout(function() {
        console.log('Initial load of tourist spots');
        console.log('Main governorate:', governorateSelect?.value);
        console.log('Passing governorates checked:', passingGovernoratesGroup ? Array.from(passingGovernoratesGroup.querySelectorAll('input[type="checkbox"]:checked')).map(cb => cb.value) : []);
        console.log('Saved selected places:', Array.from(currentSelectedPlaces.entries()));

        // التأكد من وجود محافظات مختارة قبل تحميل الأماكن
        const hasMainGovernorate = governorateSelect && governorateSelect.value;
        const hasPassingGovernorates = passingGovernoratesGroup && passingGovernoratesGroup.querySelectorAll('input[type="checkbox"]:checked').length > 0;

        if (hasMainGovernorate || hasPassingGovernorates) {
            loadTouristSpots();
        } else {
            console.warn('No governorates selected, cannot load tourist spots');
        }
    }, 1000);
});

// Included Places management
function addPlace() {
    const container = document.getElementById('includedPlacesContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2';

    // جلب الأماكن المتاحة حالياً
    const firstSelect = container.querySelector('.included-place-select');
    const options = firstSelect ? firstSelect.innerHTML : '<option value="">اختر مكان سياحي</option>';

    div.innerHTML = `
        <select name="included_places[]" class="form-control form-select included-place-select" required>
            ${options}
        </select>
        <button type="button" class="btn btn-danger btn-sm" onclick="removePlace(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removePlace(button) {
    const container = document.getElementById('includedPlacesContainer');
    if (container.children.length > 1) {
        button.closest('.flex').remove();
    } else {
        alert('يجب أن يكون هناك مكان واحد على الأقل');
    }
}

// Features management
function addFeature() {
    const container = document.getElementById('featuresContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="features[]" class="form-control" placeholder="ميزة الرحلة">
        <button type="button" class="btn btn-danger btn-sm" onclick="removeFeature(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(div);
}

function removeFeature(button) {
    button.closest('.flex').remove();
}

// Remove existing image
function removeExistingImage(button, index) {
    const div = button.closest('.relative');
    const hiddenInput = div.querySelector('input[type="hidden"]');
    if (hiddenInput) {
        hiddenInput.remove();
    }
    div.remove();
}
</script>
@endpush

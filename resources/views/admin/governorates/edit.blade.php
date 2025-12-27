@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-admin.edit-form
    :title="__('messages.edit') . ' ' . __('messages.governorate')"
    :page-title="__('messages.edit') . ' ' . __('messages.governorate') . ': ' . $governorate->name"
    :action="route('admin.governorates.update', $governorate)"
    :model="$governorate"
    :back-route="route('admin.governorates.index')"
    :back-text="__('messages.cancel')"
    :submit-text="__('messages.save_changes')"
    :enctype="true"
    layout="grid"
>
    <x-form.input
        name="name"
        :label="__('messages.governorate_name')"
        required
        :placeholder="__('messages.enter_governorate_name')"
        value="{{ old('name', $governorate->name) }}"
    />

    <div class="form-group">
        <label class="form-label">{{ __('messages.full_description') }} *</label>
        <textarea name="description"
                  class="form-control @error('description') is-invalid @enderror"
                  rows="8"
                  required
                  placeholder="{{ __('messages.enter_detailed_description') }}">{{ old('description', $governorate->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <x-form.input
        name="location"
        :label="__('messages.location')"
        required
        :placeholder="__('messages.enter_location')"
        value="{{ old('location', $governorate->location) }}"
    />

    <div class="form-group">
        <label class="form-label">{{ __('messages.coordinates') }}</label>
        <x-form.input
            name="coordinates"
            label=""
            placeholder="{{ __('messages.example_coordinates') }}"
            value="{{ old('coordinates', $governorate->coordinates) }}"
        />
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
            <i class="fas fa-info-circle ml-1"></i>
            {{ __('messages.coordinates_format_hint') }}
        </p>
        @error('coordinates')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <x-slot name="sidebar">
        <!-- Featured Image -->
        <x-card title="الصورة الرئيسية" class="!mb-0">
            <div class="space-y-4">
                @if($governorate->featured_image)
                <div class="current-image mb-4">
                    <label class="form-label">الصورة الحالية</label>
                    <img src="{{ Storage::url($governorate->featured_image) }}"
                         alt="{{ $governorate->name }}"
                         class="w-full h-64 object-cover rounded-lg shadow-md">
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">{{ $governorate->featured_image ? 'تغيير الصورة' : 'صورة المحافظة' }}</label>
                    <div class="image-upload-container">
                        <input type="file"
                               name="featured_image"
                               id="featured_image"
                               accept="image/*"
                               class="form-control @error('featured_image') is-invalid @enderror"
                               onchange="previewImage(this, 'imagePreview')">
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="image-preview mt-4" id="imagePreview">
                            @if(!$governorate->featured_image)
                            <div class="preview-placeholder">
                                <i class="fas fa-image text-gray-400 text-4xl"></i>
                                <p class="text-gray-500 mt-2">معاينة الصورة</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Best Visiting Times -->
        <x-card title="أفضل أوقات الزيارة" class="!mb-0">
            <div class="space-y-3">
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                    اختر أفضل الأوقات لزيارة هذه المحافظة (يمكن اختيار أكثر من خيار)
                </p>
                @if(isset($bestVisitingTimes) && $bestVisitingTimes->count() > 0)
                    @php
                        $selectedTimes = old('best_visiting_time_ids', $governorate->bestVisitingTimes->pluck('id')->toArray());
                    @endphp
                    @foreach($bestVisitingTimes as $visitingTime)
                        <div class="form-check">
                            <input class="form-check-input @error('best_visiting_time_ids') is-invalid @enderror"
                                   type="checkbox"
                                   name="best_visiting_time_ids[]"
                                   value="{{ $visitingTime->id }}"
                                   id="visiting_time_{{ $visitingTime->id }}"
                                   {{ in_array($visitingTime->id, $selectedTimes) ? 'checked' : '' }}>
                            <label class="form-check-label d-flex align-items-center gap-2" for="visiting_time_{{ $visitingTime->id }}">
                                @if($visitingTime->icon)
                                    <i class="{{ $visitingTime->icon }}" style="color: {{ $visitingTime->color ?? '#4361ee' }};"></i>
                                @endif
                                <span>{{ $visitingTime->name_ar }}</span>
                            </label>
                        </div>
                    @endforeach
                @else
                    <p class="text-sm text-gray-500">لا توجد أوقات زيارة متاحة</p>
                @endif
                @error('best_visiting_time_ids')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                @error('best_visiting_time_ids.*')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </x-card>

        <!-- Statistics -->
        <x-card title="الإحصائيات" class="!mb-0">
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">الأماكن السياحية</span>
                    <span class="font-bold text-blue-600 dark:text-blue-400">{{ $governorate->touristSpots->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">الرحلات</span>
                    <span class="font-bold text-green-600 dark:text-green-400">{{ $governorate->trips->count() }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <span class="text-sm text-gray-600 dark:text-gray-400">تاريخ الإنشاء</span>
                    <span class="font-bold text-gray-700 dark:text-gray-500">{{ $governorate->created_at->format('Y/m/d') }}</span>
                </div>
            </div>
        </x-card>
    </x-slot>
</x-admin.edit-form>

@push('scripts')
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}"
                         alt="Preview"
                         class="w-full h-64 object-cover rounded-lg shadow-md">
                `;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush


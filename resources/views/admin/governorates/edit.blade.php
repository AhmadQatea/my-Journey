@extends('admin.layouts.admin')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'تعديل المحافظة')
@section('page-title', 'تعديل المحافظة: ' . $governorate->name)

@section('content')
<x-card title="معلومات المحافظة">
    <form action="{{ route('admin.governorates.update', $governorate) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <x-form.input
                    name="name"
                    label="اسم المحافظة"
                    required
                    placeholder="أدخل اسم المحافظة"
                    value="{{ old('name', $governorate->name) }}"
                />

                <div class="form-group">
                    <label class="form-label">الوصف الكامل *</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="8"
                              required
                              placeholder="أدخل وصفاً مفصلاً عن المحافظة (50 حرف على الأقل)">{{ old('description', $governorate->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <x-form.input
                    name="location"
                    label="الموقع"
                    required
                    placeholder="أدخل موقع المحافظة"
                    value="{{ old('location', $governorate->location) }}"
                />
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
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
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.governorates.index') }}"
               class="px-6 py-3 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-500 transition-all duration-300">
                إلغاء
            </a>
            <button type="submit"
                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-green-500 hover:from-blue-600 hover:to-green-600 text-black transition-all duration-300 shadow-lg hover:shadow-xl">
                <i class="fas fa-save ml-2"></i>
                حفظ التغييرات
            </button>
        </div>
    </form>
</x-card>

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
@endsection


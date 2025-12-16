@extends('admin.layouts.admin')

@section('title', 'إضافة محافظة جديدة')
@section('page-title', 'إضافة محافظة جديدة')

@section('content')
<x-card title="معلومات المحافظة">
    <form action="{{ route('admin.governorates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <x-form.input
                    name="name"
                    label="اسم المحافظة"
                    required
                    placeholder="أدخل اسم المحافظة"
                    value="{{ old('name') }}"
                />

                <div class="form-group">
                    <label class="form-label">الوصف الكامل *</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="8"
                              required
                              placeholder="أدخل وصفاً مفصلاً عن المحافظة (50 حرف على الأقل)">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <x-form.input
                    name="location"
                    label="الموقع"
                    required
                    placeholder="أدخل موقع المحافظة"
                    value="{{ old('location') }}"
                />
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Featured Image -->
                <x-card title="الصورة الرئيسية" class="!mb-0">
                    <div class="space-y-4">
                        <div class="form-group">
                            <label class="form-label">صورة المحافظة *</label>
                            <div class="image-upload-container">
                                <input type="file"
                                       name="featured_image"
                                       id="featured_image"
                                       accept="image/*"
                                       class="form-control @error('featured_image') is-invalid @enderror"
                                       onchange="previewImage(this, 'imagePreview')"
                                       required>
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="image-preview mt-4" id="imagePreview">
                                    <div class="preview-placeholder">
                                        <i class="fas fa-image text-gray-400 text-4xl"></i>
                                        <p class="text-gray-500 mt-2">معاينة الصورة</p>
                                    </div>
                                </div>
                            </div>
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
                حفظ المحافظة
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
        } else {
            preview.innerHTML = `
                <div class="preview-placeholder">
                    <i class="fas fa-image text-gray-400 text-4xl"></i>
                    <p class="text-gray-500 mt-2">معاينة الصورة</p>
                </div>
            `;
        }
    }
</script>
@endpush
@endsection


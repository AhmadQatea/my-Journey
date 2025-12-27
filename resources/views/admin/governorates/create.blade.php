<x-admin.create-form
    title="إضافة محافظة جديدة"
    :action="route('admin.governorates.store')"
    :back-route="route('admin.governorates.index')"
    back-text="إلغاء"
    submit-text="حفظ المحافظة"
    :enctype="true"
    layout="grid"
>
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

    <x-slot name="sidebar">
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
    </x-slot>
</x-admin.create-form>

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


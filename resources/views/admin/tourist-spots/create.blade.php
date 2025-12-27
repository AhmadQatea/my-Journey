<x-admin.create-form
    title="إضافة مكان سياحي جديد"
    :action="route('admin.tourist-spots.store')"
    :back-route="route('admin.tourist-spots.index')"
    submit-text="حفظ المكان السياحي"
    :enctype="true"
    layout="grid"
>
    <x-slot name="main">
                <div class="form-group">
                    <label class="form-label">المحافظة *</label>
                    <select name="governorate_id"
                            class="form-control @error('governorate_id') is-invalid @enderror"
                            required>
                        <option value="">اختر المحافظة</option>
                        @foreach($governorates as $governorate)
                        <option value="{{ $governorate->id }}" {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                            {{ $governorate->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('governorate_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <x-form.input
                    name="name"
                    label="اسم المكان السياحي"
                    required
                    placeholder="أدخل اسم المكان السياحي"
                    value="{{ old('name') }}"
                />

                <div class="form-group">
                    <label class="form-label">الوصف الكامل *</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="8"
                              required
                              placeholder="أدخل وصفاً مفصلاً عن المكان السياحي (50 حرف على الأقل)">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">الموقع *</label>
                    <x-form.input
                        name="location"
                        label=""
                        required
                        placeholder="أدخل موقع المكان"
                        value="{{ old('location') }}"
                    />
                </div>

                <div class="form-group">
                    <label class="form-label">الإحداثيات على الخريطة</label>
                    <x-form.input
                        name="coordinates"
                        label=""
                        placeholder="مثال: 35.944565006326975, 36.40461466690107"
                        value="{{ old('coordinates') }}"
                    />
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        <i class="fas fa-info-circle ml-1"></i>
                        أدخل الإحداثيات بصيغة: خط العرض، خط الطول (مثال: 35.944565006326975, 36.40461466690107)
                    </p>
                    @error('coordinates')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">الفئات *</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mt-2">
                        @php
                            $oldCategoryIds = old('category_ids', []);
                        @endphp
                        @foreach($categories as $category)
                        <label class="flex items-center gap-2 p-3 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-emerald-500 dark:hover:border-emerald-500 transition-all duration-300 {{ in_array($category->id, $oldCategoryIds) ? 'border-emerald-500 dark:border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : '' }}">
                            <input type="checkbox"
                                   name="category_ids[]"
                                   value="{{ $category->id }}"
                                   class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                   {{ in_array($category->id, $oldCategoryIds) ? 'checked' : '' }}
                                   onchange="this.closest('label').classList.toggle('border-emerald-500', this.checked); this.closest('label').classList.toggle('dark:border-emerald-500', this.checked); this.closest('label').classList.toggle('bg-emerald-50', this.checked); this.closest('label').classList.toggle('dark:bg-emerald-900/20', this.checked);">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-500">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @if($categories->count() == 0)
                        <div class="alert alert-warning mt-2">
                            <i class="fas fa-exclamation-triangle ml-1"></i>
                            لا توجد فئات متاحة. يرجى <a href="{{ route('admin.categories.index') }}" class="underline">إضافة فئات</a> أولاً.
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
                        يمكنك اختيار أكثر من فئة للمكان السياحي
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-form.input
                        name="entrance_fee"
                        label="رسوم الدخول أو تكلفة قضاء الوقت (ل.س)"
                        type="number"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        value="{{ old('entrance_fee') }}"
                    />

                    <x-form.input
                        name="opening_hours"
                        label="ساعات العمل"
                        placeholder="مثال: 8:00 ص - 6:00 م"
                        value="{{ old('opening_hours') }}"
                    />
                </div>
    </x-slot>

    <x-slot name="sidebar">
        <!-- Images Upload -->
        <x-card title="صور المكان السياحي" class="!mb-0">
            <div class="space-y-4">
                <div class="form-group">
                    <label class="form-label">صور المكان السياحي *</label>
                    <div class="image-upload-container">
                        <input type="file"
                               name="images[]"
                               id="images"
                               accept="image/*"
                               multiple
                               class="form-control @error('images') is-invalid @enderror"
                               onchange="previewImages(this, 'imagesPreview')"
                               required>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="grid grid-cols-2 gap-4 mt-4" id="imagesPreview">
                            <div class="image-preview col-span-2">
                                <div class="preview-placeholder">
                                    <i class="fas fa-images text-gray-400 text-4xl"></i>
                                    <p class="text-gray-500 mt-2">معاينة الصور</p>
                                </div>
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
    function previewImages(input, previewId) {
        const preview = document.getElementById(previewId);
        const files = input.files;

        if (files && files.length > 0) {
            preview.innerHTML = '';
            Array.from(files).forEach((file, index) => {
                if (index < 6) { // عرض أول 6 صور فقط
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'image-preview';
                        div.innerHTML = `
                            <img src="${e.target.result}"
                                 alt="Preview ${index + 1}"
                                 class="w-full h-32 object-cover rounded-lg shadow-md">
                        `;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
            if (files.length > 6) {
                const moreDiv = document.createElement('div');
                moreDiv.className = 'image-preview col-span-2';
                moreDiv.innerHTML = `
                    <div class="preview-placeholder">
                        <p class="text-gray-500">+${files.length - 6} صورة إضافية</p>
                    </div>
                `;
                preview.appendChild(moreDiv);
            }
        } else {
            preview.innerHTML = `
                <div class="image-preview col-span-2">
                    <div class="preview-placeholder">
                        <i class="fas fa-images text-gray-400 text-4xl"></i>
                        <p class="text-gray-500 mt-2">معاينة الصور</p>
                    </div>
                </div>
            `;
        }
    }
</script>
@endpush
@endsection

<x-admin.edit-form
    title="تعديل المقال"
    :page-title="'تعديل المقال: ' . $article->title"
    :action="route('admin.articles.update', $article)"
    :model="$article"
    :back-route="route('admin.articles.index')"
    submit-text="حفظ التعديلات"
    :enctype="true"
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
                                <label class="form-label">الرحلة أو العرض (اختياري)</label>
                                <select name="trip_id"
                                        class="form-control form-select @error('trip_id') is-invalid @enderror">
                                    <option value="">عام (غير مرتبط برحلة أو عرض)</option>
                                    @if($trips->count() > 0)
                                        <optgroup label="الرحلات">
                                            @foreach($trips as $trip)
                                                <option value="{{ $trip->id }}" {{ old('trip_id', $article->trip_id) == $trip->id ? 'selected' : '' }}>
                                                    {{ $trip->title }} - {{ $trip->governorate->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                    @if($offers->count() > 0)
                                        <optgroup label="العروض الخاصة">
                                            @foreach($offers as $offer)
                                                <option value="{{ $offer->trip_id }}" {{ old('trip_id', $article->trip_id) == $offer->trip_id ? 'selected' : '' }}>
                                                    {{ $offer->title }} - {{ $offer->trip->governorate->name ?? 'N/A' }}
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

                        <div class="form-group">
                            <label class="form-label">عنوان المقال *</label>
                            <input type="text"
                                   name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title', $article->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">الملخص (اختياري)</label>
                            <textarea name="excerpt"
                                      class="form-control @error('excerpt') is-invalid @enderror"
                                      rows="3"
                                      placeholder="ملخص قصير عن المقال...">{{ old('excerpt', $article->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">محتوى المقال *</label>
                            <textarea id="contentEditor"
                                      name="content"
                                      class="form-control @error('content') is-invalid @enderror"
                                      rows="15"
                                      required>{{ old('content', $article->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">التقييم (اختياري)</label>
                            <select name="rating"
                                    class="form-control form-select @error('rating') is-invalid @enderror">
                                <option value="">بدون تقييم</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ old('rating', $article->rating) == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 5 ? 'نجوم' : ($i == 4 ? 'نجوم' : ($i == 3 ? 'نجوم' : ($i == 2 ? 'نجوم' : 'نجمة'))) }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">حالة المقال *</label>
                            <select name="status"
                                    id="status"
                                    class="form-control form-select @error('status') is-invalid @enderror"
                                    required
                                    onchange="toggleRejectionReason()">
                                <option value="معلقة" {{ old('status', $article->status) == 'معلقة' ? 'selected' : '' }}>معلقة</option>
                                <option value="منشورة" {{ old('status', $article->status) == 'منشورة' ? 'selected' : '' }}>منشورة</option>
                                <option value="مرفوضة" {{ old('status', $article->status) == 'مرفوضة' ? 'selected' : '' }}>مرفوضة</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" id="rejectionReasonGroup" style="display: {{ old('status', $article->status) == 'مرفوضة' ? 'block' : 'none' }};">
                            <label class="form-label">سبب الرفض *</label>
                            <textarea name="rejection_reason"
                                      class="form-control @error('rejection_reason') is-invalid @enderror"
                                      rows="3"
                                      placeholder="أدخل سبب رفض المقال...">{{ old('rejection_reason', $article->rejection_reason) }}</textarea>
                            @error('rejection_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Existing Images -->
                        @if($article->images && count($article->images) > 0)
                        <div class="form-group">
                            <label class="form-label">الصور الحالية</label>
                            <div class="grid grid-cols-4 gap-3">
                                @foreach($article->images as $index => $image)
                                <div class="relative">
                                    <img src="{{ Storage::url($image) }}"
                                         alt="صورة {{ $index + 1 }}"
                                         class="w-full h-24 object-cover rounded-lg">
                                    <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                </div>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-info-circle ml-1"></i>
                                يمكنك إضافة صور جديدة أو الاحتفاظ بالصور الحالية
                            </p>
                        </div>
                        @endif

                        <div class="form-group">
                            <label class="form-label">إضافة صور جديدة (اختياري)</label>
                            <input type="file"
                                   name="images[]"
                                   class="form-control @error('images') is-invalid @enderror"
                                   accept="image/*"
                                   multiple>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-info-circle ml-1"></i>
                                يمكن رفع حتى 10 صور
                            </p>
                        </div>
        </div>
    </div>
</x-admin.edit-form>

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    // Initialize CKEditor
    document.addEventListener('DOMContentLoaded', function() {
        CKEDITOR.replace('contentEditor', {
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
            height: 400
        });
    });

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
</script>
@endpush

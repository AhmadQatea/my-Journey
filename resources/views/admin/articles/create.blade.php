{{-- resources/views/admin/articles/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'إضافة مقال جديد')
@section('page-title', 'إضافة مقال جديد')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">إضافة مقال جديد</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">أنشئ مقالاً جديداً</p>
        </div>
        <a href="{{ route('admin.articles.index') }}"
           class="btn btn-outline inline-flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            <span>رجوع للقائمة</span>
        </a>
    </div>

    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
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
                                                <option value="{{ $trip->id }}" {{ old('trip_id') == $trip->id ? 'selected' : '' }}>
                                                    {{ $trip->title }} - {{ $trip->governorate->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                    @if($offers->count() > 0)
                                        <optgroup label="العروض الخاصة">
                                            @foreach($offers as $offer)
                                                <option value="{{ $offer->trip_id }}" {{ old('trip_id') == $offer->trip_id ? 'selected' : '' }}>
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
                                   value="{{ old('title') }}"
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
                                      placeholder="ملخص قصير عن المقال...">{{ old('excerpt') }}</textarea>
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
                                      required>{{ old('content') }}</textarea>
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
                                    <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} {{ $i == 5 ? 'نجوم' : ($i == 4 ? 'نجوم' : ($i == 3 ? 'نجوم' : ($i == 2 ? 'نجوم' : 'نجمة'))) }}
                                    </option>
                                @endfor
                            </select>
                            @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">الصور (اختياري)</label>
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
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body p-4">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-save ml-1"></i>
                            حفظ المقال
                        </button>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">معلومات</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle ml-1"></i>
                            <p class="text-sm">
                                المقالات التي ينشئها المسؤولون تكون <strong>منشورة</strong> تلقائياً.
                            </p>
                            <p class="text-sm mt-2">
                                يمكن للمسؤول إنشاء مقالات <strong>عامة</strong> للموقع (غير مرتبطة برحلة).
                            </p>
                        </div>
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
</script>
@endpush

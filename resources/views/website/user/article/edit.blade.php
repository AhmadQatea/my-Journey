@extends('website.user.layouts.user')

@section('title', 'تعديل المقال - MyJourney')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/article.css') }}">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.article-edit-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.article-edit-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.article-edit-header-section h1 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.article-edit-header-section p {
    font-size: 1rem;
    opacity: 0.9;
}

.article-edit-form {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
}

.article-edit-form::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.form-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.form-group label {
    color: #0f172a;
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-group label i {
    color: #667eea;
}

.form-input, .form-textarea, .form-select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    color: #1e293b;
    background: #ffffff;
    transition: all 0.3s;
}

.form-input:focus, .form-textarea:focus, .form-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-header h3 {
    color: #0f172a;
    font-weight: 800;
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-header h3 i {
    color: #667eea;
}

.form-header p {
    color: #475569;
    font-size: 0.875rem;
    font-weight: 500;
}

.editor-header label {
    color: #0f172a;
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.editor-header label i {
    color: #667eea;
}

#editor-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    min-height: 400px;
}

.existing-images {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.existing-image-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 16/9;
}

.existing-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.dropzone {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.3s;
}

.dropzone:hover {
    border-color: #667eea;
    background: #f8fafc;
}

.dropzone-content i {
    font-size: 3rem;
    color: #667eea;
    margin-bottom: 1rem;
}

.dropzone-content h4 {
    color: #0f172a;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.dropzone-content p {
    color: #64748b;
    font-size: 0.875rem;
}

.images-preview {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.rating-container {
    text-align: center;
}

.stars-rating {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stars-rating .fa-star {
    font-size: 2rem;
    color: #e2e8f0;
    cursor: pointer;
    transition: all 0.2s;
}

.stars-rating .fa-star.active {
    color: #f59e0b;
}

.stars-rating .fa-star:hover {
    transform: scale(1.1);
}

.rating-text {
    color: #334155;
    font-weight: 600;
    font-size: 1rem;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e2e8f0;
}

.btn {
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.btn-publish {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-publish:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-cancel {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-cancel:hover {
    background: #e2e8f0;
}

</style>
@endpush

@section('content')
<div class="article-edit-page">
    <!-- Header Section -->
    <div class="article-edit-header-section">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1>
                    <i class="fas fa-edit"></i>
                    تعديل المقال
                </h1>
                <p>تحديث معلومات مقالك</p>
            </div>
            <a href="{{ route('user-articles.show', $article) }}" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                <i class="fas fa-arrow-right"></i>
                <span>العودة</span>
            </a>
        </div>
    </div>

    <div class="article-edit-form">
    <form action="{{ route('articles.update', $article) }}" method="POST" id="article-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-section">
            <div class="form-group">
                <label for="title">
                    <i class="fas fa-heading"></i>
                    عنوان المقال
                </label>
                <input type="text" id="title" name="title"
                       class="form-input"
                       value="{{ old('title', $article->title) }}"
                       placeholder="اكتب عنواناً جذاباً لمقالك..."
                       required>
            </div>

            <div class="form-group">
                <label for="excerpt">
                    <i class="fas fa-quote-right"></i>
                    ملخص المقال
                </label>
                <textarea id="excerpt" name="excerpt"
                          class="form-textarea"
                          placeholder="اكتب ملخصاً قصيراً يصف مقالك..."
                          rows="3" maxlength="200">{{ old('excerpt', $article->excerpt) }}</textarea>
            </div>
        </div>

        <div class="form-section">
            <div class="editor-header">
                <label>
                    <i class="fas fa-edit"></i>
                    محتوى المقال
                </label>
            </div>
            <div id="editor-container">
                {!! old('content', $article->content) !!}
            </div>
            <textarea name="content" id="content" style="display: none;">{{ old('content', $article->content) }}</textarea>
        </div>

        <div class="form-section">
            <div class="form-header">
                <h3><i class="fas fa-images"></i> الصور الحالية</h3>
            </div>
            @if($article->images && count($article->images) > 0)
                <div class="existing-images">
                    @foreach($article->images as $index => $image)
                        <div class="existing-image-item">
                            <img src="{{ asset('storage/'.$image) }}" alt="صورة {{ $index + 1 }}">
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="form-header">
                <h3><i class="fas fa-images"></i> إضافة صور جديدة</h3>
            </div>
            <div class="images-upload">
                <div class="dropzone" id="dropzone">
                    <div class="dropzone-content">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h4>اسحب وأفلت الصور هنا</h4>
                        <p>أو انقر لاختيار الملفات</p>
                    </div>
                    <input type="file" id="image-upload" name="images[]"
                           multiple accept="image/*" style="display: none;">
                </div>
                <div class="images-preview" id="images-preview"></div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-group">
                <label for="trip_id">
                    <i class="fas fa-map-marked-alt"></i>
                    ربط المقال برحلة (اختياري)
                </label>
                <select name="trip_id" id="trip_id" class="form-select">
                    <option value="">لا يوجد رحلة (مقال عام)</option>
                    @foreach($bookedTrips as $trip)
                        @if($trip)
                            <option value="{{ $trip->id }}" {{ old('trip_id', $article->trip_id) == $trip->id ? 'selected' : '' }}>
                                {{ $trip->title }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-section" id="rating-section">
            <div class="form-header">
                <h3><i class="fas fa-star"></i> تقييم الرحلة</h3>
                <p>كيف تقيم تجربتك في هذه الرحلة؟</p>
            </div>
            <div class="rating-container">
                <div class="stars-rating" id="stars-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= old('rating', $article->rating ?? 5) ? 'active' : '' }}" data-rating="{{ $i }}"></i>
                    @endfor
                </div>
                <div class="rating-text" id="rating-text">
                    @if($article->rating)
                        @php
                            $texts = ['سيئة جداً', 'سيئة', 'متوسطة', 'جيدة', 'ممتازة'];
                        @endphp
                        {{ $texts[$article->rating - 1] ?? 'اضغط على النجوم للتقييم' }}
                    @else
                        اضغط على النجوم للتقييم
                    @endif
                </div>
                <input type="hidden" name="rating" id="rating" value="{{ old('rating', $article->rating ?? 5) }}">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-publish">
                <i class="fas fa-save"></i>
                حفظ التعديلات
            </button>
            <a href="{{ route('user-articles.show', $article) }}" class="btn btn-cancel">
                <i class="fas fa-times"></i>
                إلغاء
            </a>
        </div>
    </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    const quill = new Quill('#editor-container', {
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['link', 'image', 'video'],
                ['clean']
            ]
        },
        theme: 'snow'
    });

    quill.on('text-change', function() {
        document.getElementById('content').value = quill.root.innerHTML;
    });

    // Rating
    const starsRating = document.getElementById('stars-rating');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('rating-text');
    const ratingSection = document.getElementById('rating-section');
    const tripSelect = document.getElementById('trip_id');
    let currentRating = parseInt(ratingInput.value) || 5;

    function updateRating(rating) {
        starsRating.querySelectorAll('.fa-star').forEach((star, index) => {
            if (index < rating) {
                star.classList.add('active');
                star.style.color = '#f59e0b';
            } else {
                star.classList.remove('active');
                star.style.color = '#e2e8f0';
            }
        });

        const texts = ['سيئة جداً', 'سيئة', 'متوسطة', 'جيدة', 'ممتازة'];
        ratingText.textContent = texts[rating - 1] || 'اضغط على النجوم للتقييم';
        ratingInput.value = rating;
    }

    starsRating.querySelectorAll('.fa-star').forEach((star) => {
        star.addEventListener('click', function() {
            currentRating = parseInt(this.dataset.rating);
            updateRating(currentRating);
        });

        star.addEventListener('mouseover', function() {
            const hoverRating = parseInt(this.dataset.rating);
            starsRating.querySelectorAll('.fa-star').forEach((s, index) => {
                if (index < hoverRating) {
                    s.style.color = '#fbbf24';
                } else {
                    s.style.color = '#e2e8f0';
                }
            });
        });

        star.addEventListener('mouseout', function() {
            updateRating(currentRating);
        });
    });

    // إظهار/إخفاء قسم التقييم حسب اختيار الرحلة
    function toggleRatingSection() {
        if (tripSelect.value) {
            ratingSection.style.display = 'block';
        } else {
            ratingSection.style.display = 'none';
            ratingInput.value = '';
            currentRating = 0;
            updateRating(0);
        }
    }

    tripSelect.addEventListener('change', toggleRatingSection);
    toggleRatingSection(); // تهيئة أولية
</script>
@endpush


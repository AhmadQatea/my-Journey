@extends('website.user.layouts.user')

@section('title', 'كتابة مقال جديد - MyJourney')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/article.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div class="header-content">
        <h2 class="page-title">كتابة مقال جديد</h2>
        <p class="page-subtitle">شارك تجربتك مع الآخرين</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('my-articles') }}" class="btn btn-new-trip">
            <i class="fas fa-arrow-right"></i>
            <span>العودة</span>
        </a>
        <button type="submit" form="article-form" class="btn btn-new-trip">
            <i class="fas fa-paper-plane"></i>
            <span>نشر المقال</span>
        </button>
    </div>
</div>

<div class="article-main">
        <div class="article-container">
            <!-- Left Side: Editor -->
            <div class="editor-side">
                <!-- Article Form -->
                <form action="{{ route('articles.store') }}" method="POST" id="article-form" enctype="multipart/form-data">
                    @csrf

                    <!-- Title & Excerpt -->
                    <div class="form-section">
                        <div class="form-group">
                            <label for="title">
                                <i class="fas fa-heading"></i>
                                عنوان المقال
                            </label>
                            <input type="text" id="title" name="title"
                                   class="form-input"
                                   placeholder="اكتب عنواناً جذاباً لمقالك..."
                                   required>
                            <div class="form-hint">العوان الجيد يجذب القراء</div>
                        </div>

                        <div class="form-group">
                            <label for="excerpt">
                                <i class="fas fa-quote-right"></i>
                                ملخص المقال
                            </label>
                            <textarea id="excerpt" name="excerpt"
                                      class="form-textarea"
                                      placeholder="اكتب ملخصاً قصيراً يصف مقالك..."
                                      rows="3" maxlength="200"></textarea>
                            <div class="form-hint">الحد الأقصى 200 حرف</div>
                        </div>
                    </div>

                    <!-- Content Editor -->
                    <div class="form-section">
                        <div class="editor-header">
                            <label>
                                <i class="fas fa-edit"></i>
                                محتوى المقال
                            </label>
                            <div class="editor-toolbar" id="toolbar-container">
                                <!-- Quill toolbar will be inserted here -->
                            </div>
                        </div>
                        <div id="editor-container">
                            <!-- Quill editor will be inserted here -->
                        </div>
                        <textarea name="content" id="content" style="display: none;"></textarea>
                    </div>

                    <!-- Images Upload -->
                    <div class="form-section">
                        <div class="form-header">
                            <h3><i class="fas fa-images"></i> إضافة الصور</h3>
                            <p>أضف صوراً من رحلتك لجعل المقال أكثر جاذبية</p>
                        </div>

                        <div class="images-upload">
                            <!-- Dropzone -->
                            <div class="dropzone" id="dropzone">
                                <div class="dropzone-content">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <h4>اسحب وأفلت الصور هنا</h4>
                                    <p>أو انقر لاختيار الملفات</p>
                                    <span class="file-types">JPG, PNG, GIF - الحد الأقصى 5MB</span>
                                </div>
                                <input type="file" id="image-upload" name="images[]"
                                       multiple accept="image/*" style="display: none;">
                            </div>

                            <!-- Images Preview -->
                            <div class="images-preview" id="images-preview">
                                <!-- سيتم إضافة المعاينات هنا -->
                            </div>

                            <input type="hidden" name="images_data" id="images-data">
                        </div>
                    </div>

                    <!-- Trip Selection (Optional) -->
                    <div class="form-section">
                        <div class="form-header">
                            <h3><i class="fas fa-map-marked-alt"></i> ربط المقال برحلة (اختياري)</h3>
                            <p>ربط المقال برحلة محددة يجعلها أكثر مصداقية</p>
                        </div>

                        <div class="trip-selection">
                            <div class="select-wrapper">
                                <select name="trip_id" id="trip_id" class="form-select">
                                    <option value="">لا يوجد رحلة (مقال عام)</option>
                                    @foreach($bookedTrips as $trip)
                                        @if($trip)
                                            <option value="{{ $trip->id }}">{{ $trip->title }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Rating (Only if trip is selected) -->
                    <div class="form-section" id="rating-section">
                        <div class="form-header">
                            <h3><i class="fas fa-star"></i> تقييم الرحلة</h3>
                            <p>كيف تقيم تجربتك في هذه الرحلة؟</p>
                        </div>

                        <div class="rating-container">
                            <div class="stars-rating" id="stars-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star" data-rating="{{ $i }}"></i>
                                @endfor
                            </div>
                            <div class="rating-text" id="rating-text">اضغط على النجوم للتقييم</div>
                            <input type="hidden" name="rating" id="rating" value="5">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Side: Preview & Tips -->
            <div class="preview-side">
                <!-- Live Preview -->
                <div class="preview-card">
                    <div class="preview-header">
                        <h3><i class="fas fa-eye"></i> معاينة المقال</h3>
                        <button type="button" class="btn-refresh" id="refresh-preview">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                    <div class="preview-body">
                        <div class="article-preview" id="article-preview">
                            <!-- معاينة المقال ستظهر هنا -->
                            <div class="empty-preview">
                                <i class="fas fa-newspaper"></i>
                                <p>ابدأ بكتابة مقالك لترى المعاينة هنا</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Writing Tips -->
                <div class="tips-card">
                    <div class="tips-header">
                        <h3><i class="fas fa-lightbulb"></i> نصائح للكتابة</h3>
                    </div>
                    <div class="tips-body">
                        <div class="tips-list">
                            <div class="tip-item">
                                <div class="tip-icon success">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="tip-content">
                                    <h5>اكتب عنواناً جذاباً</h5>
                                    <p>العنوان هو أول ما يقرأه الزوار، اجعله مميزاً</p>
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-icon info">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div class="tip-content">
                                    <h5>استخدم صوراً عالية الجودة</h5>
                                    <p>الصور الجيدة تزيد من تفاعل القراء</p>
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-icon warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="tip-content">
                                    <h5>تجنب الأخطاء الإملائية</h5>
                                    <p>راجع مقالك قبل النشر للتأكد من خلوه من الأخطاء</p>
                                </div>
                            </div>
                            <div class="tip-item">
                                <div class="tip-icon primary">
                                    <i class="fas fa-pen"></i>
                                </div>
                                <div class="tip-content">
                                    <h5>كن صادقاً في الوصف</h5>
                                    <p>شارك تجربتك الحقيقية بصدق ووضوح</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Article Stats -->
                <div class="stats-card">
                    <div class="stats-header">
                        <h3><i class="fas fa-chart-bar"></i> إحصائيات المقال</h3>
                    </div>
                    <div class="stats-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-font"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number" id="word-count">0</div>
                                    <div class="stat-label">كلمة</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number" id="read-time">0</div>
                                    <div class="stat-label">دقيقة قراءة</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-number" id="image-count">0</div>
                                    <div class="stat-label">صورة</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('scripts')

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        // تهيئة محرر Quill
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
            placeholder: 'اكتب محتوى مقالك هنا... شارك تفاصيل رحلتك وتجربتك مع القراء...',
            theme: 'snow'
        });

        // تحديث الحقل المخفي بالمحتوى
        quill.on('text-change', function() {
            document.getElementById('content').value = quill.root.innerHTML;
            updatePreview();
            updateStats();
        });

        // متغيرات الحالة
        let uploadedImages = [];
        let currentRating = 5;

        // DOM Elements
        const btnSaveDraft = document.getElementById('btn-save-draft');
        const btnPublish = document.querySelector('.btn-publish');
        const saveDraftModal = document.getElementById('saveDraftModal');
        const publishModal = document.getElementById('publishModal');
        const dropzone = document.getElementById('dropzone');
        const imageUpload = document.getElementById('image-upload');
        const imagesPreview = document.getElementById('images-preview');
        const starsRating = document.getElementById('stars-rating');
        const ratingText = document.getElementById('rating-text');
        const ratingInput = document.getElementById('rating');
        const refreshPreviewBtn = document.getElementById('refresh-preview');

        // تهيئة التقييم
        starsRating.querySelectorAll('.fa-star').forEach((star, index) => {
            star.addEventListener('click', function() {
                currentRating = parseInt(this.dataset.rating);
                updateRating(currentRating);
            });

            star.addEventListener('mouseover', function() {
                const hoverRating = parseInt(this.dataset.rating);
                previewRating(hoverRating);
            });

            star.addEventListener('mouseout', function() {
                updateRating(currentRating);
            });
        });

        function updateRating(rating) {
            if (rating === 0) {
                starsRating.querySelectorAll('.fa-star').forEach((star) => {
                    star.classList.remove('active');
                    star.style.color = '#e2e8f0';
                });
                ratingText.textContent = 'اضغط على النجوم للتقييم';
                ratingInput.value = '';
                return;
            }

            starsRating.querySelectorAll('.fa-star').forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('active');
                    star.style.color = '#f59e0b';
                } else {
                    star.classList.remove('active');
                    star.style.color = '#e2e8f0';
                }
            });

            const texts = [
                'سيئة جداً',
                'سيئة',
                'متوسطة',
                'جيدة',
                'ممتازة'
            ];
            ratingText.textContent = texts[rating - 1];
            ratingInput.value = rating;
        }

        function previewRating(rating) {
            starsRating.querySelectorAll('.fa-star').forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#fbbf24';
                } else {
                    star.style.color = '#e2e8f0';
                }
            });
        }

        // تحديث المعاينة الحية
        function updatePreview() {
            const title = document.getElementById('title').value || 'عنوان المقال';
            const content = quill.getText() || 'محتوى المقال...';
            const excerpt = document.getElementById('excerpt').value ||
                           content.substring(0, 150) + '...';

            const preview = document.getElementById('article-preview');
            preview.innerHTML = `
                <div class="preview-article">
                    <h2 class="preview-title">${title}</h2>
                    <div class="preview-meta">
                        <span class="preview-author">بواسطة: {{ Auth::user()->full_name }}</span>
                        <span class="preview-date">${new Date().toLocaleDateString('ar-SA')}</span>
                    </div>
                    <div class="preview-excerpt">
                        <p>${excerpt}</p>
                    </div>
                    <div class="preview-content">
                        ${quill.root.innerHTML || '<p>محتوى المقال...</p>'}
                    </div>
                    ${uploadedImages.length > 0 ? `
                        <div class="preview-images">
                            <h4>صور المقال</h4>
                            <div class="images-grid">
                                ${uploadedImages.slice(0, 3).map(img => `
                                    <div class="preview-image">
                                        <img src="${img.url}" alt="صورة المقال">
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : ''}
                </div>
            `;
        }

        // تحديث الإحصائيات
        function updateStats() {
            const content = quill.getText();
            const words = content.trim().split(/\s+/).filter(word => word.length > 0).length;
            const readTime = Math.ceil(words / 200); // 200 كلمة في الدقيقة

            document.getElementById('word-count').textContent = words.toLocaleString();
            document.getElementById('read-time').textContent = readTime;
            document.getElementById('image-count').textContent = uploadedImages.length;
        }

        // نظام رفع الصور
        dropzone.addEventListener('click', () => imageUpload.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            handleImages(files);
        });

        imageUpload.addEventListener('change', (e) => {
            handleImages(e.target.files);
        });

        function handleImages(files) {
            const maxSize = 5 * 1024 * 1024; // 5MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            Array.from(files).forEach(file => {
                // التحقق من الحجم
                if (file.size > maxSize) {
                    showAlert(`الصورة ${file.name} أكبر من 5MB`, 'error');
                    return;
                }

                // التحقق من النوع
                if (!allowedTypes.includes(file.type)) {
                    showAlert(`نوع الملف ${file.name} غير مدعوم`, 'error');
                    return;
                }

                // تحويل الصورة لـ Base64
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageData = {
                        id: Date.now() + Math.random(),
                        name: file.name,
                        url: e.target.result,
                        size: file.size
                    };

                    uploadedImages.push(imageData);
                    updateImagePreview();
                    updateStats();
                    saveImagesToForm();
                };
                reader.readAsDataURL(file);
            });

            imageUpload.value = '';
        }

        function updateImagePreview() {
            imagesPreview.innerHTML = uploadedImages.map((img, index) => `
                <div class="image-preview-item" data-id="${img.id}">
                    <div class="image-container">
                        <img src="${img.url}" alt="${img.name}">
                        <button class="remove-image" onclick="removeImage('${img.id}')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="image-info">
                        <span class="image-name">${img.name}</span>
                        <span class="image-size">${formatBytes(img.size)}</span>
                    </div>
                </div>
            `).join('');
        }

        function removeImage(id) {
            uploadedImages = uploadedImages.filter(img => img.id !== id);
            updateImagePreview();
            updateStats();
            saveImagesToForm();
        }

        function formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function saveImagesToForm() {
            document.getElementById('images-data').value = JSON.stringify(uploadedImages);
        }

        // حفظ المسودة
        btnSaveDraft.addEventListener('click', () => {
            if (!validateForm()) {
                return;
            }
            saveDraftModal.style.display = 'block';
        });

        document.getElementById('confirm-save-draft').addEventListener('click', () => {
            // إضافة حقل المسودة
            const draftField = document.createElement('input');
            draftField.type = 'hidden';
            draftField.name = 'draft';
            draftField.value = '1';
            document.getElementById('article-form').appendChild(draftField);

            // إرسال النموذج
            document.getElementById('article-form').submit();
        });

        // النشر
        btnPublish.addEventListener('click', (e) => {
            e.preventDefault();
            if (!validateForm()) {
                return;
            }
            publishModal.style.display = 'block';
        });

        document.getElementById('confirm-publish').addEventListener('click', () => {
            document.getElementById('article-form').submit();
        });

        // التحقق من النموذج
        function validateForm() {
            const title = document.getElementById('title').value.trim();
            const content = quill.getText().trim();

            if (!title) {
                showAlert('يرجى إدخال عنوان المقال', 'error');
                document.getElementById('title').focus();
                return false;
            }

            if (!content) {
                showAlert('يرجى كتابة محتوى المقال', 'error');
                quill.focus();
                return false;
            }

            if (content.length < 100) {
                showAlert('محتوى المقال قصير جداً. يرجى كتابة مقال أكثر تفصيلاً', 'warning');
                quill.focus();
                return false;
            }

            return true;
        }

        // إغلاق المودال
        document.querySelectorAll('.modal-close, .btn-cancel').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.modal').style.display = 'none';
            });
        });

        // زر تحديث المعاينة
        refreshPreviewBtn.addEventListener('click', updatePreview);

        // عرض التنبيهات
        function showAlert(message, type = 'info') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' :
                                  type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                ${message}
                <button class="alert-close">&times;</button>
            `;

            alert.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'error' ? '#fee' :
                            type === 'warning' ? '#fffbeb' : '#eff6ff'};
                border: 1px solid ${type === 'error' ? '#fcc' :
                                 type === 'warning' ? '#fde68a' : '#bfdbfe'};
                color: ${type === 'error' ? '#dc2626' :
                        type === 'warning' ? '#d97706' : '#1d4ed8'};
                padding: 1rem 1.5rem;
                border-radius: 8px;
                z-index: 9999;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                animation: slideInRight 0.3s ease;
                max-width: 400px;
            `;

            document.body.appendChild(alert);

            alert.querySelector('.alert-close').addEventListener('click', () => {
                alert.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            });

            setTimeout(() => {
                if (alert.parentNode) {
                    alert.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }

        // تحديث تلقائي للمعاينة
        document.getElementById('title').addEventListener('input', updatePreview);
        document.getElementById('excerpt').addEventListener('input', updatePreview);

        // الحفظ التلقائي
        let autoSaveInterval;
        function startAutoSave() {
            autoSaveInterval = setInterval(() => {
                if (document.getElementById('title').value || quill.getText()) {
                    saveDraft(true);
                }
            }, 30000); // كل 30 ثانية
        }

        function saveDraft(silent = false) {
            // هنا يمكنك إضافة كود لحفظ المسودة في LocalStorage أو إرسالها للخادم
            if (!silent) {
                showAlert('تم الحفظ التلقائي للمسودة', 'info');
            }
        }

        // بدء الحفظ التلقائي
        startAutoSave();

        // تحديث أولي
        updateRating(currentRating);
        updateStats();

        // منع إغلاق الصفحة بدون حفظ
        window.addEventListener('beforeunload', (e) => {
            if (document.getElementById('title').value || quill.getText()) {
                e.preventDefault();
                e.returnValue = 'لديك تغييرات غير محفوظة. هل تريد المغادرة بدون حفظ؟';
            }
        });

        // إضافة أنيميشن CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }

            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
    </script>
@endpush

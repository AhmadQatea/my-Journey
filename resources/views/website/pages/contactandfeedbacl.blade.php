@extends('website.pages.layouts.app')

@section('title', 'اتصل بنا - MyJourney')

@section('content')
    @if(session('status'))
        <section class="section" style="padding-bottom: 1rem;">
            <div class="container">
                <div class="card" style="background: #ecfdf3; border: 1px solid #bbf7d0; color: #166534;">
                    <div class="card-body" style="padding: 1rem 1.5rem;">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- ========== CONTACT HERO ========== -->
    <section class="hero-section" style="background: var(--gradient-primary);">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">اتصل بنا</h1>
                <p class="hero-subtitle">
                    نحن هنا لمساعدتك، اتصل بنا لأي استفسار أو ملاحظة
                </p>
            </div>
        </div>
    </section>

    <!-- ========== CONTACT INFO ========== -->
    <section class="section">
        <div class="container">
            <div class="grid grid-3">
                <!-- Contact Card 1 -->
                <div class="contact-card fade-in">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="contact-icon" style="width: 80px; height: 80px; background: rgba(67, 97, 238, 0.1); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary); font-size: 2rem;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3>الموقع</h3>
                            <p style="color: var(--gray-600);">دمشق، سوريا</p>
                            <p style="color: var(--gray-500); font-size: 0.875rem;">
                                شارع الرئيس، بناء رقم 123
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Card 2 -->
                <div class="contact-card fade-in">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="contact-icon" style="width: 80px; height: 80px; background: rgba(157, 78, 221, 0.1); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary); font-size: 2rem;">
                                <i class="fas fa-phone"></i>
                            </div>
                            <h3>الهاتف</h3>
                            <p style="color: var(--gray-600);">+963 11 123 4567</p>
                            <p style="color: var(--gray-500); font-size: 0.875rem;">
                                الأحد - الخميس: 9 صباحاً - 5 مساءً
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Card 3 -->
                <div class="contact-card fade-in">
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="contact-icon" style="width: 80px; height: 80px; background: rgba(76, 201, 240, 0.1); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary); font-size: 2rem;">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3>البريد الإلكتروني</h3>
                            <p style="color: var(--gray-600);">info@myjourney.sy</p>
                            <p style="color: var(--gray-500); font-size: 0.875rem;">
                                نرد خلال 24 ساعة
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== CONTACT FORM ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="grid grid-2 gap-4 align-items-start">
                <!-- Contact Form -->
                <div class="fade-in">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="section-title" style="margin-bottom: 2rem;">أرسل رسالة</h2>

                            <form class="contact-form" method="POST" action="{{ route('contact.store') }}">
                                @csrf
                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700);">
                                        <i class="fas fa-user"></i> الاسم الكامل
                                    </label>
                                    <input type="text" id="name" name="name"
                                           style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-family: inherit;"
                                           placeholder="أدخل اسمك الكامل"
                                           value="{{ old('name', $user?->full_name) }}" required>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700);">
                                        <i class="fas fa-envelope"></i> البريد الإلكتروني
                                    </label>
                                    <input type="email" id="email" name="email"
                                           style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-family: inherit;"
                                           placeholder="أدخل بريدك الإلكتروني"
                                           value="{{ old('email', $user?->email) }}" required>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="subject" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700);">
                                        <i class="fas fa-tag"></i> الموضوع
                                    </label>
                                    <select id="subject" name="topic"
                                            style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-family: inherit;">
                                        <option value="">اختر الموضوع</option>
                                        <option value="booking" {{ old('topic') === 'booking' ? 'selected' : '' }}>استفسار عن حجز</option>
                                        <option value="trip" {{ old('topic') === 'trip' ? 'selected' : '' }}>استفسار عن رحلة</option>
                                        <option value="account" {{ old('topic') === 'account' ? 'selected' : '' }}>مشكلة في الحساب</option>
                                        <option value="technical" {{ old('topic') === 'technical' ? 'selected' : '' }}>مشكلة تقنية</option>
                                        <option value="other" {{ old('topic') === 'other' ? 'selected' : '' }}>موضوع آخر</option>
                                    </select>
                                </div>

                                <div class="form-group" style="margin-bottom: 1.5rem;">
                                    <label for="message" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--gray-700);">
                                        <i class="fas fa-comment"></i> الرسالة
                                    </label>
                                    <textarea id="message" name="message" rows="5"
                                              style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md); font-family: inherit; resize: vertical;"
                                              placeholder="اكتف رسالتك هنا..." required>{{ old('message') }}</textarea>
                                </div>

                                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                                    <i class="fas fa-paper-plane"></i>
                                    إرسال الرسالة
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="fade-in">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="section-title" style="margin-bottom: 2rem;">أسئلة متكررة</h2>

                            <div class="faq-accordion">
                                @php
                                    $faqs = [
                                        [
                                            'question' => 'كيف يمكنني حجز رحلة؟',
                                            'answer' => 'يمكنك حجز رحلة عن طريق التسجيل في الموقع، ثم اختيار الرحلة المناسبة وتعبئة نموذج الحجوزات. سيتواصل معك مسؤول الحجز لتأكيد الحجز.'
                                        ],
                                        [
                                            'question' => 'هل أحتاج إلى توثيق الهوية؟',
                                            'answer' => 'نعم، يجب توثيق الهوية قبل الحجز عن طريق إرسال صورة الهوية الشخصية. هذا لضمان أمن وسلامة جميع المسافرين.'
                                        ],
                                        [
                                            'question' => 'كيف يمكنني إلغاء الحجز؟',
                                            'answer' => 'يمكنك إلغاء الحجز من خلال صفحة حجوزاتك في لوحة التحكم، مع مراعاة سياسة الإلغاء الخاصة بكل رحلة.'
                                        ],
                                        [
                                            'question' => 'هل يمكنني تعديل بيانات حجزي؟',
                                            'answer' => 'نعم، يمكنك طلب تعديل الحجز من خلال صفحة الحجز، وسيقوم مسؤول الحجز بالرد على طلبك في أقرب وقت.'
                                        ],
                                        [
                                            'question' => 'كيف يمكنني كتابة مقال؟',
                                            'answer' => 'بعد تسجيل الدخول، يمكنك النقر على زر "كتابة مقال" في صفحة المقالات، وملء النموذج المطلوب.'
                                        ]
                                    ];
                                @endphp

                                @foreach($faqs as $index => $faq)
                                    <div class="faq-item" style="margin-bottom: 1rem; border: 1px solid var(--gray-200); border-radius: var(--radius-md); overflow: hidden;">
                                        <button class="faq-question"
                                                style="width: 100%; padding: 1rem; text-align: right; background: var(--gray-50); border: none; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: 600; color: var(--gray-800);"
                                                onclick="toggleFaq({{ $index }})">
                                            <span>{{ $faq['question'] }}</span>
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                        <div class="faq-answer" id="faq-{{ $index }}"
                                             style="padding: 0; max-height: 0; overflow: hidden; transition: var(--transition-base);">
                                            <div style="padding: 1rem; background: var(--gray-200); border-top: 1px solid var(--gray-200); color: var(--gray-600);">
                                                {{ $faq['answer'] }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== GOOGLE MAP ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">موقعنا على الخريطة</h2>
                <p class="section-subtitle">يمكنك زيارتنا في مقرنا الرئيسي</p>
            </div>

            <div class="map-container fade-in" style="border-radius: var(--radius-xl); overflow: hidden; box-shadow: var(--shadow-xl);">
                <!-- Google Map Embed -->
                <div style="width: 100%; height: 400px; background: linear-gradient(135deg, var(--gray-100), var(--gray-200)); display: flex; align-items: center; justify-content: center; color: var(--gray-500);">
                    <div style="text-align: center;">
                        <i class="fas fa-map-marked-alt" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <h3>دمشق، سوريا</h3>
                        <p>خريطة تفاعلية لمقرنا الرئيسي</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FEEDBACK FORM ========== -->
    <section class="section" style="background: var(--gradient-primary); color: white;">
        <div class="container">
            <div class="section-header" style="color: white;">
                <h2 class="section-title">شاركنا رأيك</h2>
                <p class="section-subtitle" style="opacity: 0.9;">ملاحظاتك تساعدنا على التطوير</p>
            </div>

            <div class="feedback-form-container fade-in">
                <div class="card" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                    <div class="card-body">
                        <form class="feedback-form" method="POST" action="{{ route('feedback.store') }}">
                            @csrf
                            <div class="grid grid-2 gap-3">
                                <div class="form-group">
                                    <label style="display: block; margin-bottom: 0.5rem; opacity: 0.9;">
                                        <i class="fas fa-user"></i> الاسم
                                    </label>
                                    <input type="text" name="name"
                                           style="width: 100%; padding: 0.75rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: var(--radius-md); color: white;"
                                           placeholder="اسمك"
                                           value="{{ old('name', $user?->full_name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label style="display: block; margin-bottom: 0.5rem; opacity: 0.9;">
                                        <i class="fas fa-star"></i> التقييم العام
                                    </label>
                                    <div class="rating-stars" style="display: flex; gap: 0.5rem; direction: ltr;">
                                        @for($i = 5; $i >= 1; $i--)
                                            <label style="cursor: pointer;">
                                                <input type="radio" name="rating" value="{{ $i }}" style="display: none;"
                                                       {{ (int) old('rating', 0) === $i ? 'checked' : '' }}>
                                                <i class="fas fa-star" style="color: #e2e8f0; font-size: 1.5rem; transition: var(--transition-fast);"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="margin: 1.5rem 0;">
                                <label style="display: block; margin-bottom: 0.5rem; opacity: 0.9;">
                                    <i class="fas fa-comment-dots"></i> ملاحظاتك
                                </label>
                                <textarea rows="4" name="comments"
                                          style="width: 100%; padding: 0.75rem 1rem; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.3); border-radius: var(--radius-md); color: white; resize: vertical;"
                                          placeholder="شاركنا تجربتك معنا..." required>{{ old('comments') }}</textarea>
                            </div>

                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label style="display: block; margin-bottom: 0.5rem; opacity: 0.9;">
                                    <i class="fas fa-thumbs-up"></i> ما الذي أعجبك؟
                                </label>
                                <div class="checkboxes" style="display: flex; flex-wrap: wrap; gap: 1rem;">
                                    @php
                                        $likes = ['سهولة الاستخدام', 'تصميم الموقع', 'تنوع الرحلات', 'خدمة العملاء', 'جودة المعلومات'];
                                    @endphp

                                    @foreach($likes as $like)
                                        <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; opacity: 0.9;">
                                            <input type="checkbox" name="likes[]" value="{{ $like }}" style="accent-color: white;"
                                                   {{ in_array($like, old('likes', [])) ? 'checked' : '' }}>
                                            <span>{{ $like }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="btn btn-outline" style="background: white; color: var(--primary); border-color: white; width: 100%; padding: 1rem;">
                                <i class="fas fa-paper-plane"></i>
                                إرسال التقييم
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // FAQ Accordion
    function toggleFaq(index) {
        const answer = document.getElementById(`faq-${index}`);
        const icon = answer.previousElementSibling.querySelector('.fa-chevron-down');

        if (answer.style.maxHeight) {
            answer.style.maxHeight = null;
            icon.style.transform = 'rotate(0deg)';
        } else {
            answer.style.maxHeight = answer.scrollHeight + 'px';
            icon.style.transform = 'rotate(180deg)';
        }
    }

    // Star Rating
    document.querySelectorAll('.rating-stars label').forEach(label => {
        const input = label.querySelector('input');
        const star = label.querySelector('.fa-star');

        label.addEventListener('mouseover', function() {
            const stars = this.parentElement.querySelectorAll('.fa-star');
            const index = Array.from(stars).indexOf(star);

            stars.forEach((s, i) => {
                if (i <= index) {
                    s.style.color = '#fbbf24';
                }
            });
        });

        label.addEventListener('mouseout', function() {
            const stars = this.parentElement.querySelectorAll('.fa-star');
            const checked = this.parentElement.querySelector('input:checked');

            stars.forEach(s => {
                s.style.color = '#e2e8f0';
            });

            if (checked) {
                const checkedIndex = Array.from(this.parentElement.querySelectorAll('input')).indexOf(checked);
                stars.forEach((s, i) => {
                    if (i >= checkedIndex) {
                        s.style.color = '#fbbf24';
                    }
                });
            }
        });

        label.addEventListener('click', function() {
            const stars = this.parentElement.querySelectorAll('.fa-star');
            const index = Array.from(stars).indexOf(star);

            stars.forEach((s, i) => {
                if (i >= index) {
                    s.style.color = '#fbbf24';
                } else {
                    s.style.color = '#e2e8f0';
                }
            });
        });
    });

    // إزالة اعتراض الإرسال الافتراضي حتى تعمل النماذج مع Laravel
</script>

@push('styles')
<style>
    .contact-card .card {
        transition: var(--transition-base);
        height: 100%;
    }

    .contact-card .card:hover {
        transform: translateY(-5px);
        background: var(--primary);
        color: white;
    }

    .contact-card .card:hover .contact-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .contact-card .card:hover p {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .faq-question {
        transition: var(--transition-base);
    }

    .faq-question:hover {
        background: var(--gray-100);
    }

    .faq-answer {
        transition: var(--transition-base);
    }

    .rating-stars .fa-star {
        transition: var(--transition-fast);
    }

    .rating-stars label:hover .fa-star {
        transform: scale(1.2);
    }

    .checkboxes label:hover {
        opacity: 1;
    }

    input:focus, textarea:focus, select:focus {
        outline: none;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1) !important;
    }
</style>
@endpush

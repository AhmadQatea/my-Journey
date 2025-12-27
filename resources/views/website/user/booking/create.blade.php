@extends('website.user.layouts.user')

@section('title', 'إنشاء حجز جديد - MyJourney')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/booking.css') }}">
@endpush

@section('content')
<div class="page-header">
    <div class="header-content">
        <h2 class="page-title">إنشاء حجز جديد</h2>
        <p class="page-subtitle">احجز رحلتك القادمة</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('my-bookings') }}" class="btn btn-new-trip">
            <i class="fas fa-arrow-right"></i>
            <span>العودة</span>
        </a>
    </div>
</div>

<main class="booking-main">
        <!-- Progress Steps -->
        <div class="progress-steps" id="progress-steps">
            <div class="steps-container">
                <div class="step active" data-step="1">
                    <div class="step-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <div class="step-content">
                        <span class="step-title">اختيار الرحلة</span>
                        <span class="step-subtitle">الخطوة 1 من 3</span>
                    </div>
                    <div class="step-line"></div>
                </div>
                <div class="step" data-step="2">
                    <div class="step-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="step-content">
                        <span class="step-title">تفاصيل الحجز</span>
                        <span class="step-subtitle">الخطوة 2 من 3</span>
                    </div>
                    <div class="step-line"></div>
                </div>
                <div class="step" data-step="3">
                    <div class="step-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="step-content">
                        <span class="step-title">التأكيد</span>
                        <span class="step-subtitle">الخطوة 3 من 3</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="booking-container">
            <!-- Left Side: Trip Selection -->
            <div class="booking-left">
                <!-- Trip Search & Filter -->
                <div class="trip-search-card">
                    <div class="search-header">
                        <h3><i class="fas fa-search"></i> بحث عن رحلة</h3>
                    </div>
                    <div class="search-body">
                        <div class="search-input-group">
                            <input type="text" id="trip-search" placeholder="ابحث عن رحلة..." class="search-input">
                            <button class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>

                        <div class="filters">
                            <div class="filter-group">
                                <label for="province-filter"><i class="fas fa-map-marker-alt"></i> المحافظة</label>
                                <select id="province-filter" class="filter-select">
                                    <option value="">جميع المحافظات</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="type-filter"><i class="fas fa-tags"></i> نوع الرحلة</label>
                                <select id="type-filter" class="filter-select">
                                    <option value="">جميع الأنواع</option>
                                    <option value="بحرية">بحرية</option>
                                    <option value="تراثية">تراثية</option>
                                    <option value="دينية">دينية</option>
                                    <option value="طبيعية">طبيعية</option>
                                    <option value="شتوية">شتوية</option>
                                    <option value="تسلق">تسلق</option>
                                    <option value="سياحية">سياحية</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="price-filter"><i class="fas fa-money-bill-wave"></i> السعر</label>
                                <select id="price-filter" class="filter-select">
                                    <option value="">جميع الأسعار</option>
                                    <option value="0-50000">أقل من 50,000 ل.س</option>
                                    <option value="50000-100000">50,000 - 100,000 ل.س</option>
                                    <option value="100000-200000">100,000 - 200,000 ل.س</option>
                                    <option value="200000-500000">200,000 - 500,000 ل.س</option>
                                    <option value="500000+">أكثر من 500,000 ل.س</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="duration-filter"><i class="fas fa-clock"></i> المدة</label>
                                <select id="duration-filter" class="filter-select">
                                    <option value="">جميع المدد</option>
                                    <option value="0-4">أقل من 4 ساعات</option>
                                    <option value="4-8">4 - 8 ساعات</option>
                                    <option value="8-12">8 - 12 ساعة</option>
                                    <option value="12+">أكثر من 12 ساعة</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="capacity-filter"><i class="fas fa-users"></i> السعة</label>
                                <select id="capacity-filter" class="filter-select">
                                    <option value="">جميع السعات</option>
                                    <option value="1-5">1 - 5 أشخاص</option>
                                    <option value="6-10">6 - 10 أشخاص</option>
                                    <option value="11-20">11 - 20 شخص</option>
                                    <option value="20+">أكثر من 20 شخص</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label for="sort-filter"><i class="fas fa-sort"></i> الترتيب</label>
                                <select id="sort-filter" class="filter-select">
                                    <option value="newest">الأحدث أولاً</option>
                                    <option value="oldest">الأقدم أولاً</option>
                                    <option value="price-low">السعر: من الأقل للأعلى</option>
                                    <option value="price-high">السعر: من الأعلى للأقل</option>
                                    <option value="duration-low">المدة: من الأقل للأعلى</option>
                                    <option value="duration-high">المدة: من الأعلى للأقل</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Trips & Offers -->
                <div class="trips-list-container">
                    <h3 class="trips-title">
                        <i class="fas fa-map-signs"></i> الرحلات والعروض المتاحة
                        @if($allItems->count() > 0)
                            <span style="background: #4361ee; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.875rem; margin-right: 0.5rem;">
                                {{ $allItems->count() }}
                            </span>
                        @endif
                    </h3>


                    @if($allItems->isEmpty())
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <h4>لا توجد رحلات أو عروض متاحة حالياً</h4>
                            <p>تفقد لاحقاً أو اختر نوع رحلة مختلف</p>
                        </div>
                    @else
                        <div class="trips-grid" id="trips-grid">
                            @foreach($allItems as $item)
                                @php
                                    $isOffer = $item['type'] === 'offer';
                                    $model = $item['model'];
                                    $tripTypes = $item['trip_types'] ?? [];
                                    $primaryType = $item['trip_type'] ?? 'سياحية';
                                    $trip = $isOffer ? $model->trip : $model;
                                    $images = $item['images'] ?? [];
                                    $price = $item['price'];
                                    $originalPrice = $item['original_price'] ?? $price;
                                    $discountPercentage = $item['discount_percentage'] ?? 0;
                                @endphp
                                <div class="trip-card"
                                     data-item-id="{{ (string)$item['id'] }}"
                                     data-item-type="{{ $item['type'] }}"
                                     data-trip-id="{{ $item['trip_id'] ?? $item['id'] }}"
                                     data-trip-type="{{ $primaryType }}"
                                     data-trip-name="{{ $item['title'] }}"
                                     data-province="{{ $item['province'] ?? '' }}"
                                     data-price="{{ $price }}"
                                     data-duration="{{ $item['duration'] }}"
                                     data-capacity="{{ $item['max_capacity'] }}">
                                    @if($isOffer)
                                        <div class="offer-badge">
                                            <i class="fas fa-tag"></i> عرض خاص
                                        </div>
                                    @endif
                                    @if(count($images) > 0)
                                        <div class="trip-card-image">
                                            <img src="{{ asset('storage/'.$images[0]) }}" alt="{{ $item['title'] }}">
                                        </div>
                                    @endif
                                    <div class="trip-card-header">
                                        <span class="trip-type-badge">{{ $primaryType }}</span>
                                        <div class="price-container">
                                            @if($isOffer && $originalPrice > $price)
                                                <span class="trip-price-original">{{ number_format($originalPrice) }} ل.س</span>
                                            @endif
                                            <span class="trip-price">{{ number_format($price) }} ل.س</span>
                                        </div>
                                    </div>
                                    <div class="trip-card-body">
                                        <h4 class="trip-title">{{ $item['title'] }}</h4>
                                        <p class="trip-description">{{ Str::limit(strip_tags($item['description']), 120) }}</p>
                                        @if($isOffer && $discountPercentage > 0)
                                            <div class="discount-badge">
                                                <i class="fas fa-percent"></i> خصم {{ number_format($discountPercentage, 0) }}%
                                            </div>
                                        @endif
                                        <div class="trip-details">
                                            <div class="trip-detail">
                                                <i class="fas fa-clock"></i>
                                                <span>{{ $item['duration'] }} ساعة</span>
                                            </div>
                                            <div class="trip-detail">
                                                <i class="fas fa-users"></i>
                                                <span>حتى {{ $item['max_capacity'] }} أشخاص</span>
                                            </div>
                                            <div class="trip-detail">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span>{{ $item['province'] ?? 'غير محدد' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="trip-card-footer">
                                        <button type="button" class="btn-select-trip"
                                                data-item-id="{{ (string)$item['id'] }}"
                                                data-item-type="{{ $item['type'] }}"
                                                data-trip-id="{{ $item['trip_id'] ?? $item['id'] }}">
                                            <i class="fas fa-calendar-check"></i>
                                            احجز {{ $isOffer ? 'هذا العرض' : 'هذه الرحلة' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Side: Booking Form -->
            <div class="booking-right">
                <div class="booking-form-container">
                    <!-- Selected Trip Preview -->
                    <div class="selected-trip-preview" id="selected-trip-preview" style="display: none;">
                        <div class="trip-preview-header">
                            <h3><i class="fas fa-check-circle"></i> الرحلة المختارة</h3>
                            <button class="btn-change-trip" id="btn-change-trip">
                                <i class="fas fa-exchange-alt"></i>
                                تغيير
                            </button>
                        </div>
                        <div class="trip-preview-body" id="trip-preview-content">
                            <!-- سيتم ملؤه جافاسكريبت -->
                        </div>
                    </div>

                    <!-- Booking Form -->
                    <form action="{{ route('bookings.store') }}" method="POST" id="booking-form" class="booking-form">
                        @csrf
                        <input type="hidden" name="trip_id" id="trip_id" value="" required>

                        <div class="form-step" id="step1">
                            <div class="form-header">
                                <h3><i class="fas fa-calendar-alt"></i> تفاصيل الحجز</h3>
                                <p>املأ بيانات الحجز الخاصة بك</p>
                            </div>

                            <div class="form-group">
                                <label for="booking_date"><i class="fas fa-calendar-day"></i> تاريخ الرحلة</label>
                                <input type="date" id="booking_date" name="booking_date"
                                       class="form-input" required
                                       min="{{ date('Y-m-d') }}">
                                <div class="form-hint">اختر تاريخاً بعد اليوم</div>
                            </div>

                            <div class="form-group">
                                <label for="guest_count"><i class="fas fa-user-friends"></i> عدد الأشخاص</label>
                                <div class="guest-counter">
                                    <button type="button" class="counter-btn minus-btn" id="guest-minus">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="guest_count" name="guest_count"
                                           class="counter-input" value="1" min="1" max="20" required readonly>
                                    <button type="button" class="counter-btn plus-btn" id="guest-plus">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div class="form-hint">الحد الأقصى 20 شخص للرحلة الواحدة</div>
                            </div>

                            <div class="form-group">
                                <label for="special_requests"><i class="fas fa-comment-alt"></i> طلبات خاصة</label>
                                <textarea id="special_requests" name="special_requests"
                                          class="form-textarea"
                                          placeholder="أي طلبات خاصة أو احتياجات إضافية..."
                                          rows="3"></textarea>
                            </div>
                        </div>

                        <div class="form-step" id="step2" style="display: none;">
                            <div class="form-header">
                                <h3><i class="fas fa-user-check"></i> معلومات إضافية</h3>
                                <p>تأكد من معلوماتك الشخصية</p>
                            </div>

                            <div class="user-info-summary">
                                <div class="info-item">
                                    <span class="info-label">الاسم الكامل:</span>
                                    <span class="info-value">{{ Auth::user()->full_name }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">البريد الإلكتروني:</span>
                                    <span class="info-value">{{ Auth::user()->email }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">رقم الهاتف:</span>
                                    <span class="info-value">{{ Auth::user()->phone ?? 'غير مضاف' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">حالة التوثيق:</span>
                                    <span class="info-value {{ Auth::user()->identity_verified ? 'verified' : 'not-verified' }}">
                                        {{ Auth::user()->identity_verified ? 'موثق ✓' : 'غير موثق' }}
                                    </span>
                                </div>
                            </div>

                            @if(!Auth::user()->identity_verified)
                                <div class="verification-alert">
                                    <div class="alert-icon">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div class="alert-content">
                                        <h5>توثيق الهوية مطلوب</h5>
                                        <p>يجب توثيق هويتك قبل الحجز. <a href="{{ route('identity-verification.create') }}">توثيق الآن</a></p>
                                    </div>
                                </div>
                            @endif

                            <div class="terms-agreement">
                                <div class="checkbox-group">
                                    <input type="checkbox" id="terms_agreement" name="terms_agreement" required>
                                    <label for="terms_agreement">
                                        أوافق على <a href="#" class="terms-link">الشروط والأحكام</a> و
                                        <a href="#" class="terms-link">سياسة الخصوصية</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-step" id="step3" style="display: none;">
                            <div class="confirmation-step">
                                <div class="confirmation-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3>تأكيد الحجز</h3>
                                <p>يرجى مراجعة معلومات الحجز قبل التأكيد</p>

                                <div class="booking-summary" id="booking-summary">
                                    <!-- سيتم ملؤه جافاسكريبت -->
                                </div>

                                <div class="payment-notice">
                                    <div class="notice-icon">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div class="notice-content">
                                        <h5>ملاحظة مهمة</h5>
                                        <p>سيتواصل معك مسؤول الحجز لتأكيد الحجز وإتمام عملية الدفع</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Navigation -->
                        <div class="form-navigation">
                            <button type="button" class="btn btn-prev" id="btn-prev" style="display: none;">
                                <i class="fas fa-arrow-right"></i>
                                السابق
                            </button>
                            <button type="button" class="btn btn-next" id="btn-next">
                                التالي
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <button type="submit" class="btn btn-submit" id="btn-submit" style="display: none;">
                                <i class="fas fa-paper-plane"></i>
                                تأكيد الحجز
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

</main>
@endsection

@push('scripts')
<script>
        // تحميل بيانات الرحلات من الـ Blade
        const tripsData = @json($tripsData ?? []);

        // معلومات تصحيح
        console.log('عدد الرحلات المحملة:', tripsData.length);
        if (tripsData.length > 0) {
            console.log('أول رحلة:', tripsData[0]);
        }


        // متغيرات الحالة
        let currentStep = 1;
        let selectedTrip = null;

        // DOM Elements
        const btnNext = document.getElementById('btn-next');
        const btnPrev = document.getElementById('btn-prev');
        const btnSubmit = document.getElementById('btn-submit');
        const formSteps = document.querySelectorAll('.form-step');
        const progressSteps = document.querySelectorAll('.step');
        const selectedTripPreview = document.getElementById('selected-trip-preview');
        const tripPreviewContent = document.getElementById('trip-preview-content');
        const bookingSummary = document.getElementById('booking-summary');
        const tripIdInput = document.getElementById('trip_id');
        const guestCountInput = document.getElementById('guest_count');
        const guestMinusBtn = document.getElementById('guest-minus');
        const guestPlusBtn = document.getElementById('guest-plus');
        const btnChangeTrip = document.getElementById('btn-change-trip');

        // تهيئة الحد الأدنى لتاريخ الحجز
        document.getElementById('booking_date').min = new Date().toISOString().split('T')[0];

        // اختيار رحلة أو عرض
        document.querySelectorAll('.btn-select-trip').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // قراءة البيانات من الزر وتحويلها إلى سلسلة
                const itemId = String(this.dataset.itemId || '');
                const tripId = parseInt(this.dataset.tripId) || 0;
                
                if (!itemId) {
                    console.error('itemId غير موجود في الزر');
                    return;
                }
                
                selectTrip(itemId, tripId, this);
            });
        });

        // وظيفة اختيار رحلة أو عرض
        function selectTrip(itemId, tripId, clickedButton) {
            // تحويل itemId إلى سلسلة للمقارنة
            const itemIdStr = String(itemId);
            
            // البحث في tripsData
            const item = tripsData.find(t => String(t.id) === itemIdStr);
            
            if (!item) {
                console.error('رحلة أو عرض غير موجود:', itemId, 'النوع:', typeof itemId);
                console.log('المتاح في tripsData:', tripsData.map(t => ({ id: t.id, type: typeof t.id, type_name: t.type, title: t.title })));
                return;
            }

            selectedTrip = item;
            tripIdInput.value = tripId;

            // إذا كان عرض، حفظ offer_id
            const offerIdInput = document.getElementById('offer_id');
            if (offerIdInput && item.type === 'offer') {
                offerIdInput.value = item.id.replace('offer_', '');
            } else if (offerIdInput) {
                offerIdInput.value = '';
            }

            // تحديث المعاينة
            const tripType = item.trip_type || 'سياحية';
            const isOffer = item.type === 'offer';
            const priceDisplay = item.original_price && item.original_price > item.price
                ? `<span style="text-decoration: line-through; color: #999; margin-left: 0.5rem;">${item.original_price.toLocaleString()} ل.س</span>`
                : '';

            tripPreviewContent.innerHTML = `
                <div class="trip-preview-card">
                    ${isOffer ? '<div class="offer-badge-preview"><i class="fas fa-tag"></i> عرض خاص</div>' : ''}
                    <h4>${item.title}</h4>
                    <p>${item.description.substring(0, 150)}...</p>
                    <div class="trip-preview-details">
                        <div class="detail-item">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>السعر: <strong>${item.price.toLocaleString()} ل.س</strong>${priceDisplay}</span>
                        </div>
                        ${isOffer && item.discount_percentage > 0 ? `
                        <div class="detail-item">
                            <i class="fas fa-percent"></i>
                            <span>الخصم: <strong>${item.discount_percentage.toFixed(0)}%</strong></span>
                        </div>
                        ` : ''}
                        <div class="detail-item">
                            <i class="fas fa-clock"></i>
                            <span>المدة: <strong>${item.duration} ساعة</strong></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>المحافظة: <strong>${item.province}</strong></span>
                        </div>
                        <div class="detail-item">
                            <i class="fas fa-users"></i>
                            <span>السعة: <strong>${item.max_capacity} شخص</strong></span>
                        </div>
                    </div>
                </div>
            `;

            selectedTripPreview.style.display = 'block';

            // تطبيق CSS للبطاقة المختارة
            const card = clickedButton.closest('.trip-card');
            if (card) {
                document.querySelectorAll('.trip-card').forEach(c => c.classList.remove('selected-card'));
                card.classList.add('selected-card');
            }

            // تحريك إلى أعلى النموذج
            document.querySelector('.booking-right').scrollIntoView({ behavior: 'smooth' });

            // تحديث جميع الأزرار
            document.querySelectorAll('.btn-select-trip').forEach(btn => {
                const itemType = btn.dataset.itemType || (btn.closest('.trip-card')?.dataset.itemType);
                const isOffer = itemType === 'offer';
                btn.disabled = false;
                btn.innerHTML = `<i class="fas fa-calendar-check"></i> احجز ${isOffer ? 'هذا العرض' : 'هذه الرحلة'}`;
                btn.classList.remove('selected');
            });

            // تحديث الزر المضغوط
            if (clickedButton) {
                clickedButton.innerHTML = '<i class="fas fa-check-circle"></i> مختارة';
                clickedButton.disabled = true;
                clickedButton.classList.add('selected');
            }

            // تمكين زر التالي إذا كانت الخطوة الأولى
            if (currentStep === 1 && btnNext) {
                btnNext.disabled = false;
            }
        }

        // تغيير عدد الأشخاص
        guestPlusBtn.addEventListener('click', function() {
            let value = parseInt(guestCountInput.value);
            if (value < 20) {
                guestCountInput.value = value + 1;
                updateBookingSummary();
            }
        });

        guestMinusBtn.addEventListener('click', function() {
            let value = parseInt(guestCountInput.value);
            if (value > 1) {
                guestCountInput.value = value - 1;
                updateBookingSummary();
            }
        });

        // زر التالي
        btnNext.addEventListener('click', function() {
            if (currentStep < 3) {
                // التحقق من صحة الخطوة الحالية
                if (!validateStep(currentStep)) {
                    return;
                }

                // الانتقال للخطوة التالية
                formSteps[currentStep - 1].style.display = 'none';
                progressSteps[currentStep - 1].classList.remove('active');

                currentStep++;
                formSteps[currentStep - 1].style.display = 'block';
                progressSteps[currentStep - 1].classList.add('active');

                // تحديث أزرار التنقل
                updateNavigationButtons();

                // إذا كانت الخطوة الأخيرة، تحديث الملخص
                if (currentStep === 3) {
                    updateBookingSummary();
                }

                // تحريك للنموذج
                document.querySelector('.form-step:not([style*="none"])').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });

        // زر السابق
        btnPrev.addEventListener('click', function() {
            if (currentStep > 1) {
                formSteps[currentStep - 1].style.display = 'none';
                progressSteps[currentStep - 1].classList.remove('active');

                currentStep--;
                formSteps[currentStep - 1].style.display = 'block';
                progressSteps[currentStep - 1].classList.add('active');

                updateNavigationButtons();

                document.querySelector('.form-step:not([style*="none"])').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });

        // التحقق من صحة الخطوة
        function validateStep(step) {
            switch(step) {
                case 1:
                    if (!selectedTrip) {
                        showAlert('يرجى اختيار رحلة', 'error');
                        return false;
                    }
                    if (!document.getElementById('booking_date').value) {
                        showAlert('يرجى اختيار تاريخ الرحلة', 'error');
                        return false;
                    }
                    return true;

                case 2:
                    if (!document.getElementById('terms_agreement').checked) {
                        showAlert('يجب الموافقة على الشروط والأحكام', 'error');
                        return false;
                    }
                    if (!document.getElementById('booking_date').value) {
                        showAlert('يرجى اختيار تاريخ الرحلة', 'error');
                        return false;
                    }
                    return true;

                default:
                    return true;
            }
        }

        // تحديث أزرار التنقل
        function updateNavigationButtons() {
            btnPrev.style.display = currentStep > 1 ? 'flex' : 'none';
            btnNext.style.display = currentStep < 3 ? 'flex' : 'none';
            btnSubmit.style.display = currentStep === 3 ? 'flex' : 'none';

            if (currentStep === 1) {
                btnNext.disabled = !selectedTrip;
            }
        }

        // تحديث ملخص الحجز
        function updateBookingSummary() {
            if (!selectedTrip) return;

            const guestCount = parseInt(guestCountInput.value);
            const bookingDate = document.getElementById('booking_date').value;
            const totalPrice = selectedTrip.price * guestCount;

            const dateObj = new Date(bookingDate);
            const formattedDate = dateObj.toLocaleDateString('ar-SA', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            bookingSummary.innerHTML = `
                <div class="summary-item">
                    <span class="summary-label">الرحلة:</span>
                    <span class="summary-value">${selectedTrip.title}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">التاريخ:</span>
                    <span class="summary-value">${formattedDate}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">عدد الأشخاص:</span>
                    <span class="summary-value">${guestCount} شخص</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">سعر الفرد:</span>
                    <span class="summary-value">${selectedTrip.price.toLocaleString()} ل.س</span>
                </div>
                <div class="summary-item total">
                    <span class="summary-label">المبلغ الإجمالي:</span>
                    <span class="summary-value">${totalPrice.toLocaleString()} ل.س</span>
                </div>
            `;
        }

        // زر تغيير الرحلة
        if (btnChangeTrip) {
            btnChangeTrip.addEventListener('click', function() {
                // إخفاء معاينة الرحلة المختارة
                selectedTripPreview.style.display = 'none';
                
                // إعادة تعيين المتغيرات
                selectedTrip = null;
                tripIdInput.value = '';
                
                // إعادة تعيين offer_id
                const offerIdInput = document.getElementById('offer_id');
                if (offerIdInput) {
                    offerIdInput.value = '';
                }
                
                // إعادة تعيين الخطوة
                currentStep = 1;

                // إعادة تعيين النموذج
                formSteps.forEach((step, index) => {
                    step.style.display = index === 0 ? 'block' : 'none';
                    progressSteps[index].classList.toggle('active', index === 0);
                });

                // إعادة تفعيل جميع الأزرار
                document.querySelectorAll('.btn-select-trip').forEach(btn => {
                    btn.disabled = false;
                    btn.classList.remove('selected');
                    const itemType = btn.dataset.itemType;
                    const isOffer = itemType === 'offer';
                    const defaultText = isOffer ? 'احجز هذا العرض' : 'احجز هذه الرحلة';
                    btn.innerHTML = `<i class="fas fa-calendar-check"></i> ${defaultText}`;
                });

                // إزالة التحديد من البطاقات
                document.querySelectorAll('.trip-card').forEach(card => {
                    card.classList.remove('selected-card');
                });

                updateNavigationButtons();

                // العودة لقائمة الرحلات
                document.querySelector('.booking-left').scrollIntoView({ behavior: 'smooth' });
            });
        }

        // فلترة وترتيب الرحلات والعروض
        function filterTrips() {
            const searchTerm = document.getElementById('trip-search')?.value.toLowerCase() || '';
            const provinceFilter = document.getElementById('province-filter')?.value || '';
            const typeFilter = document.getElementById('type-filter')?.value || '';
            const priceFilter = document.getElementById('price-filter')?.value || '';
            const durationFilter = document.getElementById('duration-filter')?.value || '';
            const capacityFilter = document.getElementById('capacity-filter')?.value || '';
            const sortFilter = document.getElementById('sort-filter')?.value || 'newest';

            const cards = Array.from(document.querySelectorAll('.trip-card'));
            const visibleCards = [];

            cards.forEach(card => {
                const itemId = card.dataset.itemId;
                const item = tripsData.find(t => t.id === itemId);
                if (!item) {
                    card.style.display = 'none';
                    return;
                }

                let show = true;

                // فلترة النص
                if (searchTerm) {
                    const titleMatch = item.title.toLowerCase().includes(searchTerm);
                    const descMatch = item.description.toLowerCase().includes(searchTerm);
                    if (!titleMatch && !descMatch) {
                        show = false;
                    }
                }

                // فلترة المحافظة (باستخدام اسم المحافظة)
                if (provinceFilter && item.province) {
                    // نحتاج للتحقق من ID المحافظة من البيانات
                    // سنستخدم اسم المحافظة كبديل
                }

                // فلترة النوع
                if (typeFilter) {
                    const itemTypes = item.trip_types || [];
                    const primaryType = item.trip_type || '';
                    if (primaryType !== typeFilter && !itemTypes.includes(typeFilter)) {
                        show = false;
                    }
                }

                // فلترة السعر
                if (priceFilter) {
                    const price = parseFloat(item.price) || 0;
                    if (priceFilter.endsWith('+')) {
                        const min = parseInt(priceFilter.replace('+', '').replace(/,/g, ''));
                        if (price < min) show = false;
                    } else {
                        const [min, max] = priceFilter.split('-').map(v => parseInt(v.replace(/,/g, '')));
                        if (max) {
                            if (price < min || price > max) show = false;
                        } else if (min) {
                            if (price < min) show = false;
                        }
                    }
                }

                // فلترة المدة
                if (durationFilter) {
                    const duration = parseFloat(item.duration) || 0;
                    if (durationFilter.endsWith('+')) {
                        const min = parseInt(durationFilter.replace('+', ''));
                        if (duration < min) show = false;
                    } else {
                        const [min, max] = durationFilter.split('-').map(Number);
                        if (max) {
                            if (duration < min || duration > max) show = false;
                        } else if (min) {
                            if (duration < min) show = false;
                        }
                    }
                }

                // فلترة السعة
                if (capacityFilter) {
                    const capacity = parseFloat(item.max_capacity) || 0;
                    if (capacityFilter.endsWith('+')) {
                        const min = parseInt(capacityFilter.replace('+', ''));
                        if (capacity < min) show = false;
                    } else {
                        const [min, max] = capacityFilter.split('-').map(Number);
                        if (max) {
                            if (capacity < min || capacity > max) show = false;
                        } else if (min) {
                            if (capacity < min) show = false;
                        }
                    }
                }

                if (show) {
                    card.style.display = 'flex';
                    visibleCards.push({ card, item });
                } else {
                    card.style.display = 'none';
                }
            });

            // ترتيب البطاقات المرئية
            if (sortFilter && visibleCards.length > 0) {
                visibleCards.sort((a, b) => {
                    const itemA = a.item;
                    const itemB = b.item;

                    switch (sortFilter) {
                        case 'newest':
                            return 0; // بالفعل مرتبة حسب الأحدث
                        case 'oldest':
                            return 0; // عكس الترتيب
                        case 'price-low':
                            return (itemA.price || 0) - (itemB.price || 0);
                        case 'price-high':
                            return (itemB.price || 0) - (itemA.price || 0);
                        case 'duration-low':
                            return (itemA.duration || 0) - (itemB.duration || 0);
                        case 'duration-high':
                            return (itemB.duration || 0) - (itemA.duration || 0);
                        default:
                            return 0;
                    }
                });

                // إعادة ترتيب البطاقات في DOM
                const grid = document.getElementById('trips-grid');
                if (grid) {
                    visibleCards.forEach(({ card }) => {
                        grid.appendChild(card);
                    });
                }
            }

            // تحديث العدد
            const count = visibleCards.length;
            const countBadge = document.querySelector('.trips-title span');
            if (countBadge) {
                countBadge.textContent = count;
            }
        }

        // إضافة مستمعي الأحداث للفلاتر
        const tripSearch = document.getElementById('trip-search');
        const provinceFilter = document.getElementById('province-filter');
        const typeFilter = document.getElementById('type-filter');
        const priceFilter = document.getElementById('price-filter');
        const durationFilter = document.getElementById('duration-filter');
        const capacityFilter = document.getElementById('capacity-filter');
        const sortFilter = document.getElementById('sort-filter');

        if (tripSearch) tripSearch.addEventListener('input', filterTrips);
        if (provinceFilter) provinceFilter.addEventListener('change', filterTrips);
        if (typeFilter) typeFilter.addEventListener('change', filterTrips);
        if (priceFilter) priceFilter.addEventListener('change', filterTrips);
        if (durationFilter) durationFilter.addEventListener('change', filterTrips);
        if (capacityFilter) capacityFilter.addEventListener('change', filterTrips);
        if (sortFilter) sortFilter.addEventListener('change', filterTrips);

        // عرض التنبيهات
        function showAlert(message, type = 'info') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                ${message}
                <button class="alert-close">&times;</button>
            `;

            alert.style.cssText = `
                position: fixed;
                top: 20px;
                left: 20px;
                right: 20px;
                background: ${type === 'error' ? '#fee' : '#eff'};
                border: 1px solid ${type === 'error' ? '#fcc' : '#ccf'};
                color: ${type === 'error' ? '#c00' : '#00c'};
                padding: 1rem;
                border-radius: 8px;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: space-between;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                animation: slideIn 0.3s ease;
            `;

            document.body.appendChild(alert);

            // زر الإغلاق
            alert.querySelector('.alert-close').addEventListener('click', () => {
                alert.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            });

            // إزالة تلقائية بعد 5 ثوان
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.style.animation = 'slideOut 0.3s ease';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }

        // تحقق من توثيق الهوية عند الإرسال
        document.getElementById('booking-form').addEventListener('submit', function(e) {
            const isVerified = {{ Auth::user()->identity_verified ? 'true' : 'false' }};
            const termsAgreed = document.getElementById('terms_agreement').checked;

            if (!isVerified) {
                e.preventDefault();
                showAlert('يجب توثيق الهوية قبل الحجز. راجع معلوماتك في الخطوة الثانية.', 'error');
                currentStep = 2;
                formSteps.forEach((step, index) => {
                    step.style.display = index === 1 ? 'block' : 'none';
                    progressSteps[index].classList.toggle('active', index === 1);
                });
                updateNavigationButtons();
                document.querySelector('#step2').scrollIntoView({ behavior: 'smooth' });
                return;
            }

            if (!termsAgreed) {
                e.preventDefault();
                showAlert('يجب الموافقة على الشروط والأحكام', 'error');
                return;
            }

            if (!selectedTrip) {
                e.preventDefault();
                showAlert('يرجى اختيار رحلة', 'error');
                return;
            }

            // إظهار مؤشر التحميل
            const submitBtn = document.getElementById('btn-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحجز...';
            submitBtn.disabled = true;

            // إعادة تعيين الزر بعد 3 ثوان (في حالة فشل الإرسال)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        // إضافة أنيميشن CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateY(-20px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }

            @keyframes slideOut {
                from { transform: translateY(0); opacity: 1; }
                to { transform: translateY(-20px); opacity: 0; }
            }

            .fade-in {
                animation: fadeIn 0.5s ease;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }

            .pulse {
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);

        // تحديث الملخص عند تغيير التاريخ
        document.getElementById('booking_date').addEventListener('change', updateBookingSummary);

        // إضافة تأثيرات عند التمرير
        document.addEventListener('scroll', function() {
            const elements = document.querySelectorAll('.trip-card, .form-step');
            elements.forEach(el => {
                const rect = el.getBoundingClientRect();
                if (rect.top < window.innerHeight - 100) {
                    el.classList.add('fade-in');
                }
            });
        });

        // تحميل أولي
        window.addEventListener('load', function() {
            // التأكد من أن جميع البطاقات مرئية
            document.querySelectorAll('.trip-card').forEach(card => {
                card.style.display = 'flex';
                card.style.opacity = '1';
                card.style.visibility = 'visible';
                card.classList.add('fade-in');
            });

            // التأكد من أن جميع الأزرار مفعلة
            document.querySelectorAll('.btn-select-trip').forEach(btn => {
                btn.disabled = false;
                const itemType = btn.dataset.itemType;
                const isOffer = itemType === 'offer';
                const defaultText = isOffer ? 'احجز هذا العرض' : 'احجز هذه الرحلة';
                if (!btn.classList.contains('selected')) {
                    btn.innerHTML = `<i class="fas fa-calendar-check"></i> ${defaultText}`;
                }
            });

            // تعطيل زر التالي حتى اختيار رحلة
            if (btnNext) {
                btnNext.disabled = true;
            }

            // معلومات تصحيح
            console.log('عدد الرحلات المحملة:', tripsData.length);
            console.log('عدد البطاقات في الصفحة:', document.querySelectorAll('.trip-card').length);
            console.log('عدد الأزرار:', document.querySelectorAll('.btn-select-trip').length);
            console.log('الأزرار المعطلة:', document.querySelectorAll('.btn-select-trip:disabled').length);

            // اختيار الرحلة/العرض تلقائياً من query parameters
            @if(isset($selectedTripId) || isset($selectedOfferId))
                @if(isset($selectedOfferId))
                    // اختيار العرض
                    const offerId = 'offer_{{ $selectedOfferId }}';
                    const offerCard = document.querySelector(`[data-item-id="${offerId}"]`);
                    if (offerCard) {
                        const offerBtn = offerCard.querySelector('.btn-select-trip');
                        if (offerBtn) {
                            offerBtn.click();
                            // التمرير إلى البطاقة
                            offerCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                @elseif(isset($selectedTripId))
                    // اختيار الرحلة
                    const tripId = '{{ $selectedTripId }}';
                    const tripCard = document.querySelector(`[data-item-id="${tripId}"]`);
                    if (tripCard) {
                        const tripBtn = tripCard.querySelector('.btn-select-trip');
                        if (tripBtn) {
                            tripBtn.click();
                            // التمرير إلى البطاقة
                            tripCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                @endif
            @endif
        });
    </script>
@endpush

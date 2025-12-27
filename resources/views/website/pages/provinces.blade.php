@extends('website.pages.layouts.app')

@section('title', 'المحافظات والأماكن السياحية - MyJourney')

@section('content')
    <!-- ========== PROVINCES HERO ========== -->
    <section class="hero-section" style="background: linear-gradient(135deg, #2d6a4f 0%, #40916c 100%);">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">المحافظات والأماكن السياحية</h1>
                <p class="hero-subtitle">
                    استكشف 14 محافظة سورية وكنوزها السياحية الخفية
                </p>
                <div class="hero-actions">
                    <a href="#provinces" class="btn btn-primary btn-lg">
                        <i class="fas fa-map"></i>
                        استعرض المحافظات
                    </a>
                    <a href="#search" class="btn btn-outline btn-lg">
                        <i class="fas fa-search"></i>
                        بحث عن مكان
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== SEARCH SECTION ========== -->
    <section id="search" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">ابحث عن الأماكن السياحية</h2>
                <p class="section-subtitle">ابحث عن الوجهة المثالية لرحلتك القادمة</p>
            </div>

            <div class="search-container fade-in">
                <div class="card">
                    <div class="card-body">
                        <div class="search-form">
                            <div class="search-input-group">
                                <input type="text"
                                       placeholder="اكتب اسم المكان أو المحافظة..."
                                       class="search-input"
                                       id="placeSearch">
                                <button class="search-btn">
                                    <i class="fas fa-search"></i>
                                    بحث
                                </button>
                            </div>

                            <div class="search-filters mt-4">
                                <div class="grid grid-4">
                                    <div class="filter-group">
                                        <label for="filter-province">
                                            <i class="fas fa-map-marker-alt"></i>
                                            المحافظة
                                        </label>
                                        <select id="filter-province" class="form-select">
                                            <option value="">جميع المحافظات</option>
                                            @foreach($governorates as $governorate)
                                                <option value="{{ $governorate->id }}">{{ $governorate->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="filter-group">
                                        <label for="filter-category">
                                            <i class="fas fa-tags"></i>
                                            الفئة
                                        </label>
                                        <select id="filter-category" class="form-select">
                                            <option value="">جميع الفئات</option>
                                            <option value="historical">تاريخي</option>
                                            <option value="natural">طبيعي</option>
                                            <option value="religious">ديني</option>
                                            <option value="recreational">ترفيهي</option>
                                            <option value="cultural">ثقافي</option>
                                        </select>
                                    </div>

                                    <div class="filter-group">
                                        <label for="filter-season">
                                            <i class="fas fa-sun"></i>
                                            الموسم المناسب
                                        </label>
                                        <select id="filter-season" class="form-select">
                                            <option value="">جميع المواسم</option>
                                            <option value="spring">الربيع</option>
                                            <option value="summer">الصيف</option>
                                            <option value="autumn">الخريف</option>
                                            <option value="winter">الشتاء</option>
                                        </select>
                                    </div>

                                    <div class="filter-group">
                                        <label for="filter-price">
                                            <i class="fas fa-money-bill-wave"></i>
                                            التكلفة
                                        </label>
                                        <select id="filter-price" class="form-select">
                                            <option value="">جميع التكاليف</option>
                                            <option value="free">مجاني</option>
                                            <option value="low">منخفض</option>
                                            <option value="medium">متوسط</option>
                                            <option value="high">مرتفع</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== ALL PROVINCES ========== -->
    <section id="provinces" class="section" style="background: var(--gray-50); ">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">المحافظات السورية</h2>
                <p class="section-subtitle">اكتشف جمال وتنوع المحافظات السورية الـ 14</p>
            </div>

            <!-- Provinces Slider (مثل الأماكن السياحية) -->
            <div class="swiper provinces-slider ">
                <div class="swiper-wrapper">
                    @forelse($governorates as $governorate)
                        <div class="swiper-slide">
                            <div class="province-card fade-in">
                                <div class="card">
                                    <div class="card-header" style="position: relative; padding: 0; height: 230px; overflow: hidden;">
                                        @if($governorate->featured_image)
                                            <img src="{{ Storage::url($governorate->featured_image) }}"
                                                 alt="{{ $governorate->name }}"
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div class="province-image"
                                                 style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary)20, var(--primary)40);
                                                        display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 4rem;">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                        @endif
                                        <div class="province-badges"
                                             style="position: absolute; top: 1rem; right: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                                            <span class="badge" style="background: var(--primary); color: #fff;">
                                                {{ $governorate->tourist_spots_count }} مكان سياحي
                                            </span>
                                            <span class="badge" style="background: var(--success); color: #fff;">
                                                {{ $governorate->trips_count }} رحلة
                                            </span>
                                        </div>
                                        <div class="province-overlay"
                                             style="position: absolute; bottom: 0; right: 0; left: 0; background: linear-gradient(transparent, rgba(0,0,0,0.7));
                                                    padding: 1.25rem; color: white;">
                                            <h3 style="margin: 0; font-size: 1.4rem;">{{ $governorate->name }}</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        {{-- <p style="margin-bottom: 5.25rem; color: var(--gray-600); line-height: 1.6; ">
                                            {{ $governorate->description ?? 'اكتشف أجمل الأماكن السياحية في ' . $governorate->name }}
                                        </p> --}}

                                        <div class="province-stats"
                                             style="display: flex; justify-content: space-between; font-size: 0.875rem; color: var(--gray-500);">
                                            <span><i class="fas fa-map-marked-alt"></i> {{ $governorate->tourist_spots_count }} مكان</span>
                                            <span><i class="fas fa-route"></i> {{ $governorate->trips_count }} رحلة</span>
                                        </div>
                                    </div>
                                    <div class="card-footer " style="margin-top: auto;">
                                        <a href="{{ route('provinces.show', $governorate) }}" class="btn btn-primary btn-sm" style="width: 100%;">
                                            <i class="fas fa-eye"></i>
                                            استكشف المحافظة
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body text-center" style="padding: 3rem;">
                                    <i class="fas fa-info-circle" style="font-size: 3rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                                    <p style="color: var(--gray-500);">لا توجد محافظات متاحة حالياً</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- ========== FEATURED PLACES SLIDER ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">أماكن سياحية مميزة</h2>
                <p class="section-subtitle">اكتشف أجمل المعالم السياحية في سوريا</p>
            </div>

            <!-- Places Slider -->
            <div class="swiper featured-places-slider">
                <div class="swiper-wrapper">
                    @forelse($touristSpots->take(10) as $spot)
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-header" style="position: relative; padding: 0; height: 250px;">
                                    @if($spot->images && count($spot->images) > 0)
                                        <img src="{{ Storage::url($spot->images[0]) }}"
                                             alt="{{ $spot->name }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="place-image"
                                             style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary)20, var(--primary)40);
                                                    display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 5rem;">
                                            <i class="fas fa-landmark"></i>
                                        </div>
                                    @endif
                                    <div class="place-badges" style="position: absolute; top: 1rem; right: 1rem; display: flex; gap: 0.5rem; flex-direction: column;">
                                        @if($spot->categories && $spot->categories->count() > 0)
                                            <span class="badge" style="background: var(--primary); color: white;">
                                                {{ $spot->categories->first()->name ?? 'سياحي' }}
                                            </span>
                                        @endif
                                        <span class="badge" style="background: var(--gray-800); color: var(--primary);">
                                            {{ $spot->governorate->name ?? 'غير محدد' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 style="margin-bottom: 0.5rem;">{{ $spot->name }}</h3>
                                    <p style="color: var(--gray-600); margin-bottom: 1rem; font-size: 0.875rem;">
                                        <i class="fas fa-map-marker-alt"></i> {{ $spot->location }}
                                    </p>
                                    <div class="place-info" style="display: flex; justify-content: space-between; color: var(--gray-500); font-size: 0.875rem;">
                                        <span><i class="fas fa-map-marker-alt"></i> {{ $spot->governorate->name ?? 'غير محدد' }}</span>
                                        @if($spot->entrance_fee)
                                            <span><i class="fas fa-money-bill-wave"></i> {{ number_format($spot->entrance_fee, 0) }} ل.س</span>
                                        @else
                                            <span><i class="fas fa-money-bill-wave"></i> مجاني</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('provinces.show', $spot->governorate) }}"
                                           class="btn btn-primary btn-sm" style="flex: 1;">
                                            <i class="fas fa-info-circle"></i>
                                            عرض تفاصيل المكان
                                        </a>
                                        <a href="#" class="btn btn-outline btn-sm">
                                            <i class="fas fa-heart"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body text-center" style="padding: 3rem;">
                                    <i class="fas fa-info-circle" style="font-size: 3rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                                    <p style="color: var(--gray-500);">لا توجد أماكن سياحية متاحة حالياً</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>


    <!-- ========== CATEGORIES SECTION ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">تصنيفات الأماكن</h2>
                <p class="section-subtitle">اكتشف الأماكن حسب اهتماماتك</p>
            </div>

            <div class="categories-grid">
                <div class="grid grid-4">
                    @forelse($categories as $category)
                        @php
                            // اختيار أيقونة ولون افتراضيين بناءً على اسم الفئة (اختياري للتجميل فقط)
                            $iconMap = [
                                'تاريخية' => 'fas fa-landmark',
                                'دينية' => 'fas fa-mosque',
                                'طبيعية' => 'fas fa-tree',
                                'ساحلية' => 'fas fa-umbrella-beach',
                                'جبلية' => 'fas fa-mountain',
                                'أثرية' => 'fas fa-archway',
                                'أسواق' => 'fas fa-store',
                                'مطاعم' => 'fas fa-utensils',
                            ];
                            $colorMap = [
                                'تاريخية' => '#4361ee',
                                'دينية' => '#9d4edd',
                                'طبيعية' => '#2d6a4f',
                                'ساحلية' => '#4cc9f0',
                                'جبلية' => '#7209b7',
                                'أثرية' => '#f48c06',
                                'أسواق' => '#e63946',
                                'مطاعم' => '#9b5de5',
                            ];

                            $icon = $iconMap[$category->name] ?? 'fas fa-landmark';
                            $color = $colorMap[$category->name] ?? '#4361ee';
                        @endphp

                        <div class="category-card fade-in">
                            <div class="card" style="cursor: pointer; transition: var(--transition-base); height: 100%;">
                                <div class="card-body text-center">
                                    <div class="category-icon"
                                         style="width: 70px; height: 70px; background: {{ $color }}20;
                                                border-radius: var(--radius-full); display: flex; align-items: center;
                                                justify-content: center; margin: 0 auto 1rem; color: {{ $color }};
                                                font-size: 1.75rem;">
                                        <i class="{{ $icon }}"></i>
                                    </div>
                                    <h4 style="margin-bottom: 0.5rem; color: var(--gray-900);">{{ $category->name }}</h4>
                                    <div class="category-count"
                                         style="background: {{ $color }}; color: white; padding: 0.25rem 0.75rem;
                                                border-radius: var(--radius-full); font-size: 0.875rem; display: inline-block;">
                                        {{ $touristSpots->filter(function ($spot) use ($category) {
                                            return in_array($category->id, $spot->category_ids ?? []);
                                        })->count() }} مكان
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center" style="color: var(--gray-500);">لا توجد تصنيفات متاحة حالياً.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <!-- ========== STATISTICS ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">إحصائيات الأماكن السياحية</h2>
                <p class="section-subtitle">نظرة عامة على ثروات سوريا السياحية</p>
            </div>

            <div class="stats-cards">
                <div class="grid grid-4">
                    <div class="stat-card fade-in">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="stat-icon"
                                     style="width: 60px; height: 60px; background: var(--primary); color: white;
                                            border-radius: var(--radius-full); display: flex; align-items: center;
                                            justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <h3 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;">812</h3>
                                <p style="color: var(--gray-600);">مكان سياحي</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card fade-in">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="stat-icon"
                                     style="width: 60px; height: 60px; background: var(--success); color: white;
                                            border-radius: var(--radius-full); display: flex; align-items: center;
                                            justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                                    <i class="fas fa-flag"></i>
                                </div>
                                <h3 style="font-size: 2.5rem; color: var(--success); margin-bottom: 0.5rem;">14</h3>
                                <p style="color: var(--gray-600);">محافظة</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card fade-in">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="stat-icon"
                                     style="width: 60px; height: 60px; background: var(--warning); color: white;
                                            border-radius: var(--radius-full); display: flex; align-items: center;
                                            justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h3 style="font-size: 2.5rem; color: var(--warning); margin-bottom: 0.5rem;">4.7</h3>
                                <p style="color: var(--gray-600);">متوسط التقييم</p>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card fade-in">
                        <div class="card text-center">
                            <div class="card-body">
                                <div class="stat-icon"
                                     style="width: 60px; height: 60px; background: var(--danger); color: white;
                                            border-radius: var(--radius-full); display: flex; align-items: center;
                                            justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <h3 style="font-size: 2.5rem; color: var(--danger); margin-bottom: 0.5rem;">50K+</h3>
                                <p style="color: var(--gray-600);">مشاهدة شهرياً</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('styles')
<style>
    .search-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .search-input-group {
        display: flex;
        gap: 0.5rem;
    }

    .search-filters {
        margin-top: 1.5rem;
    }

    .filter-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.95rem;
    }

    .filter-group label i {
        color: var(--primary);
        margin-left: 0.35rem;
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-lg);
        border: 1px solid var(--gray-300);
        background-color: #40916c;
        color: var(--gray-800);
        font-size: 0.95rem;
        line-height: 1.4;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: linear-gradient(45deg, transparent 50%, var(--gray-500) 50%),
        linear-gradient(135deg, var(--gray-500) 50%, transparent 50%);
        background-position: calc(100% - 18px) calc(50% - 3px), calc(100% - 12px) calc(50% - 3px);
        background-size: 6px 6px, 6px 6px;
        background-repeat: no-repeat;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast), background-color var(--transition-fast), transform var(--transition-fast);
    }

    .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.12);
        background-color: #40916c;
        transform: translateY(-1px);
    }

    .form-select:hover {
        border-color: var(--gray-400);
    }

    .form-select option {
        background-color: var(--gradient-secondary);
        color: var(--gray-600);
        padding: 0.5rem 1rem;
    }

    .form-select option:disabled {
        color: var(--gray-400);
    }

    .search-input {
        flex: 1;
        padding: 1rem 1.5rem;
        border: 2px solid var(--gray-300);
        border-radius: var(--radius-lg);
        font-size: 1.125rem;
        transition: var(--transition-fast);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .search-btn {
        padding: 0 2rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition-fast);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .search-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .province-card .card {
        transition: var(--transition-base);
        height: 100%;
    }

    .province-card .card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-xl);
    }

    .province-card .card:hover .province-image {
        transform: scale(1.1);
        transition: transform 0.6s ease;
    }

    .place-image {
        transition: var(--transition-base);
    }

    .swiper-slide:hover .place-image {
        transform: scale(1.05);
    }

    .category-card .card:hover {
        transform: translateY(-5px);
        background: var(--primary);
    }

    .category-card .card:hover .category-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .category-card .card:hover h4 {
        color: white !important;
    }

    .category-card .card:hover .category-count {
        background: white;
        color: var(--primary);
    }

    .tag {
        transition: var(--transition-fast);
    }

    .tag:hover {
        background: var(--primary) !important;
        color: white !important;
        transform: scale(1.05);
    }

    .stat-card .card {
        transition: var(--transition-base);
    }

    .stat-card .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }
</style>
@endpush

@push('scripts')
<script>
    // Search and Filter Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('placeSearch');
        const filterProvince = document.getElementById('filter-province');
        const filterCategory = document.getElementById('filter-category');
        const filterSeason = document.getElementById('filter-season');
        const filterPrice = document.getElementById('filter-price');
        const searchBtn = document.querySelector('.search-btn');

        // Placeholder data for search
        const allPlaces = [
            { name: 'الجامع الأموي', province: 'دمشق', category: 'تاريخي', season: 'all', price: 'free' },
            { name: 'قلعة حلب', province: 'حلب', category: 'تاريخي', season: 'all', price: 'low' },
            { name: 'نواعير حماة', province: 'حماة', category: 'تراثي', season: 'all', price: 'free' },
            { name: 'شاطئ اللاذقية', province: 'اللاذقية', category: 'ساحلي', season: 'summer', price: 'free' },
            { name: 'معبد باخوس', province: 'السويداء', category: 'أثري', season: 'all', price: 'low' }
        ];

        // Search function
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            const provinceFilter = filterProvince.value;
            const categoryFilter = filterCategory.value;
            const seasonFilter = filterSeason.value;
            const priceFilter = filterPrice.value;

            // Here you would normally make an AJAX request to the server
            // For now, we'll just show an alert with the search criteria
            let message = 'بحث عن: ';
            if (searchTerm) message += `"${searchTerm}" `;
            if (provinceFilter) message += `في ${filterProvince.options[filterProvince.selectedIndex].text} `;
            if (categoryFilter) message += `نوع ${filterCategory.options[filterCategory.selectedIndex].text} `;
            if (seasonFilter) message += `موسم ${filterSeason.options[filterSeason.selectedIndex].text} `;
            if (priceFilter) message += `تكلفة ${filterPrice.options[filterPrice.selectedIndex].text}`;

            alert(message);

            // In a real implementation, you would:
            // 1. Make AJAX request to server with filters
            // 2. Update the DOM with search results
            // 3. Handle pagination
        }

        // Event listeners
        searchBtn.addEventListener('click', performSearch);

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });

        // Add click event to category cards
        document.querySelectorAll('.category-card .card').forEach(card => {
            card.addEventListener('click', function() {
                const categoryName = this.querySelector('h4').textContent;
                searchInput.value = '';
                filterCategory.value = this.querySelector('.category-count').textContent.includes('تاريخية') ? 'historical' :
                                      this.querySelector('.category-count').textContent.includes('دينية') ? 'religious' : '';

                alert(`بحث عن أماكن في فئة: ${categoryName}`);
            });
        });

        // Note: Province cards now use direct links, no need for click handlers

        // Initialize Swiper for featured places
        const featuredPlacesSwiper = new Swiper('.featured-places-slider', {
            direction: 'horizontal',
            loop: true,
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
    });
</script>
@endpush

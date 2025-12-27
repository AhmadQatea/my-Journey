@extends('website.pages.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', $governorate->name . ' - المحافظات والأماكن السياحية - MyJourney')

@section('content')
    <!-- ========== PROVINCE HERO ========== -->
    <section class="hero-section" style="background: linear-gradient(135deg, #4361ee 0%, #4895ef 100%);">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">{{ $governorate->name }}</h1>
                <p class="hero-subtitle">
                    اكتشف جمال {{ $governorate->name }} وأهم الأماكن السياحية فيها
                </p>
                <div class="hero-actions">
                    <a href="#places" class="btn btn-primary btn-lg">
                        <i class="fas fa-map-marked-alt"></i>
                        الأماكن السياحية
                    </a>
                    <a href="#map" class="btn btn-outline btn-lg">
                        <i class="fas fa-map"></i>
                        الخريطة التفاعلية
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== PROVINCE OVERVIEW ========== -->
    <section class="section">
        <div class="container">
            <div class="grid grid-2 gap-4 align-items-center">
                <div class="fade-in">
                    <div class="province-info-card">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="section-title" style="margin-bottom: 1.5rem;">
                                    <i class="fas fa-info-circle"></i>
                                    معلومات عن {{ $governorate->name }}
                                </h2>

                                <div class="province-details">
                                    <div class="detail-item" style="display: flex; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--gray-200);">
                                        <div style="min-width: 120px;">
                                            <strong style="color: var(--gray-700);">الموقع:</strong>
                                        </div>
                                        <div>
                                            <span>{{ $governorate->location ?? 'غير محدد' }}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item" style="display: flex; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--gray-200);">
                                        <div style="min-width: 120px;">
                                            <strong style="color: var(--gray-700);">عدد الأماكن السياحية:</strong>
                                        </div>
                                        <div>
                                            <span>{{ $governorate->touristSpots->count() }} مكان</span>
                                        </div>
                                    </div>

                                    <div class="detail-item" style="display: flex; margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--gray-200);">
                                        <div style="min-width: 120px;">
                                            <strong style="color: var(--gray-700);">عدد الرحلات:</strong>
                                        </div>
                                        <div>
                                            <span>{{ $governorate->trips->count() }} رحلة</span>
                                        </div>
                                    </div>

                                    @if($governorate->touristSpots->count() > 0)
                                        <div class="detail-item" style="display: flex; margin-bottom: 1.5rem;">
                                            <div style="min-width: 120px;">
                                                <strong style="color: var(--gray-700);">أشهر الأماكن:</strong>
                                            </div>
                                            <div>
                                                <div class="landmarks-tags" style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                                    @foreach($governorate->touristSpots->take(5) as $spot)
                                                        <span class="tag" style="background: var(--gray-100); color: var(--gray-700); padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.875rem;">
                                                            {{ $spot->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="province-description">
                                    <h3 style="margin-bottom: 1rem; color: var(--gray-800);">
                                        <i class="fas fa-book-open"></i>
                                        وصف المحافظة
                                    </h3>
                                    <p style="color: var(--gray-600); line-height: 1.8; text-align: justify;">
                                        {{ $governorate->description ?? 'تعتبر ' . $governorate->name . ' من المحافظات السورية المميزة التي تجمع بين التاريخ العريق والطبيعة الخلابة. تزخر المحافظة بالعديد من الأماكن السياحية والتاريخية التي تجذب السياح من مختلف أنحاء العالم.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fade-in">
                    <div class="province-stats-card">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="section-title" style="margin-bottom: 2rem; text-align: center;">
                                    <i class="fas fa-chart-bar"></i>
                                    إحصائيات {{ $governorate->name }}
                                </h2>

                                <div class="stats-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                                    <div class="stat-item text-center">
                                        <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(67, 97, 238, 0.1); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--primary); font-size: 1.5rem;">
                                            <i class="fas fa-landmark"></i>
                                        </div>
                                        <div class="stat-number" style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem;">
                                            {{ $governorate->places_count ?? $governorate->touristSpots->count() }}
                                        </div>
                                        <div class="stat-label" style="color: var(--gray-600); font-size: 0.875rem;">
                                            مكان سياحي
                                        </div>
                                    </div>

                                    <div class="stat-item text-center">
                                        <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(157, 78, 221, 0.1); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--primary); font-size: 1.5rem;">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div class="stat-number" style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem;">
                                            4.5
                                        </div>
                                        <div class="stat-label" style="color: var(--gray-600); font-size: 0.875rem;">
                                            متوسط التقييم
                                        </div>
                                    </div>

                                    <div class="stat-item text-center">
                                        <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(76, 201, 240, 0.1); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--primary); font-size: 1.5rem;">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                        <div class="stat-number" style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem;">
                                            {{ number_format(rand(1000, 5000)) }}
                                        </div>
                                        <div class="stat-label" style="color: var(--gray-600); font-size: 0.875rem;">
                                            مشاهدة شهرياً
                                        </div>
                                    </div>

                                    <div class="stat-item text-center">
                                        <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(74, 222, 128, 0.1); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--primary); font-size: 1.5rem;">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="stat-number" style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem;">
                                            {{ number_format(rand(5000, 10000)) }}
                                        </div>
                                        <div class="stat-label" style="color: var(--gray-600); font-size: 0.875rem;">
                                            زائر سنوياً
                                        </div>
                                    </div>
                                </div>

                                @if($governorate->bestVisitingTimes->count() > 0)
                                <div class="best-time-visit" style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
                                    <h3 style="margin-bottom: 1rem; color: var(--gray-800); text-align: center;">
                                        <i class="fas fa-calendar-alt"></i>
                                        أفضل وقت للزيارة
                                    </h3>
                                    <div style="display: flex; align-items: center; gap: 1rem; justify-content: center;">
                                        <div class="season-badges" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                            @php
                                                $allSeasons = \App\Models\BestVisitingTime::all()->keyBy('name');
                                                $selectedSeasonNames = $governorate->bestVisitingTimes->pluck('name')->toArray();
                                            @endphp

                                            @foreach(['spring', 'summer', 'autumn', 'winter'] as $seasonKey)
                                                @php
                                                    $season = $allSeasons->get($seasonKey);
                                                    $isSelected = in_array($seasonKey, $selectedSeasonNames);
                                                @endphp

                                                @if($season)
                                                <div class="season-badge"
                                                     style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; padding: 1rem;
                                                            border-radius: var(--radius-lg); background: {{ $isSelected ? ($season->color ?? '#4361ee') . '20' : 'var(--gray-100)' }};
                                                            border: 2px solid {{ $isSelected ? ($season->color ?? '#4361ee') : 'transparent' }};
                                                            transition: var(--transition-base);">
                                                    <i class="{{ $season->icon ?? 'fas fa-calendar' }}"
                                                       style="color: {{ $isSelected ? ($season->color ?? '#4361ee') : 'var(--gray-500)' }}; font-size: 1.5rem;">
                                                    </i>
                                                    <span style="font-size: 0.875rem; color: var(--gray-700);">
                                                        {{ $season->name_ar }}
                                                    </span>
                                                    @if($isSelected)
                                                        <span class="badge" style="background: {{ $season->color ?? '#4361ee' }}; color: white; font-size: 0.75rem; padding: 0.25rem 0.5rem; border-radius: var(--radius-full);">
                                                            ممتاز
                                                        </span>
                                                    @endif
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== INTERACTIVE MAP ========== -->
    <section id="map" class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">الخريطة التفاعلية</h2>
                <p class="section-subtitle">موقع {{ $governorate->name }} على خريطة سوريا</p>
            </div>

            <div class="map-container fade-in">
                <div class="card">
                    <div class="card-body">
                        <div class="map-wrapper" style="position: relative; height: 500px; border-radius: var(--radius-lg); overflow: hidden; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
                            <!-- Syrian Map with Province Highlight -->
                            <div id="syria-map" style="width: 100%; height: 100%; position: relative;">
                                <!-- Syrian Map SVG or Interactive Map will be rendered here -->
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; position: relative;">
                                    <!-- Syrian Map Outline -->
                                    <svg width="600" height="400" viewBox="0 0 600 400" style="max-width: 100%;">
                                        <!-- Syrian Borders -->
                                        <path d="M100,150 L200,120 L300,140 L400,130 L500,160 L550,200 L500,250 L400,280 L300,300 L200,280 L100,250 Z"
                                              fill="var(--gray-200)" stroke="var(--gray-400)" stroke-width="2"/>

                                        <!-- Province Highlight based on name -->
                                        @php
                                            $provinceCoords = [
                                                'دمشق' => ['cx' => 300, 'cy' => 200, 'r' => 25, 'color' => '#4361ee'],
                                                'حلب' => ['cx' => 200, 'cy' => 120, 'r' => 30, 'color' => '#f72585'],
                                                'حمص' => ['cx' => 250, 'cy' => 180, 'r' => 28, 'color' => '#4cc9f0'],
                                                'اللاذقية' => ['cx' => 150, 'cy' => 170, 'r' => 25, 'color' => '#9d4edd'],
                                                'طرطوس' => ['cx' => 130, 'cy' => 190, 'r' => 22, 'color' => '#4ade80'],
                                                'حماة' => ['cx' => 270, 'cy' => 170, 'r' => 24, 'color' => '#f48c06'],
                                                'ادلب' => ['cx' => 180, 'cy' => 140, 'r' => 20, 'color' => '#7209b7'],
                                                'الحسكة' => ['cx' => 450, 'cy' => 150, 'r' => 32, 'color' => '#2d6a4f'],
                                                'دير الزور' => ['cx' => 400, 'cy' => 200, 'r' => 30, 'color' => '#e63946'],
                                                'الرقة' => ['cx' => 350, 'cy' => 180, 'r' => 26, 'color' => '#006d77'],
                                                'السويداء' => ['cx' => 280, 'cy' => 220, 'r' => 23, 'color' => '#9b5de5'],
                                                'درعا' => ['cx' => 260, 'cy' => 240, 'r' => 22, 'color' => '#00bbf9'],
                                                'القنيطرة' => ['cx' => 240, 'cy' => 210, 'r' => 18, 'color' => '#ff9e00'],
                                                'ريف دمشق' => ['cx' => 290, 'cy' => 190, 'r' => 27, 'color' => '#6a994e']
                                            ];

                                            $currentProvince = $provinceCoords[$governorate->name] ?? $provinceCoords['دمشق'];
                                        @endphp

                                        <!-- Highlighted Province -->
                                        <circle cx="{{ $currentProvince['cx'] }}"
                                                cy="{{ $currentProvince['cy'] }}"
                                                r="{{ $currentProvince['r'] }}"
                                                fill="{{ $currentProvince['color'] }}"
                                                stroke="white"
                                                stroke-width="3"
                                                filter="url(#glow)"
                                                class="pulse-animation"/>

                                        <!-- Province Name Label -->
                                        <text x="{{ $currentProvince['cx'] }}"
                                              y="{{ $currentProvince['cy'] }}"
                                              text-anchor="middle"
                                              fill="white"
                                              font-weight="bold"
                                              font-size="14"
                                              dy="5">
                                            {{ $governorate->name }}
                                        </text>

                                        <!-- Major Cities Markers -->
                                        @if($governorate->touristSpots->count() > 0)
                                            @foreach($governorate->touristSpots->take(3) as $index => $spot)
                                                <circle cx="{{ $currentProvince['cx'] + rand(-20, 20) }}"
                                                        cy="{{ $currentProvince['cy'] + rand(-20, 20) }}"
                                                        r="5"
                                                        fill="white"
                                                        stroke="{{ $currentProvince['color'] }}"
                                                        stroke-width="2"/>
                                            @endforeach
                                        @endif

                                        <!-- Glow Effect -->
                                        <defs>
                                            <filter id="glow">
                                                <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                                                <feMerge>
                                                    <feMergeNode in="coloredBlur"/>
                                                    <feMergeNode in="SourceGraphic"/>
                                                </feMerge>
                                            </filter>
                                        </defs>
                                    </svg>

                                    <!-- Map Controls -->
                                    <div class="map-controls" style="position: absolute; bottom: 1rem; right: 1rem; display: flex; gap: 0.5rem;">
                                        <button class="btn btn-sm btn-outline" onclick="zoomIn()" style="background: white;">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline" onclick="zoomOut()" style="background: white;">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline" onclick="resetMap()" style="background: white;">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </div>

                                    <!-- Map Legend -->
                                    <div class="map-legend" style="position: absolute; top: 1rem; left: 1rem; background: rgba(255, 255, 255, 0.9); padding: 1rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); max-width: 200px;">
                                        <h4 style="margin-bottom: 0.75rem; color: var(--gray-800);">
                                            <i class="fas fa-map-signs"></i>
                                            دليل الخريطة
                                        </h4>
                                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <div style="width: 12px; height: 12px; background: {{ $currentProvince['color'] }}; border-radius: var(--radius-full);"></div>
                                                <span style="font-size: 0.875rem; color: var(--gray-700);">
                                                    {{ $governorate->name }} (المحافظة الحالية)
                                                </span>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <div style="width: 8px; height: 8px; background: white; border: 2px solid {{ $currentProvince['color'] }}; border-radius: var(--radius-full);"></div>
                                                <span style="font-size: 0.875rem; color: var(--gray-700);">
                                                    المدن الرئيسية
                                                </span>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <div style="width: 100%; height: 2px; background: var(--gray-400);"></div>
                                                <span style="font-size: 0.875rem; color: var(--gray-700);">
                                                    حدود المحافظات
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nearby Provinces Info -->
                            <div class="nearby-provinces" style="position: absolute; bottom: 1rem; left: 1rem; background: rgba(255, 255, 255, 0.9); padding: 1rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); max-width: 250px;">
                                <h4 style="margin-bottom: 0.75rem; color: var(--gray-800);">
                                    <i class="fas fa-map-marker-alt"></i>
                                    المحافظات المجاورة
                                </h4>
                                <div class="nearby-list" style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                    @php
                                        $allGovernorates = \App\Models\Governorate::where('id', '!=', $governorate->id)->pluck('name')->take(4);
                                    @endphp

                                    @foreach($allGovernorates as $nearby)
                                        <a href="{{ route('provinces.show', \App\Models\Governorate::where('name', $nearby)->first()) }}" class="nearby-tag"
                                           style="display: inline-block; background: var(--gray-100); color: var(--gray-700); padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem; text-decoration: none; transition: var(--transition-fast);">
                                            {{ $nearby }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Map Info -->
                        <div class="map-info" style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200);">
                            <div class="grid grid-3">
                                <div class="info-item text-center">
                                    <div class="info-icon" style="width: 40px; height: 40px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; color: var(--primary);">
                                        <i class="fas fa-road"></i>
                                    </div>
                                    <div class="info-text" style="font-size: 0.875rem; color: var(--gray-600);">
                                        <strong>{{ rand(50, 500) }} كم</strong>
                                        <div>من دمشق</div>
                                    </div>
                                </div>

                                <div class="info-item text-center">
                                    <div class="info-icon" style="width: 40px; height: 40px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; color: var(--primary);">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <div class="info-text" style="font-size: 0.875rem; color: var(--gray-600);">
                                        <strong>{{ rand(1, 5) }} ساعات</strong>
                                        <div>بالسيارة</div>
                                    </div>
                                </div>

                                <div class="info-item text-center">
                                    <div class="info-icon" style="width: 40px; height: 40px; background: var(--gray-100); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 0.5rem; color: var(--primary);">
                                        <i class="fas fa-bus"></i>
                                    </div>
                                    <div class="info-text" style="font-size: 0.875rem; color: var(--gray-600);">
                                        <strong>نعم</strong>
                                        <div>مواصلات عامة</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== TOURIST PLACES ========== -->
    <section id="places" class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">الأماكن السياحية في {{ $governorate->name }}</h2>
                <p class="section-subtitle">اكتشف أجمل المعالم والوجهات السياحية</p>
            </div>

            <!-- Places Filter -->
            <div class="places-filter mb-4 fade-in">
                <div class="card">
                    <div class="card-body">
                        <div class="filter-options" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                            <div style="font-weight: 600; color: var(--gray-700);">
                                <i class="fas fa-filter"></i>
                                تصفية حسب:
                            </div>

                            <div class="filter-buttons" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button class="btn btn-sm btn-primary active" data-filter="all">
                                    الكل
                                </button>
                                <button class="btn btn-sm btn-outline" data-filter="historical">
                                    <i class="fas fa-landmark"></i>
                                    تاريخية
                                </button>
                                <button class="btn btn-sm btn-outline" data-filter="natural">
                                    <i class="fas fa-tree"></i>
                                    طبيعية
                                </button>
                                <button class="btn btn-sm btn-outline" data-filter="religious">
                                    <i class="fas fa-mosque"></i>
                                    دينية
                                </button>
                                <button class="btn btn-sm btn-outline" data-filter="recreational">
                                    <i class="fas fa-umbrella-beach"></i>
                                    ترفيهية
                                </button>
                            </div>

                            <div class="sort-options" style="margin-right: auto;">
                                <select class="form-select" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;">
                                    <option value="popular">الأكثر شهرة</option>
                                    <option value="rating">الأعلى تقييماً</option>
                                    <option value="newest">الأحدث</option>
                                    <option value="name">حسب الاسم</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Places Grid -->
            <div class="places-grid">
                <div class="grid grid-3">
                    @forelse($touristSpots as $spot)
                        @php
                            $categoryName = $spot->categories->first()->name ?? 'سياحي';
                            $categoryType = strtolower($categoryName);
                            $typeColors = [
                                'تاريخي' => '#4361ee',
                                'تاريخية' => '#4361ee',
                                'طبيعي' => '#2d6a4f',
                                'طبيعية' => '#2d6a4f',
                                'ديني' => '#9d4edd',
                                'دينية' => '#9d4edd',
                                'ترفيهي' => '#f48c06',
                                'ترفيهية' => '#f48c06',
                            ];
                            $typeColor = $typeColors[$categoryName] ?? '#4361ee';
                            $typeIcons = [
                                'تاريخي' => 'fa-landmark',
                                'تاريخية' => 'fa-landmark',
                                'طبيعي' => 'fa-tree',
                                'طبيعية' => 'fa-tree',
                                'ديني' => 'fa-mosque',
                                'دينية' => 'fa-mosque',
                                'ترفيهي' => 'fa-umbrella-beach',
                                'ترفيهية' => 'fa-umbrella-beach',
                            ];
                            $typeIcon = $typeIcons[$categoryName] ?? 'fa-landmark';
                        @endphp
                        <div class="place-card fade-in" data-type="{{ $categoryType }}">
                            <div class="card">
                                <div class="card-header" style="position: relative; padding: 0; height: 200px;">
                                    @if($spot->images && count($spot->images) > 0)
                                        <img src="{{ Storage::url($spot->images[0]) }}"
                                             alt="{{ $spot->name }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="place-image"
                                             style="width: 100%; height: 100%; background: linear-gradient(135deg, {{ $typeColor }}20, {{ $typeColor }}40);
                                                    display: flex; align-items: center; justify-content: center;
                                                    color: {{ $typeColor }}; font-size: 3rem;">
                                            <i class="fas {{ $typeIcon }}"></i>
                                        </div>
                                    @endif
                                    <div class="place-badges" style="position: absolute; top: 1rem; right: 1rem; display: flex; gap: 0.5rem; flex-direction: column;">
                                        <span class="badge"
                                              style="background: {{ $typeColor }};
                                                     color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem;">
                                            {{ $categoryName }}
                                        </span>
                                        <span class="badge"
                                              style="background: {{ $spot->entrance_fee && $spot->entrance_fee > 0 ? ($spot->entrance_fee < 1000 ? '#fbbf24' : '#ef4444') : '#4ade80' }};
                                                     color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem;">
                                            @if($spot->entrance_fee && $spot->entrance_fee > 0)
                                                {{ $spot->entrance_fee < 1000 ? 'منخفض' : 'متوسط' }}
                                            @else
                                                مجاني
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 style="margin-bottom: 0.75rem; font-size: 1.125rem;">{{ $spot->name }}</h3>
                                    <p style="color: var(--gray-600); margin-bottom: 0.5rem; font-size: 0.875rem;">
                                        <i class="fas fa-map-marker-alt"></i> {{ $spot->location }}
                                    </p>
                                    <p style="color: var(--gray-600); margin-bottom: 1.5rem; font-size: 0.875rem; line-height: 1.6;">
                                        {{ Str::limit($spot->description, 120) }}
                                    </p>

                                    <div class="place-details" style="display: flex; justify-content: space-between; font-size: 0.875rem; color: var(--gray-500);">
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); margin-bottom: 0.25rem;">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div>{{ $governorate->name }}</div>
                                        </div>
                                        @if($spot->entrance_fee)
                                            <div class="detail" style="text-align: center;">
                                                <div style="color: var(--primary); margin-bottom: 0.25rem;">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </div>
                                                <div>{{ number_format($spot->entrance_fee, 0) }} ل.س</div>
                                            </div>
                                        @else
                                            <div class="detail" style="text-align: center;">
                                                <div style="color: var(--primary); margin-bottom: 0.25rem;">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </div>
                                                <div>مجاني</div>
                                            </div>
                                        @endif
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); margin-bottom: 0.25rem;">
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <div>4.5</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div style="display: flex; gap: 0.5rem;">
                                        <a href="{{ route('trips') }}" class="btn btn-primary btn-sm" style="flex: 1;">
                                            <i class="fas fa-info-circle"></i>
                                            التفاصيل
                                        </a>
                                        <button class="btn btn-outline btn-sm btn-save" data-place="{{ $spot->name }}">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center" style="padding: 3rem;">
                            <i class="fas fa-info-circle" style="font-size: 3rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                            <p style="color: var(--gray-500);">لا توجد أماكن سياحية متاحة حالياً في {{ $governorate->name }}</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination mt-5">
                <li class="page-item disabled">
                    <a class="page-link" href="#" aria-label="السابق">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#" aria-label="التالي">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            </div>
        </div>
    </section>

    <!-- ========== RELATED TOURS ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">رحلات سياحية في {{ $governorate->name }}</h2>
                <p class="section-subtitle">احجز رحلتك لاكتشاف {{ $governorate->name }}</p>
            </div>

            <!-- Tours Slider -->
            <div class="swiper related-tours-slider">
                <div class="swiper-wrapper">
                    @forelse($trips as $trip)
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-header" style="position: relative; padding: 0; height: 200px;">
                                    @if($trip->images && count($trip->images) > 0)
                                        <img src="{{ Storage::url($trip->images[0]) }}"
                                             alt="{{ $trip->title }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="tour-image"
                                             style="width: 100%; height: 100%; background: linear-gradient(135deg, #9d4edd20, #9d4edd40);
                                                    display: flex; align-items: center; justify-content: center; color: #9d4edd; font-size: 3rem;">
                                            <i class="fas fa-map-marked-alt"></i>
                                        </div>
                                    @endif
                                    @if($trip->is_featured)
                                        <div class="tour-badge"
                                             style="position: absolute; top: 1rem; right: 1rem; background: var(--primary); color: white;
                                                    padding: 0.5rem 1rem; border-radius: var(--radius-full); font-weight: bold;">
                                            مميز
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h3 style="margin-bottom: 0.75rem;">{{ $trip->title }}</h3>
                                    <p style="color: var(--gray-600); margin-bottom: 1.5rem; font-size: 0.875rem;">
                                        {{ Str::limit($trip->description, 100) }}
                                    </p>

                                    <div class="tour-details" style="display: flex; justify-content: space-between; font-size: 0.875rem; color: var(--gray-500); margin-bottom: 1.5rem;">
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); margin-bottom: 0.25rem;">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div>{{ $trip->duration_hours }} ساعة</div>
                                        </div>
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); margin-bottom: 0.25rem;">
                                                <i class="fas fa-user-friends"></i>
                                            </div>
                                            <div>{{ $trip->max_persons }} أشخاص</div>
                                        </div>
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); margin-bottom: 0.25rem;">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </div>
                                            <div>{{ $governorate->name }}</div>
                                        </div>
                                    </div>

                                    <div class="tour-price" style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-size: 1.25rem; font-weight: bold; color: var(--primary);">
                                                {{ number_format($trip->price, 0) }} ل.س
                                            </div>
                                            <div style="font-size: 0.75rem; color: var(--gray-500);">
                                                للفرد
                                            </div>
                                        </div>
                                        <a href="{{ route('trips') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-calendar-check"></i>
                                            احجز الآن
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
                                    <p style="color: var(--gray-500);">لا توجد رحلات متاحة حالياً في {{ $governorate->name }}</p>
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

    <!-- ========== CTA SECTION ========== -->
    <section class="section" style="background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white;">
        <div class="container">
            <div class="grid grid-2 align-items-center gap-4">
                <div class="fade-in">
                    <h2 style="font-size: 2rem; margin-bottom: 1rem;">هل زرت {{ $governorate->name }} من قبل؟</h2>
                    <p style="font-size: 1.125rem; opacity: 0.9; margin-bottom: 1.5rem; line-height: 1.6;">
                        شارك تجربتك مع المسافرين الآخرين وساعدهم في التخطيط لرحلتهم
                    </p>
                    <div style="display: flex; gap: 1rem;">
                        <a href="#" class="btn btn-outline" style="background: white; color: var(--primary); border-color: white;">
                            <i class="fas fa-star"></i>
                            اكتب تقييماً
                        </a>
                        <a href="#" class="btn btn-outline" style="background: transparent; color: white; border-color: white;">
                            <i class="fas fa-camera"></i>
                            أضف صوراً
                        </a>
                    </div>
                </div>
                <div class="fade-in">
                    <div class="cta-stats" style="background: rgba(255, 255, 255, 0.1); border-radius: var(--radius-xl); padding: 2rem; text-align: center;">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div style="font-size: 2.5rem; font-weight: bold; margin-bottom: 0.5rem;">
                            {{ rand(1000, 5000) }}
                        </div>
                        <p style="opacity: 0.9;">مسافر زاروا {{ $governorate->name }} هذا العام</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .season-badge {
        transition: var(--transition-base);
        cursor: pointer;
    }

    .season-badge:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .nearby-tag:hover {
        background: var(--primary) !important;
        color: white !important;
        transform: translateY(-2px);
    }

    .place-card .card {
        transition: var(--transition-base);
        height: 100%;
    }

    .place-card .card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
    }

    .place-card .card:hover .place-image {
        transform: scale(1.05);
        transition: transform 0.6s ease;
    }

    .btn-save:hover {
        background: var(--danger) !important;
        color: white !important;
        border-color: var(--danger) !important;
    }

    .filter-buttons .btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .tour-image {
        transition: var(--transition-base);
    }

    .swiper-slide:hover .tour-image {
        transform: scale(1.05);
    }

    .map-controls .btn:hover {
        background: var(--primary) !important;
        color: white !important;
    }

    /* Custom scrollbar for map */
    .map-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .map-wrapper::-webkit-scrollbar-track {
        background: var(--gray-100);
        border-radius: var(--radius-full);
    }

    .map-wrapper::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: var(--radius-full);
    }
</style>
@endpush

@push('scripts')
<script>
    // Sample Province Data (In real app, this comes from controller)
    const provinceData = @json($governorate);

    // Map Controls
    function zoomIn() {
        const svg = document.querySelector('#syria-map svg');
        const currentScale = parseFloat(svg.style.transform?.replace('scale(', '')?.replace(')', '')) || 1;
        svg.style.transform = `scale(${currentScale * 1.2})`;
    }

    function zoomOut() {
        const svg = document.querySelector('#syria-map svg');
        const currentScale = parseFloat(svg.style.transform?.replace('scale(', '')?.replace(')', '')) || 1;
        svg.style.transform = `scale(${Math.max(0.5, currentScale * 0.8)})`;
    }

    function resetMap() {
        const svg = document.querySelector('#syria-map svg');
        svg.style.transform = 'scale(1)';
    }

    // Places Filtering
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-buttons .btn');
        const placeCards = document.querySelectorAll('.place-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active', 'btn-primary'));
                filterButtons.forEach(btn => btn.classList.add('btn-outline'));
                this.classList.remove('btn-outline');
                this.classList.add('active', 'btn-primary');

                const filterValue = this.getAttribute('data-filter');

                // Filter place cards
                placeCards.forEach(card => {
                    if (filterValue === 'all' || card.getAttribute('data-type') === filterValue) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 10);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });

        // Save place to favorites
        document.querySelectorAll('.btn-save').forEach(button => {
            button.addEventListener('click', function() {
                const placeName = this.getAttribute('data-place');
                const icon = this.querySelector('i');

                if (icon.classList.contains('fas')) {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.classList.remove('btn-danger');
                    this.classList.add('btn-outline');
                    showToast(`تم إزالة "${placeName}" من المفضلة`, 'info');
                } else {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.classList.remove('btn-outline');
                    this.classList.add('btn-danger');
                    showToast(`تم إضافة "${placeName}" إلى المفضلة`, 'success');
                }
            });
        });

        // Sort places
        const sortSelect = document.querySelector('.sort-options select');
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const placesContainer = document.querySelector('.places-grid .grid');
            const placeCardsArray = Array.from(placeCards);

            // In a real app, you would make an AJAX request here
            showToast(`تم التصنيف حسب: ${this.options[this.selectedIndex].text}`, 'info');
        });

        // Initialize Swiper for related tours
        const toursSwiper = new Swiper('.related-tours-slider', {
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

        // Nearby provinces click
        document.querySelectorAll('.nearby-tag').forEach(tag => {
            tag.addEventListener('click', function(e) {
                e.preventDefault();
                const provinceName = this.textContent;
                showToast(`جاري تحميل معلومات محافظة ${provinceName}...`, 'info');
                // In real app: window.location.href = `/provinces/${provinceName}`;
            });
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            toast.innerHTML = `
                <div class="toast-content">
                    <i class="fas fa-${type === 'success' ? 'check-circle' :
                                      type === 'error' ? 'exclamation-circle' :
                                      type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
                <button class="toast-close">&times;</button>
            `;

            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? '#10b981' :
                           type === 'error' ? '#ef4444' :
                           type === 'warning' ? '#f59e0b' : '#3b82f6'};
                color: white;
                padding: 1rem 1.5rem;
                border-radius: var(--radius-lg);
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                box-shadow: var(--shadow-xl);
                animation: slideInRight 0.3s ease;
                max-width: 400px;
            `;

            document.body.appendChild(toast);

            // Close button
            toast.querySelector('.toast-close').addEventListener('click', () => {
                toast.style.animation = 'slideOutRight 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            });

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.style.animation = 'slideOutRight 0.3s ease';
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        }

        // Add animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    });
</script>
@endpush

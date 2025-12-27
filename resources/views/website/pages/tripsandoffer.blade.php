@extends('website.pages.layouts.app')

@section('title', 'الرحلات والعروض - MyJourney')

@section('content')
    <!-- ========== TRIPS HERO ========== -->
    <section class="hero-section" style="background: var(--gradient-primary);">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">الرحلات والعروض</h1>
                <p class="hero-subtitle">
                    اكتشف مجموعة واسعة من الرحلات السياحية في جميع أنحاء سوريا
                </p>
            </div>
        </div>
    </section>

    <!-- ========== FILTERS ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="filters-card">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('trips') }}" id="filters-form">
                        <div class="filters-grid">
                            <!-- Province Filter -->
                            <div class="filter-group">
                                <label for="province"><i class="fas fa-map-marker-alt"></i> المحافظة</label>
                                <select name="province" id="province" class="form-select" style="width: 100%; padding: 0.75rem; border: 1px solid var(--gray-300); border-radius: var(--radius-md);">
                                    <option value="">جميع المحافظات</option>
                                    @foreach($governorates as $governorate)
                                        <option value="{{ $governorate->id }}" {{ request('province') == $governorate->id ? 'selected' : '' }}>
                                            {{ $governorate->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Trip Type Filter -->
                            <div class="filter-group">
                                <label for="type"><i class="fas fa-tags"></i> نوع الرحلة</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="">جميع الأنواع</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->name }}" {{ request('type') == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="filter-group">
                                <label for="price"><i class="fas fa-money-bill-wave"></i> نطاق السعر</label>
                                <select name="price" id="price" class="form-select">
                                    <option value="">جميع الأسعار</option>
                                    <option value="0-5000" {{ request('price') == '0-5000' ? 'selected' : '' }}>أقل من 5000 ل.س</option>
                                    <option value="5000-10000" {{ request('price') == '5000-10000' ? 'selected' : '' }}>5000 - 10000 ل.س</option>
                                    <option value="10000-20000" {{ request('price') == '10000-20000' ? 'selected' : '' }}>10000 - 20000 ل.س</option>
                                    <option value="20000+" {{ request('price') == '20000+' ? 'selected' : '' }}>أكثر من 20000 ل.س</option>
                                </select>
                            </div>

                            <!-- Duration -->
                            <div class="filter-group">
                                <label for="duration"><i class="fas fa-clock"></i> المدة</label>
                                <select name="duration" id="duration" class="form-select">
                                    <option value="">جميع المدد</option>
                                    <option value="1-3" {{ request('duration') == '1-3' ? 'selected' : '' }}>1-3 ساعات</option>
                                    <option value="3-6" {{ request('duration') == '3-6' ? 'selected' : '' }}>3-6 ساعات</option>
                                    <option value="6-12" {{ request('duration') == '6-12' ? 'selected' : '' }}>6-12 ساعة</option>
                                    <option value="12+" {{ request('duration') == '12+' ? 'selected' : '' }}>أكثر من 12 ساعة</option>
                                </select>
                            </div>

                            <!-- Search Button -->
                            <div class="filter-group">
                                <label style="visibility: hidden;">بحث</label>
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    <i class="fas fa-search"></i>
                                    بحث
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FEATURED OFFERS ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">عروض خاصة</h2>
                <p class="section-subtitle">استفد من أفضل العروض المميزة</p>
            </div>

            <!-- Offers Slider -->
            <div class="swiper offers-slider">
                <div class="swiper-wrapper">
                    @forelse($offers as $offer)
                        <div class="swiper-slide">
                            <div class="card offer-card" style="border: 2px solid var(--warning);">
                                <div class="card-header" style="position: relative; padding: 0;">
                                    @if($offer->trip->images && count($offer->trip->images) > 0)
                                        <img src="{{ Storage::url($offer->trip->images[0]) }}"
                                             alt="{{ $offer->title }}"
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                    @else
                                        <img src="https://images.unsplash.com/photo-{{ 1700000000000 + $loop->index }}?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                             alt="{{ $offer->title }}"
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="offer-badge" style="position: absolute; top: 1rem; right: 1rem; display: flex; gap: 0.5rem; flex-direction: column;">
                                        <span class="badge" style="background: var(--warning); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem;">
                                            خصم {{ number_format($offer->discount_percentage, 0) }}%
                                        </span>
                                        @if($offer->trip->trip_types && count($offer->trip->trip_types) > 0)
                                            <span class="badge" style="background: var(--primary); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem;">
                                                {{ $offer->trip->trip_types[0] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3>{{ $offer->title }}</h3>
                                    <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1rem;">
                                        <i class="fas fa-map-marker-alt"></i> {{ $offer->trip->governorate->name ?? 'غير محدد' }}
                                    </p>
                                    <p style="margin-bottom: 1rem;">{{ Str::limit($offer->description, 120) }}</p>

                                    <div class="trip-details" style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); font-weight: bold;">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div style="font-size: 0.875rem;">{{ $offer->getDurationHours() }} ساعة</div>
                                        </div>
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); font-weight: bold;">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div style="font-size: 0.875rem;">{{ $offer->getMaxPersons() }} أشخاص</div>
                                        </div>
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); font-weight: bold;">
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <div style="font-size: 0.875rem;">4.5</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" style="display: flex; justify-content: space-between; align-items: center;">
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                        <span style="font-weight: bold; color: var(--danger); font-size: 1.125rem;">
                                            {{ number_format($offer->getFinalPrice(), 0) }} ل.س
                                        </span>
                                        <span class="old-price" style="text-decoration: line-through; color: var(--gray-500); font-size: 0.875rem;">
                                            {{ number_format($offer->trip->price, 0) }} ل.س
                                        </span>
                                    </div>
                                    @auth
                                        <a href="{{ route('bookings.create', ['trip_id' => $offer->trip->id, 'offer_id' => $offer->id]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-calendar-check"></i>
                                            احجز
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-sign-in-alt"></i>
                                            سجل الدخول
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body text-center" style="padding: 3rem;">
                                    <i class="fas fa-info-circle" style="font-size: 3rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                                    <p style="color: var(--gray-500);">لا توجد عروض خاصة متاحة حالياً</p>
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

    <!-- ========== ALL TRIPS ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">جميع الرحلات</h2>
                <p class="section-subtitle">اختر رحلتك المفضلة من بين مجموعة واسعة من الخيارات</p>
            </div>

            <!-- Trips Grid -->
            <div class="trips-grid">
                <div class="grid grid-3">
                    @forelse($trips as $trip)
                        <div class="trip-card fade-in">
                            <div class="card">
                                <div class="card-header" style="position: relative; padding: 0;">
                                    @if($trip->images && count($trip->images) > 0)
                                        <img src="{{ Storage::url($trip->images[0]) }}"
                                             alt="{{ $trip->title }}"
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                    @else
                                        <img src="https://images.unsplash.com/photo-{{ 1800000000000 + $loop->index }}?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                             alt="{{ $trip->title }}"
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="trip-badges" style="position: absolute; top: 1rem; right: 1rem; display: flex; gap: 0.5rem; flex-direction: column;">
                                        @if($trip->is_featured)
                                            <span class="badge" style="background: var(--warning); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem;">
                                                مميز
                                            </span>
                                        @endif
                                        @if($trip->trip_types && count($trip->trip_types) > 0)
                                            <span class="badge" style="background: var(--primary); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem;">
                                                {{ $trip->trip_types[0] }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3>{{ $trip->title }}</h3>
                                    <p style="color: var(--gray-500); font-size: 0.875rem; margin-bottom: 1rem;">
                                        <i class="fas fa-map-marker-alt"></i> {{ $trip->governorate->name ?? 'غير محدد' }}
                                    </p>
                                    <p style="margin-bottom: 1rem;">{{ Str::limit($trip->description, 120) }}</p>

                                    <div class="trip-details" style="display: flex; justify-content: space-between; margin-bottom: 1rem; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); font-weight: bold;">
                                                <i class="fas fa-clock"></i>
                                            </div>
                                            <div style="font-size: 0.875rem;">{{ $trip->duration_hours }} ساعة</div>
                                        </div>
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); font-weight: bold;">
                                                <i class="fas fa-users"></i>
                                            </div>
                                            <div style="font-size: 0.875rem;">{{ $trip->max_persons }} أشخاص</div>
                                        </div>
                                        <div class="detail" style="text-align: center;">
                                            <div style="color: var(--primary); font-weight: bold;">
                                                <i class="fas fa-star"></i>
                                            </div>
                                            <div style="font-size: 0.875rem;">4.5</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer" style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-weight: bold; color: var(--primary);">
                                        {{ number_format($trip->price, 0) }} ل.س
                                    </span>
                                    @auth
                                        <a href="{{ route('bookings.create', ['trip_id' => $trip->id]) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-calendar-check"></i>
                                            احجز
                                        </a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-sign-in-alt"></i>
                                            سجل الدخول
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center" style="padding: 3rem;">
                            <i class="fas fa-info-circle" style="font-size: 3rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                            <p style="color: var(--gray-500);">لا توجد رحلات متاحة حالياً</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($trips->hasPages())
                <div class="pagination mt-5">
                    @if($trips->onFirstPage())
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="السابق">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $trips->previousPageUrl() }}" aria-label="السابق">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    @endif

                    @foreach($trips->getUrlRange(1, $trips->lastPage()) as $page => $url)
                        <li class="page-item {{ $trips->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if($trips->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $trips->nextPageUrl() }}" aria-label="التالي">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="التالي">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <!-- ========== TRIP CATEGORIES ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">أنواع الرحلات</h2>
                <p class="section-subtitle">اختر النوع الذي يناسب اهتماماتك</p>
            </div>

            <div class="grid grid-4">
                @php
                    $categoryIcons = [
                        'بحرية' => 'fas fa-umbrella-beach',
                        'تراثية' => 'fas fa-landmark',
                        'دينية' => 'fas fa-mosque',
                        'طبيعية' => 'fas fa-mountain',
                        'تاريخية' => 'fas fa-landmark',
                        'ترفيهية' => 'fas fa-umbrella-beach',
                        'default' => 'fas fa-map-marked-alt'
                    ];
                @endphp

                @forelse($categories as $category)
                    <div class="category-card fade-in">
                        <div class="card" style="cursor: pointer; transition: var(--transition-base);">
                            <div class="card-body text-center">
                                <div class="category-icon" style="width: 60px; height: 60px; background: rgba(67, 97, 238, 0.1); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: var(--primary); font-size: 1.5rem;">
                                    <i class="{{ $categoryIcons[$category->name] ?? $categoryIcons['default'] }}"></i>
                                </div>
                                <h4 style="margin-bottom: 0.5rem;">{{ $category->name }}</h4>
                                <span class="badge" style="background: var(--gray-200); color: var(--gray-700);">
                                    {{ $categoryStats[$category->id] ?? 0 }} رحلة
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center" style="padding: 3rem;">
                        <i class="fas fa-info-circle" style="font-size: 3rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                        <p style="color: var(--gray-500);">لا توجد فئات متاحة حالياً</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .filter-group label {
        font-weight: 600;
        color: var(--gray-700);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .offer-card {
        transition: var(--transition-base);
    }

    .offer-card:hover {
        transform: scale(1.05);
    }

    .category-card .card:hover {
        transform: translateY(-5px);
        background: var(--primary);
        color: white;
    }

    .category-card .card:hover .category-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .category-card .card:hover h4,
    .category-card .card:hover .badge {
        color: white !important;
        background: rgba(255, 255, 255, 0.2) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Offers Slider
        @if($offers->count() > 0)
        const offersSlider = new Swiper('.offers-slider', {
            direction: 'horizontal',
            loop: {{ $offers->count() > 3 ? 'true' : 'false' }},
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.offers-slider .swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.offers-slider .swiper-button-next',
                prevEl: '.offers-slider .swiper-button-prev',
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                    spaceBetween: 15
                },
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
            },
        });
        @endif
    });
</script>
@endpush

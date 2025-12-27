@extends('website.pages.layouts.app')

@section('title', 'MyJourney - الرئيسية')

@section('content')
    <!-- ========== HERO SECTION ========== -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">اكتشف جمال سوريا مع MyJourney</h1>
                <p class="hero-subtitle">
                    منصة سياحية متكاملة تقدم أفضل الرحلات والعروض في جميع المحافظات السورية
                </p>
                <div class="hero-actions">
                    <a href="{{ route('trips') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-search"></i>
                        استعرض الرحلات
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-outline btn-lg">
                        <i class="fas fa-info-circle"></i>
                        تعرف علينا
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ========== FEATURED TRIPS ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">رحلات مميزة</h2>
                <p class="section-subtitle">استمتع بأفضل الرحلات السياحية في سوريا</p>
            </div>

            <!-- Swiper Slider -->
            <div class="swiper featured-trips-slider">
                <div class="swiper-wrapper">
                    @forelse($featuredTrips as $trip)
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-header" style="position: relative; padding: 0;">
                                    @if($trip->images && count($trip->images) > 0)
                                        <img src="{{ Storage::url($trip->images[0]) }}"
                                             alt="{{ $trip->title }}"
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                             alt="{{ $trip->title }}"
                                             style="width: 100%; height: 200px; object-fit: cover;">
                                    @endif
                                    @if($trip->trip_types && count($trip->trip_types) > 0)
                                        <span class="badge" style="position: absolute; top: 1rem; left: 1rem; background: var(--primary); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem;">
                                            {{ $trip->trip_types[0] }}
                                        </span>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h3>{{ $trip->title }}</h3>
                                    <p style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">
                                        <i class="fas fa-map-marker-alt"></i> {{ $trip->governorate->name ?? 'غير محدد' }}
                                    </p>
                                    <p style="font-size: 0.875rem; line-height: 1.5; margin-bottom: 1rem;">
                                        {{ Str::limit($trip->description, 100) }}
                                    </p>
                                    <div class="trip-info" style="display: flex; justify-content: space-between; margin-top: 1rem; font-size: 0.875rem; color: var(--gray-600);">
                                        <span><i class="fas fa-clock"></i> {{ $trip->duration_hours }} ساعة</span>
                                        <span><i class="fas fa-users"></i> {{ $trip->max_persons }} أشخاص</span>
                                        <span><i class="fas fa-money-bill-wave"></i> {{ number_format($trip->price, 0) }} ل.س</span>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('trips') }}" class="btn btn-primary btn-sm" style="width: 100%;">
                                        <i class="fas fa-calendar-check"></i>
                                        احجز الآن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body text-center" style="padding: 3rem;">
                                    <i class="fas fa-info-circle" style="font-size: 3rem; color: var(--gray-400); margin-bottom: 1rem;"></i>
                                    <p style="color: var(--gray-500);">لا توجد رحلات مميزة حالياً</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- ========== STATISTICS ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="grid grid-4">
                @php
                    $statsData = [
                        ['icon' => 'fas fa-map-marker-alt', 'count' => $stats['governorates_count'], 'label' => 'محافظة'],
                        ['icon' => 'fas fa-calendar-check', 'count' => $stats['trips_count'], 'label' => 'رحلة'],
                        ['icon' => 'fas fa-user-friends', 'count' => $stats['travelers_count'], 'label' => 'مسافر'],
                        ['icon' => 'fas fa-star', 'count' => $stats['average_rating'], 'label' => 'تقييم']
                    ];
                @endphp

                @foreach($statsData as $stat)
                    <div class="stat-card text-center fade-in">
                        <div class="stat-icon" style="width: 80px; height: 80px; background: var(--gradient-primary); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; color: white; font-size: 2rem;">
                            <i class="{{ $stat['icon'] }}"></i>
                        </div>
                        <h3 style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;">{{ $stat['count'] }}</h3>
                        <p style="color: var(--gray-600); font-size: 1.125rem;">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========== PROVINCES ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">استكشف محافظات سوريا</h2>
                <p class="section-subtitle">اكتشف أجمل الأماكن السياحية في كل محافظة</p>
            </div>

            <!-- Governorates Slider -->
            <div class="swiper governorates-slider">
                <div class="swiper-wrapper">
                    @forelse($governorates as $governorate)
                        <div class="swiper-slide">
                            <div class="province-card fade-in">
                                <div class="card" style="height: 100%; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
                                    <div class="card-header" style="position: relative; padding: 0; height: 200px; overflow: hidden;">
                                        @if($governorate->featured_image)
                                            <img src="{{ Storage::url($governorate->featured_image) }}"
                                                 alt="{{ $governorate->name }}"
                                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                        @else
                                            <img src="https://images.unsplash.com/photo-{{ 1600000000000 + $loop->index }}?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                                 alt="{{ $governorate->name }}"
                                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                        @endif
                                        <div style="position: absolute; top: 0; right: 0; left: 0; bottom: 0; background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.8) 100%);"></div>
                                        <div style="position: absolute; bottom: 0; right: 0; left: 0; padding: 1.5rem;">
                                            <h3 style="margin: 0 0 0.5rem 0; color: white; font-size: 1.5rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                                {{ $governorate->name }}
                                            </h3>
                                            <p style="margin: 0; color: rgba(255,255,255,0.95); font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span>{{ $governorate->location ?? 'سوريا' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card-body" style="padding: 2rem 1.5rem; display: flex; align-items: center; justify-content: center; min-height: 200px; background: rgba(255, 255, 255, 0);">
                                        <a href="{{ route('provinces.show', $governorate) }}" class="btn btn-primary" style="width: 100%; padding: 1.25rem 1.75rem; font-weight: 700; border-radius: var(--radius-lg); font-size: 1.125rem; box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3); transition: all 0.3s ease;">
                                            <i class="fas fa-arrow-left" style="margin-left: 0.5rem;"></i>
                                            عرض المزيد
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

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- ========== TOURIST SPOTS ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">أماكن سياحية مميزة</h2>
                <p class="section-subtitle">اكتشف أجمل الأماكن السياحية في سوريا</p>
            </div>

            <!-- Tourist Spots Slider -->
            <div class="swiper tourist-spots-slider">
                <div class="swiper-wrapper">
                    @forelse($featuredTouristSpots as $spot)
                        <div class="swiper-slide">
                            <div class="tourist-spot-card fade-in">
                                <div class="card" style="height: 100%; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); overflow: hidden;">
                                    <div class="card-header" style="position: relative; padding: 0; height: 200px; overflow: hidden;">
                                        @if($spot->images && count($spot->images) > 0)
                                            <img src="{{ Storage::url($spot->images[0]) }}"
                                                 alt="{{ $spot->name }}"
                                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                        @else
                                            <img src="https://images.unsplash.com/photo-{{ 1700000000000 + $loop->index }}?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                                                 alt="{{ $spot->name }}"
                                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;">
                                        @endif
                                        <div style="position: absolute; top: 0; right: 0; left: 0; bottom: 0; background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.8) 100%);"></div>
                                        <div style="position: absolute; bottom: 0; right: 0; left: 0; padding: 1.5rem;">
                                            <h3 style="margin: 0 0 0.5rem 0; color: white; font-size: 1.5rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                                {{ $spot->name }}
                                            </h3>
                                            <p style="margin: 0; color: rgba(255,255,255,0.95); font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <span>{{ $spot->location }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card-body" style="padding: 2rem 1.5rem; display: flex; align-items: center; justify-content: center; min-height: 200px; background: rgba(255, 255, 255, 0);">
                                        <a href="{{ route('provinces') }}" class="btn btn-primary" style="width: 100%; padding: 1.25rem 1.75rem; font-weight: 700; border-radius: var(--radius-lg); font-size: 1.125rem; box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3); transition: all 0.3s ease;">
                                            <i class="fas fa-arrow-left" style="margin-left: 0.5rem;"></i>
                                            عرض المزيد
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

                <!-- Pagination -->
                <div class="swiper-pagination"></div>

                <!-- Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </section>

    <!-- ========== CTA SECTION ========== -->
    <section class="section" style="background: var(--gradient-primary); color: white;">
        <div class="container">
            <div class="text-center">
                <h2 style="font-size: 2.5rem; margin-bottom: 1rem;">جاهز لرحلتك القادمة؟</h2>
                <p style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9;">
                    انضم إلى آلاف المسافرين الذين اختاروا MyJourney لرحلاتهم
                </p>
                <a href="{{ route('register') }}" class="btn btn-outline btn-lg" style="background: white; color: var(--primary); border-color: white;">
                    <i class="fas fa-user-plus"></i>
                    انضم إلينا الآن
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .governorates-slider,
    .tourist-spots-slider {
        padding: 2rem 0;
        overflow: hidden;
    }

    .governorates-slider .swiper-slide,
    .tourist-spots-slider .swiper-slide {
        height: auto;
    }

    .province-card .card,
    .tourist-spot-card .card {
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        border-radius: var(--radius-lg);
    }

    .province-card .card-body,
    .tourist-spot-card .card-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .province-card .card:hover,
    .tourist-spot-card .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }

    .province-card .card-header,
    .tourist-spot-card .card-header {
        position: relative;
        overflow: hidden;
    }

    .province-card .card-header img,
    .tourist-spot-card .card-header img {
        transition: transform 0.5s ease;
    }

    .province-card .card:hover .card-header img,
    .tourist-spot-card .card:hover .card-header img {
        transform: scale(1.1);
    }

    .province-card .btn,
    .tourist-spot-card .btn {
        transition: all 0.3s ease;
    }

    .province-card .btn:hover,
    .tourist-spot-card .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        background: #3a0ca3 !important;
        border-color: #3a0ca3 !important;
        color: white !important;
    }

    .governorates-slider .swiper-button-next,
    .governorates-slider .swiper-button-prev,
    .tourist-spots-slider .swiper-button-next,
    .tourist-spots-slider .swiper-button-prev {
        color: var(--primary);
        background: white;
        width: 40px;
        height: 40px;
        border-radius: var(--radius-full);
        box-shadow: var(--shadow-md);
    }

    .governorates-slider .swiper-button-next:after,
    .governorates-slider .swiper-button-prev:after,
    .tourist-spots-slider .swiper-button-next:after,
    .tourist-spots-slider .swiper-button-prev:after {
        font-size: 16px;
    }

    .governorates-slider .swiper-pagination,
    .tourist-spots-slider .swiper-pagination {
        position: relative;
        margin-top: 2rem;
    }

    .governorates-slider .swiper-pagination-bullet,
    .tourist-spots-slider .swiper-pagination-bullet {
        background: var(--primary);
        opacity: 0.3;
    }

    .governorates-slider .swiper-pagination-bullet-active,
    .tourist-spots-slider .swiper-pagination-bullet-active {
        opacity: 1;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Governorates Slider
        const governoratesSlider = new Swiper('.governorates-slider', {
            direction: 'horizontal',
            loop: {{ $governorates->count() > 3 ? 'true' : 'false' }},
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.governorates-slider .swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: '.governorates-slider .swiper-button-next',
                prevEl: '.governorates-slider .swiper-button-prev',
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
                768: {
                    slidesPerView: 2,
                    spaceBetween: 25
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                1280: {
                    slidesPerView: 4,
                    spaceBetween: 30
                }
            },
            speed: 600,
            effect: 'slide',
        });

        // Initialize Tourist Spots Slider
        const touristSpotsSlider = new Swiper('.tourist-spots-slider', {
            direction: 'horizontal',
            loop: {{ $featuredTouristSpots->count() > 3 ? 'true' : 'false' }},
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.tourist-spots-slider .swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: '.tourist-spots-slider .swiper-button-next',
                prevEl: '.tourist-spots-slider .swiper-button-prev',
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
                768: {
                    slidesPerView: 2,
                    spaceBetween: 25
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                1280: {
                    slidesPerView: 4,
                    spaceBetween: 30
                }
            },
            speed: 600,
            effect: 'slide',
        });
    });
</script>
@endpush


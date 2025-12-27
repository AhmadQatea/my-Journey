@extends('website.pages.layouts.app')

@section('title', 'المقالات والتقييمات - MyJourney')

@section('content')
    <!-- ========== ARTICLES HERO ========== -->
    <section class="hero-section" style="background: var(--gradient-purple);">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">المقالات والتقييمات</h1>
                <p class="hero-subtitle">
                    اقرأ تجارب المسافرين وشارك تجربتك الخاصة
                </p>
            </div>
        </div>
    </section>

    <!-- ========== FEATURED ARTICLES (ADMIN ARTICLES) ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">مقالات مميزة من فريق MyJourney</h2>
                <p class="section-subtitle">أفضل المقالات المختارة التي كتبها مسؤولو المنصة</p>
            </div>

            @if(isset($featuredArticles) && $featuredArticles->count())
                <!-- Featured Articles Slider -->
                <div class="swiper featured-articles-slider">
                    <div class="swiper-wrapper">
                        @foreach($featuredArticles as $article)
                            <div class="swiper-slide">
                                <div class="card article-card">
                                    <div class="card-header" style="position: relative; padding: 0; height: 250px;">
                                        @php
                                            $image = ($article->images && count($article->images) > 0)
                                                ? asset('storage/'.$article->images[0])
                                                : 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80';
                                        @endphp
                                        <img src="{{ $image }}"
                                             alt="{{ $article->title }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                        <div class="article-badges" style="position: absolute; top: 1rem; right: 1rem; display: flex; gap: 0.5rem;">
                                            <span class="badge" style="background: var(--primary); color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full);">
                                                مميز
                                            </span>
                                            @if($article->rating)
                                                <span class="badge" style="background: #f59e0b; color: white; padding: 0.25rem 0.75rem; border-radius: var(--radius-full);">
                                                    <i class="fas fa-star"></i> {{ $article->rating }}/5
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $authorName = $article->user?->full_name
                                                ?? $article->adminCreator?->name
                                                ?? 'فريق MyJourney';
                                            $authorAvatar = $article->user && $article->user->avatar
                                                ? asset('storage/'.$article->user->avatar)
                                                : asset('assets/images/person.png');
                                        @endphp
                                        <div class="article-meta" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <img src="{{ $authorAvatar }}"
                                                     alt="{{ $authorName }}"
                                                     style="width: 36px; height: 36px; border-radius: var(--radius-full); object-fit: cover;">
                                                <span>{{ $authorName }}</span>
                                            </div>
                                            <span>
                                                <i class="fas fa-calendar"></i>
                                                {{ $article->created_at->format('Y-m-d') }}
                                            </span>
                                        </div>
                                        <h3>{{ $article->title }}</h3>
                                        <p style="margin-bottom: 1rem; line-height: 1.6;">
                                            {{ Str::limit($article->excerpt ?: strip_tags($article->content), 150) }}
                                        </p>
                                        <div class="article-stats" style="display: flex; gap: 1rem; color: var(--gray-500); font-size: 0.875rem;">
                                            <span><i class="fas fa-eye"></i> {{ $article->views_count ?? 0 }} مشاهدة</span>
                                            @if($article->trip)
                                                <span><i class="fas fa-map-marker-alt"></i> {{ Str::limit($article->trip->title, 20) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-outline btn-sm" style="width: 100%;">
                                            <i class="fas fa-book-reader"></i>
                                            اقرأ المزيد
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            @else
                <p class="text-center" style="color: var(--gray-500);">لا توجد مقالات مميزة حالياً.</p>
            @endif
        </div>
    </section>

    <!-- ========== ARTICLES GRID ========== -->
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">جميع المقالات</h2>
                <p class="section-subtitle">استكشف مقالات المسافرين وتجاربهم</p>
            </div>

            <div class="articles-filter" style="margin-bottom: 2rem;">
                <div class="filter-tabs" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @php
                        $filter = $currentFilter ?? 'all';
                    @endphp
                    <a href="{{ route('articles', ['filter' => 'all']) }}"
                       class="btn btn-sm {{ $filter === 'all' ? 'btn-primary' : 'btn-outline' }}">
                        جميع المقالات
                    </a>
                    <a href="{{ route('articles', ['filter' => 'admin']) }}"
                       class="btn btn-sm {{ $filter === 'admin' ? 'btn-primary' : 'btn-outline' }}">
                        من قبل المسؤول
                    </a>
                    <a href="{{ route('articles', ['filter' => 'users']) }}"
                       class="btn btn-sm {{ $filter === 'users' ? 'btn-primary' : 'btn-outline' }}">
                        من قبل المستخدمين
                    </a>
                    <a href="{{ route('articles', ['filter' => 'top-rated']) }}"
                       class="btn btn-sm {{ $filter === 'top-rated' ? 'btn-primary' : 'btn-outline' }}">
                        الأعلى تقييماً
                    </a>
                </div>
            </div>

            <div class="articles-grid">
                <div class="grid grid-3">
                    @forelse($articles as $article)
                        <div class="article-item fade-in">
                            <div class="card">
                                <div class="card-body">
                                    <div class="article-header" style="margin-bottom: 1rem;">
                                        @php
                                            $authorName = $article->user?->full_name
                                                ?? $article->adminCreator?->name
                                                ?? 'فريق MyJourney';
                                            $authorAvatar = $article->user && $article->user->avatar
                                                ? asset('storage/'.$article->user->avatar)
                                                : asset('assets/images/person.png');
                                        @endphp
                                        <div class="author-info" style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                                            <img src="{{ $authorAvatar }}"
                                                 alt="{{ $authorName }}"
                                                 style="width: 40px; height: 40px; border-radius: var(--radius-full); object-fit: cover;">
                                            <div>
                                                <div style="font-weight: 600;">
                                                    {{ $authorName }}
                                                </div>
                                                <div style="font-size: 0.75rem; color: var(--gray-500);">
                                                    {{ $article->created_at->format('Y-m-d') }}
                                                </div>
                                            </div>
                                        </div>
                                        <h4 style="margin-bottom: 0.75rem;">{{ $article->title }}</h4>
                                        <p style="color: var(--gray-600); font-size: 0.875rem; line-height: 1.6; margin-bottom: 1rem;">
                                            {{ Str::limit($article->excerpt ?: strip_tags($article->content), 160) }}
                                        </p>
                                    </div>

                                    <div class="article-footer" style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--gray-200);">
                                        <div class="article-meta" style="display: flex; gap: 1rem; font-size: 0.75rem; color: var(--gray-500);">
                                            <span><i class="fas fa-eye"></i> {{ $article->views_count ?? 0 }}</span>
                                            @if($article->rating)
                                                <span><i class="fas fa-star"></i> {{ $article->rating }}/5</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('articles.show', $article) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-book-open"></i>
                                            قراءة
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center" style="color: var(--gray-500);">لا توجد مقالات حالياً.</p>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($articles->hasPages())
                <div class="pagination mt-5">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- ========== TESTIMONIALS (FROM FEEDBACK TABLE) ========== -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">آراء المسافرين</h2>
                <p class="section-subtitle">ماذا قال المسافرون عن تجربتهم مع MyJourney</p>
            </div>

            @if(isset($testimonials) && $testimonials->count())
                <!-- Testimonials Slider -->
                <div class="swiper testimonials-slider">
                    <div class="swiper-wrapper">
                        @foreach($testimonials as $feedback)
                            <div class="swiper-slide">
                                <div class="testimonial-card">
                                    <div class="testimonial-content" style="background: var(--gray-50); padding: 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-md); position: relative; margin-bottom: 1.5rem;">
                                        <div class="quote-icon" style="position: absolute; top: -15px; right: 20px; width: 30px; height: 30px; background: var(--primary); color: white; border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-quote-right"></i>
                                        </div>
                                        <p style="line-height: 1.8; color: var(--gray-700);">
                                            {{ $feedback->comments ?: 'لم يضف المستخدم تعليقاً كتابياً، لكن ترك تقييماً إيجابياً.' }}
                                        </p>
                                    </div>
                                    <div class="testimonial-author" style="display: flex; align-items: center; gap: 1rem;">
                                        @php
                                            $avatar = $feedback->user && $feedback->user->avatar
                                                ? asset('storage/'.$feedback->user->avatar)
                                                : asset('assets/images/person.png');
                                        @endphp
                                        <img src="{{ $avatar }}"
                                             alt="{{ $feedback->name }}"
                                             style="width: 60px; height: 60px; border-radius: var(--radius-full); object-fit: cover;">
                                        <div>
                                            <div style="font-weight: 600; color: var(--gray-900);">
                                                {{ $feedback->name }}
                                            </div>
                                            <div style="color: var(--gray-500); font-size: 0.875rem;">
                                                {{ $feedback->created_at->format('Y-m-d') }}
                                            </div>
                                            <div class="stars" style="color: #fbbf24; margin-top: 0.25rem;">
                                                @for($s = 1; $s <= 5; $s++)
                                                    <i class="fas fa-star{{ $s <= $feedback->rating ? '' : '' }}"></i>
                                                @endfor
                                            </div>
                                            @if($feedback->likes && is_array($feedback->likes) && count($feedback->likes))
                                                <div style="margin-top: 0.25rem; font-size: 0.8rem; color: var(--gray-600);">
                                                    أعجبه: {{ implode('، ', $feedback->likes) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            @else
                <p class="text-center" style="color: var(--gray-500);">
                    لا توجد ملاحظات من المستخدمين حالياً، كن أول من يشارك رأيه من صفحة التواصل والتقييم.
                </p>
            @endif
        </div>
    </section>

    <!-- ========== WRITE ARTICLE CTA ========== -->
    <section class="section" style="background: var(--gradient-primary); color: white;">
        <div class="container">
            <div class="grid grid-2 align-items-center gap-4">
                <div class="fade-in">
                    <h2 style="font-size: 2rem; margin-bottom: 1rem;">شارك تجربتك مع الآخرين</h2>
                    <p style="font-size: 1.125rem; opacity: 0.9; margin-bottom: 1.5rem;">
                        ساعد المسافرين الآخرين باتخاذ القرار الصحيح بمشاركة تجربتك وتقييمك للرحلات
                    </p>
                    <a href="#" class="btn btn-outline btn-lg" style="background: white; color: var(--primary); border-color: white;">
                        <i class="fas fa-pen"></i>
                        اكتب مقالك الآن
                    </a>
                </div>
                <div class="fade-in">
                    <img src="{{asset('assets/images/create-article.jpg')}}"
                         alt="كتابة مقال"
                         style="width: 100%; border-radius: var(--radius-xl); box-shadow: var(--shadow-xl);">
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .article-card {
        transition: var(--transition-base);
        height: 100%;
    }

    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
    }

    .filter-tabs .btn.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .testimonial-card {
        padding: 1rem;
    }

    .testimonial-content {
        position: relative;
    }

    .testimonial-content::after {
        content: '';
        position: absolute;
        bottom: -10px;
        right: 40px;
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-top: 10px solid white;
    }

    .articles-grid .article-item {
        transition: var(--transition-base);
    }

    .articles-grid .article-item:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

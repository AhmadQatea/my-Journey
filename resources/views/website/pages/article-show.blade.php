@extends('website.pages.layouts.app')

@section('title', $article->title . ' - MyJourney')

@section('content')
    <section class="section" style="background: var(--gray-50);">
        <div class="container">
            <div class="article-detail-page" style="max-width: 1000px; margin: 0 auto;">
                <!-- Header -->
                <div class="article-header-section"
                     style="background: var(--gradient-purple); border-radius: 16px; padding: 2rem; color: white; margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
                        <div>
                            <h1 style="font-size: 2rem; margin-bottom: 0.75rem;">
                                <i class="fas fa-newspaper"></i>
                                {{ $article->title }}
                            </h1>
                            <div style="display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.9rem; opacity: 0.95;">
                                <span>
                                    <i class="fas fa-user"></i>
                                    {{ $article->user?->full_name ?? $article->adminCreator?->name ?? 'فريق MyJourney' }}
                                </span>
                                <span>
                                    <i class="fas fa-calendar"></i>
                                    {{ $article->created_at->format('Y-m-d') }}
                                </span>
                                <span>
                                    <i class="fas fa-eye"></i>
                                    {{ $article->views_count ?? 0 }} مشاهدة
                                </span>
                                @if($article->rating)
                                    <span>
                                        <i class="fas fa-star"></i>
                                        {{ $article->rating }}/5
                                    </span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('articles') }}"
                           class="btn btn-outline"
                           style="background: transparent; color: white; border-color: rgba(255,255,255,0.6);">
                            <i class="fas fa-arrow-right"></i>
                            العودة إلى المقالات
                        </a>
                    </div>
                </div>

                <!-- Content -->
                <div class="article-content-card"
                     style="background: var(--gray-50); border-radius: 16px; padding: 2rem; box-shadow: var(--shadow-lg); border: 1px solid var(--gray-200);">
                    @if($article->images && count($article->images) > 0)
                        <div class="article-images" style="margin-bottom: 2rem;">
                            <div class="article-images-grid"
                                 style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1rem;">
                                @foreach($article->images as $image)
                                    @php
                                        if (is_string($image) && (str_starts_with($image, 'http://') || str_starts_with($image, 'https://'))) {
                                            $imageUrl = $image;
                                        } elseif (is_string($image) && str_starts_with($image, 'storage/')) {
                                            $imageUrl = asset($image);
                                        } elseif (is_string($image)) {
                                            $imageUrl = asset('storage/' . $image);
                                        } else {
                                            $imageUrl = null;
                                        }
                                    @endphp
                                    @if($imageUrl)
                                        <div style="border-radius: 12px; overflow: hidden; aspect-ratio: 16/9;">
                                            <img src="{{ $imageUrl }}"
                                                 alt="{{ $article->title }}"
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($article->excerpt)
                        <div style="background: var(--gray-50); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; border-right: 4px solid var(--primary); font-style: italic; color: var(--gray-800);">
                            {{ $article->excerpt }}
                        </div>
                    @endif

                    <div class="article-body"
                         style="color: var(--gray-800); line-height: 1.9; font-size: 1.02rem;">
                        {!! $article->content !!}
                    </div>

                    @if($article->trip)
                        <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--gray-200); font-size: 0.95rem; color: var(--gray-600);">
                            <h4 style="margin-bottom: 0.5rem;">
                                <i class="fas fa-map-marked-alt"></i>
                                الرحلة المرتبطة
                            </h4>
                            <p>{{ $article->trip->title }}</p>
                            @if($article->trip->governorate)
                                <p style="margin-top: 0.25rem;">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $article->trip->governorate->name }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

<div>
    <!-- Simplicity is the essence of happiness. - Cedric Bledsoe -->
</div>

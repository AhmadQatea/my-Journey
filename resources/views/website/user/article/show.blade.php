@extends('website.user.layouts.user')

@section('title', $article->title . ' - MyJourney')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/article.css') }}">
<style>
.article-detail-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.article-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.article-header-section h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.article-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
}

.article-content-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
    margin-bottom: 2rem;
}

.article-content-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.article-images {
    margin-bottom: 2rem;
}

.article-images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.article-image-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    aspect-ratio: 16/9;
    cursor: pointer;
    transition: transform 0.3s;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.article-image-item:hover {
    transform: scale(1.05);
}

.article-image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.article-excerpt {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    border-left: 4px solid #667eea;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.article-excerpt p {
    color: #1e293b;
    font-size: 1.125rem;
    line-height: 1.8;
    font-weight: 500;
    font-style: italic;
    margin: 0;
}

.article-body {
    color: #1e293b;
    line-height: 1.9;
    font-size: 1.05rem;
    font-weight: 500;
}

.article-body p {
    margin-bottom: 1.5rem;
    color: #1e293b;
}

.article-body h1, .article-body h2, .article-body h3, .article-body h4 {
    color: #0f172a;
    font-weight: 800;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.article-body ul, .article-body ol {
    margin-bottom: 1.5rem;
    padding-right: 2rem;
}

.article-body li {
    color: #1e293b;
    margin-bottom: 0.5rem;
}

.article-rating {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 2rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.article-rating h4 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #0f172a;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stars-display {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stars-display i {
    font-size: 1.5rem;
    color: #cbd5e1;
}

.stars-display i.active {
    color: #fbbf24;
}

.stars-display span {
    color: #334155;
    font-weight: 700;
    font-size: 1.125rem;
    margin-right: 0.5rem;
}

.article-trip {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 12px;
    padding: 1.5rem;
    margin-top: 2rem;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.article-trip h4 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #0f172a;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.article-trip p {
    color: #334155;
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
}

.rejection-notice {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #fecaca;
    border-left: 4px solid #ef4444;
    margin-top: 2rem;
}

.rejection-notice h5 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #991b1b;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.rejection-notice p {
    color: #7f1d1d;
    line-height: 1.8;
    font-weight: 600;
    font-size: 1rem;
}

.empty-section {
    text-align: center;
    padding: 3rem 1rem;
    color: #6b7280;
}

.empty-section i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #d1d5db;
}

</style>
@endpush

@section('content')
<div class="article-detail-page">
    <!-- Header Section -->
    <div class="article-header-section">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1>
                    <i class="fas fa-newspaper"></i>
                    {{ $article->title }}
                </h1>
                <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <div class="article-status-badge">
                        <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                        {{ $article->status }}
                    </div>
                    <span style="font-size: 0.875rem; opacity: 0.9;">
                        <i class="fas fa-calendar"></i>
                        {{ $article->created_at->format('Y-m-d') }}
                    </span>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('my-articles') }}" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                    <i class="fas fa-arrow-right"></i>
                    <span>العودة</span>
                </a>
                @if($article->status !== 'منشورة')
                    <a href="{{ route('articles.edit', $article) }}" class="btn" style="background: white; color: #667eea; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                        <i class="fas fa-edit"></i>
                        <span>تعديل</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="article-content-card">
        @if($article->images && count($article->images) > 0)
            <div class="article-images">
                <h4 style="font-size: 1.25rem; margin-bottom: 1rem; color: #0f172a; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-images"></i>
                    صور المقال
                </h4>
                <div class="article-images-grid">
                    @foreach($article->images as $image)
                        @php
                            // التأكد من أن المسار صحيح
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
                            <div class="article-image-item" onclick="openImageModal('{{ $imageUrl }}')">
                                <img src="{{ $imageUrl }}" alt="{{ $article->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        @if($article->excerpt)
            <div class="article-excerpt">
                <p>{{ $article->excerpt }}</p>
            </div>
        @endif

        <div class="article-body">
            {!! $article->content !!}
        </div>

        @if($article->rating)
            <div class="article-rating">
                <h4>
                    <i class="fas fa-star"></i>
                    تقييم الرحلة
                </h4>
                <div class="stars-display">
                    <span>{{ $article->rating }}/5</span>
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $article->rating ? 'active' : '' }}"></i>
                    @endfor
                </div>
            </div>
        @endif

        @if($article->trip)
            <div class="article-trip">
                <h4>
                    <i class="fas fa-map-marked-alt"></i>
                    الرحلة المرتبطة
                </h4>
                <p>{{ $article->trip->title }}</p>
                @if($article->trip->governorate)
                    <p style="margin-top: 0.5rem; color: #64748b; font-size: 0.875rem;">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $article->trip->governorate->name }}
                    </p>
                @endif
            </div>
        @endif

        @if($article->status === 'مرفوضة' && $article->rejection_reason)
            <div class="rejection-notice">
                <h5>
                    <i class="fas fa-exclamation-triangle"></i>
                    سبب الرفض
                </h5>
                <p>{{ $article->rejection_reason }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; cursor: pointer; align-items: center; justify-content: center;" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="صورة المقال" style="max-width: 90%; max-height: 90%; object-fit: contain; border-radius: 8px;">
</div>

@push('scripts')
<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').style.display = 'flex';
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// إغلاق عند الضغط على ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endpush
@endsection

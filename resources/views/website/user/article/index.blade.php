@extends('website.user.layouts.user')

@section('title', 'مقالاتي - MyJourney')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/article.css') }}">
<style>

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .articles-grid {
        grid-template-columns: 1fr;
    }
}

.article-card-modern {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    position: relative;
}

.article-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    z-index: 1;
}

.article-card-modern:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    transform: translateY(-4px);
}

.article-card-header-modern {
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
    z-index: 2;
}

.article-card-header-modern h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #ffffff;
}

.article-card-header-modern .status-badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 0.875rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
}

.status-badge-modern.status-published {
    background: rgba(16, 185, 129, 0.2);
}

.status-badge-modern.status-pending {
    background: rgba(251, 191, 36, 0.2);
}

.status-badge-modern.status-rejected {
    background: rgba(239, 68, 68, 0.2);
}

.article-card-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: #f3f4f6;
}

.article-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.article-card-modern:hover .article-card-image img {
    transform: scale(1.1);
}

.article-card-body-modern {
    padding: 1.5rem;
    flex: 1;
    background: transparent;
}

.article-card-body-modern p {
    color: #1e293b;
    font-size: 0.875rem;
    line-height: 1.7;
    margin-bottom: 1rem;
    font-weight: 500;
}

.article-meta-modern {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
}

.article-meta-modern span {
    font-size: 0.875rem;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
}

.article-meta-modern i {
    color: #667eea;
}

.article-card-footer-modern {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f9fafb 0%, #f1f5f9 100%);
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-modern {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-view-modern {
    background: #667eea;
    color: white;
}

.btn-view-modern:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

.btn-edit-modern {
    background: #10b981;
    color: white;
}

.btn-edit-modern:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-delete-modern {
    background: #ef4444;
    color: white;
}

.btn-delete-modern:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.inline-form {
    display: inline;
}

.empty-state-modern {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    grid-column: 1 / -1;
}

.empty-icon-modern {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1.5rem;
}

.empty-state-modern h5 {
    font-size: 1.5rem;
    color: #0f172a;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.empty-state-modern p {
    color: #334155;
    margin-bottom: 2rem;
    font-size: 1rem;
    font-weight: 600;
}

.btn-explore-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-explore-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.pagination-wrapper-modern {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.stats-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.stat-card .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.stat-card .stat-label {
    color: #334155;
    font-size: 0.875rem;
    font-weight: 700;
}

</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h2 class="page-title">مقالاتي</h2>
            <p class="page-subtitle">إدارة ومتابعة جميع مقالاتك</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('articles.create') }}" class="btn btn-new-trip">
                <i class="fas fa-newspaper"></i>
                <span>مقال جديد</span>
            </a>
        </div>
    </div>

    <!-- Stats Section -->
    @if($articles->count() > 0)
        @php
            $stats = [
                'total' => $articles->total(),
                'published' => $articles->where('status', 'منشورة')->count(),
                'pending' => $articles->where('status', 'معلقة')->count(),
                'rejected' => $articles->where('status', 'مرفوضة')->count(),
            ];
        @endphp
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card card-articles">
                    <div class="card-header">
                        <h3 class="card-title">إجمالي المقالات</h3>
                        <div class="card-icon">
                            <i class="fas fa-newspaper"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="stat-number">{{ $stats['total'] }}</div>
                        <p class="stat-desc">مقال إجمالي</p>
                    </div>
                </div>
                <div class="stat-card card-bookings">
                    <div class="card-header">
                        <h3 class="card-title">منشورة</h3>
                        <div class="card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="stat-number" style="color: #10b981;">{{ $stats['published'] }}</div>
                        <p class="stat-desc">مقال منشور</p>
                    </div>
                </div>
                <div class="stat-card card-status">
                    <div class="card-header">
                        <h3 class="card-title">معلقة</h3>
                        <div class="card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="stat-number" style="color: #f59e0b;">{{ $stats['pending'] }}</div>
                        <p class="stat-desc">في الانتظار</p>
                    </div>
                </div>
                <div class="stat-card card-status">
                    <div class="card-header">
                        <h3 class="card-title">مرفوضة</h3>
                        <div class="card-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="stat-number" style="color: #ef4444;">{{ $stats['rejected'] }}</div>
                        <p class="stat-desc">مرفوضة</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Articles Grid -->
    <div class="articles-grid" style="margin-top: 2rem;">
        @forelse($articles as $article)
            <div class="article-card-modern">
                <!-- Card Header -->
                <div class="article-card-header-modern">
                    <h3>
                        <i class="fas fa-file-alt"></i>
                        {{ $article->title }}
                    </h3>
                    <span class="status-badge-modern status-{{ $article->status === 'منشورة' ? 'published' : ($article->status === 'معلقة' ? 'pending' : 'rejected') }}">
                        <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                        {{ $article->status }}
                    </span>
                </div>

                <!-- Article Image -->
                @if($article->images && count($article->images) > 0)
                    <div class="article-card-image">
                        <img src="{{ asset('storage/'.$article->images[0]) }}" alt="{{ $article->title }}">
                    </div>
                @endif

                <!-- Card Body -->
                <div class="article-card-body-modern">
                    <p>{{ Str::limit(strip_tags($article->content), 200) }}</p>
                    
                    <div class="article-meta-modern">
                        <span>
                            <i class="fas fa-calendar"></i>
                            {{ $article->created_at->format('Y-m-d') }}
                        </span>
                        @if($article->trip)
                            <span>
                                <i class="fas fa-map-marker-alt"></i>
                                {{ Str::limit($article->trip->title, 20) }}
                            </span>
                        @endif
                        @if($article->rating)
                            <span>
                                <i class="fas fa-star"></i>
                                {{ $article->rating }}/5
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="article-card-footer-modern">
                    <a href="{{ route('user-articles.show', $article) }}" class="btn-modern btn-view-modern">
                        <i class="fas fa-eye"></i>
                        عرض
                    </a>
                    @if($article->status !== 'منشورة')
                        <a href="{{ route('articles.edit', $article) }}" class="btn-modern btn-edit-modern">
                            <i class="fas fa-edit"></i>
                            تعديل
                        </a>
                    @endif
                    <form action="{{ route('articles.destroy', $article) }}" method="POST" class="inline-form" onsubmit="return confirm('هل أنت متأكد من حذف هذا المقال؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-modern btn-delete-modern">
                            <i class="fas fa-trash"></i>
                            حذف
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state" style="margin-top: 2rem;">
                <div class="empty-icon">
                    <i class="fas fa-newspaper"></i>
                </div>
                <h5>لا توجد مقالات</h5>
                <p>ابدأ بكتابة مقالك الأول وشارك تجربتك</p>
                <a href="{{ route('articles.create') }}" class="btn btn-write">
                    <i class="fas fa-pen"></i>
                    كتابة مقال جديد
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
        <div class="pagination-wrapper-modern">
            {{ $articles->links() }}
        </div>
    @endif
</div>
@endsection

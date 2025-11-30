@extends('admin.layouts.admin')

@section('title', 'Articles Management')
@section('page-title', 'Articles Management')

@section('content')
<x-card>
    <x-slot:actions>
        <div class="flex gap-3 flex-wrap">
            <div class="search-box">
                <input type="text" class="form-control search-input" placeholder="Search articles...">
                <i class="fas fa-search search-icon"></i>
            </div>
            <select class="form-control filter-select" onchange="filterByStatus(this.value)">
                <option value="">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="pending">Pending Review</option>
                <option value="rejected">Rejected</option>
            </select>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Article
            </a>
        </div>
    </x-slot:actions>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Likes</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                <tr>
                    <td>
                        <img src="{{ asset($article->featured_image) }}" alt="{{ $article->title }}" 
                             class="w-12 h-12 rounded object-cover">
                    </td>
                    <td>
                        <div>
                            <h4 class="font-medium">{{ Str::limit($article->title, 50) }}</h4>
                            <p class="text-sm text-gray-500">{{ Str::limit($article->excerpt, 30) }}</p>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-blue-500 text-white flex items-center justify-center text-xs">
                                {{ substr($article->author->name, 0, 1) }}
                            </div>
                            {{ $article->author->name }}
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $article->category->name }}</span>
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'published' => 'success',
                                'draft' => 'secondary',
                                'pending' => 'warning',
                                'rejected' => 'danger'
                            ];
                        @endphp
                        <span class="badge badge-{{ $statusColors[$article->status] }} status-badge">
                            {{ ucfirst($article->status) }}
                        </span>
                    </td>
                    <td>{{ number_format($article->views) }}</td>
                    <td>{{ number_format($article->likes_count) }}</td>
                    <td>{{ $article->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.articles.show', $article) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-item" 
                                    data-id="{{ $article->id }}" 
                                    data-type="article"
                                    data-url="{{ route('admin.articles.destroy', ':id') }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            @if($article->status == 'pending')
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <form action="{{ route('admin.articles.approve', $article) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-success">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.articles.reject', $article) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</x-card>

<!-- Articles Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #3b82f6;">
            <i class="fas fa-newspaper"></i>
        </div>
        <div class="stat-number">{{ $totalArticles }}</div>
        <div class="stat-label">Total Articles</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #10b981;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number">{{ $publishedArticles }}</div>
        <div class="stat-label">Published</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-number">{{ $pendingArticles }}</div>
        <div class="stat-label">Pending Review</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fce7f3; color: #ec4899;">
            <i class="fas fa-eye"></i>
        </div>
        <div class="stat-number">{{ number_format($totalViews) }}</div>
        <div class="stat-label">Total Views</div>
    </div>
</div>

<!-- Top Performing Articles -->
<x-card title="Top Performing Articles" class="mt-6">
    <div class="space-y-4">
        @foreach($topArticles as $article)
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
            <div class="flex items-center gap-3">
                <img src="{{ asset($article->featured_image) }}" alt="{{ $article->title }}" 
                     class="w-12 h-12 rounded object-cover">
                <div>
                    <h4 class="font-medium">{{ $article->title }}</h4>
                    <p class="text-sm text-gray-500">By {{ $article->author->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-center">
                    <div class="font-bold text-blue-600">{{ number_format($article->views) }}</div>
                    <div class="text-xs text-gray-500">Views</div>
                </div>
                <div class="text-center">
                    <div class="font-bold text-green-600">{{ number_format($article->likes_count) }}</div>
                    <div class="text-xs text-gray-500">Likes</div>
                </div>
                <div class="text-center">
                    <div class="font-bold text-purple-600">{{ $article->comments_count }}</div>
                    <div class="text-xs text-gray-500">Comments</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-card>
@endsection
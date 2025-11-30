@extends('admin.layouts.admin')

@section('title', 'Article Details')
@section('page-title', 'Article Details: ' . $article->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Article Content -->
    <div class="lg:col-span-2">
        <x-card>
            <!-- Featured Image -->
            @if($article->featured_image)
            <div class="mb-6">
                <img src="{{ asset($article->featured_image) }}" alt="{{ $article->title }}" 
                     class="w-full h-64 object-cover rounded-lg">
            </div>
            @endif
            
            <!-- Article Meta -->
            <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-500">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user"></i>
                    <span>By {{ $article->author->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $article->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-eye"></i>
                    <span>{{ number_format($article->views) }} views</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-heart"></i>
                    <span>{{ number_format($article->likes_count) }} likes</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-comments"></i>
                    <span>{{ $article->comments_count }} comments</span>
                </div>
            </div>
            
            <!-- Article Content -->
            <div class="prose max-w-none">
                {!! $article->content !!}
            </div>
            
            <!-- Tags -->
            @if($article->tags)
            <div class="mt-6">
                <h4 class="font-semibold mb-2">Tags:</h4>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $article->tags) as $tag)
                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm">
                        {{ trim($tag) }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Article Images Gallery -->
            @if($article->images->count() > 0)
            <div class="mt-6">
                <h4 class="font-semibold mb-3">Article Images</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($article->images as $image)
                    <img src="{{ asset($image->path) }}" alt="Article image" 
                         class="w-full h-32 object-cover rounded-lg cursor-pointer" 
                         onclick="openImageModal('{{ asset($image->path) }}')">
                    @endforeach
                </div>
            </div>
            @endif
        </x-card>
        
        <!-- Comments Section -->
        @if($article->allow_comments)
        <x-card title="Comments ({{ $article->comments_count }})" class="mt-6">
            <div class="space-y-4">
                @foreach($article->comments()->with('user')->latest()->get() as $comment)
                <div class="flex gap-3 p-3 border rounded-lg">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center text-sm font-bold">
                        {{ substr($comment->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium">{{ $comment->user->name }}</h4>
                                <p class="text-gray-500 text-sm">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="action-buttons">
                                <button class="btn btn-danger btn-sm delete-item" 
                                        data-id="{{ $comment->id }}" 
                                        data-type="comment"
                                        data-url="{{ route('admin.comments.destroy', ':id') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <p class="mt-2">{{ $comment->content }}</p>
                    </div>
                </div>
                @endforeach
                
                @if($article->comments_count == 0)
                <p class="text-gray-500 text-center py-4">No comments yet.</p>
                @endif
            </div>
        </x-card>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Article Info -->
        <x-card title="Article Information">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="font-medium">Status:</span>
                    @php
                        $statusColors = [
                            'published' => 'success',
                            'draft' => 'secondary',
                            'pending' => 'warning',
                            'rejected' => 'danger'
                        ];
                    @endphp
                    <span class="badge badge-{{ $statusColors[$article->status] }}">
                        {{ ucfirst($article->status) }}
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Category:</span>
                    <span>{{ $article->category->name }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Featured:</span>
                    <span>
                        @if($article->is_featured)
                            <i class="fas fa-check text-green-500"></i>
                        @else
                            <i class="fas fa-times text-red-500"></i>
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Comments:</span>
                    <span>
                        @if($article->allow_comments)
                            <i class="fas fa-check text-green-500"></i>
                        @else
                            <i class="fas fa-times text-red-500"></i>
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Last Updated:</span>
                    <span>{{ $article->updated_at->format('M d, Y H:i') }}</span>
                </div>
            </div>
        </x-card>
        
        <!-- SEO Information -->
        <x-card title="SEO Information">
            <div class="space-y-3">
                <div>
                    <span class="font-medium block mb-1">Meta Title:</span>
                    <p class="text-sm text-gray-600">{{ $article->meta_title ?: 'Not set' }}</p>
                </div>
                
                <div>
                    <span class="font-medium block mb-1">Meta Description:</span>
                    <p class="text-sm text-gray-600">{{ $article->meta_description ?: 'Not set' }}</p>
                </div>
                
                <div>
                    <span class="font-medium block mb-1">Slug:</span>
                    <p class="text-sm text-gray-600">{{ $article->slug }}</p>
                </div>
            </div>
        </x-card>
        
        <!-- Actions -->
        <x-card title="Actions">
            <div class="space-y-2">
                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-warning w-full">
                    <i class="fas fa-edit"></i> Edit Article
                </a>
                
                @if($article->status == 'pending')
                <form action="{{ route('admin.articles.approve', $article) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="btn btn-success w-full">
                        <i class="fas fa-check"></i> Approve Article
                    </button>
                </form>
                
                <form action="{{ route('admin.articles.reject', $article) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="btn btn-danger w-full">
                        <i class="fas fa-times"></i> Reject Article
                    </button>
                </form>
                @endif
                
                <button type="button" class="btn btn-danger w-full delete-item" 
                        data-id="{{ $article->id }}" 
                        data-type="article"
                        data-url="{{ route('admin.articles.destroy', $article) }}">
                    <i class="fas fa-trash"></i> Delete Article
                </button>
            </div>
        </x-card>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 gap-4">
            <div class="stat-card text-center">
                <div class="stat-number text-blue-600">{{ number_format($article->views) }}</div>
                <div class="stat-label">Views</div>
            </div>
            
            <div class="stat-card text-center">
                <div class="stat-number text-green-600">{{ number_format($article->likes_count) }}</div>
                <div class="stat-label">Likes</div>
            </div>
            
            <div class="stat-card text-center">
                <div class="stat-number text-purple-600">{{ $article->comments_count }}</div>
                <div class="stat-label">Comments</div>
            </div>
            
            <div class="stat-card text-center">
                <div class="stat-number text-orange-600">{{ $article->shares }}</div>
                <div class="stat-label">Shares</div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <div class="modal-content" style="max-width: 90vw; max-height: 90vh;">
        <div class="modal-header">
            <button type="button" class="modal-close" data-modal-hide="imageModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <img id="modalImage" src="" class="w-full h-auto max-h-[80vh] object-contain">
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').style.display = 'flex';
}
</script>
@endpush
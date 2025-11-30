@extends('admin.layouts.admin')

@section('title', 'Edit Article')
@section('page-title', 'Edit Article: ' . $article->title)

@section('content')
<x-card title="Edit Article Information">
    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <x-form.input 
                    name="title" 
                    label="Article Title" 
                    required 
                    value="{{ old('title', $article->title) }}"
                />
                
                <x-form.textarea 
                    name="excerpt" 
                    label="Excerpt" 
                    rows="3"
                >{{ old('excerpt', $article->excerpt) }}</x-form.textarea>
                
                <div class="form-group">
                    <label class="form-label">Content *</label>
                    <textarea id="contentEditor" class="form-control @error('content') is-invalid @enderror" 
                              name="content" rows="15">{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Existing Images -->
                <div class="form-group">
                    <label class="form-label">Article Images</label>
                    <div class="grid grid-cols-4 gap-3 mt-3">
                        @foreach($article->images as $image)
                        <div class="relative">
                            <img src="{{ asset($image->path) }}" class="w-full h-24 object-cover rounded">
                            <button type="button" class="remove-image absolute top-1 left-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs"
                                    data-id="{{ $image->id }}"
                                    data-url="{{ route('admin.article-images.destroy', ':id') }}">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- New Images Upload -->
                    <div class="multi-image-upload mt-3" data-preview="imagePreview">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p class="text-gray-500">Drag & drop additional images here or click to browse</p>
                        <input type="file" multiple accept="image/*" class="hidden" name="new_images[]">
                    </div>
                    <div id="imagePreview" class="image-preview-container mt-3"></div>
                </div>
            </div>
            
            <!-- Sidebar Settings -->
            <div class="space-y-6">
                <!-- Featured Image -->
                <div class="form-group">
                    <label class="form-label">Featured Image</label>
                    @if($article->featured_image)
                    <div class="mb-3">
                        <img src="{{ asset($article->featured_image) }}" alt="Current featured image" 
                             class="w-full h-48 object-cover rounded">
                    </div>
                    @endif
                    <div class="featured-image-upload">
                        <i class="fas fa-image"></i>
                        <p class="text-gray-500 text-sm">Click to change featured image</p>
                        <input type="file" accept="image/*" class="hidden" name="featured_image">
                    </div>
                    <div class="featured-image-preview hidden mt-3">
                        <img class="w-full h-48 object-cover rounded">
                    </div>
                </div>
                
                <!-- Article Settings -->
                <x-card title="Article Settings" class="!mb-0">
                    <div class="space-y-4">
                        <x-form.select 
                            name="category_id" 
                            label="Category" 
                            :options="$categories->pluck('name', 'id')" 
                            required 
                            :selected="old('category_id', $article->category_id)"
                        />
                        
                        <x-form.input 
                            name="tags" 
                            label="Tags" 
                            placeholder="tag1, tag2, tag3"
                            value="{{ old('tags', $article->tags) }}"
                        />
                        
                        <x-form.select 
                            name="status" 
                            label="Status" 
                            :options="[
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'pending' => 'Pending Review'
                            ]" 
                            required 
                            :selected="old('status', $article->status)"
                        />
                        
                        <x-form.checkbox 
                            name="is_featured" 
                            label="Featured Article" 
                            :checked="old('is_featured', $article->is_featured)"
                        />
                        
                        <x-form.checkbox 
                            name="allow_comments" 
                            label="Allow Comments" 
                            :checked="old('allow_comments', $article->allow_comments)"
                        />
                    </div>
                </x-card>
                
                <!-- SEO Settings -->
                <x-card title="SEO Settings" class="!mb-0">
                    <div class="space-y-4">
                        <x-form.input 
                            name="meta_title" 
                            label="Meta Title" 
                            value="{{ old('meta_title', $article->meta_title) }}"
                        />
                        
                        <x-form.textarea 
                            name="meta_description" 
                            label="Meta Description" 
                            rows="3"
                        >{{ old('meta_description', $article->meta_description) }}</x-form.textarea>
                        
                        <x-form.input 
                            name="slug" 
                            label="Slug" 
                            value="{{ old('slug', $article->slug) }}"
                        />
                    </div>
                </x-card>
                
                <!-- Article Stats -->
                <x-card title="Article Statistics" class="!mb-0">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Views:</span>
                            <span class="font-medium">{{ number_format($article->views) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Likes:</span>
                            <span class="font-medium">{{ number_format($article->likes_count) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Comments:</span>
                            <span class="font-medium">{{ $article->comments_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Created:</span>
                            <span class="font-medium">{{ $article->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Last Updated:</span>
                            <span class="font-medium">{{ $article->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
        
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Article
            </button>
        </div>
    </form>
</x-card>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
// Initialize CKEditor
document.addEventListener('DOMContentLoaded', function() {
    CKEDITOR.replace('contentEditor', {
        toolbar: [
            { name: 'document', items: ['Source'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
            '/',
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
            '/',
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize', 'ShowBlocks'] }
        ],
        height: 400
    });
});

// Remove image functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.remove-image').forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.getAttribute('data-id');
            const url = this.getAttribute('data-url').replace(':id', imageId);
            
            if (confirm('Are you sure you want to remove this image?')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Error removing image');
                    }
                });
            }
        });
    });
});
</script>
@endpush
@extends('admin.layouts.admin')

@section('title', 'Create Article')
@section('page-title', 'Create New Article')

@section('content')
<x-card title="Article Information">
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <x-form.input 
                    name="title" 
                    label="Article Title" 
                    required 
                    placeholder="Enter article title"
                    value="{{ old('title') }}"
                />
                
                <x-form.textarea 
                    name="excerpt" 
                    label="Excerpt" 
                    rows="3"
                    placeholder="Brief description of the article"
                >{{ old('excerpt') }}</x-form.textarea>
                
                <div class="form-group">
                    <label class="form-label">Content *</label>
                    <textarea id="contentEditor" class="form-control @error('content') is-invalid @enderror" 
                              name="content" rows="15">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Image Gallery -->
                <div class="form-group">
                    <label class="form-label">Article Images</label>
                    <div class="multi-image-upload" data-preview="imagePreview">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p class="text-gray-500">Drag & drop images here or click to browse</p>
                        <input type="file" multiple accept="image/*" class="hidden" name="images[]">
                    </div>
                    <div id="imagePreview" class="image-preview-container mt-3"></div>
                </div>
            </div>
            
            <!-- Sidebar Settings -->
            <div class="space-y-6">
                <!-- Featured Image -->
                <div class="form-group">
                    <label class="form-label">Featured Image *</label>
                    <div class="featured-image-upload">
                        <i class="fas fa-image"></i>
                        <p class="text-gray-500 text-sm">Click to upload featured image</p>
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
                            :selected="old('category_id')"
                        />
                        
                        <x-form.input 
                            name="tags" 
                            label="Tags" 
                            placeholder="tag1, tag2, tag3"
                            value="{{ old('tags') }}"
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
                            :selected="old('status')"
                        />
                        
                        <x-form.checkbox 
                            name="is_featured" 
                            label="Featured Article" 
                            :checked="old('is_featured')"
                        />
                        
                        <x-form.checkbox 
                            name="allow_comments" 
                            label="Allow Comments" 
                            :checked="old('allow_comments', true)"
                        />
                    </div>
                </x-card>
                
                <!-- SEO Settings -->
                <x-card title="SEO Settings" class="!mb-0">
                    <div class="space-y-4">
                        <x-form.input 
                            name="meta_title" 
                            label="Meta Title" 
                            placeholder="Meta title for SEO"
                            value="{{ old('meta_title') }}"
                        />
                        
                        <x-form.textarea 
                            name="meta_description" 
                            label="Meta Description" 
                            rows="3"
                            placeholder="Meta description for SEO"
                        >{{ old('meta_description') }}</x-form.textarea>
                        
                        <x-form.input 
                            name="slug" 
                            label="Slug" 
                            placeholder="URL-friendly slug"
                            value="{{ old('slug') }}"
                        />
                    </div>
                </x-card>
            </div>
        </div>
        
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Create Article
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
</script>
@endpush
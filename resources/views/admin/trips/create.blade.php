@extends('admin.layouts.admin')

@section('title', 'Create Trip')
@section('page-title', 'Create New Trip')

@section('content')
<x-card title="Trip Information">
    <form action="{{ route('admin.trips.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <x-form.input 
                    name="title" 
                    label="Trip Title" 
                    required 
                    placeholder="Enter trip title"
                    value="{{ old('title') }}"
                />
                
                <x-form.textarea 
                    name="short_description" 
                    label="Short Description" 
                    rows="3"
                    placeholder="Brief description of the trip"
                    required
                >{{ old('short_description') }}</x-form.textarea>
                
                <div class="form-group">
                    <label class="form-label">Full Description *</label>
                    <textarea id="descriptionEditor" class="form-control @error('description') is-invalid @enderror" 
                              name="description" rows="10" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Trip Highlights -->
                <div class="form-group">
                    <label class="form-label">Trip Highlights</label>
                    <div id="highlightsContainer">
                        @if(old('highlights'))
                            @foreach(old('highlights') as $highlight)
                            <div class="flex gap-2 mb-2">
                                <input type="text" name="highlights[]" value="{{ $highlight }}" 
                                       class="form-control" placeholder="Enter highlight">
                                <x-button type="button" variant="danger" size="sm" onclick="removeHighlight(this)">
                                    <i class="fas fa-times"></i>
                                </x-button>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <x-button type="button" variant="outline-primary" size="sm" onclick="addHighlight()" class="mt-2">
                        <i class="fas fa-plus"></i> Add Highlight
                    </x-button>
                </div>
                
                <!-- Image Gallery -->
                <div class="form-group">
                    <label class="form-label">Trip Images</label>
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
                        <input type="file" accept="image/*" class="hidden" name="featured_image" required>
                    </div>
                    <div class="featured-image-preview hidden mt-3">
                        <img class="w-full h-48 object-cover rounded">
                    </div>
                </div>
                
                <!-- Trip Details -->
                <x-card title="Trip Details" class="!mb-0">
                    <div class="space-y-4">
                        <x-form.select 
                            name="city_id" 
                            label="City" 
                            :options="$cities->pluck('name', 'id')" 
                            required 
                            :selected="old('city_id')"
                        />
                        
                        <x-form.input 
                            name="price" 
                            label="Price ($)" 
                            type="number" 
                            step="0.01"
                            min="0"
                            required 
                            value="{{ old('price') }}"
                        />
                        
                        <x-form.input 
                            name="duration_hours" 
                            label="Duration (hours)" 
                            type="number" 
                            min="1"
                            required 
                            value="{{ old('duration_hours') }}"
                        />
                        
                        <x-form.input 
                            name="max_capacity" 
                            label="Maximum Capacity" 
                            type="number" 
                            min="1"
                            required 
                            value="{{ old('max_capacity') }}"
                        />
                    </div>
                </x-card>
                
                <!-- Trip Settings -->
                <x-card title="Trip Settings" class="!mb-0">
                    <div class="space-y-4">
                        <x-form.checkbox 
                            name="is_active" 
                            label="Active Trip" 
                            :checked="old('is_active', true)"
                        />
                        
                        <x-form.checkbox 
                            name="is_featured" 
                            label="Featured Trip" 
                            :checked="old('is_featured')"
                        />
                        
                        <x-form.checkbox 
                            name="allow_bookings" 
                            label="Allow Bookings" 
                            :checked="old('allow_bookings', true)"
                        />
                    </div>
                </x-card>
                
                <!-- Trip Schedule -->
                <x-card title="Trip Schedule" class="!mb-0">
                    <div class="space-y-4">
                        <x-form.input 
                            name="start_time" 
                            label="Start Time" 
                            type="time" 
                            required 
                            value="{{ old('start_time', '08:00') }}"
                        />
                        
                        <x-form.input 
                            name="meeting_point" 
                            label="Meeting Point" 
                            value="{{ old('meeting_point') }}"
                            placeholder="Where participants should meet"
                        />
                    </div>
                </x-card>
            </div>
        </div>
        
        <div class="flex justify-end gap-3 mt-6">
            <x-button href="{{ route('admin.trips.index') }}" variant="secondary">
                Cancel
            </x-button>
            <x-button type="submit" variant="primary">
                <i class="fas fa-save"></i> Create Trip
            </x-button>
        </div>
    </form>
</x-card>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
// Initialize CKEditor
document.addEventListener('DOMContentLoaded', function() {
    CKEDITOR.replace('descriptionEditor', {
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
        height: 300
    });
});

// Highlights management
function addHighlight() {
    const container = document.getElementById('highlightsContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="highlights[]" class="form-control" placeholder="Enter highlight">
        <x-button type="button" variant="danger" size="sm" onclick="removeHighlight(this)">
            <i class="fas fa-times"></i>
        </x-button>
    `;
    container.appendChild(div);
}

function removeHighlight(button) {
    button.closest('.flex').remove();
}
</script>
@endpush
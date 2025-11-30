@extends('admin.layouts.admin')

@section('title', 'Edit Trip')
@section('page-title', 'Edit Trip: ' . $trip->title)

@section('content')
<x-card title="Edit Trip Information">
    <form action="{{ route('admin.trips.update', $trip) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <x-form.input 
                    name="title" 
                    label="Trip Title" 
                    required 
                    value="{{ old('title', $trip->title) }}"
                />
                
                <x-form.textarea 
                    name="short_description" 
                    label="Short Description" 
                    rows="3"
                    required
                >{{ old('short_description', $trip->short_description) }}</x-form.textarea>
                
                <div class="form-group">
                    <label class="form-label">Full Description *</label>
                    <textarea id="descriptionEditor" class="form-control @error('description') is-invalid @enderror" 
                              name="description" rows="10" required>{{ old('description', $trip->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Trip Highlights -->
                <div class="form-group">
                    <label class="form-label">Trip Highlights</label>
                    <div id="highlightsContainer">
                        @foreach($trip->highlights ?? [] as $highlight)
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="highlights[]" value="{{ $highlight }}" 
                                   class="form-control" placeholder="Enter highlight">
                            <x-button type="button" variant="danger" size="sm" onclick="removeHighlight(this)">
                                <i class="fas fa-times"></i>
                            </x-button>
                        </div>
                        @endforeach
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
                
                <!-- Existing Images -->
                <div class="form-group">
                    <label class="form-label">Trip Images</label>
                    <div class="grid grid-cols-4 gap-3 mt-3">
                        @foreach($trip->images as $image)
                        <div class="relative">
                            <img src="{{ asset($image->path) }}" class="w-full h-24 object-cover rounded">
                            <x-button 
                                type="button" 
                                variant="danger" 
                                size="sm"
                                class="remove-image absolute top-1 left-1 !p-1 !w-6 !h-6"
                                data-id="{{ $image->id }}"
                                data-url="{{ route('admin.trip-images.destroy', ':id') }}"
                            >
                                <i class="fas fa-times text-xs"></i>
                            </x-button>
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
                    @if($trip->featured_image)
                    <div class="mb-3">
                        <img src="{{ asset($trip->featured_image) }}" alt="Current featured image" 
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
                
                <!-- Trip Details -->
                <x-card title="Trip Details" class="!mb-0">
                    <div class="space-y-4">
                        <x-form.select 
                            name="city_id" 
                            label="City" 
                            :options="$cities->pluck('name', 'id')" 
                            required 
                            :selected="old('city_id', $trip->city_id)"
                        />
                        
                        <x-form.input 
                            name="price" 
                            label="Price ($)" 
                            type="number" 
                            step="0.01"
                            min="0"
                            required 
                            value="{{ old('price', $trip->price) }}"
                        />
                        
                        <x-form.input 
                            name="duration_hours" 
                            label="Duration (hours)" 
                            type="number" 
                            min="1"
                            required 
                            value="{{ old('duration_hours', $trip->duration_hours) }}"
                        />
                        
                        <x-form.input 
                            name="max_capacity" 
                            label="Maximum Capacity" 
                            type="number" 
                            min="1"
                            required 
                            value="{{ old('max_capacity', $trip->max_capacity) }}"
                        />
                    </div>
                </x-card>
                
                <!-- Trip Settings -->
                <x-card title="Trip Settings" class="!mb-0">
                    <div class="space-y-4">
                        <x-form.checkbox 
                            name="is_active" 
                            label="Active Trip" 
                            :checked="old('is_active', $trip->is_active)"
                        />
                        
                        <x-form.checkbox 
                            name="is_featured" 
                            label="Featured Trip" 
                            :checked="old('is_featured', $trip->is_featured)"
                        />
                        
                        <x-form.checkbox 
                            name="allow_bookings" 
                            label="Allow Bookings" 
                            :checked="old('allow_bookings', $trip->allow_bookings)"
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
                            value="{{ old('start_time', $trip->start_time) }}"
                        />
                        
                        <x-form.input 
                            name="meeting_point" 
                            label="Meeting Point" 
                            value="{{ old('meeting_point', $trip->meeting_point) }}"
                        />
                    </div>
                </x-card>
                
                <!-- Trip Statistics -->
                <x-card title="Trip Statistics" class="!mb-0">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span>Total Bookings:</span>
                            <span class="font-semibold text-blue-600">{{ $trip->bookings_count }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Available Spots:</span>
                            <span class="font-semibold {{ $trip->available_capacity < 10 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $trip->available_capacity }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Average Rating:</span>
                            <span class="font-semibold text-yellow-600">{{ $trip->average_rating }}/5</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Total Revenue:</span>
                            <span class="font-semibold text-green-600">${{ number_format($trip->total_revenue, 2) }}</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
        
        <div class="flex justify-end gap-3 mt-6">
            <x-button href="{{ route('admin.trips.index') }}" variant="secondary">
                Cancel
            </x-button>
            <x-button type="submit" variant="primary">
                <i class="fas fa-save"></i> Update Trip
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
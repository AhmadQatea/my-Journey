@extends('admin.layouts.admin')

@section('title', 'Trip Details')
@section('page-title', 'Trip Details: ' . $trip->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Trip Content -->
    <div class="lg:col-span-2">
        <x-card>
            <!-- Featured Image -->
            @if($trip->featured_image)
            <div class="mb-6">
                <img src="{{ asset($trip->featured_image) }}" alt="{{ $trip->title }}" 
                     class="w-full h-64 object-cover rounded-lg">
            </div>
            @endif
            
            <!-- Trip Meta -->
            <div class="flex flex-wrap items-center gap-4 mb-6 text-sm text-gray-500">
                <div class="flex items-center gap-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $trip->city->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock"></i>
                    <span>{{ $trip->duration_hours }} hours</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-users"></i>
                    <span>{{ $trip->available_capacity }} spots available</span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-star"></i>
                    <span>{{ $trip->average_rating }}/5 ({{ $trip->reviews_count }} reviews)</span>
                </div>
            </div>
            
            <!-- Trip Description -->
            <div class="prose max-w-none mb-6">
                {!! $trip->description !!}
            </div>
            
            <!-- Trip Highlights -->
            @if($trip->highlights && count($trip->highlights) > 0)
            <div class="mb-6">
                <h4 class="font-semibold text-lg mb-3">Trip Highlights</h4>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($trip->highlights as $highlight)
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check text-green-500"></i>
                        <span>{{ $highlight }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <!-- Trip Images Gallery -->
            @if($trip->images->count() > 0)
            <div class="mt-6">
                <h4 class="font-semibold mb-3">Trip Images</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($trip->images as $image)
                    <img src="{{ asset($image->path) }}" alt="Trip image" 
                         class="w-full h-32 object-cover rounded-lg cursor-pointer" 
                         onclick="openImageModal('{{ asset($image->path) }}')">
                    @endforeach
                </div>
            </div>
            @endif
        </x-card>
        
        <!-- Recent Bookings -->
        <x-card title="Recent Bookings" class="mt-6">
            <div class="space-y-3">
                @foreach($recentBookings as $booking)
                <div class="flex items-center justify-between p-3 border rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center text-sm font-bold">
                            {{ substr($booking->user->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-medium">{{ $booking->user->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-green-600">${{ number_format($booking->total_price, 2) }}</div>
                        <x-badge variant="{{ $booking->status === 'confirmed' ? 'success' : 'warning' }}">
                            {{ ucfirst($booking->status) }}
                        </x-badge>
                    </div>
                </div>
                @endforeach
                
                @if($trip->bookings_count == 0)
                <p class="text-gray-500 text-center py-4">No bookings yet.</p>
                @endif
            </div>
            
            @if($trip->bookings_count > 5)
            <div class="mt-4 text-center">
                <x-button href="{{ route('admin.bookings.index', ['trip' => $trip->id]) }}" variant="outline-primary" size="sm">
                    View All Bookings
                </x-button>
            </div>
            @endif
        </x-card>
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Trip Info -->
        <x-card title="Trip Information">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="font-medium">Status:</span>
                    <x-badge variant="{{ $trip->is_active ? 'success' : 'danger' }}">
                        {{ $trip->is_active ? 'Active' : 'Inactive' }}
                    </x-badge>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Featured:</span>
                    <span>
                        @if($trip->is_featured)
                            <i class="fas fa-check text-green-500"></i>
                        @else
                            <i class="fas fa-times text-red-500"></i>
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Bookings Allowed:</span>
                    <span>
                        @if($trip->allow_bookings)
                            <i class="fas fa-check text-green-500"></i>
                        @else
                            <i class="fas fa-times text-red-500"></i>
                        @endif
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Start Time:</span>
                    <span>{{ \Carbon\Carbon::parse($trip->start_time)->format('h:i A') }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Meeting Point:</span>
                    <span class="text-right">{{ $trip->meeting_point ?? 'Not specified' }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Created:</span>
                    <span>{{ $trip->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </x-card>
        
        <!-- Pricing & Capacity -->
        <x-card title="Pricing & Capacity">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="font-medium">Price:</span>
                    <span class="text-2xl font-bold text-green-600">${{ number_format($trip->price, 2) }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium">Capacity:</span>
                    <span>{{ $trip->available_capacity }}/{{ $trip->max_capacity }}</span>
                </div>
                
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" 
                         style="width: {{ ($trip->bookings_count / $trip->max_capacity) * 100 }}%"></div>
                </div>
                
                <div class="text-center text-sm text-gray-500">
                    {{ number_format(($trip->bookings_count / $trip->max_capacity) * 100, 1) }}% booked
                </div>
            </div>
        </x-card>
        
        <!-- Actions -->
        <x-card title="Actions">
            <div class="space-y-2">
                <x-button href="{{ route('admin.trips.edit', $trip) }}" variant="warning" class="w-full">
                    <i class="fas fa-edit"></i> Edit Trip
                </x-button>
                
                @if($trip->is_active)
                <form action="{{ route('admin.trips.deactivate', $trip) }}" method="POST" class="w-full">
                    @csrf
                    <x-button type="submit" variant="secondary" class="w-full">
                        <i class="fas fa-pause"></i> Deactivate
                    </x-button>
                </form>
                @else
                <form action="{{ route('admin.trips.activate', $trip) }}" method="POST" class="w-full">
                    @csrf
                    <x-button type="submit" variant="success" class="w-full">
                        <i class="fas fa-play"></i> Activate
                    </x-button>
                </form>
                @endif
                
                @if($trip->allow_bookings)
                <form action="{{ route('admin.trips.disable-bookings', $trip) }}" method="POST" class="w-full">
                    @csrf
                    <x-button type="submit" variant="secondary" class="w-full">
                        <i class="fas fa-ban"></i> Disable Bookings
                    </x-button>
                </form>
                @else
                <form action="{{ route('admin.trips.enable-bookings', $trip) }}" method="POST" class="w-full">
                    @csrf
                    <x-button type="submit" variant="success" class="w-full">
                        <i class="fas fa-check"></i> Enable Bookings
                    </x-button>
                </form>
                @endif
            </div>
        </x-card>
        
        <!-- Quick Stats -->
        <div class="grid grid-cols-2 gap-4">
            <div class="stat-card text-center">
                <div class="stat-number text-blue-600">{{ $trip->bookings_count }}</div>
                <div class="stat-label">Total Bookings</div>
            </div>
            
            <div class="stat-card text-center">
                <div class="stat-number text-green-600">${{ number_format($trip->total_revenue, 2) }}</div>
                <div class="stat-label">Total Revenue</div>
            </div>
            
            <div class="stat-card text-center">
                <div class="stat-number text-purple-600">{{ $trip->reviews_count }}</div>
                <div class="stat-label">Reviews</div>
            </div>
            
            <div class="stat-card text-center">
                <div class="stat-number text-yellow-600">{{ $trip->average_rating }}/5</div>
                <div class="stat-label">Avg Rating</div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal">
    <div class="modal-content" style="max-width: 90vw; max-height: 90vh;">
        <div class="modal-header">
            <x-button type="button" variant="secondary" size="sm" class="modal-close" data-modal-hide="imageModal">
                <i class="fas fa-times"></i>
            </x-button>
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
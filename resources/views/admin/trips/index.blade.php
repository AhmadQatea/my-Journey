@extends('admin.layouts.admin')

@section('title', 'Trips Management')
@section('page-title', 'Trips Management')

@section('content')
<x-card>
    <x-slot:actions>
        <div class="flex gap-3 flex-wrap">
            <div class="search-box">
                <input type="text" class="form-control search-input" placeholder="Search trips...">
                <i class="fas fa-search search-icon"></i>
            </div>
            <select class="form-control filter-select" onchange="filterByCity(this.value)">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                <option value="{{ $city->id }}">{{ $city->name }}</option>
                @endforeach
            </select>
            <select class="form-control filter-select" onchange="filterByStatus(this.value)">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <x-button href="{{ route('admin.trips.create') }}" variant="primary">
                <i class="fas fa-plus"></i> Add Trip
            </x-button>
        </div>
    </x-slot:actions>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Trip Name</th>
                    <th data-city="true">City</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Capacity</th>
                    <th>Bookings</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trips as $trip)
                <tr>
                    <td>
                        <img src="{{ asset($trip->featured_image) }}" alt="{{ $trip->title }}" 
                             class="w-12 h-12 rounded object-cover">
                    </td>
                    <td>
                        <div>
                            <h4 class="font-medium">{{ $trip->title }}</h4>
                            <p class="text-sm text-gray-500">{{ Str::limit($trip->short_description, 50) }}</p>
                        </div>
                    </td>
                    <td data-city="{{ $trip->city_id }}">
                        <x-badge variant="info">{{ $trip->city->name }}</x-badge>
                    </td>
                    <td>${{ number_format($trip->price, 2) }}</td>
                    <td>{{ $trip->duration_hours }}h</td>
                    <td>
                        <div class="text-center">
                            <span class="font-semibold {{ $trip->available_capacity < 10 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $trip->available_capacity }}/{{ $trip->max_capacity }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="text-center">
                            <span class="font-semibold text-blue-600">{{ $trip->bookings_count }}</span>
                        </div>
                    </td>
                    <td>
                        <x-badge variant="{{ $trip->is_active ? 'success' : 'danger' }}">
                            {{ $trip->is_active ? 'Active' : 'Inactive' }}
                        </x-badge>
                    </td>
                    <td>{{ $trip->created_at->format('M d, Y') }}</td>
                    <td>
                        <div class="action-buttons">
                            <x-button href="{{ route('admin.trips.show', $trip) }}" variant="info" size="sm">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button href="{{ route('admin.trips.edit', $trip) }}" variant="warning" size="sm">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button 
                                class="delete-item" 
                                variant="danger" 
                                size="sm"
                                data-id="{{ $trip->id }}"
                                data-type="trip"
                                data-url="{{ route('admin.trips.destroy', ':id') }}"
                            >
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $trips->links() }}
    </div>
</x-card>

<!-- Trip Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #3b82f6;">
            <i class="fas fa-map-marked-alt"></i>
        </div>
        <div class="stat-number">{{ $totalTrips }}</div>
        <div class="stat-label">Total Trips</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #10b981;">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-number">{{ $activeTrips }}</div>
        <div class="stat-label">Active Trips</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
            <i class="fas fa-city"></i>
        </div>
        <div class="stat-number">{{ $totalCities }}</div>
        <div class="stat-label">Cities</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fce7f3; color: #ec4899;">
            <i class="fas fa-star"></i>
        </div>
        <div class="stat-number">{{ $featuredTrips }}</div>
        <div class="stat-label">Featured Trips</div>
    </div>
</div>

<!-- Popular Trips -->
<x-card title="Popular Trips" class="mt-6">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($popularTrips as $trip)
        <div class="trip-card bg-white rounded-lg shadow-md overflow-hidden">
            <div class="trip-image relative">
                <img src="{{ asset($trip->featured_image) }}" 
                     alt="{{ $trip->title }}" class="w-full h-48 object-cover">
                @if($trip->is_featured)
                <div class="absolute top-3 left-3 bg-yellow-500 text-white px-2 py-1 rounded text-xs">
                    <i class="fas fa-star"></i> Featured
                </div>
                @endif
            </div>
            <div class="p-4">
                <h4 class="font-bold text-lg mb-2">{{ $trip->title }}</h4>
                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($trip->short_description, 80) }}</p>
                
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <span class="text-2xl font-bold text-green-600">${{ number_format($trip->price, 2) }}</span>
                    </div>
                    <x-badge variant="info">{{ $trip->city->name }}</x-badge>
                </div>
                
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <div>
                        <i class="fas fa-clock"></i> {{ $trip->duration_hours }}h
                    </div>
                    <div>
                        <i class="fas fa-users"></i> {{ $trip->bookings_count }} bookings
                    </div>
                    <div>
                        <i class="fas fa-star"></i> {{ $trip->average_rating }}/5
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-card>
@endsection
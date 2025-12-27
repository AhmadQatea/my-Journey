@extends('website.pages.layouts.app')

@section('title', $trip->title . ' - MyJourney')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .trip-show-page {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .trip-hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 3rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .trip-hero-section h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .trip-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-top: 1.5rem;
        font-size: 1rem;
    }

    .trip-meta span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.2);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        backdrop-filter: blur(10px);
    }

    .trip-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .trip-details-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .trip-details-grid {
            grid-template-columns: 1fr;
        }
    }

    .trip-main-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .trip-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .trip-card {
        background: var(--color-gray-500);
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
    }

    .trip-card h3 {
        font-size: 1.5rem;
        margin-bottom: 1.5rem;
        color: var(--color-white);
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .trip-card h3 i {
        color: #667eea;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item label {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 600;
    }

    .info-item span {
        font-size: 1rem;
        color: #0f172a;
        font-weight: 700;
    }

    .price-badge {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        text-align: center;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .trip-images-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .trip-image-item {
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 16/9;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .trip-image-item:hover {
        transform: scale(1.05);
    }

    .trip-image-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .tourist-spots-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .tourist-spot-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #e2e8f0;
        border-left: 3px solid #667eea;
        transition: all 0.3s;
    }

    .tourist-spot-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        transform: translateY(-4px);
    }

    .tourist-spot-card h5 {
        font-size: 1.125rem;
        margin-bottom: 0.75rem;
        color: #111827;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tourist-spot-card h5 i {
        color: #667eea;
    }

    #tripMap {
        height: 500px;
        width: 100%;
        border-radius: 12px;
        margin-top: 1rem;
    }

    .offers-section {
        margin-top: 2rem;
    }

    .offers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .offer-card {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 2px solid #fbbf24;
    }

    .offer-card h5 {
        font-size: 1.25rem;
        margin-bottom: 0.75rem;
        color: #92400e;
        font-weight: 700;
    }

    .offer-discount {
        background: #dc2626;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        display: inline-block;
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="trip-show-page">
    <!-- Hero Section -->
    <div class="trip-hero-section">
        <h1>
            <i class="fas fa-map-marked-alt"></i>
            {{ $trip->title }}
        </h1>
        <div class="trip-meta">
            <span>
                <i class="fas fa-map-marker-alt"></i>
                {{ $trip->governorate->name ?? 'غير محدد' }}
            </span>
            @if($trip->departureGovernorate)
                <span>
                    <i class="fas fa-plane-departure"></i>
                    انطلاق من: {{ $trip->departureGovernorate->name }}
                </span>
            @endif
            <span>
                <i class="fas fa-clock"></i>
                {{ $trip->duration_hours }} ساعة
            </span>
            <span>
                <i class="fas fa-users"></i>
                حتى {{ $trip->max_persons }} شخص
            </span>
            @if($trip->trip_type)
                <span>
                    <i class="fas fa-tag"></i>
                    {{ $trip->trip_type }}
                </span>
            @endif
        </div>
        <div class="trip-actions">
            <a href="{{ route('bookings.create', ['trip_id' => $trip->id]) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-calendar-check"></i>
                {{ __('messages.book_trip') }}
            </a>
            <a href="{{ route('trips') }}" class="btn btn-outline btn-lg" style="background: rgba(255,255,255,0.2); color: white; border-color: rgba(255,255,255,0.6);">
                <i class="fas fa-arrow-right"></i>
                {{ __('messages.back') }} {{ __('messages.trips') }}
            </a>
        </div>
    </div>

    <div class="trip-details-grid">
        <!-- Main Content -->
        <div class="trip-main-content">
            <!-- Description -->
            @if($trip->description)
                <div class="trip-card">
                    <h3>
                        <i class="fas fa-align-right"></i>
                        {{ __('messages.trip_description') }}
                    </h3>
                    <div style="color: #1e293b; line-height: 1.9; font-size: 1.05rem;">
                        {!! $trip->description !!}
                    </div>
                </div>
            @endif

            <!-- Images Gallery -->
            @if($trip->images && count($trip->images) > 0)
                <div class="trip-card">
                    <h3>
                        <i class="fas fa-images"></i>
                        {{ __('messages.trip_images') }}
                    </h3>
                    <div class="trip-images-gallery">
                        @foreach($trip->images as $image)
                            @php
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
                                <div class="trip-image-item">
                                    <img src="{{ $imageUrl }}" alt="{{ $trip->title }}">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tourist Spots -->
            @if($touristSpots->count() > 0)
                <div class="trip-card">
                    <h3>
                        <i class="fas fa-map-pin"></i>
                        {{ __('messages.included_places') }}
                    </h3>
                    <div class="tourist-spots-grid">
                        @foreach($touristSpots as $spot)
                            <div class="tourist-spot-card">
                                <h5>
                                    <i class="fas fa-landmark"></i>
                                    {{ $spot->name }}
                                </h5>
                                @if($spot->description)
                                    <p style="color: #6b7280; font-size: 0.875rem; line-height: 1.6; margin-bottom: 0.75rem;">
                                        {{ Str::limit($spot->description, 100) }}
                                    </p>
                                @endif
                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; font-size: 0.875rem; color: #64748b;">
                                    @if($spot->governorate)
                                        <span>
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $spot->governorate->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Passing Governorates -->
            @if($passingGovernorates->count() > 0)
                <div class="trip-card">
                    <h3>
                        <i class="fas fa-route"></i>
                        {{ __('messages.passing_governorates') }}
                    </h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
                        @foreach($passingGovernorates as $governorate)
                            <span style="background: #e0e7ff; color: #4338ca; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600;">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $governorate->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Map -->
            @if($touristSpots->count() > 0)
                @php
                    $hasCoordinates = $touristSpots->filter(function($spot) {
                        return $spot->coordinates && !empty(trim($spot->coordinates));
                    })->count() > 0;
                @endphp
                @if($hasCoordinates)
                    <div class="trip-card">
                        <h3>
                            <i class="fas fa-map"></i>
                            {{ __('messages.trip_map') }}
                        </h3>
                        <div id="tripMap" style="height: 500px; width: 100%; border-radius: 12px; margin-top: 1rem;"></div>

                        <!-- Route Info -->
                        <div id="routeInfo" style="display: none; margin-top: 1rem; padding: 1rem; background: #f0f9ff; border-radius: 8px; border-right: 4px solid #4361ee;">
                            <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
                                <div>
                                    <span style="color: #64748b; font-size: 0.875rem;">المسافة:</span>
                                    <strong id="routeDistance" style="color: #0f172a; margin-right: 0.5rem;"></strong>
                                </div>
                                <div>
                                    <span style="color: #64748b; font-size: 0.875rem;">المدة:</span>
                                    <strong id="routeDuration" style="color: #0f172a; margin-right: 0.5rem;"></strong>
                                </div>
                            </div>
                        </div>

                        <!-- Hotels Section -->
                        <div id="hotelsSection" style="display: none; margin-top: 1.5rem;">
                            <h4 style="font-size: 1.125rem; margin-bottom: 1rem; color: #0f172a; font-weight: 700;">
                                <i class="fas fa-hotel" style="color: #f59e0b;"></i>
                                الفنادق القريبة من المسار
                            </h4>
                            <div id="hotelsList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem;"></div>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Sidebar -->
        <div class="trip-sidebar">
            <!-- Price Card -->
            <div class="trip-card">
                <div class="price-badge">
                    <div style="font-size: 0.875rem; opacity: 0.9; margin-bottom: 0.25rem;">السعر</div>
                    <div>{{ number_format($trip->price) }} ل.س</div>
                </div>
            </div>

            <!-- Trip Details -->
            <div class="trip-card">
                <h3>
                    <i class="fas fa-info-circle"></i>
                    {{ __('messages.trip_details') }}
                </h3>
                <div class="info-item">
                    <label>المحافظة</label>
                    <span>{{ $trip->governorate->name ?? 'غير محدد' }}</span>
                </div>
                @if($trip->departureGovernorate)
                    <div class="info-item">
                        <label>محافظة الانطلاق</label>
                        <span>{{ $trip->departureGovernorate->name }}</span>
                    </div>
                @endif
                <div class="info-item">
                    <label>المدة</label>
                    <span>{{ $trip->duration_hours }} ساعة</span>
                </div>
                <div class="info-item">
                    <label>الحد الأقصى للأشخاص</label>
                    <span>{{ $trip->max_persons }} شخص</span>
                </div>
                @if($trip->trip_type)
                    <div class="info-item">
                        <label>نوع الرحلة</label>
                        <span>{{ $trip->trip_type }}</span>
                    </div>
                @endif
                @if($trip->trip_types && count($trip->trip_types) > 0)
                    <div class="info-item">
                        <label>أنواع الرحلة</label>
                        <span>{{ implode('، ', $trip->trip_types) }}</span>
                    </div>
                @endif
                @if($trip->start_date)
                    <div class="info-item">
                        <label>تاريخ البدء</label>
                        <span>{{ \Carbon\Carbon::parse($trip->start_date)->format('Y-m-d') }}</span>
                    </div>
                @endif
            </div>

            <!-- Offers -->
            @if($offers->count() > 0)
                <div class="trip-card offers-section">
                    <h3>
                        <i class="fas fa-tags"></i>
                        عروض خاصة
                    </h3>
                    <div class="offers-grid">
                        @foreach($offers as $offer)
                            <div class="offer-card">
                                <h5>{{ $offer->title }}</h5>
                                @if($offer->description)
                                    <p style="color: #92400e; font-size: 0.875rem; margin-bottom: 0.5rem;">
                                        {{ Str::limit($offer->description, 80) }}
                                    </p>
                                @endif
                                @if($offer->discount_percentage)
                                    <div class="offer-discount">
                                        خصم {{ $offer->discount_percentage }}%
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    @if($touristSpots->count() > 0)
        @php
            $hasCoordinates = $touristSpots->filter(function($spot) {
                return $spot->coordinates && !empty(trim($spot->coordinates));
            })->count() > 0;
        @endphp
        @if($hasCoordinates)
            document.addEventListener('DOMContentLoaded', function() {
                const mapElement = document.getElementById('tripMap');
                if (!mapElement) return;

                // Initialize map
                const map = L.map('tripMap').setView([33.5138, 36.2765], 7);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                }).addTo(map);

                // Collect coordinates
                const coordinates = [];
                const markers = [];

                @foreach($touristSpots as $spot)
                    @if($spot->coordinates && !empty(trim($spot->coordinates)))
                        @php
                            $coords = \App\Services\MapService::parseCoordinates($spot->coordinates);
                        @endphp
                        @if($coords)
                            coordinates.push({
                                lat: {{ $coords['lat'] }},
                                lng: {{ $coords['lng'] }},
                                name: '{{ addslashes($spot->name) }}',
                                governorate: '{{ addslashes($spot->governorate->name ?? '') }}'
                            });
                        @endif
                    @endif
                @endforeach

                // Add departure point if available
                @if($trip->departureGovernorate && $trip->departureGovernorate->coordinates)
                    @php
                        $departureCoords = \App\Services\MapService::parseCoordinates($trip->departureGovernorate->coordinates);
                    @endphp
                    @if($departureCoords)
                        const departurePoint = {
                            lat: {{ $departureCoords['lat'] }},
                            lng: {{ $departureCoords['lng'] }},
                            name: '{{ addslashes($trip->departureGovernorate->name) }} - نقطة الانطلاق'
                        };
                        coordinates.unshift(departurePoint);
                    @endif
                @endif

                // Add markers
                coordinates.forEach((coord, index) => {
                    const isDeparture = index === 0 && {{ $trip->departureGovernorate && $trip->departureGovernorate->coordinates ? 'true' : 'false' }};
                    const iconColor = isDeparture ? 'red' : 'blue';
                    const icon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background-color: ${iconColor}; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });

                    const marker = L.marker([coord.lat, coord.lng], { icon: icon }).addTo(map);
                    marker.bindPopup(`
                        <div style="text-align: right;">
                            <strong>${coord.name}</strong>
                            ${coord.governorate ? '<br><small style="color: #64748b;">' + coord.governorate + '</small>' : ''}
                        </div>
                    `);
                    markers.push(marker);
                });

                // Fetch route if we have multiple points
                if (coordinates.length >= 2) {
                    fetch('{{ route("api.map.trip.route", $trip) }}')
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.route && data.route.geometry) {
                                // Draw route
                                const routeLayer = L.geoJSON(data.route.geometry, {
                                    style: {
                                        color: '#4361ee',
                                        weight: 5,
                                        opacity: 0.8
                                    }
                                }).addTo(map);

                                // Fit map to show all markers and route
                                if (markers.length > 0) {
                                    const group = new L.featureGroup([...markers, routeLayer]);
                                    map.fitBounds(group.getBounds().pad(0.1));
                                }

                                // Display route info
                                const routeInfo = document.getElementById('routeInfo');
                                const routeDistance = document.getElementById('routeDistance');
                                const routeDuration = document.getElementById('routeDuration');

                                if (routeInfo && routeDistance && routeDuration) {
                                    routeInfo.style.display = 'block';
                                    routeDistance.textContent = data.route.distance + ' كم';
                                    routeDuration.textContent = data.route.duration_hours + ' ساعة';
                                }

                                // Display hotels
                                if (data.hotels && data.hotels.length > 0) {
                                    const hotelsSection = document.getElementById('hotelsSection');
                                    const hotelsList = document.getElementById('hotelsList');

                                    if (hotelsSection && hotelsList) {
                                        hotelsSection.style.display = 'block';

                                        data.hotels.forEach(hotel => {
                                            const hotelName = hotel.name_ar || hotel.name;
                                            const hotelCard = document.createElement('div');
                                            hotelCard.className = 'hotel-card';
                                            hotelCard.style.cssText = 'background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; border-left: 3px solid #f59e0b;';
                                            hotelCard.innerHTML = `
                                                <h5 style="margin-bottom: 0.5rem; color: #111827; font-weight: 700; font-size: 1rem;">
                                                    <i class="fas fa-hotel" style="color: #f59e0b;"></i>
                                                    ${hotelName}
                                                </h5>
                                                ${hotel.address ? '<p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 0.5rem;"><i class="fas fa-map-marker-alt"></i> ' + hotel.address + '</p>' : ''}
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                                                    <span style="font-size: 0.75rem; color: #64748b;">
                                                        <i class="fas fa-ruler"></i> ${(hotel.distance / 1000).toFixed(1)} كم
                                                    </span>
                                                    ${hotel.phone ? '<a href="tel:' + hotel.phone + '" style="font-size: 0.75rem; color: #4361ee;"><i class="fas fa-phone"></i></a>' : ''}
                                                </div>
                                            `;
                                            hotelsList.appendChild(hotelCard);

                                            // Add hotel marker
                                            L.marker([hotel.lat, hotel.lng], {
                                                icon: L.divIcon({
                                                    className: 'hotel-marker',
                                                    html: '<div style="background-color: #f59e0b; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;"><i class="fas fa-hotel"></i></div>',
                                                    iconSize: [24, 24],
                                                    iconAnchor: [12, 12]
                                                })
                                            }).addTo(map)
                                            .bindPopup(`
                                                <div style="text-align: right;">
                                                    <strong>${hotelName}</strong><br>
                                                    ${hotel.address ? '<small>' + hotel.address + '</small><br>' : ''}
                                                    <small style="color: #f59e0b;">${(hotel.distance / 1000).toFixed(1)} كم من المسار</small>
                                                </div>
                                            `);
                                        });
                                    }
                                }
                            } else {
                                // If route calculation fails, just fit to markers
                                if (markers.length > 0) {
                                    const group = new L.featureGroup(markers);
                                    map.fitBounds(group.getBounds().pad(0.1));
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching route:', error);
                            // Fit to markers if route fails
                            if (markers.length > 0) {
                                const group = new L.featureGroup(markers);
                                map.fitBounds(group.getBounds().pad(0.1));
                            }
                        });
                } else if (markers.length > 0) {
                    // If only one marker, center on it
                    map.setView([coordinates[0].lat, coordinates[0].lng], 12);
                }
            });
        @endif
    @endif
</script>
@endpush


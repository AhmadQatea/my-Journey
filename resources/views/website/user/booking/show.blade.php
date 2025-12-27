@extends('website.user.layouts.user')

@section('title', 'تفاصيل الحجز - MyJourney')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/booking.css') }}">
<style>
.booking-detail-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.booking-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.booking-header-section h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.booking-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
}

.trip-details-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .trip-details-section {
        grid-template-columns: 1fr;
    }
}

.trip-info-card, .booking-info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
}

.trip-info-card::before, .booking-info-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.trip-info-card h3, .booking-info-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    color: #0f172a;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.trip-info-card h3 i, .booking-info-card h3 i {
    color: #667eea;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.info-item label {
    font-size: 0.875rem;
    color: #475569;
    font-weight: 600;
}

.info-item span {
    font-size: 1rem;
    color: #0f172a;
    font-weight: 700;
}

.total-price {
    color: #10b981;
    font-size: 1.25rem;
}

.trip-images-gallery {
    margin-top: 2rem;
}

.trip-images-gallery h4 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #0f172a;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.images-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.image-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    aspect-ratio: 16/9;
    cursor: pointer;
    transition: transform 0.3s;
}

.image-item:hover {
    transform: scale(1.05);
}

.image-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.tourist-spots-section {
    margin-top: 2rem;
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

.tourist-spot-card p {
    color: #6b7280;
    font-size: 0.875rem;
    line-height: 1.6;
    margin-bottom: 0.75rem;
}

.tourist-spot-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e5e7eb;
}

.tourist-spot-meta span {
    font-size: 0.875rem;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 0.375rem;
    font-weight: 600;
}

.tourist-spot-meta i {
    color: #667eea;
}

.features-section {
    margin-top: 2rem;
}

.features-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.feature-item i {
    color: #10b981;
    font-size: 1.125rem;
}

.feature-item span {
    color: #0f172a;
    font-weight: 600;
}

.governorates-section {
    margin-top: 2rem;
}

.governorates-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-top: 1rem;
}

.governorate-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.875rem;
}

.special-requests-card, .rejection-notice, .admin-notes {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    margin-top: 2rem;
    position: relative;
    overflow: hidden;
}

.special-requests-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
}

.rejection-notice {
    border-left: 4px solid #ef4444;
    background: #fef2f2;
}

.admin-notes {
    border-left: 4px solid #3b82f6;
    background: #eff6ff;
}

.special-requests-card h4, .rejection-notice h4, .admin-notes h4 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #0f172a;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.special-requests-card p, .rejection-notice p, .admin-notes p {
    color: #1e293b;
    line-height: 1.8;
    font-weight: 500;
    font-size: 1rem;
}

.trip-description {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    margin-top: 2rem;
    position: relative;
    overflow: hidden;
}

.trip-description::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.trip-description h4 {
    font-size: 1.25rem;
    margin-bottom: 1rem;
    color: #0f172a;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.trip-description-content {
    color: #1e293b;
    line-height: 1.9;
    font-size: 1.05rem;
    font-weight: 500;
}

.trip-description-content p {
    color: #1e293b;
    margin-bottom: 1rem;
    padding-bottom: 3rem;
}

.trip-description-content p {
    margin-bottom: 1rem;
}

.empty-section {
    text-align: center;
    padding: 3rem 1rem;
    color: #6b7280;
}

.empty-section i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #d1d5db;
}

</style>
@endpush

@section('content')
<div class="booking-detail-page">
    <!-- Header Section -->
    <div class="booking-header-section">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1>
                    <i class="fas fa-calendar-check"></i>
                    تفاصيل الحجز
                </h1>
                <div class="booking-status-badge">
                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                    {{ $booking->status }}
                </div>
            </div>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('my-bookings') }}" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                    <i class="fas fa-arrow-right"></i>
                    <span>العودة</span>
                </a>
                @if($booking->status === 'معلقة')
                    <a href="{{ route('bookings.edit', $booking) }}" class="btn" style="background: white; color: #667eea; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                        <i class="fas fa-edit"></i>
                        <span>تعديل</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($booking->trip)
        <!-- Trip Details Section -->
        <div class="trip-details-section">
            <!-- Trip Info Card -->
            <div class="trip-info-card">
                <h3>
                    <i class="fas fa-map-marked-alt"></i>
                    معلومات الرحلة
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>اسم الرحلة</label>
                        <span>{{ $booking->trip->title }}</span>
                    </div>
                    <div class="info-item">
                        <label>المحافظة الرئيسية</label>
                        <span>{{ $booking->trip->governorate->name ?? 'غير محدد' }}</span>
                    </div>
                    @if($booking->trip->departureGovernorate)
                        <div class="info-item">
                            <label>محافظة الانطلاق</label>
                            <span>{{ $booking->trip->departureGovernorate->name }}</span>
                        </div>
                    @endif
                    <div class="info-item">
                        <label>نوع الرحلة</label>
                        <span>{{ $booking->trip->trip_type ?? 'غير محدد' }}</span>
                    </div>
                    @if($booking->trip->trip_types && count($booking->trip->trip_types) > 0)
                        <div class="info-item">
                            <label>أنواع الرحلة</label>
                            <span>{{ implode('، ', $booking->trip->trip_types) }}</span>
                        </div>
                    @endif
                    <div class="info-item">
                        <label>المدة</label>
                        <span>{{ $booking->trip->duration_hours }} ساعة</span>
                    </div>
                    <div class="info-item">
                        <label>السعر للفرد</label>
                        <span>{{ number_format($booking->trip->price) }} ل.س</span>
                    </div>
                    <div class="info-item">
                        <label>العدد الأقصى</label>
                        <span>{{ $booking->trip->max_persons }} أشخاص</span>
                    </div>
                    @if($booking->trip->start_date)
                        <div class="info-item">
                            <label>تاريخ البدء</label>
                            <span>{{ $booking->trip->start_date->format('Y-m-d') }}</span>
                        </div>
                    @endif
                    @if($booking->trip->start_time)
                        <div class="info-item">
                            <label>وقت البدء</label>
                            <span>{{ $booking->trip->start_time->format('H:i') }}</span>
                        </div>
                    @endif
                    @if($booking->trip->meeting_point)
                        <div class="info-item" style="grid-column: 1 / -1;">
                            <label>نقطة اللقاء</label>
                            <span>{{ $booking->trip->meeting_point }}</span>
                        </div>
                    @endif
                </div>

                <!-- Trip Images -->
                @if($booking->trip->images && count($booking->trip->images) > 0)
                    <div class="trip-images-gallery">
                        <h4>
                            <i class="fas fa-images"></i>
                            صور الرحلة
                        </h4>
                        <div class="images-grid">
                            @foreach($booking->trip->images as $image)
                                <div class="image-item" onclick="openImageModal('{{ asset('storage/'.$image) }}')">
                                    <img src="{{ asset('storage/'.$image) }}" alt="صورة الرحلة">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Features -->
                @if($booking->trip->features && count($booking->trip->features) > 0)
                    <div class="features-section">
                        <h4>
                            <i class="fas fa-star"></i>
                            مميزات الرحلة
                        </h4>
                        <div class="features-list">
                            @foreach($booking->trip->features as $feature)
                                <div class="feature-item">
                                    <i class="fas fa-check-circle"></i>
                                    <span>{{ $feature }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Passing Governorates -->
                @if($passingGovernorates->count() > 0)
                    <div class="governorates-section">
                        <h4>
                            <i class="fas fa-route"></i>
                            المحافظات التي سنمر بها
                        </h4>
                        <div class="governorates-list">
                            @foreach($passingGovernorates as $governorate)
                                <span class="governorate-badge">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $governorate->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Booking Info Card -->
            <div class="booking-info-card">
                <h3>
                    <i class="fas fa-calendar-alt"></i>
                    معلومات الحجز
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>تاريخ الحجز</label>
                        <span>{{ $booking->booking_date->format('Y-m-d') }}</span>
                    </div>
                    <div class="info-item">
                        <label>عدد الأشخاص</label>
                        <span>{{ $booking->guest_count }} شخص</span>
                    </div>
                    <div class="info-item">
                        <label>المبلغ الإجمالي</label>
                        <span class="total-price">{{ number_format($booking->total_price) }} ل.س</span>
                    </div>
                    <div class="info-item">
                        <label>تاريخ الإنشاء</label>
                        <span>{{ $booking->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if($booking->updated_at && $booking->updated_at != $booking->created_at)
                        <div class="info-item">
                            <label>آخر تحديث</label>
                            <span>{{ $booking->updated_at->format('Y-m-d H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Trip Description -->
        @if($booking->trip->description)
            <div class="trip-description">
                <h4>
                    <i class="fas fa-align-right"></i>
                    وصف الرحلة
                </h4>
                <div class="trip-description-content">
                    {!! $booking->trip->description !!}
                </div>
            </div>
        @endif

        <!-- Tourist Spots Section -->
        @if($touristSpots->count() > 0)
            <div class="tourist-spots-section">
                <div class="trip-info-card">
                    <h3>
                        <i class="fas fa-map-pin"></i>
                        الأماكن السياحية المضمنة
                    </h3>
                    <div class="tourist-spots-grid">
                        @foreach($touristSpots as $spot)
                            <div class="tourist-spot-card">
                                <h5>
                                    <i class="fas fa-landmark"></i>
                                    {{ $spot->name }}
                                </h5>
                                @if($spot->description)
                                    <p>{{ Str::limit($spot->description, 150) }}</p>
                                @endif
                                <div class="tourist-spot-meta">
                                    @if($spot->governorate)
                                        <span>
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $spot->governorate->name }}
                                        </span>
                                    @endif
                                    @if($spot->location)
                                        <span>
                                            <i class="fas fa-location-dot"></i>
                                            {{ $spot->location }}
                                        </span>
                                    @endif
                                    @if($spot->entrance_fee)
                                        <span>
                                            <i class="fas fa-ticket-alt"></i>
                                            {{ number_format($spot->entrance_fee) }} ل.س
                                        </span>
                                    @endif
                                </div>
                                @if($spot->images && count($spot->images) > 0)
                                    <div style="margin-top: 1rem;">
                                        <img src="{{ asset('storage/'.$spot->images[0]) }}" alt="{{ $spot->name }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Requirements -->
        @if($booking->trip->requirements)
            <div class="special-requests-card">
                <h4>
                    <i class="fas fa-clipboard-list"></i>
                    متطلبات الرحلة
                </h4>
                <p>{{ $booking->trip->requirements }}</p>
            </div>
        @endif
    @else
        <div class="trip-info-card">
            <div class="empty-section">
                <i class="fas fa-exclamation-triangle"></i>
                <h4>رحلة محذوفة</h4>
                <p>الرحلة المرتبطة بهذا الحجز تم حذفها</p>
            </div>
        </div>
    @endif

    <!-- Special Requests -->
    @if($booking->special_requests)
        <div class="special-requests-card">
            <h4>
                <i class="fas fa-comment-dots"></i>
                طلبات خاصة
            </h4>
            <p>{{ $booking->special_requests }}</p>
        </div>
    @endif

    <!-- Rejection Notice -->
    @if($booking->status === 'مرفوضة' && $booking->rejection_reason)
        <div class="rejection-notice">
            <h4>
                <i class="fas fa-exclamation-triangle"></i>
                سبب الرفض
            </h4>
            <p>{{ $booking->rejection_reason }}</p>
        </div>
    @endif

    <!-- Admin Notes -->
    @if($booking->admin_notes)
        <div class="admin-notes">
            <h4>
                <i class="fas fa-sticky-note"></i>
                ملاحظات المسؤول
            </h4>
            <p>{{ $booking->admin_notes }}</p>
        </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; cursor: pointer; align-items: center; justify-content: center;" onclick="closeImageModal()">
    <img id="modalImage" src="" alt="صورة الرحلة" style="max-width: 90%; max-height: 90%; object-fit: contain; border-radius: 8px;">
</div>

@push('scripts')
<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').style.display = 'flex';
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}

// إغلاق عند الضغط على ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endpush
@endsection

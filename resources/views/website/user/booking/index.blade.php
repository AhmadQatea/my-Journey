@extends('website.user.layouts.user')

@section('title', 'حجوزاتي - MyJourney')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/booking.css') }}">
<style>

.bookings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .bookings-grid {
        grid-template-columns: 1fr;
    }
}

.booking-card-modern {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
    display: flex;
    flex-direction: column;
    position: relative;
}

.booking-card-modern::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    z-index: 1;
}

.booking-card-modern:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
    transform: translateY(-4px);
}

.booking-card-header-modern {
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: relative;
    z-index: 2;
}

.booking-card-header-modern h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #ffffff;
}

.booking-card-header-modern .status-badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 0.875rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 600;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
}

.status-badge-modern.status-confirmed {
    background: rgba(16, 185, 129, 0.2);
}

.status-badge-modern.status-pending {
    background: rgba(251, 191, 36, 0.2);
}

.status-badge-modern.status-rejected {
    background: rgba(239, 68, 68, 0.2);
}

.status-badge-modern.status-cancelled {
    background: rgba(107, 114, 128, 0.2);
}

.booking-card-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
    background: #f3f4f6;
}

.booking-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.booking-card-modern:hover .booking-card-image img {
    transform: scale(1.1);
}

.booking-card-body-modern {
    padding: 1.5rem;
    flex: 1;
    background: transparent;
}

.booking-details-modern {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-bottom: 1rem;
}

.booking-detail-item-modern {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f9fafb;
    border-radius: 8px;
}

.booking-detail-item-modern i {
    color: #667eea;
    font-size: 1.125rem;
    width: 20px;
    text-align: center;
}

.booking-detail-item-modern span {
    font-size: 0.875rem;
    color: #1e293b;
    flex: 1;
    line-height: 1.7;
    font-weight: 500;
}

.booking-detail-item-modern strong {
    color: #0f172a;
    font-weight: 800;
}

.special-requests-modern {
    margin-top: 1rem;
    padding: 1rem;
    background: #eff6ff;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
}

.special-requests-modern strong {
    display: block;
    margin-bottom: 0.5rem;
    color: #1e40af;
    font-size: 0.875rem;
    font-weight: 700;
}

.special-requests-modern p {
    color: #1e3a8a;
    font-size: 0.875rem;
    line-height: 1.7;
    margin: 0;
    font-weight: 600;
}

.rejection-notice-modern {
    margin-top: 1rem;
    padding: 1rem;
    background: #fef2f2;
    border-radius: 8px;
    border-left: 4px solid #ef4444;
}

.rejection-notice-modern strong {
    display: block;
    margin-bottom: 0.5rem;
    color: #991b1b;
    font-size: 0.875rem;
    font-weight: 700;
}

.rejection-notice-modern p {
    color: #7f1d1d;
    font-size: 0.875rem;
    line-height: 1.7;
    margin: 0;
    font-weight: 600;
}

.booking-card-footer-modern {
    padding: 1.5rem;
    background: linear-gradient(135deg, #f9fafb 0%, #f1f5f9 100%);
    border-top: 1px solid #e2e8f0;
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.btn-modern {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 0.875rem;
}

.btn-view-modern {
    background: #667eea;
    color: white;
}

.btn-view-modern:hover {
    background: #5568d3;
    transform: translateY(-2px);
}

.btn-edit-modern {
    background: #10b981;
    color: white;
}

.btn-edit-modern:hover {
    background: #059669;
    transform: translateY(-2px);
}

.btn-delete-modern {
    background: #ef4444;
    color: white;
}

.btn-delete-modern:hover {
    background: #dc2626;
    transform: translateY(-2px);
}

.inline-form {
    display: inline;
}

.empty-state-modern {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
}

.empty-icon-modern {
    font-size: 4rem;
    color: #d1d5db;
    margin-bottom: 1.5rem;
}

.empty-state-modern h5 {
    font-size: 1.5rem;
    color: #0f172a;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.empty-state-modern p {
    color: #334155;
    margin-bottom: 2rem;
    font-size: 1rem;
    font-weight: 600;
}

.btn-explore-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 12px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    transition: all 0.3s;
}

.btn-explore-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.pagination-wrapper-modern {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

.stats-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.stat-card .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.stat-card .stat-label {
    color: #334155;
    font-size: 0.875rem;
    font-weight: 700;
}

</style>
@endpush

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <h2 class="page-title">حجوزاتي</h2>
            <p class="page-subtitle">إدارة ومتابعة جميع حجوزاتك</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('bookings.create') }}" class="btn btn-new-trip">
                <i class="fas fa-calendar-check"></i>
                <span>حجز جديد</span>
            </a>
        </div>
    </div>

    <!-- Stats Section -->
    @if($bookings->count() > 0)
        @php
            $stats = [
                'total' => $bookings->total(),
                'confirmed' => $bookings->where('status', 'مؤكدة')->count(),
                'pending' => $bookings->where('status', 'معلقة')->count(),
                'rejected' => $bookings->where('status', 'مرفوضة')->count(),
            ];
        @endphp
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card card-bookings">
                    <div class="card-header">
                        <h3 class="card-title">إجمالي الحجوزات</h3>
                        <div class="card-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="stat-number">{{ $stats['total'] }}</div>
                        <p class="stat-desc">حجز إجمالي</p>
                    </div>
                </div>
                <div class="stat-card card-articles">
                    <div class="card-header">
                        <h3 class="card-title">مؤكدة</h3>
                        <div class="card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="stat-number" style="color: #10b981;">{{ $stats['confirmed'] }}</div>
                        <p class="stat-desc">حجز مؤكد</p>
                    </div>
                </div>
                <div class="stat-card card-status">
                    <div class="card-header">
                        <h3 class="card-title">معلقة</h3>
                        <div class="card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="stat-number" style="color: #f59e0b;">{{ $stats['pending'] }}</div>
                        <p class="stat-desc">في الانتظار</p>
                    </div>
                </div>
                <div class="stat-card card-status">
                    <div class="card-header">
                        <h3 class="card-title">مرفوضة</h3>
                        <div class="card-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="stat-number" style="color: #ef4444;">{{ $stats['rejected'] }}</div>
                        <p class="stat-desc">مرفوضة</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Bookings Grid -->
    <div class="bookings-grid" style="margin-top: 2rem;">
        @forelse($bookings as $booking)
            <div class="booking-card-modern">
                <!-- Card Header -->
                <div class="booking-card-header-modern">
                    <h3>
                        <i class="fas fa-map-marked-alt"></i>
                        {{ $booking->trip->title ?? 'رحلة محذوفة' }}
                    </h3>
                    <span class="status-badge-modern status-{{ $booking->status === 'مؤكدة' ? 'confirmed' : ($booking->status === 'معلقة' ? 'pending' : ($booking->status === 'مرفوضة' ? 'rejected' : 'cancelled')) }}">
                        <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                        {{ $booking->status }}
                    </span>
                </div>

                <!-- Trip Image -->
                @if($booking->trip && $booking->trip->images && count($booking->trip->images) > 0)
                    <div class="booking-card-image">
                        <img src="{{ asset('storage/'.$booking->trip->images[0]) }}" alt="{{ $booking->trip->title }}">
                    </div>
                @endif

                <!-- Card Body -->
                <div class="booking-card-body-modern">
                    <div class="booking-details-modern">
                        <div class="booking-detail-item-modern">
                            <i class="fas fa-calendar"></i>
                            <span>
                                <strong>تاريخ الحجز:</strong><br>
                                {{ $booking->booking_date->format('Y-m-d') }}
                            </span>
                        </div>
                        <div class="booking-detail-item-modern">
                            <i class="fas fa-users"></i>
                            <span>
                                <strong>عدد الأشخاص:</strong><br>
                                {{ $booking->guest_count }} شخص
                            </span>
                        </div>
                        <div class="booking-detail-item-modern">
                            <i class="fas fa-money-bill-wave"></i>
                            <span>
                                <strong>المبلغ الإجمالي:</strong><br>
                                <span style="color: #10b981; font-weight: 700;">{{ number_format($booking->total_price) }} ل.س</span>
                            </span>
                        </div>
                        @if($booking->trip)
                            <div class="booking-detail-item-modern">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>
                                    <strong>المحافظة:</strong><br>
                                    {{ $booking->trip->governorate->name ?? 'غير محدد' }}
                                </span>
                            </div>
                            @if($booking->trip->duration_hours)
                                <div class="booking-detail-item-modern">
                                    <i class="fas fa-clock"></i>
                                    <span>
                                        <strong>المدة:</strong><br>
                                        {{ $booking->trip->duration_hours }} ساعة
                                    </span>
                                </div>
                            @endif
                            <div class="booking-detail-item-modern">
                                <i class="fas fa-tag"></i>
                                <span>
                                    <strong>سعر الفرد:</strong><br>
                                    {{ number_format($booking->trip->price) }} ل.س
                                </span>
                            </div>
                        @endif
                    </div>

                    @if($booking->special_requests)
                        <div class="special-requests-modern">
                            <strong>
                                <i class="fas fa-comment-dots"></i>
                                طلبات خاصة:
                            </strong>
                            <p>{{ Str::limit($booking->special_requests, 100) }}</p>
                        </div>
                    @endif

                    @if($booking->status === 'مرفوضة' && $booking->rejection_reason)
                        <div class="rejection-notice-modern">
                            <strong>
                                <i class="fas fa-exclamation-triangle"></i>
                                سبب الرفض:
                            </strong>
                            <p>{{ Str::limit($booking->rejection_reason, 100) }}</p>
                        </div>
                    @endif
                </div>

                <!-- Card Footer -->
                <div class="booking-card-footer-modern">
                    <a href="{{ route('bookings.show', $booking) }}" class="btn-modern btn-view-modern">
                        <i class="fas fa-eye"></i>
                        عرض التفاصيل
                    </a>
                    @if($booking->status === 'معلقة')
                        <a href="{{ route('bookings.edit', $booking) }}" class="btn-modern btn-edit-modern">
                            <i class="fas fa-edit"></i>
                            تعديل
                        </a>
                        <form action="{{ route('bookings.destroy', $booking) }}" method="POST" class="inline-form" onsubmit="return confirm('هل أنت متأكد من حذف هذا الحجز؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-modern btn-delete-modern">
                                <i class="fas fa-trash"></i>
                                حذف
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state" style="grid-column: 1 / -1; margin-top: 2rem;">
                <div class="empty-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h5>لا توجد حجوزات</h5>
                <p>ابدأ بحجز رحلتك الأولى واستمتع بتجربة لا تُنسى</p>
                <a href="{{ route('bookings.create') }}" class="btn btn-explore">
                    <i class="fas fa-calendar-check"></i>
                    حجز رحلة جديدة
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="pagination-wrapper-modern">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection

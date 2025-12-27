@extends('website.user.layouts.user')

@section('title', 'الملف الشخصي - MyJourney')

@push('styles')
<style>
.profile-detail-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.profile-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.profile-header-section h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.profile-content-section {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .profile-content-section {
        grid-template-columns: 1fr;
    }
}

.profile-avatar-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
    text-align: center;
}

.profile-avatar-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.profile-avatar-container {
    position: relative;
    display: inline-block;
    margin-bottom: 1.5rem;
}

.profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.profile-avatar-placeholder {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 4px solid #ffffff;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    font-size: 4rem;
    color: white;
}

.profile-name {
    font-size: 1.5rem;
    color: #0f172a;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.profile-email {
    color: #475569;
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 1rem;
}

.profile-info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    border: 1px solid #e2e8f0;
    position: relative;
    overflow: hidden;
}

.profile-info-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.profile-info-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    color: #0f172a;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.profile-info-card h3 i {
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

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.stat-item {
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.stat-item .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.5rem;
}

.stat-item .stat-label {
    color: #334155;
    font-size: 0.875rem;
    font-weight: 600;
}

.account-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    margin-top: 1rem;
}

.account-badge.vip {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
    color: white;
}

.account-badge.active {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.identity-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.identity-badge.verified {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.identity-badge.unverified {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

</style>
@endpush

@section('content')
<div class="profile-detail-page">
    <!-- Header Section -->
    <div class="profile-header-section">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h1>
                    <i class="fas fa-user-circle"></i>
                    الملف الشخصي
                </h1>
            </div>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('profile.edit') }}" class="btn" style="background: white; color: #667eea; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-weight: 600;">
                    <i class="fas fa-edit"></i>
                    <span>تعديل</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content-section">
        <!-- Avatar Card -->
        <div class="profile-avatar-card">
            <div class="profile-avatar-container">
                @if($user->avatar)
                    <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->full_name }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            <div class="profile-name">{{ $user->full_name }}</div>
            <div class="profile-email">
                <i class="fas fa-envelope"></i>
                {{ $user->email }}
            </div>
            @if($user->isVip())
                <span class="account-badge vip">
                    <i class="fas fa-crown"></i>
                    حساب VIP
                </span>
            @else
                <span class="account-badge active">
                    <i class="fas fa-check-circle"></i>
                    حساب نشط
                </span>
            @endif
            @if($user->identity_verified)
                <span class="identity-badge verified">
                    <i class="fas fa-shield-check"></i>
                    هوية موثقة
                </span>
            @else
                <span class="identity-badge unverified">
                    <i class="fas fa-shield-alt"></i>
                    هوية غير موثقة
                </span>
            @endif
        </div>

        <!-- Info Card -->
        <div class="profile-info-card">
            <h3>
                <i class="fas fa-info-circle"></i>
                معلومات الحساب
            </h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>الاسم الكامل</label>
                    <span>{{ $user->full_name }}</span>
                </div>
                <div class="info-item">
                    <label>البريد الإلكتروني</label>
                    <span>{{ $user->email }}</span>
                </div>
                @if($user->phone)
                    <div class="info-item">
                        <label>رقم الهاتف</label>
                        <span>{{ $user->phone }}</span>
                    </div>
                @endif
                <div class="info-item">
                    <label>نوع الحساب</label>
                    <span>{{ $user->isVip() ? 'VIP' : 'عادي' }}</span>
                </div>
                <div class="info-item">
                    <label>حالة التوثيق</label>
                    <span>{{ $user->identity_verified ? 'موثق' : 'غير موثق' }}</span>
                </div>
                <div class="info-item">
                    <label>تاريخ التسجيل</label>
                    <span>{{ $user->created_at->format('Y-m-d') }}</span>
                </div>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number">{{ $bookingsCount ?? 0 }}</div>
                    <div class="stat-label">إجمالي الحجوزات</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">{{ $articlesCount ?? 0 }}</div>
                    <div class="stat-label">إجمالي المقالات</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


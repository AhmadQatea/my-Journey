{{-- resources/views/admin/users/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'تفاصيل المستخدم: ' . $user->full_name)
@section('page-title', 'تفاصيل المستخدم: ' . $user->full_name)

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ $user->full_name }}</h1>
            <div class="flex items-center gap-3 mt-2 flex-wrap">
                @php
                    $accountTypeColors = [
                        'visitor' => 'badge-secondary',
                        'active' => 'badge-success',
                        'vip' => 'badge-warning',
                    ];
                    $accountTypeLabels = [
                        'visitor' => 'زائر',
                        'active' => 'نشط',
                        'vip' => 'VIP',
                    ];
                @endphp
                <span class="badge {{ $accountTypeColors[$user->account_type] ?? 'badge-secondary' }}">
                    {{ $accountTypeLabels[$user->account_type] ?? $user->account_type }}
                </span>
                @if($user->email_verified_at)
                    <span class="badge badge-success">متحقق من البريد</span>
                @else
                    <span class="badge badge-warning">غير متحقق</span>
                @endif
                @if($user->identity_verified)
                    <span class="badge badge-info">هوية موثقة</span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.contact', $user) }}"
               class="btn btn-primary inline-flex items-center gap-2">
                <i class="fas fa-envelope"></i>
                <span>التواصل</span>
            </a>
            <a href="{{ route('admin.users.edit', $user) }}"
               class="btn btn-warning inline-flex items-center gap-2">
                <i class="fas fa-edit"></i>
                <span>تعديل</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="btn btn-outline inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>رجوع للقائمة</span>
            </a>
        </div>
            </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Information -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات المستخدم</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">الاسم الكامل</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $user->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">البريد الإلكتروني</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $user->email }}</p>
                        </div>
                        @if($user->phone)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">رقم الهاتف</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $user->phone }}</p>
            </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">الدور</p>
                            <p class="font-medium text-gray-900 dark:text-gray">
                                {{ $user->role->name ?? 'بدون دور' }}
                            </p>
            </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">نوع الحساب</p>
                            <span class="badge {{ $accountTypeColors[$user->account_type] ?? 'badge-secondary' }}">
                                {{ $accountTypeLabels[$user->account_type] ?? $user->account_type }}
                            </span>
            </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">تاريخ التسجيل</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $user->created_at->format('Y-m-d H:i') }}</p>
            </div>
                        @if($user->email_verified_at)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">تاريخ التحقق من البريد</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $user->email_verified_at->format('Y-m-d H:i') }}</p>
        </div>
            @endif
        </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">الإحصائيات</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $user->bookings_count ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">إجمالي الحجوزات</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $user->articles_count ?? 0 }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">المقالات</div>
            </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $completedBookings }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">حجوزات مكتملة</div>
            </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ number_format($totalSpent, 0) }} ل.س</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">إجمالي الإنفاق</div>
                        </div>
                    </div>
            </div>
        </div>

        <!-- Recent Bookings -->
            @if($recentBookings->count() > 0)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">الحجوزات الأخيرة</h3>
        <div class="space-y-3">
            @foreach($recentBookings as $booking)
            <div class="flex items-center justify-between p-3 border rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-gray">{{ $booking->trip->title ?? 'رحلة محذوفة' }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $booking->trip->governorate->name ?? 'N/A' }} - {{ $booking->booking_date->format('Y-m-d') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-green-600">{{ number_format($booking->total_price, 0) }} ل.س</div>
                                @php
                                    $statusColors = [
                                        'معلقة' => 'badge-warning',
                                        'مؤكدة' => 'badge-success',
                                        'مرفوضة' => 'badge-danger',
                                        'ملغاة' => 'badge-secondary',
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$booking->status] ?? 'badge-secondary' }}">
                                    {{ $booking->status }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @if($user->bookings_count > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.bookings.index', ['user_id' => $user->id]) }}" class="btn btn-outline-primary btn-sm">
                            عرض جميع الحجوزات
                        </a>
                </div>
                    @endif
                </div>
            </div>
            @endif

<!-- Recent Articles -->
            @if($recentArticles->count() > 0)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">المقالات الأخيرة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($recentArticles as $article)
                        <div class="border rounded-lg overflow-hidden">
                            <div class="p-3">
                <h4 class="font-bold text-lg mb-2">{{ Str::limit($article->title, 50) }}</h4>
                                @if($article->excerpt)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">{{ Str::limit($article->excerpt, 80) }}</p>
                                @endif
                <div class="flex justify-between items-center">
                                    @php
                                        $statusColors = [
                                            'معلقة' => 'badge-warning',
                                            'منشورة' => 'badge-success',
                                            'مرفوضة' => 'badge-danger',
                                        ];
                                    @endphp
                                    <span class="badge {{ $statusColors[$article->status] ?? 'badge-secondary' }}">
                                        {{ $article->status }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $article->created_at->format('Y-m-d') }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
                    @if($user->articles_count > 6)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.articles.index', ['user_id' => $user->id]) }}" class="btn btn-outline-primary btn-sm">
                            عرض جميع المقالات
                        </a>
                    </div>
    @endif
                </div>
    </div>
    @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">الإجراءات</h3>
                </div>
                <div class="card-body space-y-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning w-full">
                        <i class="fas fa-edit ml-1"></i>
                        تعديل المستخدم
                    </a>
                    @if(!$user->email_verified_at)
                    <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info w-full">
                            <i class="fas fa-envelope ml-1"></i>
                            التحقق من البريد
                        </button>
                    </form>
                    @endif
                    @if(!$user->identity_verified)
                    <form action="{{ route('admin.users.verify-identity', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info w-full">
                            <i class="fas fa-id-card ml-1"></i>
                            توثيق الهوية
                        </button>
                    </form>
                    @endif
                    @if($user->account_type !== 'vip')
                    <form action="{{ route('admin.users.upgrade-to-vip', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-full">
                            <i class="fas fa-crown ml-1"></i>
                            ترقية إلى VIP
                        </button>
                    </form>
                    @endif
                    @if($user->account_type === 'visitor')
                    <form action="{{ route('admin.users.activate', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-full">
                            <i class="fas fa-check ml-1"></i>
                            تفعيل المستخدم
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.users.deactivate', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning w-full">
                            <i class="fas fa-pause ml-1"></i>
                            إلغاء التفعيل
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- User Info Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">معلومات إضافية</h3>
                </div>
                <div class="card-body space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">آخر تحديث:</span>
                        <span class="font-medium text-gray-900 dark:text-gray">{{ $user->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                    @if($user->identity_verified)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">الهوية:</span>
                        <span class="badge badge-success">موثقة</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

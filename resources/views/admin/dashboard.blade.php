@extends('admin.layouts.admin')

@section('title', __('messages.dashboard'))
@section('page-title', __('messages.dashboard'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <x-card title="">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">{{ __('messages.total_users') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </x-card>

    <x-card title="">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('messages.total_verified_users') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-black">{{ $totalVerifiedUsers }}</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-check text-indigo-600 dark:text-indigo-400 text-xl"></i>
            </div>
        </div>
    </x-card>
    <x-card title="">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">{{ __('messages.total_offers') }}</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-black">{{ $totalOffers }}</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-gift text-indigo-600 dark:text-indigo-400 text-xl"></i>
            </div>
        </div>
    </x-card>

    <x-card title="">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">{{ __('messages.total_bookings') }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-check text-green-600 text-xl"></i>
            </div>
        </div>
    </x-card>

    <x-card title="">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">إجمالي المقالات</p>
                <p class="text-2xl font-bold text-gray-900">{{ $totalArticles }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-newspaper text-purple-600 text-xl"></i>
            </div>
        </div>
    </x-card>

    <x-card title="">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">إجمالي الأماكن السياحية في سوريا</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-black">{{ $totalTouristSpots ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-map-marker-alt text-emerald-600 dark:text-emerald-400 text-xl"></i>
            </div>
        </div>
    </x-card>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <x-card :title="__('messages.bookings_overview')">
        <canvas id="bookingsChart" height="250"></canvas>
    </x-card>

    <x-card title="تحليل الإيرادات">
        <canvas id="revenueChart" height="250"></canvas>
    </x-card>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-card title="المستخدمون الجدد">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">البريد</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الانضمام</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentUsers as $user)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $user->full_name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-4 py-3 text-center text-sm text-gray-500">لا توجد مستخدمين</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <x-button variant="primary" size="sm" href="{{ route('admin.users.index') }}">
                عرض الكل
            </x-button>
        </div>
    </x-card>

    <x-card :title="__('messages.recent_bookings')">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المستخدم</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الرحلة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentBookings as $booking)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $booking->user->full_name }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $booking->trip->title ?? 'N/A' }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <x-badge type="{{ $booking->status == 'مؤكدة' ? 'success' : 'warning' }}">
                                {{ $booking->status }}
                            </x-badge>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $booking->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">لا توجد حجوزات</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            <x-button variant="primary" size="sm" href="{{ route('admin.bookings.index') }}">
                عرض الكل
            </x-button>
        </div>
    </x-card>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // بيانات الحجوزات من قاعدة البيانات
    const bookingsLabels = @json($bookingsData['labels']);
    const bookingsData = @json($bookingsData['data']);

    // بيانات الإيرادات من قاعدة البيانات
    const revenueLabels = @json($revenueData['labels']);
    const revenueData = @json($revenueData['data']);

    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    const bookingsChart = new Chart(bookingsCtx, {
        type: 'line',
        data: {
            labels: bookingsLabels,
            datasets: [{
                label: '{{ __('messages.bookings') }}',
                data: bookingsData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'الإيرادات (ل.س)',
                data: revenueData,
                backgroundColor: '#10b981',
                borderColor: '#059669',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'الإيرادات: ' + new Intl.NumberFormat('ar-SY').format(context.parsed.y) + ' ل.س';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('ar-SY').format(value) + ' ل.س';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection

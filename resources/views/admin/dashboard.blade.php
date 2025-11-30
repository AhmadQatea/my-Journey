@extends('admin.layouts.admin')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <x-card title="">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">إجمالي المستخدمين</p>
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
                <p class="text-sm text-gray-600 mb-1">إجمالي الحجوزات</p>
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
                <p class="text-sm text-gray-600 mb-1">إجمالي الإيرادات</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($totalRevenue, 2) }} ل.س</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
            </div>
        </div>
    </x-card>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <x-card title="نظرة عامة على الحجوزات">
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

    <x-card title="الحجوزات الأخيرة">
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
    // Bookings Chart
    const bookingsCtx = document.getElementById('bookingsChart').getContext('2d');
    const bookingsChart = new Chart(bookingsCtx, {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'الحجوزات',
                data: [12, 19, 3, 5, 2, 3],
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
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'الإيرادات (ل.س)',
                data: [1200, 1900, 300, 500, 200, 300],
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
                }
            }
        }
    });
</script>
@endpush
@endsection

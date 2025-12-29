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
        <div class="mb-4 flex items-center justify-end gap-2">
            <button type="button" class="chart-filter-btn" data-period="days" data-count="7">
                <i class="fas fa-calendar-day text-xs ml-1"></i>
                <span>7 أيام</span>
            </button>
            <button type="button" class="chart-filter-btn" data-period="weeks" data-count="4">
                <i class="fas fa-calendar-week text-xs ml-1"></i>
                <span>4 أسابيع</span>
            </button>
            <button type="button" class="chart-filter-btn active" data-period="months" data-count="6">
                <i class="fas fa-calendar-alt text-xs ml-1"></i>
                <span>6 أشهر</span>
            </button>
        </div>
        <div class="chart-wrapper">
            <canvas id="bookingsChart"></canvas>
        </div>
    </x-card>

    <x-card title="تحليل الإيرادات">
        <div class="mb-4 flex items-center justify-end gap-2">
            <button type="button" class="chart-filter-btn" data-period="days" data-count="7">
                <i class="fas fa-calendar-day text-xs ml-1"></i>
                <span>7 أيام</span>
            </button>
            <button type="button" class="chart-filter-btn" data-period="weeks" data-count="4">
                <i class="fas fa-calendar-week text-xs ml-1"></i>
                <span>4 أسابيع</span>
            </button>
            <button type="button" class="chart-filter-btn active" data-period="months" data-count="6">
                <i class="fas fa-calendar-alt text-xs ml-1"></i>
                <span>6 أشهر</span>
            </button>
        </div>
        <div class="chart-wrapper">
            <canvas id="revenueChart"></canvas>
        </div>
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

@push('styles')
<style>
.chart-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.5rem 0.875rem;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #64748b;
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.chart-filter-btn:hover {
    background: #e2e8f0;
    color: #475569;
    border-color: #cbd5e1;
}

.chart-filter-btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.chart-filter-btn.active:hover {
    background: #2563eb;
    border-color: #2563eb;
}

.dark .chart-filter-btn {
    background: #1e293b;
    border-color: #334155;
    color: #cbd5e1;
}

.dark .chart-filter-btn:hover {
    background: #334155;
    color: #e2e8f0;
}

.dark .chart-filter-btn.active {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.chart-wrapper {
    position: relative;
    height: 250px;
    width: 100%;
    overflow: hidden;
}

.chart-wrapper canvas {
    max-width: 100%;
    max-height: 100%;
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    'use strict';

    // بيانات الحجوزات من قاعدة البيانات
    let bookingsLabels = @json($bookingsData['labels']);
    let bookingsData = @json($bookingsData['data']);

    // بيانات الإيرادات من قاعدة البيانات
    let revenueLabels = @json($revenueData['labels']);
    let revenueData = @json($revenueData['data']);

    let bookingsChart = null;
    let revenueChart = null;

    // دالة تحديث الرسوم البيانية
    function updateCharts(period, count, card) {
        const bookingsCanvas = card.querySelector('#bookingsChart');
        const revenueCanvas = card.querySelector('#revenueChart');
        const isBookingsChart = bookingsCanvas !== null;

        if (!bookingsCanvas && !revenueCanvas) {
            console.error('Canvas not found');
            return;
        }

        const canvas = bookingsCanvas || revenueCanvas;
        canvas.style.opacity = '0.5';
        canvas.style.pointerEvents = 'none';

        const url = '{{ route("admin.dashboard.chart-data") }}?period=' + encodeURIComponent(period) + '&count=' + encodeURIComponent(count);

        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (isBookingsChart && bookingsChart) {
                bookingsChart.data.labels = data.bookings.labels;
                bookingsChart.data.datasets[0].data = data.bookings.data;
                bookingsChart.update('active');
            } else if (revenueChart) {
                revenueChart.data.labels = data.revenue.labels;
                revenueChart.data.datasets[0].data = data.revenue.data;
                revenueChart.update('active');
            }

            canvas.style.opacity = '1';
            canvas.style.pointerEvents = 'auto';
        })
        .catch(error => {
            console.error('Error updating charts:', error);
            canvas.style.opacity = '1';
            canvas.style.pointerEvents = 'auto';
            alert('حدث خطأ أثناء تحديث البيانات: ' + error.message);
        });
    }

    // تهيئة الرسوم البيانية والأزرار
    function initCharts() {
        // Bookings Chart
        const bookingsCtx = document.getElementById('bookingsChart');
        if (bookingsCtx) {
            bookingsChart = new Chart(bookingsCtx.getContext('2d'), {
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
        }

        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            revenueChart = new Chart(revenueCtx.getContext('2d'), {
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
        }

        // ربط أحداث الأزرار
        const filterButtons = document.querySelectorAll('.chart-filter-btn');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const period = this.dataset.period;
                const count = this.dataset.count;

                // البحث عن العنصر الأب (x-card component)
                // x-card يحتوي على class="bg-white rounded-lg"
                let card = this.closest('.bg-white.rounded-lg');

                // إذا لم نجد، نبحث عن العنصر الذي يحتوي على canvas
                if (!card) {
                    const canvasId = this.parentElement.nextElementSibling?.querySelector('canvas')?.id;
                    if (canvasId) {
                        const canvas = document.getElementById(canvasId);
                        if (canvas) {
                            card = canvas.closest('.bg-white.rounded-lg');
                        }
                    }
                }

                // طريقة بديلة: البحث عن العنصر الأب الذي يحتوي على chart-wrapper
                if (!card) {
                    let parent = this.parentElement;
                    while (parent && parent !== document.body) {
                        if (parent.querySelector('.chart-wrapper') || parent.querySelector('canvas')) {
                            card = parent.closest('.bg-white.rounded-lg') || parent;
                            break;
                        }
                        parent = parent.parentElement;
                    }
                }

                if (!card) {
                    console.error('Card not found', this);
                    return;
                }

                // إزالة active من جميع الأزرار في نفس الكارد
                const allButtons = card.querySelectorAll('.chart-filter-btn');
                allButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                updateCharts(period, count, card);
            });
        });
    }

    // انتظار تحميل الصفحة
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCharts);
    } else {
        initCharts();
    }
})();
</script>
@endpush
@endsection


{{-- resources/views/admin/bookings/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'إدارة الحجوزات')
@section('page-title', 'إدارة الحجوزات')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">إدارة الحجوزات</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">إدارة جميع الحجوزات في النظام</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- إحصائيات سريعة -->
            <div class="hidden sm:flex items-center gap-4 text-sm">
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">مؤكدة: {{ $stats['confirmed'] }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">معلقة: {{ $stats['pending'] }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">مرفوضة: {{ $stats['rejected'] }}</span>
                </div>
            </div>

            <a href="{{ route('admin.bookings.create') }}"
               class="btn btn-primary inline-flex items-center gap-2">
                <i class="fas fa-plus text-sm"></i>
                <span>إضافة حجز جديد</span>
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-6">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.bookings.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="form-group">
                        <label class="form-label">بحث</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="ابحث عن حجز...">
                    </div>

                    <!-- Status Filter -->
                    <div class="form-group">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-control form-select">
                            <option value="all">جميع الحالات</option>
                            <option value="معلقة" {{ request('status') == 'معلقة' ? 'selected' : '' }}>معلقة</option>
                            <option value="مؤكدة" {{ request('status') == 'مؤكدة' ? 'selected' : '' }}>مؤكدة</option>
                            <option value="مرفوضة" {{ request('status') == 'مرفوضة' ? 'selected' : '' }}>مرفوضة</option>
                            <option value="ملغاة" {{ request('status') == 'ملغاة' ? 'selected' : '' }}>ملغاة</option>
                        </select>
                    </div>

                    <!-- Trip Filter -->
                    <div class="form-group">
                        <label class="form-label">الرحلة</label>
                        <select name="trip_id" class="form-control form-select">
                            <option value="">جميع الرحلات</option>
                            @foreach($trips as $trip)
                                <option value="{{ $trip->id }}" {{ request('trip_id') == $trip->id ? 'selected' : '' }}>
                                    {{ $trip->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- User Filter -->
                    <div class="form-group">
                        <label class="form-label">المستخدم</label>
                        <select name="user_id" class="form-control form-select">
                            <option value="">جميع المستخدمين</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div class="form-group">
                        <label class="form-label">من تاريخ</label>
                        <input type="date"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="form-control">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Date To -->
                    <div class="form-group">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="form-control">
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter ml-1"></i>
                            فلترة
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline">
                            <i class="fas fa-sync ml-1"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">إجمالي الحجوزات</p>
                    <p class="stat-value">{{ $stats['total'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-blue-500 to-cyan-500">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">معلقة</p>
                    <p class="stat-value">{{ $stats['pending'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-yellow-500 to-orange-500">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">مؤكدة</p>
                    <p class="stat-value">{{ $stats['confirmed'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-emerald-500 to-green-500">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">مرفوضة</p>
                    <p class="stat-value">{{ $stats['rejected'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-red-500 to-pink-500">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h3 class="card-title">قائمة الحجوزات</h3>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    عرض {{ $bookings->firstItem() ?? 0 }} - {{ $bookings->lastItem() ?? 0 }} من {{ $bookings->total() }}
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($bookings->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-16">#</th>
                                <th>المستخدم</th>
                                <th>الرحلة</th>
                                <th class="w-24">عدد الضيوف</th>
                                <th class="w-28">تاريخ الحجز</th>
                                <th class="w-24">السعر الإجمالي</th>
                                <th class="w-24">الحالة</th>
                                <th class="w-32">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="font-medium text-gray-900 dark:text-gray-200">{{ $booking->user->full_name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->user->email ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="font-medium text-gray-900 dark:text-gray-200">{{ $booking->trip->title ?? 'رحلة محذوفة' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $booking->trip->governorate->name ?? 'N/A' }}</div>
                                </td>
                                <td>{{ $booking->guest_count }}</td>
                                <td>{{ $booking->booking_date->format('Y-m-d') }}</td>
                                <td>{{ number_format($booking->total_price, 0) }} ل.س</td>
                                <td>
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
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $booking->id }})" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h4 class="empty-state-title">لا توجد حجوزات</h4>
                    <p class="empty-state-description">
                        لم يتم إضافة أي حجوزات بعد. ابدأ بإضافة أول حجز.
                    </p>
                    <a href="{{ route('admin.bookings.create') }}"
                       class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>إضافة حجز جديد</span>
                    </a>
                </div>
            @endif
        </div>

        @if($bookings->hasPages())
            <div class="card-footer">
                {{ $bookings->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal hidden">
    <div class="modal-content max-w-sm">
        <div class="modal-header">
            <h3 class="modal-title">تأكيد الحذف</h3>
            <button type="button" class="modal-close" data-modal-hide="deleteModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <p>هل أنت متأكد أنك تريد حذف هذا الحجز؟ لا يمكن التراجع عن هذا الإجراء.</p>
        </div>
        <div class="modal-footer flex justify-end gap-3">
            <button type="button" class="btn btn-outline" data-modal-hide="deleteModal">إلغاء</button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">حذف</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(bookingId) {
        const form = document.getElementById('deleteForm');
        form.action = `{{ url('admin/bookings') }}/${bookingId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    document.querySelectorAll('[data-modal-hide="deleteModal"]').forEach(button => {
        button.addEventListener('click', () => {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        });
    });
</script>
@endpush


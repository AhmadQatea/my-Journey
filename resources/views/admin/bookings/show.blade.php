{{-- resources/views/admin/bookings/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'تفاصيل الحجز: #' . $booking->id)
@section('page-title', 'تفاصيل الحجز: #' . $booking->id)

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">تفاصيل الحجز</h1>
            <div class="flex items-center gap-3 mt-2 flex-wrap">
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
                @if($booking->created_by_admin)
                    <span class="badge badge-info">
                        <i class="fas fa-user-shield ml-1"></i> من قبل المسؤول
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bookings.edit', $booking) }}"
               class="btn btn-warning inline-flex items-center gap-2">
                <i class="fas fa-edit"></i>
                <span>تعديل</span>
            </a>
            <a href="{{ route('admin.bookings.index') }}"
               class="btn btn-outline inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>رجوع للقائمة</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Booking Information -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات الحجز</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">رقم الحجز</p>
                            <p class="font-medium text-gray-900 dark:text-gray">#{{ $booking->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">تاريخ الحجز</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->booking_date->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">عدد الضيوف</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->guest_count }} شخص</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">السعر الإجمالي</p>
                            <p class="font-medium text-green-600 dark:text-green-400">{{ number_format($booking->total_price, 0) }} ل.س</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">تاريخ الإنشاء</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">آخر تحديث</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                        @if($booking->created_by_admin && $booking->adminCreator)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">تم الإنشاء من قبل</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->adminCreator->name }}</p>
                        </div>
                        @endif
                        @if($booking->status === 'مؤكدة' && $booking->adminConfirmer)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">تم التأكيد من قبل</p>
                            <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->adminConfirmer->name }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Trip Information -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات الرحلة</h3>
                    @if($booking->trip)
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">عنوان الرحلة</p>
                                <a href="{{ route('admin.trips.show', $booking->trip) }}" class="font-medium text-primary hover:underline">
                                    {{ $booking->trip->title }}
                                </a>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">المحافظة</p>
                                <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->trip->governorate->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">سعر الرحلة للفرد</p>
                                <p class="font-medium text-gray-900 dark:text-gray">{{ number_format($booking->trip->price, 0) }} ل.س</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">الرحلة محذوفة</p>
                    @endif
                </div>
            </div>

            <!-- User Information -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات المستخدم</h3>
                    @if($booking->user)
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">الاسم الكامل</p>
                                <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->user->full_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">البريد الإلكتروني</p>
                                <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->user->email }}</p>
                            </div>
                            @if($booking->user->phone)
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">رقم الهاتف</p>
                                <p class="font-medium text-gray-900 dark:text-gray">{{ $booking->user->phone }}</p>
                            </div>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">المستخدم محذوف</p>
                    @endif
                </div>
            </div>

            <!-- Special Requests -->
            @if($booking->special_requests)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">الطلبات الخاصة</h3>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $booking->special_requests }}</p>
                </div>
            </div>
            @endif

            <!-- Rejection Reason -->
            @if($booking->status === 'مرفوضة' && $booking->rejection_reason)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">سبب الرفض</h3>
                    <p class="text-red-600 dark:text-red-400 whitespace-pre-line">{{ $booking->rejection_reason }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Change -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تغيير الحالة</h3>
                </div>
                <div class="card-body space-y-3">
                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">الحالة</label>
                            <select name="status" id="statusSelect" class="form-control form-select">
                                <option value="معلقة" {{ $booking->status == 'معلقة' ? 'selected' : '' }}>معلقة</option>
                                <option value="مؤكدة" {{ $booking->status == 'مؤكدة' ? 'selected' : '' }}>مؤكدة</option>
                                <option value="مرفوضة" {{ $booking->status == 'مرفوضة' ? 'selected' : '' }}>مرفوضة</option>
                                <option value="ملغاة" {{ $booking->status == 'ملغاة' ? 'selected' : '' }}>ملغاة</option>
                            </select>
                        </div>
                        <div class="form-group" id="rejectionReasonGroup" style="display: {{ $booking->status == 'مرفوضة' ? 'block' : 'none' }};">
                            <label class="form-label">سبب الرفض</label>
                            <textarea name="rejection_reason"
                                      class="form-control"
                                      rows="3"
                                      placeholder="أدخل سبب الرفض...">{{ $booking->rejection_reason }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-save ml-1"></i>
                            تحديث الحالة
                        </button>
                    </form>
                </div>
            </div>

            <!-- Admin Notes -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">ملاحظات المسؤول</h3>
                </div>
                <div class="card-body p-4">
                    @if($booking->admin_notes)
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $booking->admin_notes }}</p>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">لا توجد ملاحظات</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">الإجراءات</h3>
                </div>
                <div class="card-body space-y-3">
                    <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn btn-warning w-full">
                        <i class="fas fa-edit ml-1"></i>
                        تعديل الحجز
                    </a>
                    <button type="button" class="btn btn-danger w-full" onclick="confirmDelete({{ $booking->id }})">
                        <i class="fas fa-trash ml-1"></i>
                        حذف الحجز
                    </button>
                </div>
            </div>
        </div>
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
    // Toggle rejection reason field
    document.getElementById('statusSelect').addEventListener('change', function() {
        const rejectionReasonGroup = document.getElementById('rejectionReasonGroup');
        if (this.value === 'مرفوضة') {
            rejectionReasonGroup.style.display = 'block';
            rejectionReasonGroup.querySelector('textarea').required = true;
        } else {
            rejectionReasonGroup.style.display = 'none';
            rejectionReasonGroup.querySelector('textarea').required = false;
        }
    });

    // Delete confirmation
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


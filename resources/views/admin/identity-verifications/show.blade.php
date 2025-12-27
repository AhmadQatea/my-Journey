@extends('admin.layouts.admin')

@section('title', 'تفاصيل طلب توثيق الهوية')
@section('page-title', 'تفاصيل طلب توثيق الهوية')

@section('content')
<div class="container mx-auto px-4 py-4">
    <div class="mb-4">
        <a href="{{ route('admin.identity-verifications.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-right ml-1"></i>رجوع للقائمة
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- User Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">معلومات المستخدم</h3>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">الاسم الكامل</p>
                            <p class="font-medium">{{ $identityVerification->user->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">البريد الإلكتروني</p>
                            <p class="font-medium">{{ $identityVerification->user->email }}</p>
                        </div>
                        @if($identityVerification->user->phone)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">رقم الهاتف</p>
                            <p class="font-medium">{{ $identityVerification->user->phone }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">حالة التحقق من البريد</p>
                            @if($identityVerification->user->email_verified_at)
                                <span class="badge badge-success">متحقق</span>
                            @else
                                <span class="badge badge-warning">غير متحقق</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identity Image -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">صورة الهوية الشخصية</h3>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $identityVerification->identity_image) }}" 
                             alt="صورة الهوية" 
                             class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
                             style="max-height: 600px;">
                    </div>
                </div>
            </div>

            <!-- Request Details -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تفاصيل الطلب</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">تاريخ الطلب:</span>
                            <span class="font-medium">{{ $identityVerification->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">الحالة:</span>
                            @php
                                $statusColors = [
                                    'pending' => 'badge-warning',
                                    'approved' => 'badge-success',
                                    'rejected' => 'badge-danger',
                                ];
                                $statusLabels = [
                                    'pending' => 'معلقة',
                                    'approved' => 'مقبولة',
                                    'rejected' => 'مرفوضة',
                                ];
                            @endphp
                            <span class="badge {{ $statusColors[$identityVerification->status] ?? 'badge-secondary' }}">
                                {{ $statusLabels[$identityVerification->status] ?? $identityVerification->status }}
                            </span>
                        </div>
                        @if($identityVerification->reviewer)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">المراجع:</span>
                            <span class="font-medium">{{ $identityVerification->reviewer->name }}</span>
                        </div>
                        @endif
                        @if($identityVerification->reviewed_at)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">تاريخ المراجعة:</span>
                            <span class="font-medium">{{ $identityVerification->reviewed_at->format('Y-m-d H:i') }}</span>
                        </div>
                        @endif
                        @if($identityVerification->rejection_reason)
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 mb-2">سبب الرفض:</p>
                            <p class="bg-red-50 dark:bg-red-900/20 p-3 rounded-lg text-red-700 dark:text-red-400">
                                {{ $identityVerification->rejection_reason }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            @if($identityVerification->status === 'pending')
                <!-- Approve Action -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">الموافقة</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.identity-verifications.approve', $identityVerification) }}" 
                              method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من الموافقة على هذا الطلب؟')">
                            @csrf
                            <button type="submit" class="btn btn-success w-full">
                                <i class="fas fa-check ml-1"></i>الموافقة على الطلب
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Reject Action -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">الرفض</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.identity-verifications.reject', $identityVerification) }}" 
                              method="POST" 
                              id="rejectForm">
                            @csrf
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label">سبب الرفض <span class="text-danger">*</span></label>
                                <textarea name="rejection_reason" 
                                          id="rejection_reason" 
                                          class="form-textarea w-full" 
                                          rows="4" 
                                          required
                                          placeholder="أدخل سبب رفض الطلب..."></textarea>
                                @error('rejection_reason')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" 
                                    class="btn btn-danger w-full"
                                    onclick="return confirm('هل أنت متأكد من رفض هذا الطلب؟')">
                                <i class="fas fa-times ml-1"></i>رفض الطلب
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle ml-1"></i>
                            تمت معالجة هذا الطلب بالفعل.
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">إجراءات المستخدم</h3>
                </div>
                <div class="card-body space-y-2">
                    <a href="{{ route('admin.users.show', $identityVerification->user) }}" 
                       class="btn btn-outline-primary w-full">
                        <i class="fas fa-user ml-1"></i>عرض ملف المستخدم
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


{{-- resources/views/admin/users/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'تعديل المستخدم: ' . $user->full_name)
@section('page-title', 'تعديل المستخدم: ' . $user->full_name)

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">تعديل المستخدم</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $user->full_name }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="btn btn-outline inline-flex items-center gap-2">
            <i class="fas fa-arrow-right"></i>
            <span>رجوع للقائمة</span>
        </a>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">المعلومات الأساسية</h3>
                    </div>
                    <div class="card-body space-y-4">
                        <div class="form-group">
                            <label class="form-label">الاسم الكامل *</label>
                            <input type="text"
                                   name="full_name"
                                   class="form-control @error('full_name') is-invalid @enderror"
                                   value="{{ old('full_name', $user->full_name) }}"
                                   required>
                            @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">البريد الإلكتروني *</label>
                            <input type="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text"
                                   name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label class="form-label">الدور *</label>
                                <select name="role_id"
                                        class="form-control form-select @error('role_id') is-invalid @enderror"
                                        required>
                                    <option value="">اختر الدور</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">نوع الحساب *</label>
                                <select name="account_type"
                                        class="form-control form-select @error('account_type') is-invalid @enderror"
                                        required>
                                    <option value="visitor" {{ old('account_type', $user->account_type) == 'visitor' ? 'selected' : '' }}>زائر</option>
                                    <option value="active" {{ old('account_type', $user->account_type) == 'active' ? 'selected' : '' }}>نشط</option>
                                    <option value="vip" {{ old('account_type', $user->account_type) == 'vip' ? 'selected' : '' }}>VIP</option>
                                </select>
                                @error('account_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- User Info -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">معلومات المستخدم</h3>
                    </div>
                    <div class="card-body space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">تاريخ التسجيل:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $user->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">البريد الإلكتروني:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">متحقق</span>
                                @else
                                    <span class="badge badge-warning">غير متحقق</span>
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">إجمالي الحجوزات:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $user->bookings()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">إجمالي المقالات:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $user->articles()->count() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card">
                    <div class="card-body p-4">
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-save ml-1"></i>
                            حفظ التعديلات
                        </button>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline w-full mt-2">
                            <i class="fas fa-eye ml-1"></i>
                            عرض التفاصيل
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection


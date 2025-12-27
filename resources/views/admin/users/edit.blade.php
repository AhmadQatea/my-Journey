<x-admin.edit-form
    title="تعديل المستخدم"
    :page-title="'تعديل المستخدم: ' . $user->full_name"
    :action="route('admin.users.update', $user)"
    :model="$user"
    :back-route="route('admin.users.index')"
    submit-text="حفظ التعديلات"
    layout="grid"
>
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

    <x-slot name="sidebar">
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
    </x-slot>
</x-admin.edit-form>


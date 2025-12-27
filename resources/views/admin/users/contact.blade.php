{{-- resources/views/admin/users/contact.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'التواصل مع المستخدم: ' . $user->full_name)
@section('page-title', 'التواصل مع المستخدم')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">التواصل مع المستخدم</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                إرسال رسالة إلى: <span class="font-medium">{{ $user->full_name }}</span> ({{ $user->email }})
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.show', $user) }}"
               class="btn btn-outline inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>رجوع</span>
            </a>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">نموذج التواصل</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.contact.send', $user) }}" method="POST">
                @csrf

                <!-- User Info -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white text-2xl font-bold">
                            {{ mb_substr($user->full_name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-gray">{{ $user->full_name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            @if($user->phone)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $user->phone }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Subject -->
                <div class="form-group mb-4">
                    <label class="form-label" for="subject">
                        موضوع الرسالة <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="subject"
                           id="subject"
                           class="form-control @error('subject') is-invalid @enderror"
                           value="{{ old('subject') }}"
                           placeholder="أدخل موضوع الرسالة"
                           required>
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Message -->
                <div class="form-group mb-6">
                    <label class="form-label" for="message">
                        محتوى الرسالة <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message"
                              id="message"
                              rows="10"
                              class="form-control @error('message') is-invalid @enderror"
                              placeholder="اكتب رسالتك هنا..."
                              required>{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        الحد الأدنى: 10 أحرف
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        <span>إرسال الرسالة</span>
                    </button>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


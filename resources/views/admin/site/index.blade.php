@extends('admin.layouts.admin')

@section('title', 'إعدادات الموقع')
@section('page-title', 'إعدادات الموقع')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">إعدادات الموقع</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">عرض وتعديل معلومات الموقع العامة</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.site.edit') }}" class="btn btn-primary inline-flex items-center gap-2">
                <i class="fas fa-edit"></i>
                <span>تعديل الإعدادات</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="custom-alert success mb-6 animate-slideInUp">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 space-x-reverse">
                    <i class="fas fa-check-circle text-green-500 text-xl animate-pulse"></i>
                    <span class="text-green-700 dark:text-green-300 font-bold">{{ session('success') }}</span>
                </div>
                <button type="button" class="text-green-500 hover:text-green-700 dark:hover:text-green-400 transition-colors duration-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Settings Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- وصف مختصر عن الموقع -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title flex items-center gap-2">
                    <i class="fas fa-book"></i>
                    الوصف المختصر عن الموقع
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">الوصف</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 min-h-[100px]">
                            {!! nl2br(e($settings->about_story ?: 'لم يتم إضافة وصف للموقع بعد')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات التواصل -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title flex items-center gap-2">
                    <i class="fas fa-address-card"></i>
                    معلومات التواصل
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">البريد الإلكتروني</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            {{ $settings->contact_email ?: 'غير محدد' }}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">رقم الهاتف</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            {{ $settings->contact_phone ?: 'غير محدد' }}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">العنوان</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            {{ $settings->contact_address ?: 'غير محدد' }}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">أوقات الدوام</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            @if($settings->working_hours)
                                @if(is_array($settings->working_hours))
                                    @foreach($settings->working_hours as $day => $hours)
                                        <div>{{ $day }}: {{ $hours }}</div>
                                    @endforeach
                                @else
                                    {{ $settings->working_hours }}
                                @endif
                            @else
                                غير محدد
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- روابط التواصل الاجتماعي -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title flex items-center gap-2">
                    <i class="fas fa-share-alt"></i>
                    روابط مواقع التواصل الاجتماعي
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-3">
                    @foreach([
                        'facebook' => ['icon' => 'fab fa-facebook-f', 'label' => 'فيسبوك'],
                        'twitter' => ['icon' => 'fab fa-twitter', 'label' => 'تويتر'],
                        'instagram' => ['icon' => 'fab fa-instagram', 'label' => 'إنستغرام'],
                        'youtube' => ['icon' => 'fab fa-youtube', 'label' => 'يوتيوب'],
                        'linkedin' => ['icon' => 'fab fa-linkedin-in', 'label' => 'لينكد إن'],
                        'whatsapp' => ['icon' => 'fab fa-whatsapp', 'label' => 'واتساب'],
                    ] as $key => $info)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex items-center gap-3">
                                <i class="{{ $info['icon'] }} text-xl"></i>
                                <span>{{ $info['label'] }}</span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $settings->{'social_' . $key} ?: 'غير محدد' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- السياسات -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title flex items-center gap-2">
                    <i class="fas fa-file-contract"></i>
                    السياسات والشروط
                </h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    <div>
                        <label class="form-label">الشروط والأحكام</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 max-h-[150px] overflow-y-auto">
                            {!! $settings->terms_and_conditions ?: '<span class="text-gray-500">لم يتم إضافة الشروط والأحكام بعد</span>' !!}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">سياسة الخصوصية</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 max-h-[150px] overflow-y-auto">
                            {!! $settings->privacy_policy ?: '<span class="text-gray-500">لم يتم إضافة سياسة الخصوصية بعد</span>' !!}
                        </div>
                    </div>
                    <div>
                        <label class="form-label">سياسة ملفات التعريف</label>
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 max-h-[150px] overflow-y-auto">
                            {!! $settings->cookie_policy ?: '<span class="text-gray-500">لم يتم إضافة سياسة ملفات التعريف بعد</span>' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


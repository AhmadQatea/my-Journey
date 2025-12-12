@extends('admin.layouts.admin')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'تفاصيل المحافظة')
@section('page-title', 'تفاصيل المحافظة: ' . $governorate->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Governorate Info -->
        <x-card title="معلومات المحافظة">
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">اسم المحافظة</label>
                    <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $governorate->name }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">الموقع</label>
                    <p class="text-gray-700 dark:text-gray-300 mt-1 flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                        <span>{{ $governorate->location }}</span>
                    </p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">الوصف</label>
                    <p class="text-gray-700 dark:text-gray-300 mt-1 leading-relaxed">{{ $governorate->description }}</p>
                </div>
            </div>
        </x-card>

        <!-- Tourist Spots -->
        <x-card title="الأماكن السياحية ({{ $governorate->touristSpots->count() }})">
            @if($governorate->touristSpots->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($governorate->touristSpots as $spot)
                <div class="content-card p-4 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start space-x-3 space-x-reverse">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-emerald-400 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $spot->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($spot->description, 60) }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
                                    {{ $spot->type }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-map-marker-alt text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">لا توجد أماكن سياحية في هذه المحافظة</p>
            </div>
            @endif
        </x-card>

        <!-- Trips -->
        <x-card title="الرحلات ({{ $governorate->trips->count() }})">
            @if($governorate->trips->count() > 0)
            <div class="space-y-4">
                @foreach($governorate->trips as $trip)
                <div class="content-card p-4 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 space-x-reverse">
                            @if($trip->images && count($trip->images) > 0)
                            <img src="{{ Storage::url($trip->images[0]) }}"
                                 alt="{{ $trip->title }}"
                                 class="w-16 h-16 rounded-lg object-cover">
                            @else
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg flex items-center justify-center">
                                <i class="fas fa-route text-white"></i>
                            </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $trip->title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($trip->description, 60) }}</p>
                            </div>
                        </div>
                        <div class="text-left">
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                {{ number_format($trip->price, 2) }} ل.س
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-route text-gray-400 text-4xl mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">لا توجد رحلات في هذه المحافظة</p>
            </div>
            @endif
        </x-card>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Featured Image -->
        <x-card title="الصورة الرئيسية">
            @if($governorate->featured_image)
            <img src="{{ Storage::url($governorate->featured_image) }}"
                 alt="{{ $governorate->name }}"
                 class="w-full rounded-lg shadow-lg">
            @else
            <div class="w-full h-64 bg-gradient-to-br from-blue-400 to-green-400 rounded-lg flex items-center justify-center">
                <i class="fas fa-mountain text-white text-6xl"></i>
            </div>
            @endif
        </x-card>

        <!-- Statistics -->
        <x-card title="الإحصائيات">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">الأماكن السياحية</span>
                    </div>
                    <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $governorate->touristSpots->count() }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-route text-white"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">الرحلات</span>
                    </div>
                    <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $governorate->trips->count() }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-white"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">تاريخ الإنشاء</span>
                    </div>
                    <span class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ $governorate->created_at->format('Y/m/d') }}</span>
                </div>
            </div>
        </x-card>

        <!-- Actions -->
        <x-card title="الإجراءات">
            <div class="space-y-3">
                <a href="{{ route('admin.governorates.edit', $governorate) }}"
                   class="w-full px-4 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white text-center transition-all duration-300 flex items-center justify-center space-x-2 space-x-reverse">
                    <i class="fas fa-edit"></i>
                    <span>تعديل المحافظة</span>
                </a>
                <a href="{{ route('admin.governorates.index') }}"
                   class="w-full px-4 py-3 rounded-xl bg-gray-500 hover:bg-gray-600 text-white text-center transition-all duration-300 flex items-center justify-center space-x-2 space-x-reverse">
                    <i class="fas fa-arrow-right"></i>
                    <span>العودة للقائمة</span>
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection


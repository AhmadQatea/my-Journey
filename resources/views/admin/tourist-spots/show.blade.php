@extends('admin.layouts.admin')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'تفاصيل المكان السياحي')
@section('page-title', 'تفاصيل المكان السياحي: ' . $touristSpot->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Tourist Spot Info -->
        <x-card title="معلومات المكان السياحي">
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">اسم المكان السياحي</label>
                    <p class="text-lg font-bold text-gray-900 dark:text-black mt-1">{{ $touristSpot->name }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">المحافظة</label>
                    <p class="text-gray-700 dark:text-gray-500 mt-1 flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-map text-indigo-500"></i>
                        <span>{{ $touristSpot->governorate->name }}</span>
                    </p>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">الفئات</label>
                    <div class="flex flex-wrap gap-2 mt-1">
                        @if($touristSpot->categories && $touristSpot->categories->count() > 0)
                            @foreach($touristSpot->categories as $category)
                            <span class="badge badge-purple">
                                <i class="fas fa-tag ml-1"></i>
                                {{ $category->name }}
                            </span>
                            @endforeach
                        @else
                            <span class="text-sm text-gray-500 dark:text-gray-400">لا توجد فئات</span>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">الموقع</label>
                    <p class="text-gray-700 dark:text-gray-500 mt-1 flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-map-marker-alt text-red-500"></i>
                        <span>{{ $touristSpot->location }}</span>
                    </p>
                </div>

                @if($touristSpot->coordinates)
                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">الإحداثيات على الخريطة</label>
                    <div class="mt-2 space-y-2">
                        <p class="text-gray-700 dark:text-gray-500 flex items-center space-x-2 space-x-reverse">
                            <i class="fas fa-map text-indigo-500"></i>
                            <span><strong>{{ $touristSpot->coordinates }}</strong></span>
                        </p>
                        @php
                            $coords = explode(',', trim($touristSpot->coordinates));
                            $lat = trim($coords[0] ?? '');
                            $lng = trim($coords[1] ?? '');
                        @endphp
                        @if($lat && $lng)
                        <a href="https://www.google.com/maps?q={{ $lat }},{{ $lng }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-black rounded-lg transition-all duration-300 mt-2">
                            <i class="fas fa-external-link-alt"></i>
                            <span>فتح في Google Maps</span>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">الوصف</label>
                    <p class="text-gray-700 dark:text-gray-500 mt-1 leading-relaxed">{{ $touristSpot->description }}</p>
                </div>

                @if($touristSpot->entrance_fee)
                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">رسوم الدخول</label>
                    <p class="text-lg font-bold text-green-600 dark:text-green-400 mt-1">
                        {{ number_format($touristSpot->entrance_fee, 0) }} ل.س
                    </p>
                </div>
                @endif

                @if($touristSpot->opening_hours)
                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">ساعات العمل</label>
                    <p class="text-gray-700 dark:text-gray-500 mt-1 flex items-center space-x-2 space-x-reverse">
                        <i class="fas fa-clock text-amber-500"></i>
                        <span>{{ $touristSpot->opening_hours }}</span>
                    </p>
                </div>
                @endif
            </div>
        </x-card>

        <!-- Images Gallery -->
        @if($touristSpot->images && count($touristSpot->images) > 0)
        <x-card title="معرض الصور ({{ count($touristSpot->images) }})">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($touristSpot->images as $image)
                <div class="relative group overflow-hidden rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-emerald-500 transition-all duration-300">
                    <img src="{{ Storage::url($image) }}"
                         alt="{{ $touristSpot->name }}"
                         class="w-full h-48 object-cover hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 flex items-center justify-center">
                        <a href="{{ Storage::url($image) }}"
                           target="_blank"
                           class="opacity-0 group-hover:opacity-100 transition-opacity duration-300 text-black bg-emerald-500 p-2 rounded-full">
                            <i class="fas fa-expand"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </x-card>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Featured Image -->
        @if($touristSpot->images && count($touristSpot->images) > 0)
        <x-card title="الصورة الرئيسية">
            <img src="{{ Storage::url($touristSpot->images[0]) }}"
                 alt="{{ $touristSpot->name }}"
                 class="w-full rounded-lg shadow-lg">
        </x-card>
        @else
        <x-card title="الصورة الرئيسية">
            <div class="w-full h-64 bg-gradient-to-br from-emerald-400 to-teal-400 rounded-lg flex items-center justify-center">
                <i class="fas fa-image text-black text-6xl"></i>
            </div>
        </x-card>
        @endif

        <!-- Statistics -->
        <x-card title="الإحصائيات">
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-images text-black"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-500">عدد الصور</span>
                    </div>
                    <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $touristSpot->images ? count($touristSpot->images) : 0 }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-indigo-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-map text-black"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-500">المحافظة</span>
                    </div>
                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ $touristSpot->governorate->name }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tag text-black"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-500">الفئات</span>
                    </div>
                    <div class="flex flex-wrap gap-1 justify-end">
                        @if($touristSpot->categories && $touristSpot->categories->count() > 0)
                            @foreach($touristSpot->categories as $category)
                            <span class="text-xs font-bold text-purple-600 dark:text-purple-400">{{ $category->name }}</span>
                            @endforeach
                        @else
                            <span class="text-xs text-gray-500 dark:text-gray-400">-</span>
                        @endif
                    </div>
                </div>

                @if($touristSpot->entrance_fee)
                <div class="flex items-center justify-between p-4 bg-amber-50 dark:bg-amber-900/20 rounded-lg">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-coins text-black"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-500">رسوم الدخول</span>
                    </div>
                    <span class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ number_format($touristSpot->entrance_fee, 0) }} ل.س</span>
                </div>
                @endif

                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar text-black"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-500">تاريخ الإنشاء</span>
                    </div>
                    <span class="text-sm font-bold text-gray-600 dark:text-gray-400">{{ $touristSpot->created_at->format('Y/m/d') }}</span>
                </div>
            </div>
        </x-card>

        <!-- Actions -->
        <x-card title="الإجراءات">
            <div class="space-y-3">
                <a href="{{ route('admin.tourist-spots.edit', $touristSpot) }}"
                   class="w-full px-4 py-3 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-black text-center transition-all duration-300 flex items-center justify-center space-x-2 space-x-reverse">
                    <i class="fas fa-edit"></i>
                    <span>تعديل المكان</span>
                </a>
                <a href="{{ route('admin.tourist-spots.index') }}"
                   class="w-full px-4 py-3 rounded-xl bg-gray-500 hover:bg-gray-600 text-black text-center transition-all duration-300 flex items-center justify-center space-x-2 space-x-reverse">
                    <i class="fas fa-arrow-right"></i>
                    <span>العودة للقائمة</span>
                </a>
            </div>
        </x-card>
    </div>
</div>
@endsection

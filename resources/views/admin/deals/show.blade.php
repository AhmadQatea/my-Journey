{{-- resources/views/admin/deals/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'تفاصيل العرض: ' . $deal->title)
@section('page-title', 'تفاصيل العرض: ' . $deal->title)

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ $deal->title }}</h1>
            <div class="flex items-center gap-3 mt-2 flex-wrap">
                <span class="badge {{ $deal->status == 'مفعل' ? 'badge-success' : 'badge-danger' }}">
                    {{ $deal->status }}
                </span>
                <span class="badge badge-danger">
                    خصم {{ $deal->discount_percentage }}%
                </span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.deals.edit', $deal) }}"
               class="btn btn-warning inline-flex items-center gap-2">
                <i class="fas fa-edit"></i>
                <span>تعديل</span>
            </a>
            <a href="{{ route('admin.deals.index') }}"
               class="btn btn-outline inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>رجوع للقائمة</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Offer Description -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">وصف العرض</h3>
                    <div class="prose max-w-none text-gray-700 dark:text-gray-300">
                        {!! nl2br(e($deal->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Trip Information -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات الرحلة المرتبطة</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">عنوان الرحلة:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $deal->trip->title }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">المحافظة:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $deal->trip->governorate->name }}</span>
                        </div>
                        @if($deal->trip->departureGovernorate)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">محافظة الانطلاق:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $deal->trip->departureGovernorate->name }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">السعر الأصلي:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ number_format($deal->trip->price, 0) }} ل.س</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customizations -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">التخصيصات</h3>
                    <div class="space-y-4">
                        @if($deal->custom_price)
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-tag text-blue-600 dark:text-blue-400"></i>
                                <span class="font-medium text-gray-900 dark:text-gray">سعر مخصص</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ number_format($deal->custom_price, 0) }} ل.س</p>
                        </div>
                        @endif

                        @if($deal->custom_start_time)
                        <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-clock text-green-600 dark:text-green-400"></i>
                                <span class="font-medium text-gray-900 dark:text-gray">وقت بدء مخصص</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($deal->custom_start_time)->format('h:i A') }}</p>
                        </div>
                        @endif

                        @if($deal->custom_duration_hours)
                        <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-hourglass-half text-purple-600 dark:text-purple-400"></i>
                                <span class="font-medium text-gray-900 dark:text-gray">مدة مخصصة</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $deal->custom_duration_hours }} ساعة</p>
                        </div>
                        @endif

                        @if($deal->custom_max_persons)
                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-users text-yellow-600 dark:text-yellow-400"></i>
                                <span class="font-medium text-gray-900 dark:text-gray">عدد أقصى مخصص</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $deal->custom_max_persons }} شخص</p>
                        </div>
                        @endif

                        @if($deal->custom_meeting_point)
                        <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-map-marker-alt text-indigo-600 dark:text-indigo-400"></i>
                                <span class="font-medium text-gray-900 dark:text-gray">نقطة لقاء مخصصة</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $deal->custom_meeting_point }}</p>
                        </div>
                        @endif

                        @if($deal->custom_departure_governorate_id && $deal->customDepartureGovernorate)
                        <div class="p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-map text-teal-600 dark:text-teal-400"></i>
                                <span class="font-medium text-gray-900 dark:text-gray">محافظة انطلاق مخصصة</span>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300">{{ $deal->customDepartureGovernorate->name }}</p>
                        </div>
                        @endif

                        @if(!$deal->custom_price && !$deal->custom_start_time && !$deal->custom_duration_hours && !$deal->custom_max_persons && !$deal->custom_meeting_point && !$deal->custom_departure_governorate_id)
                        <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                            <i class="fas fa-info-circle text-2xl mb-2"></i>
                            <p>لا توجد تخصيصات - سيتم استخدام قيم الرحلة الأصلية</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Included Places -->
            @php
                $includedPlaces = $deal->getIncludedPlaces();
                $placeIds = array_filter($includedPlaces, 'is_numeric');
                $touristSpots = \App\Models\TouristSpot::whereIn('id', $placeIds)->with('governorate')->get();
            @endphp
            @if($touristSpots->count() > 0)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">الأماكن المضمنة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($touristSpots as $spot)
                        @php
                            $hasCoordinates = $spot->coordinates && !empty(trim($spot->coordinates));
                            $mapUrl = null;
                            if ($hasCoordinates) {
                                $coords = explode(',', trim($spot->coordinates));
                                $lat = trim($coords[0] ?? '');
                                $lng = trim($coords[1] ?? '');
                                if ($lat && $lng) {
                                    $mapUrl = "https://www.google.com/maps?q={$lat},{$lng}";
                                }
                            }
                        @endphp
                        @if($mapUrl)
                        <a href="{{ $mapUrl }}"
                           target="_blank"
                           class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 cursor-pointer group">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div class="flex-1">
                                <span class="font-medium text-gray-800 dark:text-gray-200 block group-hover:text-primary transition-colors">{{ $spot->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $spot->governorate->name }}</span>
                            </div>
                            <i class="fas fa-external-link-alt text-gray-400 group-hover:text-primary transition-colors text-sm"></i>
                        </a>
                        @else
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div>
                                <span class="font-medium text-gray-800 dark:text-gray-200 block">{{ $spot->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $spot->governorate->name }}</span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Features -->
            @php
                $features = $deal->getFeatures();
            @endphp
            @if(count($features) > 0)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">مميزات العرض</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($features as $feature)
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pricing -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">السعر</h3>
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1">
                            {{ number_format($deal->getFinalPrice(), 0) }} ل.س
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">السعر النهائي</p>
                        @if(!$deal->custom_price)
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400 line-through">
                                {{ number_format($deal->trip->price, 0) }} ل.س
                            </p>
                            <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                خصم {{ $deal->discount_percentage }}%
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Offer Period -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">فترة العرض</h3>
                <div class="card-body p-4">
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">تاريخ البدء:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $deal->start_date->format('Y/m/d') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">تاريخ الانتهاء:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $deal->end_date->format('Y/m/d') }}</span>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            @php
                                $daysRemaining = now()->diffInDays($deal->end_date, false);
                            @endphp
                            @if($daysRemaining > 0)
                                <p class="text-sm text-green-600 dark:text-green-400">
                                    <i class="fas fa-clock ml-1"></i>
                                    متبقي {{ $daysRemaining }} يوم
                                </p>
                            @else
                                <p class="text-sm text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-circle ml-1"></i>
                                    العرض منتهي
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trip Details -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">تفاصيل الرحلة</h3>
                <div class="card-body p-4">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">المدة:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $deal->getDurationHours() }} ساعة</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">وقت البدء:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ \Carbon\Carbon::parse($deal->getStartTime())->format('h:i A') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">العدد الأقصى:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $deal->getMaxPersons() }} شخص</span>
                        </div>
                        @if($deal->getMeetingPoint())
                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400 block mb-1">نقطة اللقاء:</span>
                            <span class="text-gray-900 dark:text-gray">{{ $deal->getMeetingPoint() }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">الإجراءات</h3>
                <div class="card-body p-4 space-y-3">
                    <form action="{{ route('admin.deals.changeStatus', $deal) }}" method="POST" class="mb-0">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">تغيير الحالة</label>
                            <select name="status" class="form-control form-select">
                                <option value="مفعل" {{ $deal->status == 'مفعل' ? 'selected' : '' }}>مفعل</option>
                                <option value="منتهي" {{ $deal->status == 'منتهي' ? 'selected' : '' }}>منتهي</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fas fa-save ml-1"></i>
                            تحديث الحالة
                        </button>
                    </form>

                    <hr class="border-gray-200 dark:border-gray-700">

                    <form action="{{ route('admin.deals.destroy', $deal) }}"
                          method="POST"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full">
                            <i class="fas fa-trash ml-1"></i>
                            حذف العرض
                        </button>
                    </form>
                </div>
            </div>

            <!-- Creator Info (VIP Users) -->
            @if($deal->creator && !$deal->created_by_admin)
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات المنشئ</h3>
                <div class="card-body p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-gray">{{ $deal->creator->full_name ?? $deal->creator->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $deal->created_at->format('Y/m/d') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Creator Info (Admin) -->
            @if($deal->created_by_admin && $deal->adminCreator)
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات المنشئ</h3>
                <div class="card-body p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center">
                            <i class="fas fa-user-shield text-emerald-600"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-gray">{{ $deal->adminCreator->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $deal->adminCreator->email }}</p>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1">
                                <i class="fas fa-shield-alt ml-1"></i> مسؤول
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection


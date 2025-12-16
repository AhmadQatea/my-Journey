{{-- resources/views/admin/trips/show.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'تفاصيل الرحلة: ' . $trip->title)
@section('page-title', 'تفاصيل الرحلة: ' . $trip->title)

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ $trip->title }}</h1>
            <div class="flex items-center gap-3 mt-2 flex-wrap">
                <span class="badge {{ $trip->status == 'مقبولة' || $trip->status == 'قيد التفعيل' ? 'badge-success' : ($trip->status == 'مرفوضة' ? 'badge-danger' : 'badge-warning') }}">
                    {{ $trip->status }}
                </span>
                @if($trip->is_featured)
                    <span class="badge badge-warning">
                        <i class="fas fa-star ml-1"></i> مميزة
                    </span>
                @endif
                @if($trip->source_type == 'vip_user')
                    <span class="badge badge-purple">
                        <i class="fas fa-crown ml-1"></i> من VIP
                    </span>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.trips.edit', $trip) }}"
               class="btn btn-warning inline-flex items-center gap-2">
                <i class="fas fa-edit"></i>
                <span>تعديل</span>
            </a>
            <a href="{{ route('admin.trips.index') }}"
               class="btn btn-outline inline-flex items-center gap-2">
                <i class="fas fa-arrow-right"></i>
                <span>رجوع للقائمة</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Images Gallery -->
            @if($trip->images && count($trip->images) > 0)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">صور الرحلة</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($trip->images as $index => $image)
                        <div class="relative group">
                            <img src="{{ Storage::url($image) }}"
                                 alt="صورة الرحلة {{ $index + 1 }}"
                                 class="w-full h-48 object-cover rounded-lg cursor-pointer"
                                 onclick="openImageModal('{{ Storage::url($image) }}')">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                <button type="button"
                                        onclick="openImageModal('{{ Storage::url($image) }}')"
                                        class="btn btn-primary btn-sm">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Trip Description -->
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">وصف الرحلة</h3>
                    <div class="prose max-w-none text-gray-700 dark:text-gray-300">
                        {!! $trip->description !!}
                    </div>
                </div>
            </div>

            <!-- Included Places -->
            @if($trip->included_places && count($trip->included_places) > 0)
            @php
                // تحويل IDs إلى أسماء الأماكن السياحية
                $placeIds = array_filter($trip->included_places, 'is_numeric');
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
                                <span class="text-xs text-gray-400 dark:text-gray-500 block mt-1">
                                    <i class="fas fa-info-circle ml-1"></i>
                                    لا توجد إحداثيات متاحة
                                </span>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- Trip Features -->
            @if($trip->features && count($trip->features) > 0)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">مميزات الرحلة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($trip->features as $feature)
                        <div class="flex items-center gap-3">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Requirements -->
            @if($trip->requirements)
            <div class="card">
                <div class="card-body p-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">متطلبات الرحلة</h3>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $trip->requirements }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Trip Information -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات الرحلة</h3>
                <div class="space-y-4">
                    <!-- Basic Info -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">المحافظة:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $trip->governorate->name }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">التصنيفات:</span>
                            <div class="flex flex-wrap gap-1">
                                @if($trip->categories && $trip->categories->count() > 0)
                                    @foreach($trip->categories as $category)
                                        <span class="badge badge-info text-xs">
                                            {{ $category->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-sm text-gray-400">غير محدد</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">نوع الرحلة:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $trip->trip_type }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">المدة:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $trip->duration_hours }} ساعة</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">العدد الأقصى:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $trip->max_persons }} شخص</span>
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700">

                    <!-- Schedule Info -->
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">تاريخ البدء:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ $trip->start_date->format('Y/m/d') }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">وقت البدء:</span>
                            <span class="font-medium text-gray-900 dark:text-gray">{{ \Carbon\Carbon::parse($trip->start_time)->format('h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Capacity -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">السعر والسعة</h3>
                <div class="space-y-4">
                    <!-- Price -->
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400 mb-1">
                            {{ number_format($trip->price, 0) }} ل.س
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">سعر الفرد</p>
                    </div>

                    <!-- Capacity Progress -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">المقاعد:</span>
                            <span class="font-medium {{ $trip->available_seats < 5 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $trip->available_seats }} / {{ $trip->max_persons }}
                            </span>
                        </div>

                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            @php
                                $percentage = (($trip->max_persons - $trip->available_seats) / $trip->max_persons) * 100;
                            @endphp
                            <div class="bg-primary h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>

                        <div class="text-center text-sm text-gray-500">
                            {{ number_format($percentage, 1) }}% محجوز
                        </div>
                    </div>

                    @if($trip->source_type == 'vip_user' && $trip->vip_commission)
                    <div class="text-center p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                        <p class="text-sm text-purple-600 dark:text-purple-400">
                            <i class="fas fa-crown ml-1"></i>
                            عمولة VIP: {{ $trip->vip_commission }}%
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Meeting Point -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">نقطة اللقاء</h3>
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $trip->meeting_point }}</p>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-2 gap-4">
                <div class="stat-card text-center">
                    <div class="stat-number text-blue-600">{{ $trip->bookings_count ?? 0 }}</div>
                    <div class="stat-label">الحجوزات</div>
                </div>

                <div class="stat-card text-center">
                    <div class="stat-number text-green-600">
                        {{ number_format(($trip->bookings_count ?? 0) * $trip->price, 0) }}
                    </div>
                    <div class="stat-label">الإيرادات (ل.س)</div>
                </div>

                <div class="stat-card text-center">
                    <div class="stat-number text-yellow-600">{{ $trip->views_count ?? 0 }}</div>
                    <div class="stat-label">المشاهدات</div>
                </div>

                <div class="stat-card text-center">
                    <div class="stat-number text-purple-600">
                        {{ $trip->created_at->diffForHumans() }}
                    </div>
                    <div class="stat-label">منذ الإنشاء</div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">الإجراءات</h3>
                <div class="space-y-3">
                    <!-- Status Change -->
                    @if($trip->source_type == 'vip_user')
                    <!-- للرحلات التي ينشئها مستخدمون VIP -->
                    <div class="space-y-2">
                        <div class="form-group">
                            <label class="form-label">تغيير الحالة</label>
                            <select id="statusSelect" name="status" class="form-control form-select">
                                <option value="معلقة" {{ $trip->status == 'معلقة' ? 'selected' : '' }}>معلقة</option>
                                <option value="مقبولة" {{ $trip->status == 'مقبولة' ? 'selected' : '' }}>مقبولة</option>
                                <option value="مرفوضة" {{ $trip->status == 'مرفوضة' ? 'selected' : '' }}>مرفوضة</option>
                            </select>
                        </div>

                        <div id="rejectReasonGroup" class="form-group {{ $trip->status == 'مرفوضة' ? '' : 'hidden' }}">
                            <label class="form-label">سبب الرفض</label>
                            <textarea id="rejectReason"
                                      name="reason"
                                      class="form-control"
                                      rows="3"
                                      placeholder="أدخل سبب الرفض (اختياري)">{{ $trip->rejection_reason ?? '' }}</textarea>
                        </div>

                        <button type="button" id="updateStatusBtn" class="btn btn-primary w-full">
                            <i class="fas fa-save ml-1"></i>
                            تحديث الحالة
                        </button>
                    </div>
                    @else
                    <!-- للرحلات التي ينشئها المسؤولون: تكون مفعلة (مقبولة) بشكل افتراضي، يمكن تغييرها إلى "قيد التفعيل" -->
                    <div class="space-y-2">
                        <div class="form-group">
                            <label class="form-label">تغيير الحالة</label>
                            <select id="statusSelect" name="status" class="form-control form-select">
                                <option value="مقبولة" {{ $trip->status == 'مقبولة' ? 'selected' : '' }}>مقبولة (مفعلة)</option>
                                <option value="قيد التفعيل" {{ $trip->status == 'قيد التفعيل' ? 'selected' : '' }}>قيد التفعيل</option>
                            </select>
                        </div>

                        <button type="button" id="updateStatusBtn" class="btn btn-primary w-full">
                            <i class="fas fa-save ml-1"></i>
                            تحديث الحالة
                        </button>
                    </div>
                    @endif

                    <hr class="border-gray-200 dark:border-gray-700">

                    <!-- Toggle Featured -->
                    <form action="{{ route('admin.trips.feature', $trip) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="btn {{ $trip->is_featured ? 'btn-secondary' : 'btn-primary' }} w-full">
                            <i class="fas fa-star ml-1"></i>
                            {{ $trip->is_featured ? 'إلغاء التمييز' : 'تمييز الرحلة' }}
                        </button>
                    </form>

                    <!-- Delete -->
                    <form action="{{ route('admin.trips.destroy', $trip) }}"
                          method="POST"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذه الرحلة؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-full">
                            <i class="fas fa-trash ml-1"></i>
                            حذف الرحلة
                        </button>
                    </form>
                </div>
            </div>

            <!-- Creator Info (VIP Trips) -->
            @if($trip->source_type == 'vip_user' && $trip->creator)
            <div class="card">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray mb-4">معلومات المنشئ</h3>
                <div class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                    <div class="w-12 h-12 rounded-full bg-purple-500 text-gray flex items-center justify-center">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-gray">{{ $trip->creator->full_name ?? 'مستخدم VIP' }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $trip->creator->email }}</p>
                        <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">
                            <i class="fas fa-phone ml-1"></i> {{ $trip->creator->phone ?? 'غير متوفر' }}
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 p-4">
    <div class="relative w-full max-w-4xl">
        <button type="button"
                onclick="closeImageModal()"
                class="absolute -top-10 left-0 text-gray hover:text-gray-300">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="modalImage" src="" class="w-full h-auto max-h-[80vh] object-contain rounded-lg">
    </div>
</div>
@endsection

@push('scripts')
<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Close modal on background click
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Status change functionality
const statusSelect = document.getElementById('statusSelect');
const rejectReasonGroup = document.getElementById('rejectReasonGroup');
const updateStatusBtn = document.getElementById('updateStatusBtn');

if (statusSelect) {
    statusSelect.addEventListener('change', function() {
        // إظهار/إخفاء حقل سبب الرفض فقط للرحلات VIP
        if (rejectReasonGroup) {
            if (this.value === 'مرفوضة') {
                rejectReasonGroup.classList.remove('hidden');
            } else {
                rejectReasonGroup.classList.add('hidden');
            }
        }
    });
}

if (updateStatusBtn) {
    updateStatusBtn.addEventListener('click', function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.trips.status', $trip) }}';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = statusSelect.value;
        form.appendChild(statusInput);

        // إضافة سبب الرفض فقط للرحلات VIP عند الرفض
        if (statusSelect.value === 'مرفوضة' && rejectReasonGroup) {
            const rejectReason = document.getElementById('rejectReason');
            if (rejectReason) {
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'reason';
                reasonInput.value = rejectReason.value;
                form.appendChild(reasonInput);
            }
        }

        document.body.appendChild(form);
        form.submit();
    });
}
</script>
@endpush

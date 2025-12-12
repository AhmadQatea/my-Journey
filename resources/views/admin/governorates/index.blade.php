@extends('admin.layouts.admin')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'إدارة المحافظات')
@section('page-title', 'إدارة المحافظات')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Search and Actions Section -->
    <div class="flex flex-col lg:flex-row gap-6 mb-8">
        <div class="flex-1">
            <div class="search-box">
                <input type="text"
                       class="search-input"
                       placeholder="البحث في المحافظات..."
                       id="searchInput">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.governorates.create') }}"
               class="btn-primary flex items-center gap-2 px-6 py-3 rounded-xl transition-all duration-300 hover-lift">
                <i class="fas fa-plus"></i>
                <span>إضافة محافظة</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-8">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">إجمالي المحافظات</p>
                    <p class="stat-value">{{ $governorates->total() }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">محافظة</p>
                </div>
                <div class="icon-container bg-gradient-to-br from-blue-500 to-cyan-500">
                    <i class="fas fa-mountain text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">الأماكن السياحية</p>
                    <p class="stat-value">{{ $governorates->sum('tourist_spots_count') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">مكان سياحي</p>
                </div>
                <div class="icon-container bg-gradient-to-br from-emerald-500 to-green-500">
                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">الرحلات</p>
                    <p class="stat-value">{{ $governorates->sum('trips_count') }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">رحلة</p>
                </div>
                <div class="icon-container bg-gradient-to-br from-violet-500 to-purple-500">
                    <i class="fas fa-route text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-1">المحافظات النشطة</p>
                    <p class="stat-value">{{ $governorates->count() }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">نشطة</p>
                </div>
                <div class="icon-container bg-gradient-to-br from-amber-500 to-orange-500">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="content-card p-6">
        @if($governorates->count() > 0)
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>الصورة</th>
                        <th>اسم المحافظة</th>
                        <th>الموقع</th>
                        <th>الأماكن السياحية</th>
                        <th>الرحلات</th>
                        <th>تاريخ الإنشاء</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($governorates as $governorate)
                    <tr>
                        <td>
                            <div class="w-16 h-16 rounded-xl overflow-hidden border-2 border-gray-200 dark:border-gray-700">
                                @if($governorate->featured_image)
                                <img src="{{ Storage::url($governorate->featured_image) }}"
                                     alt="{{ $governorate->name }}"
                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                @else
                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-cyan-400 flex items-center justify-center">
                                    <i class="fas fa-mountain text-white text-xl"></i>
                                </div>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white">{{ $governorate->name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                    {{ Str::limit($governorate->description, 70) }}
                                </p>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-map-marker-alt text-blue-500"></i>
                                <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $governorate->location }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="flex justify-center">
                                <span class="badge badge-green">
                                    <i class="fas fa-map-marker-alt ml-1"></i>
                                    {{ $governorate->tourist_spots_count }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="flex justify-center">
                                <span class="badge badge-blue">
                                    <i class="fas fa-route ml-1"></i>
                                    {{ $governorate->trips_count }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                                {{ $governorate->created_at->format('Y/m/d') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.governorates.show', $governorate) }}"
                                   class="action-btn view"
                                   title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.governorates.edit', $governorate) }}"
                                   class="action-btn edit"
                                   title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.governorates.destroy', $governorate) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('هل أنت متأكد من حذف هذه المحافظة؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="action-btn delete"
                                            title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-mountain"></i>
            <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">لا توجد محافظات</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">ابدأ بإضافة محافظة جديدة لإدارة المحتوى السياحي</p>
            <a href="{{ route('admin.governorates.create') }}"
               class="btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-xl">
                <i class="fas fa-plus"></i>
                <span>إضافة محافظة جديدة</span>
            </a>
        </div>
        @endif

        <!-- Pagination -->
        @if($governorates->hasPages())
        <div class="mt-8">
            {{ $governorates->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.data-table tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const isVisible = text.includes(searchTerm);
            row.style.display = isVisible ? '' : 'none';
            row.style.opacity = isVisible ? '1' : '0';
            row.style.transform = isVisible ? 'translateX(0)' : 'translateX(-20px)';
        });
    });

    // Loading animation for images
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('load', function() {
            this.classList.add('loaded');
        });
    });
</script>
@endpush
@endsection

{{-- governorates/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'إدارة المحافظات')
@section('page-title', 'المحافظات')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-black">المحافظات</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">إدارة جميع محافظات النظام</p>
        </div>

        <a href="{{ route('admin.governorates.create') }}"
           class="btn btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i>
            <span>إضافة محافظة جديدة</span>
        </a>
    </div>

    <!-- Filters Section -->
    <div class="card mb-6">
        <div class="card-body p-4">
            <div class="search-box">
                <input type="text"
                       class="search-input"
                       placeholder="ابحث عن محافظة..."
                       id="searchInput"
                       autocomplete="off">
                <i class="fas fa-search search-icon"></i>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">إجمالي المحافظات</p>
                    <p class="stat-value">{{ $governorates->total() }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-blue-500 to-cyan-500">
                    <i class="fas fa-mountain"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">الأماكن السياحية</p>
                    <p class="stat-value">{{ $governorates->sum('tourist_spots_count') }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-emerald-500 to-green-500">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">الرحلات</p>
                    <p class="stat-value">{{ $governorates->sum('trips_count') }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-violet-500 to-purple-500">
                    <i class="fas fa-route"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">المحافظات النشطة</p>
                    <p class="stat-value">{{ $governorates->count() }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-amber-500 to-orange-500">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">قائمة المحافظات</h3>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    عرض {{ $governorates->firstItem() ?? 0 }} - {{ $governorates->lastItem() ?? 0 }} من {{ $governorates->total() }}
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($governorates->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-16">الصورة</th>
                                <th>المحافظة</th>
                                <th class="w-32">الموقع</th>
                                <th class="w-24">الأماكن</th>
                                <th class="w-24">الرحلات</th>
                                <th class="w-28">التاريخ</th>
                                <th class="w-28">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($governorates as $governorate)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td>
                                        <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mx-auto">
                                            @if($governorate->featured_image)
                                                <img src="{{ Storage::url($governorate->featured_image) }}"
                                                     alt="{{ $governorate->name }}"
                                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-200">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-cyan-400 flex items-center justify-center">
                                                    <i class="fas fa-mountain text-black text-xs"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="space-y-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-black text-sm">
                                                {{ $governorate->name }}
                                            </h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 max-w-[200px]">
                                                {{ Str::limit(strip_tags($governorate->description), 60) }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-blue-500 text-xs"></i>
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-500 truncate max-w-[120px]">
                                                {{ $governorate->location }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-success text-xs">
                                            {{ $governorate->tourist_spots_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary text-xs">
                                            {{ $governorate->trips_count }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $governorate->created_at->format('Y/m/d') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons justify-center">
                                            <a href="{{ route('admin.governorates.show', $governorate) }}"
                                               class="action-btn view"
                                               title="عرض التفاصيل"
                                               data-tooltip="عرض">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('admin.governorates.edit', $governorate) }}"
                                               class="action-btn edit"
                                               title="تعديل"
                                               data-tooltip="تعديل">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <form action="{{ route('admin.governorates.destroy', $governorate) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف {{ $governorate->name }}؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn delete"
                                                        title="حذف"
                                                        data-tooltip="حذف">
                                                    <i class="fas fa-trash text-xs"></i>
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
                    <div class="empty-state-icon">
                        <i class="fas fa-mountain"></i>
                    </div>
                    <h4 class="empty-state-title">لا توجد محافظات</h4>
                    <p class="empty-state-description">
                        لم يتم إضافة أي محافظات بعد. ابدأ بإضافة أول محافظة.
                    </p>
                    <a href="{{ route('admin.governorates.create') }}"
                       class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>إضافة محافظة جديدة</span>
                    </a>
                </div>
            @endif
        </div>

        @if($governorates->hasPages())
            <div class="card-footer">
                <div class="pagination">
                    @if($governorates->onFirstPage())
                        <span class="pagination-link disabled">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @else
                        <a href="{{ $governorates->previousPageUrl() }}" class="pagination-link">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @endif

                    @foreach(range(1, $governorates->lastPage()) as $page)
                        @if($page == $governorates->currentPage())
                            <span class="pagination-link active">{{ $page }}</span>
                        @else
                            <a href="{{ $governorates->url($page) }}" class="pagination-link">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($governorates->hasMorePages())
                        <a href="{{ $governorates->nextPageUrl() }}" class="pagination-link">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @else
                        <span class="pagination-link disabled">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Real-time search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('.data-table tbody tr');

        if (searchInput && tableRows.length > 0) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();

                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    row.style.display = rowText.includes(searchTerm) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush

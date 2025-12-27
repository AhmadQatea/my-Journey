{{-- tourist-spots/index.blade.php --}}
@extends('admin.layouts.admin')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', __('messages.manage_tourist_spots'))
@section('page-title', __('messages.tourist_spots'))

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-black">{{ __('messages.tourist_spots') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.manage_all_tourist_spots') }}</p>
        </div>

        <a href="{{ route('admin.tourist-spots.create') }}"
           class="btn btn-primary inline-flex items-center gap-2">
            <i class="fas fa-plus text-sm"></i>
            <span>إضافة مكان جديد</span>
        </a>
    </div>

    <!-- Filters Section -->
    <div class="card mb-6">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.tourist-spots.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.search') }}</label>
                        <div class="search-box">
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="search-input"
                                   placeholder="{{ __('messages.search_tourist_spot') }}"
                                   autocomplete="off">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>

                    <!-- Governorate Filter -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.governorate') }}</label>
                        <select name="governorate_id"
                                class="form-control form-select"
                                onchange="this.form.submit()">
                            <option value="">{{ __('messages.all_provinces') }}</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}" {{ request('governorate_id') == $gov->id ? 'selected' : '' }}>
                                    {{ $gov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.category') }}</label>
                        <select name="category_id"
                                class="form-control form-select"
                                onchange="this.form.submit()">
                            <option value="">{{ __('messages.all_categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if(request('search') || request('governorate_id') || request('category_id'))
                    <div class="flex items-center gap-2 pt-2">
                        <span class="text-xs text-gray-600 dark:text-gray-400">{{ __('messages.filter_active') }}:</span>
                        @if(request('search'))
                            <span class="badge badge-outline text-xs">{{ request('search') }}</span>
                        @endif
                        @if(request('governorate_id'))
                            @php $gov = $governorates->where('id', request('governorate_id'))->first(); @endphp
                            @if($gov)
                                <span class="badge badge-outline text-xs">{{ $gov->name }}</span>
                            @endif
                        @endif
                        @if(request('category_id'))
                            @php $cat = $categories->where('id', request('category_id'))->first(); @endphp
                            @if($cat)
                                <span class="badge badge-outline text-xs">{{ $cat->name }}</span>
                            @endif
                        @endif
                        <a href="{{ route('admin.tourist-spots.index') }}"
                           class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 flex items-center gap-1 ml-2">
                            <i class="fas fa-times text-xs"></i>
                            {{ __('messages.remove_filter') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">إجمالي الأماكن</p>
                    <p class="stat-value">{{ $touristSpots->total() }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-emerald-500 to-teal-500">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">فئات مختلفة</p>
                    <p class="stat-value">{{ $categories->count() }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-purple-500 to-pink-500">
                    <i class="fas fa-tags"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">متوسط الرسوم</p>
                    <p class="stat-value">{{ number_format($touristSpots->whereNotNull('entrance_fee')->avg('entrance_fee') ?? 0, 0) }}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">ل.س</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-amber-500 to-yellow-500">
                    <i class="fas fa-coins"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">المحافظات</p>
                    <p class="stat-value">{{ $touristSpots->pluck('governorate_id')->unique()->count() }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-indigo-500 to-blue-500">
                    <i class="fas fa-map"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h3 class="card-title">قائمة الأماكن السياحية</h3>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    عرض {{ $touristSpots->firstItem() ?? 0 }} - {{ $touristSpots->lastItem() ?? 0 }} من {{ $touristSpots->total() }}
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($touristSpots->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-20">الصورة</th>
                                <th>المكان</th>
                                <th class="w-32">المحافظة</th>
                                <th class="w-32">النوع</th>
                                <th class="w-28">الرسوم</th>
                                <th class="w-28">التاريخ</th>
                                <th class="w-28">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($touristSpots as $spot)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td>
                                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mx-auto">
                                            @if($spot->images && count($spot->images) > 0)
                                                <img src="{{ Storage::url($spot->images[0]) }}"
                                                     alt="{{ $spot->name }}"
                                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-200">
                                                @if(count($spot->images) > 1)
                                                    <div class="absolute top-1 left-1 bg-black/70 text-black text-[10px] px-1.5 py-0.5 rounded-full">
                                                        +{{ count($spot->images) - 1 }}
                                                    </div>
                                                @endif
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-emerald-400 to-teal-400 flex items-center justify-center">
                                                    <i class="fas fa-image text-black text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="space-y-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-black text-sm truncate max-w-[200px]">
                                                {{ $spot->name }}
                                            </h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 max-w-[200px]">
                                                {{ Str::limit(strip_tags($spot->description), 50) }}
                                            </p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-map text-indigo-500 text-xs"></i>
                                            <span class="text-xs font-medium text-gray-700 dark:text-gray-500">
                                                {{ $spot->governorate->name }}
                                            </span>
                                        </div>
                                    </td>
                    <td>
                        <div class="flex flex-wrap gap-1">
                            @if($spot->categories && $spot->categories->count() > 0)
                                @foreach($spot->categories as $category)
                                    <span class="badge badge-info text-[10px] px-2 py-0.5">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-xs text-gray-500 dark:text-gray-400">-</span>
                            @endif
                        </div>
                    </td>
                                    <td>
                                        @if($spot->entrance_fee)
                                            <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                                {{ number_format($spot->entrance_fee, 0) }} ل.س
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500 dark:text-gray-400">مجاني</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $spot->created_at->format('Y/m/d') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons justify-center">
                                            <a href="{{ route('admin.tourist-spots.show', $spot) }}"
                                               class="action-btn view"
                                               title="عرض التفاصيل"
                                               data-tooltip="عرض">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('admin.tourist-spots.edit', $spot) }}"
                                               class="action-btn edit"
                                               title="تعديل"
                                               data-tooltip="تعديل">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <form action="{{ route('admin.tourist-spots.destroy', $spot) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف {{ $spot->name }}؟');">
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
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h4 class="empty-state-title">لا توجد أماكن سياحية</h4>
                    <p class="empty-state-description">
                        لم يتم إضافة أي أماكن سياحية بعد. ابدأ بإضافة أول مكان سياحي.
                    </p>
                    <a href="{{ route('admin.tourist-spots.create') }}"
                       class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>{{ __('messages.add_new_tourist_spot') }}</span>
                    </a>
                </div>
            @endif
        </div>

        @if($touristSpots->hasPages())
            <div class="card-footer">
                {{ $touristSpots->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Real-time search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
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

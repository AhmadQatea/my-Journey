{{-- resources/views/admin/deals/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'إدارة العروض')
@section('page-title', 'إدارة العروض')

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">إدارة العروض</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">إدارة جميع العروض والصفقات الخاصة</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- إحصائيات سريعة -->
            <div class="hidden sm:flex items-center gap-4 text-sm">
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">مفعل: {{ $stats['active'] }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">منتهي: {{ $stats['expired'] }}</span>
                </div>
            </div>

            <a href="{{ route('admin.deals.create') }}"
               class="btn btn-primary inline-flex items-center gap-2">
                <i class="fas fa-plus text-sm"></i>
                <span>إضافة عرض جديد</span>
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-6">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.deals.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="form-group">
                        <label class="form-label">بحث</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="ابحث عن عرض...">
                    </div>

                    <!-- Status Filter -->
                    <div class="form-group">
                        <label class="form-label">الحالة</label>
                        <select name="status" class="form-control form-select">
                            <option value="">جميع الحالات</option>
                            <option value="مفعل" {{ request('status') == 'مفعل' ? 'selected' : '' }}>مفعل</option>
                            <option value="منتهي" {{ request('status') == 'منتهي' ? 'selected' : '' }}>منتهي</option>
                        </select>
                    </div>

                    <!-- Trip Filter -->
                    <div class="form-group">
                        <label class="form-label">الرحلة</label>
                        <select name="trip_id" class="form-control form-select">
                            <option value="">جميع الرحلات</option>
                            @foreach($trips as $trip)
                                <option value="{{ $trip->id }}" {{ request('trip_id') == $trip->id ? 'selected' : '' }}>
                                    {{ $trip->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search ml-1"></i>
                        بحث
                    </button>
                    <a href="{{ route('admin.deals.index') }}" class="btn btn-outline">
                        <i class="fas fa-redo ml-1"></i>
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">إجمالي العروض</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                        <i class="fas fa-tags text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">عروض مفعلة</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['active'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">عروض منتهية</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $stats['expired'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 dark:text-red-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h3 class="card-title">قائمة العروض</h3>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    عرض {{ $offers->firstItem() ?? 0 }} - {{ $offers->lastItem() ?? 0 }} من {{ $offers->total() }}
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($offers->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>العنوان</th>
                                <th class="w-48">الرحلة</th>
                                <th class="w-24">الخصم</th>
                                <th class="w-32">السعر النهائي</th>
                                <th class="w-32">الفترة</th>
                                <th class="w-24">الحالة</th>
                                <th class="w-32">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($offers as $offer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td>
                                    <div class="font-medium text-gray-900 dark:text-gray">{{ $offer->title }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ Str::limit($offer->description, 50) }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900 dark:text-gray">{{ $offer->trip->title }}</div>
                                        <div class="text-gray-500 dark:text-gray-400">{{ $offer->trip->governorate->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-danger">
                                        {{ $offer->discount_percentage }}%
                                    </span>
                                </td>
                                <td>
                                    <div class="font-bold text-green-600 dark:text-green-400">
                                        {{ number_format($offer->getFinalPrice(), 0) }} ل.س
                                    </div>
                                    @if($offer->custom_price)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">سعر مخصص</div>
                                    @else
                                        <div class="text-xs text-gray-500 dark:text-gray-400 line-through">
                                            {{ number_format($offer->trip->price, 0) }} ل.س
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <div class="text-gray-900 dark:text-gray">{{ $offer->start_date->format('Y/m/d') }}</div>
                                        <div class="text-gray-500 dark:text-gray-400">إلى {{ $offer->end_date->format('Y/m/d') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $offer->status == 'مفعل' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $offer->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.deals.show', $offer) }}"
                                           class="btn btn-sm btn-info"
                                           title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.deals.edit', $offer) }}"
                                           class="btn btn-sm btn-warning"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.deals.destroy', $offer) }}"
                                              method="POST"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
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
                    <div class="empty-state-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h4 class="empty-state-title">لا توجد عروض</h4>
                    <p class="empty-state-description">
                        لم يتم إضافة أي عروض بعد. ابدأ بإضافة أول عرض.
                    </p>
                    <a href="{{ route('admin.deals.create') }}"
                       class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>إضافة عرض جديد</span>
                    </a>
                </div>
            @endif
        </div>

        @if($offers->hasPages())
            <div class="card-footer">
                {{ $offers->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>
@endsection

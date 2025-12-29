{{-- resources/views/admin/trips/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', __('messages.manage_trips'))
@section('page-title', __('messages.manage_trips'))

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ __('messages.manage_trips') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.manage_all_trips') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- إحصائيات سريعة -->
            <div class="hidden sm:flex items-center gap-4 text-sm">
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">{{ __('messages.accepted') }}: {{ $stats['accepted'] }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">{{ __('messages.pending') }}: {{ $stats['pending'] }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">{{ __('messages.rejected') }}: {{ $stats['rejected'] }}</span>
                </div>
            </div>

            <a href="{{ route('admin.trips.create') }}"
               class="btn btn-primary inline-flex items-center gap-2">
                <i class="fas fa-plus text-sm"></i>
                <span>{{ __('messages.add_new_trip') }}</span>
            </a>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div id="bulkRejectModal" class="modal hidden">
        <div class="modal-content max-w-md">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('messages.reject_selected_trips') }}</h3>
                <button type="button" class="modal-close" data-modal-hide="bulkRejectModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="bulkRejectForm">
                <div class="modal-body">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        @php
                            $countText = str_replace(':count', '<span id="selectedCount">0</span>', __('messages.will_reject_count'));
                        @endphp
                        {!! $countText !!}
                    </p>

                    <div class="form-group">
                        <label class="form-label">{{ __('messages.rejection_reason_optional') }}</label>
                        <textarea id="bulkRejectReason"
                                  name="reason"
                                  class="form-control"
                                  rows="4"
                                  placeholder="{{ __('messages.enter_rejection_reason_users') }}"></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ __('messages.will_send_reason_email') }}
                        </p>
                    </div>

                    <input type="hidden" id="bulkRejectIds" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-modal-hide="bulkRejectModal">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="button" id="submitBulkReject" class="btn btn-danger">
                        <i class="fas fa-times ml-1"></i>
                        {{ __('messages.reject_selected') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-6">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.trips.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.search') }}</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'ابحث عن رحلة...' : 'Search for a trip...' }}">
                    </div>

                    <!-- Governorate Filter -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.governorate') }}</label>
                        <select name="governorate_id" class="form-control form-select">
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
                        <label class="form-label">{{ __('messages.type_filter') }}</label>
                        <select name="category_id" class="form-control form-select">
                            <option value="">{{ __('messages.all_types') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.status') }}</label>
                        <select name="status" class="form-control form-select">
                            <option value="">{{ app()->getLocale() === 'ar' ? 'جميع الحالات' : 'All Statuses' }}</option>
                            <option value="معلقة" {{ request('status') == 'معلقة' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                            <option value="مقبولة" {{ request('status') == 'مقبولة' ? 'selected' : '' }}>{{ __('messages.accepted') }}</option>
                            <option value="قيد التفعيل" {{ request('status') == 'قيد التفعيل' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                            <option value="مرفوضة" {{ request('status') == 'مرفوضة' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                        </select>
                    </div>

                    <!-- Source Type Filter -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.source') }}</label>
                        <select name="source_type" class="form-control form-select">
                            <option value="">{{ __('messages.all_sources') }}</option>
                            <option value="admin" {{ request('source_type') == 'admin' ? 'selected' : '' }}>{{ __('messages.from_admin') }}</option>
                            <option value="vip_user" {{ request('source_type') == 'vip_user' ? 'selected' : '' }}>{{ __('messages.from_vip') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Date Range and Advanced Filters -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                    <!-- Date From -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.from_date') }}</label>
                        <input type="date"
                               name="date_from"
                               value="{{ request('date_from') }}"
                               class="form-control">
                    </div>

                    <!-- Date To -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.to_date') }}</label>
                        <input type="date"
                               name="date_to"
                               value="{{ request('date_to') }}"
                               class="form-control">
                    </div>

                    <!-- Price Range -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.price_from') }}</label>
                        <input type="number"
                               name="price_from"
                               value="{{ request('price_from') }}"
                               class="form-control"
                               placeholder="{{ __('messages.lowest_price') }}"
                               min="0">
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ __('messages.price_to') }}</label>
                        <input type="number"
                               name="price_to"
                               value="{{ request('price_to') }}"
                               class="form-control"
                               placeholder="{{ __('messages.highest_price') }}"
                               min="0">
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        <span>{{ __('messages.apply_filter') }}</span>
                    </button>

                    @if(request()->anyFilled(['search', 'governorate_id', 'category_id', 'status', 'source_type', 'date_from', 'date_to', 'price_from', 'price_to']))
                        <a href="{{ route('admin.trips.index') }}" class="btn btn-outline text-sm inline-flex items-center gap-1">
                            <i class="fas fa-times"></i>
                            <span>{{ __('messages.remove_filter') }}</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">{{ __('messages.total_trips') }}</p>
                    <p class="stat-value">{{ $stats['total'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-blue-500 to-cyan-500">
                    <i class="fas fa-route"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">{{ __('messages.pending') }}</p>
                    <p class="stat-value">{{ $stats['pending'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-yellow-500 to-orange-500">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">{{ __('messages.accepted') }}</p>
                    <p class="stat-value">{{ $stats['accepted'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-emerald-500 to-green-500">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">{{ __('messages.from_vip_short') }}</p>
                    <p class="stat-value">{{ $stats['vip_trips'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-purple-500 to-pink-500">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar" class="hidden card mb-4">
        <div class="card-body p-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-700 dark:text-gray-300">
                        @php
                            $selectedText = str_replace(':count', '<span id="selectedItemsCount">0</span>', __('messages.selected_trips_count'));
                        @endphp
                        {!! $selectedText !!}
                    </span>
                    <button type="button" id="clearSelection" class="text-sm text-red-600 hover:text-red-700">
                        <i class="fas fa-times ml-1"></i> {{ __('messages.clear_selection') }}
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" id="bulkAcceptBtn" class="btn btn-success btn-sm hidden">
                        <i class="fas fa-check ml-1"></i> {{ __('messages.accept_selected') }}
                    </button>
                    <button type="button" id="bulkRejectBtn" class="btn btn-danger btn-sm hidden">
                        <i class="fas fa-times ml-1"></i> {{ __('messages.reject_selected') }}
                    </button>
                    <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash ml-1"></i> {{ __('messages.delete_selected') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h3 class="card-title">{{ __('messages.trips_list') }}</h3>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('messages.showing') }} {{ $trips->firstItem() ?? 0 }} {{ __('messages.to') }} {{ $trips->lastItem() ?? 0 }} {{ __('messages.of') }} {{ $trips->total() }}
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($trips->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-12">
                                    <input type="checkbox" id="selectAll" class="rounded">
                                </th>
                                <th class="w-16">{{ __('messages.image') }}</th>
                                <th>{{ __('messages.trip') }}</th>
                                <th class="w-32">{{ __('messages.governorate') }}</th>
                                <th class="w-32">{{ __('messages.type_filter') }}</th>
                                <th class="w-24">{{ __('messages.price') }}</th>
                                <th class="w-20">{{ __('messages.seats') }}</th>
                                <th class="w-24">{{ __('messages.status') }}</th>
                                <th class="w-28">{{ __('messages.date') }}</th>
                                <th class="w-32">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trips as $trip)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50" data-source-type="{{ $trip->source_type }}">
                                    <td>
                                        <input type="checkbox"
                                               class="trip-checkbox rounded"
                                               value="{{ $trip->id }}"
                                               data-status="{{ $trip->status }}">
                                    </td>
                                    <td>
                                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mx-auto">
                                            @if($trip->images && count($trip->images) > 0)
                                                <img src="{{ Storage::url($trip->images[0]) }}"
                                                     alt="{{ $trip->title }}"
                                                     class="w-full h-full object-cover hover:scale-110 transition-transform duration-200">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-cyan-400 flex items-center justify-center">
                                                    <i class="fas fa-route text-gray text-sm"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-semibold text-gray-900 dark:text-gray text-sm truncate max-w-[200px]">
                                                    {{ $trip->title }}
                                                </h4>
                                                @if($trip->is_featured)
                                                    <span class="badge badge-warning text-[10px] px-1.5 py-0.5">
                                                        <i class="fas fa-star text-xs ml-1"></i>
                                                        {{ __('messages.featured') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 max-w-[200px]">
                                                {{ Str::limit(strip_tags($trip->description), 60) }}
                                            </p>
                                            @if($trip->source_type == 'vip_user' && $trip->creator)
                                                <div class="flex items-center gap-1 mt-1">
                                                    <i class="fas fa-crown text-purple-500 text-xs"></i>
                                                    <span class="text-[10px] text-purple-600 dark:text-purple-400 font-medium">
                                                        {{ $trip->creator?->full_name ?? __('messages.vip_user') }}
                                                    </span>
                                                </div>
                                            @elseif($trip->created_by_admin && $trip->adminCreator)
                                                <div class="flex items-center gap-1 mt-1">
                                                    <i class="fas fa-user-shield text-emerald-500 text-xs"></i>
                                                    <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">
                                                        {{ $trip->adminCreator->name }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                            {{ $trip->governorate->name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($trip->categories && $trip->categories->count() > 0)
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($trip->categories as $category)
                                                    <span class="badge badge-info text-xs">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="badge badge-info text-xs">{{ __('messages.not_specified') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            {{ number_format($trip->price, 0) }} ل.س
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex flex-col items-center">
                                            <span class="text-xs font-semibold {{ $trip->available_seats < 5 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $trip->available_seats }}
                                            </span>
                                            <span class="text-[10px] text-gray-500">{{ __('messages.from') }} {{ $trip->max_persons }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($trip->status == 'مقبولة')
                                            <span class="badge badge-success text-xs">{{ __('messages.accepted') }}</span>
                                        @elseif($trip->status == 'قيد التفعيل')
                                            <span class="badge badge-success text-xs">{{ __('messages.active') }}</span>
                                        @elseif($trip->status == 'مرفوضة')
                                            <span class="badge badge-danger text-xs">{{ __('messages.rejected') }}</span>
                                        @else
                                            <span class="badge badge-warning text-xs">{{ __('messages.pending') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $trip->created_at->format('Y/m/d') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons justify-center">
                                            <a href="{{ route('admin.trips.show', $trip) }}"
                                               class="action-btn view"
                                               title="{{ __('messages.view') }}"
                                               data-tooltip="{{ __('messages.view') }}">
                                                <i class="fas fa-eye text-xs"></i>
                                            </a>
                                            <a href="{{ route('admin.trips.edit', $trip) }}"
                                               class="action-btn edit"
                                               title="{{ __('messages.edit') }}"
                                               data-tooltip="{{ __('messages.edit') }}">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>

                                            <!-- Status Actions (فقط للرحلات التي ينشئها مستخدمون VIP) -->
                                            @if($trip->source_type == 'vip_user')
                                                @if($trip->status != 'مقبولة')
                                                    <form action="{{ route('admin.trips.status', $trip) }}"
                                                          method="POST"
                                                          class="inline"
                                                          onsubmit="return confirm('{{ __('messages.do_you_want_to_accept_trip') }}');">
                                                        @csrf
                                                        <input type="hidden" name="status" value="مقبولة">
                                                        <button type="submit"
                                                                class="action-btn view"
                                                                title="{{ __('messages.accept') }}"
                                                                data-tooltip="{{ __('messages.accept') }}">
                                                            <i class="fas fa-check text-xs"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($trip->status != 'مرفوضة')
                                                    <button type="button"
                                                            onclick="showRejectModal('{{ $trip->id }}', '{{ $trip->title }}')"
                                                            class="action-btn delete"
                                                            title="{{ __('messages.reject') }}"
                                                            data-tooltip="{{ __('messages.reject') }}">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </button>
                                                @endif
                                            @endif

                                            <form action="{{ route('admin.trips.destroy', $trip) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('{{ __('messages.are_you_sure') }} {{ __('messages.delete') }} {{ $trip->title }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="action-btn delete"
                                                        title="{{ __('messages.delete') }}"
                                                        data-tooltip="{{ __('messages.delete') }}">
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
                        <i class="fas fa-route"></i>
                    </div>
                    <h4 class="empty-state-title">{{ __('messages.no_trips_found') }}</h4>
                    <p class="empty-state-description">
                        {{ __('messages.no_trips_added_yet') }}
                    </p>
                    <a href="{{ route('admin.trips.create') }}"
                       class="btn btn-primary inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>{{ __('messages.add_new_trip') }}</span>
                    </a>
                </div>
            @endif
        </div>

        @if($trips->hasPages())
            <div class="card-footer">
                {{ $trips->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>

<!-- Reject Modal for Single Trip -->
<div id="rejectModal" class="modal hidden">
    <div class="modal-content max-w-md">
        <div class="modal-header">
            <h3 class="modal-title">{{ __('messages.reject_trip') }}</h3>
            <button type="button" class="modal-close" data-modal-hide="rejectModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="modal-body">
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    {{ __('messages.will_reject_trip') }} <span id="tripTitle" class="font-semibold"></span>
                </p>

                <div class="form-group">
                    <label class="form-label">{{ __('messages.rejection_reason_optional') }}</label>
                    <textarea name="reason"
                              class="form-control"
                              rows="4"
                              placeholder="{{ __('messages.enter_rejection_reason_user') }}"></textarea>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ __('messages.will_send_reason_email_user') }}
                    </p>
                </div>

                <input type="hidden" name="status" value="مرفوضة">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-hide="rejectModal">
                    {{ __('messages.cancel') }}
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-times ml-1"></i>
                    {{ __('messages.reject_trip') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All Checkbox
    const selectAll = document.getElementById('selectAll');
    const tripCheckboxes = document.querySelectorAll('.trip-checkbox');
    const bulkActionsBar = document.getElementById('bulkActionsBar');
    const selectedItemsCount = document.getElementById('selectedItemsCount');
    const clearSelectionBtn = document.getElementById('clearSelection');

    // Function to update bulk actions bar
    function updateBulkActionsBar() {
        const selectedCheckboxes = document.querySelectorAll('.trip-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;

        // Count VIP trips only
        let vipCount = 0;
        selectedCheckboxes.forEach(checkbox => {
            const tripRow = checkbox.closest('tr');
            const sourceType = tripRow?.getAttribute('data-source-type');
            if (sourceType === 'vip_user') {
                vipCount++;
            }
        });

        if (selectedCount > 0) {
            bulkActionsBar.classList.remove('hidden');
            selectedItemsCount.textContent = selectedCount;

            // Show/hide accept/reject buttons only if there are VIP trips selected
            const bulkAcceptBtn = document.getElementById('bulkAcceptBtn');
            const bulkRejectBtn = document.getElementById('bulkRejectBtn');

            if (vipCount > 0) {
                bulkAcceptBtn?.classList.remove('hidden');
                bulkRejectBtn?.classList.remove('hidden');
            } else {
                bulkAcceptBtn?.classList.add('hidden');
                bulkRejectBtn?.classList.add('hidden');
            }
        } else {
            bulkActionsBar.classList.add('hidden');
        }

        // Update Select All checkbox state
        if (selectAll) {
            selectAll.checked = selectedCount === tripCheckboxes.length;
            selectAll.indeterminate = selectedCount > 0 && selectedCount < tripCheckboxes.length;
        }
    }

    // Select All functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            const isChecked = this.checked;
            tripCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateBulkActionsBar();
        });
    }

    // Individual checkbox functionality
    tripCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsBar);
    });

    // Clear selection
    if (clearSelectionBtn) {
        clearSelectionBtn.addEventListener('click', function() {
            tripCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            if (selectAll) selectAll.checked = false;
            updateBulkActionsBar();
        });
    }

    // Bulk Accept
    const bulkAcceptBtn = document.getElementById('bulkAcceptBtn');
    if (bulkAcceptBtn) {
        bulkAcceptBtn.addEventListener('click', function() {
            const selectedIds = getSelectedTripIds();
            if (selectedIds.length === 0) {
                alert('يرجى اختيار رحلات أولاً');
                return;
            }

            // Filter only VIP trips
            const vipTripIds = selectedIds.filter(id => {
                const checkbox = document.querySelector(`.trip-checkbox[value="${id}"]`);
                const tripRow = checkbox?.closest('tr');
                return tripRow?.getAttribute('data-source-type') === 'vip_user';
            });

            if (vipTripIds.length === 0) {
                alert('{{ __('messages.cannot_accept_selected') }}');
                return;
            }

            const acceptMessage = '{{ __('messages.do_you_want_to_accept') }}'.replace(':count', vipTripIds.length);
            if (confirm(acceptMessage)) {
                sendBulkAction('accept', vipTripIds);
            }
        });
    }

    // Bulk Reject
    const bulkRejectBtn = document.getElementById('bulkRejectBtn');
    if (bulkRejectBtn) {
        bulkRejectBtn.addEventListener('click', function() {
            const selectedIds = getSelectedTripIds();
            if (selectedIds.length === 0) {
                alert('{{ __('messages.please_select_trips') }}');
                return;
            }

            // Filter only VIP trips
            const vipTripIds = selectedIds.filter(id => {
                const checkbox = document.querySelector(`.trip-checkbox[value="${id}"]`);
                const tripRow = checkbox?.closest('tr');
                return tripRow?.getAttribute('data-source-type') === 'vip_user';
            });

            if (vipTripIds.length === 0) {
                alert('{{ __('messages.cannot_reject_selected') }}');
                return;
            }

            // Show reject modal for bulk action
            document.getElementById('selectedCount').textContent = vipTripIds.length;
            document.getElementById('bulkRejectIds').value = JSON.stringify(vipTripIds);
            document.getElementById('bulkRejectReason').value = '';

            const modal = new Modal(document.getElementById('bulkRejectModal'));
            modal.show();
        });
    }

    // Submit bulk reject form
    const submitBulkReject = document.getElementById('submitBulkReject');
    if (submitBulkReject) {
        submitBulkReject.addEventListener('click', function() {
            const selectedIds = JSON.parse(document.getElementById('bulkRejectIds').value || '[]');
            const reason = document.getElementById('bulkRejectReason').value;

            if (selectedIds.length === 0) {
                alert('{{ __('messages.please_select_trips') }}');
                return;
            }

            sendBulkAction('reject', selectedIds, reason);

            // Hide modal
            const modal = new Modal(document.getElementById('bulkRejectModal'));
            modal.hide();
        });
    }

    // Bulk Delete
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function() {
            const selectedIds = getSelectedTripIds();
            if (selectedIds.length === 0) {
                alert('{{ __('messages.please_select_trips') }}');
                return;
            }

            const deleteMessage = '{{ __('messages.are_you_sure_delete_count') }}'.replace(':count', selectedIds.length);
            if (confirm(deleteMessage)) {
                sendBulkAction('delete', selectedIds);
            }
        });
    }

    // Single trip reject modal
    window.showRejectModal = function(tripId, tripTitle) {
        document.getElementById('tripTitle').textContent = tripTitle;
        document.getElementById('rejectForm').action = `/admin/trips/${tripId}/status`;

        const modal = new Modal(document.getElementById('rejectModal'));
        modal.show();
    };

    // Helper function to get selected trip IDs
    function getSelectedTripIds() {
        const selectedIds = [];
        const selectedCheckboxes = document.querySelectorAll('.trip-checkbox:checked');

        selectedCheckboxes.forEach(checkbox => {
            selectedIds.push(checkbox.value);
        });

        return selectedIds;
    }

    // Helper function to send bulk action
    function sendBulkAction(action, ids, reason = null) {
        const formData = new FormData();
        formData.append('action', action);
        formData.append('ids', JSON.stringify(ids));
        if (reason) {
            formData.append('reason', reason);
        }

        fetch('{{ route("admin.trips.bulk-action") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('{{ __('messages.error_occurred') }}: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('messages.error_executing_action') }}');
        });
    }

    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            const tooltip = document.createElement('div');
            tooltip.className = 'fixed z-50 px-2 py-1 text-xs font-medium text-gray bg-gray-900 rounded-lg shadow-sm';
            tooltip.textContent = this.getAttribute('data-tooltip');
            document.body.appendChild(tooltip);

            const rect = this.getBoundingClientRect();
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';
            tooltip.style.left = (rect.left + rect.width / 2 - tooltip.offsetWidth / 2) + 'px';

            this._tooltip = tooltip;
        });

        element.addEventListener('mouseleave', function(e) {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });

    // Modal functionality
    class Modal {
        constructor(modalElement) {
            this.modal = modalElement;
            this.init();
        }

        init() {
            // Close buttons
            const closeButtons = this.modal.querySelectorAll('.modal-close, [data-modal-hide]');
            closeButtons.forEach(button => {
                button.addEventListener('click', () => this.hide());
            });

            // Close on background click
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.hide();
                }
            });

            // Close on ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                    this.hide();
                }
            });
        }

        show() {
            this.modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        hide() {
            this.modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    // Initialize all modals
    document.querySelectorAll('.modal').forEach(modal => {
        new Modal(modal);
    });
});
</script>
@endpush

@extends('admin.layouts.admin')

@section('title', __('messages.identity_verification_requests'))
@section('page-title', __('messages.identity_verification'))

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('messages.pending_verifications') }}</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</p>
                    </div>
                    <i class="fas fa-clock text-3xl text-yellow-500"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('messages.approved_verifications') }}</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</p>
                    </div>
                    <i class="fas fa-check-circle text-3xl text-green-500"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('messages.rejected_verifications') }}</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
                    </div>
                    <i class="fas fa-times-circle text-3xl text-red-500"></i>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('messages.total_verifications') }}</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</p>
                    </div>
                    <i class="fas fa-list text-3xl text-blue-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.identity-verifications.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="{{ __('messages.search_by_name_email') }}"
                           class="form-input w-full">
                </div>
                <div class="min-w-[150px]">
                    <select name="status" class="form-select w-full">
                        <option value="">{{ __('messages.all_statuses') }}</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.pending') }}</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>{{ __('messages.accepted') }}</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>{{ __('messages.rejected') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search ml-1"></i>{{ __('messages.search') }}
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.identity-verifications.index') }}" class="btn btn-outline">
                        <i class="fas fa-times ml-1"></i>{{ __('messages.reset') }}
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            @if($verifications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-right p-3">{{ __('messages.users') }}</th>
                                <th class="text-right p-3">{{ __('messages.request_date') }}</th>
                                <th class="text-right p-3">{{ __('messages.status') }}</th>
                                <th class="text-right p-3">{{ __('messages.reviewer') }}</th>
                                <th class="text-right p-3">{{ __('messages.review_date') }}</th>
                                <th class="text-right p-3">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($verifications as $verification)
                                <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="p-3">
                                        <div>
                                            <div class="font-medium">{{ $verification->user->full_name }}</div>
                                            <div class="text-sm text-gray-500">{{ $verification->user->email }}</div>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        {{ $verification->created_at->format('Y-m-d H:i') }}
                                    </td>
                                    <td class="p-3">
                                        @php
                                            $statusColors = [
                                                'pending' => 'badge-warning',
                                                'approved' => 'badge-success',
                                                'rejected' => 'badge-danger',
                                            ];
                                            $statusLabels = [
                                                'pending' => 'معلقة',
                                                'approved' => 'مقبولة',
                                                'rejected' => 'مرفوضة',
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusColors[$verification->status] ?? 'badge-secondary' }}">
                                            {{ $statusLabels[$verification->status] ?? $verification->status }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        {{ $verification->reviewer->name ?? '-' }}
                                    </td>
                                    <td class="p-3">
                                        {{ $verification->reviewed_at ? $verification->reviewed_at->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    <td class="p-3">
                                        <a href="{{ route('admin.identity-verifications.show', $verification) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye ml-1"></i>عرض
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $verifications->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 dark:text-gray-400">لا توجد طلبات توثيق هوية</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection


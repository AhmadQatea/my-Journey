{{-- resources/views/admin/users/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', __('messages.manage_users'))
@section('page-title', __('messages.manage_users'))

@section('content')
<div class="container mx-auto px-4 py-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray">{{ __('messages.manage_users') }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('messages.manage_all_users') }}</p>
        </div>

        <div class="flex items-center gap-3">
            <!-- إحصائيات سريعة -->
            <div class="hidden sm:flex items-center gap-4 text-sm">
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">{{ __('messages.visitor') }}: {{ $stats['visitor'] }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">{{ __('messages.active') }}: {{ $stats['active'] }}</span>
                </div>
                <div class="flex items-center gap-1">
                    <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                    <span class="text-gray-600 dark:text-gray-400">{{ __('messages.vip') }}: {{ $stats['vip'] }}</span>
            </div>
            </div>
        </div>
            </div>

    <!-- Filters Section -->
    <div class="card mb-6">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.search') }}</label>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               class="form-control"
                               placeholder="{{ __('messages.search_user') }}">
            </div>

                    <!-- Account Type Filter -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.account_type') }}</label>
                        <select name="account_type" class="form-control form-select">
                            <option value="all">{{ __('messages.all_types') }}</option>
                            <option value="visitor" {{ request('account_type') == 'visitor' ? 'selected' : '' }}>{{ __('messages.visitor') }}</option>
                            <option value="active" {{ request('account_type') == 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                            <option value="vip" {{ request('account_type') == 'vip' ? 'selected' : '' }}>{{ __('messages.vip') }}</option>
                        </select>
            </div>

                    <!-- Role Filter -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.role') }}</label>
                        <select name="role_id" class="form-control form-select">
                            <option value="">{{ __('messages.all_roles') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
            </div>

                    <!-- Email Verified Filter -->
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.email_verified') }}</label>
                        <select name="email_verified" class="form-control form-select">
                            <option value="">{{ __('messages.all') }}</option>
                            <option value="yes" {{ request('email_verified') == 'yes' ? 'selected' : '' }}>{{ __('messages.verified') }}</option>
                            <option value="no" {{ request('email_verified') == 'no' ? 'selected' : '' }}>{{ __('messages.not_verified') }}</option>
                        </select>
            </div>
        </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter ml-1"></i>
                        {{ __('messages.filter') }}
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline">
                        <i class="fas fa-sync ml-1"></i>
                        {{ __('messages.reset') }}
                    </a>
                </div>
            </form>
        </div>
            </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">{{ __('messages.total_users') }}</p>
                    <p class="stat-value">{{ $stats['total'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-blue-500 to-cyan-500">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">{{ __('messages.visitor') }}</p>
                    <p class="stat-value">{{ $stats['visitor'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-gray-500 to-gray-600">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                    <div>
                    <p class="stat-label">{{ __('messages.active') }}</p>
                    <p class="stat-value">{{ $stats['active'] }}</p>
                </div>
                <div class="stat-icon bg-gradient-to-br from-emerald-500 to-green-500">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="stat-label">VIP</p>
                    <p class="stat-value">{{ $stats['vip'] }}</p>
        </div>
                <div class="stat-icon bg-gradient-to-br from-purple-500 to-pink-500">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-header">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <h3 class="card-title">{{ __('messages.users_list') }}</h3>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('messages.showing') }} {{ $users->firstItem() ?? 0 }} {{ __('messages.to') }} {{ $users->lastItem() ?? 0 }} {{ __('messages.of') }} {{ $users->total() }}
                </span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($users->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th class="w-16">#</th>
                                <th>{{ __('messages.users') }}</th>
                                <th class="w-32">{{ __('messages.role') }}</th>
                                <th class="w-32">{{ __('messages.account_type') }}</th>
                                <th class="w-24">{{ __('messages.bookings') }}</th>
                                <th class="w-24">{{ __('messages.articles') }}</th>
                                <th class="w-24">{{ __('messages.status') }}</th>
                                <th class="w-32">{{ __('messages.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="font-medium text-gray-900 dark:text-gray-200">{{ $user->full_name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->phone }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($user->role)
                                        <span class="badge badge-info">{{ $user->role->name }}</span>
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $accountTypeColors = [
                                            'visitor' => 'badge-secondary',
                                            'active' => 'badge-success',
                                            'vip' => 'badge-warning',
                                        ];
                                        $accountTypeLabels = [
                                            'visitor' => __('messages.visitor'),
                                            'active' => __('messages.active'),
                                            'vip' => __('messages.vip'),
                                        ];
                                    @endphp
                                    <span class="badge {{ $accountTypeColors[$user->account_type] ?? 'badge-secondary' }}">
                                        {{ $accountTypeLabels[$user->account_type] ?? $user->account_type }}
                                    </span>
                                </td>
                                <td>{{ $user->bookings_count ?? 0 }}</td>
                                <td>{{ $user->articles_count ?? 0 }}</td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge badge-success">{{ __('messages.verified') }}</span>
                                    @else
                                        <span class="badge badge-warning">{{ __('messages.not_verified') }}</span>
    @endif
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm" title="{{ __('messages.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm" title="{{ __('messages.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.users.contact', $user) }}" class="btn btn-primary btn-sm" title="{{ app()->getLocale() === 'ar' ? 'التواصل' : 'Contact' }}">
                                            <i class="fas fa-envelope"></i>
                                        </a>
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
                        <i class="fas fa-users"></i>
                    </div>
                    <h4 class="empty-state-title">{{ __('messages.no_users_found') }}</h4>
                    <p class="empty-state-description">
                        {{ app()->getLocale() === 'ar' ? 'لم يتم إضافة أي مستخدمين بعد.' : 'No users have been added yet.' }}
                    </p>
                </div>
            @endif
        </div>

        @if($users->hasPages())
            <div class="card-footer">
                {{ $users->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
    </div>
@endsection

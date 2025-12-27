@extends('admin.layouts.admin')

@section('title', __('messages.manage_admins'))
@section('page-title', __('messages.manage_admins'))

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('messages.all_administrators') }}</h3>
        <div class="flex gap-3">
            <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
                <i class="fas fa-user-tag"></i> {{ __('messages.create_role') }}
            </a>
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> {{ __('messages.add_admin') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.role') }}</th>
                        <th>{{ __('messages.super_admin') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar">
                                    {{ substr($admin->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-medium">{{ $admin->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ __('messages.id') }}: {{ $admin->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $admin->email }}</td>
                        <td>
                            @if($admin->role)
                                <span class="badge badge-info">{{ $admin->role->name }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('messages.no_role') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($admin->is_super_admin)
                                <span class="badge badge-success">{{ __('messages.yes') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('messages.no') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $admin->is_active ? 'success' : 'danger' }}">
                                {{ $admin->is_active ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.admins.show', $admin) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($admin->id !== auth()->guard('admin')->id())
                                <button class="btn btn-danger btn-sm delete-item" 
                                        data-id="{{ $admin->id }}" 
                                        data-type="admin"
                                        data-url="{{ route('admin.admins.destroy', ':id') }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $admins->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>

<!-- Admin Statistics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #3b82f6;">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-number">{{ $totalAdmins }}</div>
        <div class="stat-label">{{ __('messages.total_admins') }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #10b981;">
            <i class="fas fa-crown"></i>
        </div>
        <div class="stat-number">{{ $superAdmins }}</div>
        <div class="stat-label">{{ __('messages.super_admins') }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-number">{{ $activeAdmins }}</div>
        <div class="stat-label">{{ __('messages.active_admins') }}</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">
            <i class="fas fa-user-times"></i>
        </div>
        <div class="stat-number">{{ $inactiveAdmins }}</div>
        <div class="stat-label">{{ __('messages.inactive_admins') }}</div>
    </div>
</div>

@endsection
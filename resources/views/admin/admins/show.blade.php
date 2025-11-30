@extends('admin.layouts.admin')

@section('title', 'Admin Details')
@section('page-title', 'Admin Details: ' . $admin->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Admin Profile -->
    <x-card title="Profile Information">
        <div class="text-center mb-6">
            <div class="w-24 h-24 rounded-full bg-primary text-white flex items-center justify-center text-2xl font-bold mx-auto mb-3">
                {{ substr($admin->name, 0, 1) }}
            </div>
            <h3 class="text-xl font-bold">{{ $admin->name }}</h3>
            <p class="text-gray-500">{{ $admin->email }}</p>
            <div class="mt-2">
                <span class="badge badge-{{ $admin->is_active ? 'success' : 'danger' }}">
                    {{ $admin->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($admin->is_super_admin)
                <span class="badge badge-warning">Super Admin</span>
                @endif
            </div>
        </div>
        
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="font-medium">Role Type:</span>
                <span class="badge badge-info">{{ $admin->role_type }}</span>
            </div>
            
            <div class="flex justify-between">
                <span class="font-medium">Phone:</span>
                <span>{{ $admin->phone ?? 'N/A' }}</span>
            </div>
            
            <div class="flex justify-between">
                <span class="font-medium">Last Login:</span>
                <span>{{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i') : 'Never' }}</span>
            </div>
            
            <div class="flex justify-between">
                <span class="font-medium">Member Since:</span>
                <span>{{ $admin->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        
        <div class="mt-6 flex gap-2">
            <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-warning flex-1">
                <i class="fas fa-edit"></i> Edit
            </a>
            @if($admin->id !== auth()->guard('admin')->id())
            <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-full delete-item" 
                        data-type="admin"
                        data-url="{{ route('admin.admins.destroy', $admin) }}">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
            @endif
        </div>
    </x-card>
    
    <!-- Roles & Permissions -->
    <x-card title="Roles & Permissions" class="lg:col-span-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Assigned Roles -->
            <div>
                <h4 class="font-semibold mb-3">Assigned Roles</h4>
                <div class="space-y-2">
                    @foreach($admin->roles as $role)
                    <div class="p-3 border rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ $role->name }}</span>
                            <span class="badge badge-primary">{{ $role->guard_name }}</span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">{{ $role->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Direct Permissions -->
            <div>
                <h4 class="font-semibold mb-3">Direct Permissions</h4>
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @foreach($admin->getAllPermissions() as $permission)
                    <div class="p-2 border rounded">
                        <span class="font-medium text-sm">{{ $permission->name }}</span>
                        <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </x-card>
</div>

<!-- Activity Timeline -->
<x-card title="Activity Timeline" class="mt-6">
    <div class="space-y-4">
        @foreach($activities as $activity)
        <div class="flex gap-4">
            <div class="flex flex-col items-center">
                <div class="w-8 h-8 rounded-full bg-{{ $activity->type_color }}-100 text-{{ $activity->type_color }}-500 flex items-center justify-center">
                    <i class="fas fa-{{ $activity->type_icon }} text-sm"></i>
                </div>
                <div class="w-0.5 h-full bg-gray-200 mt-2"></div>
            </div>
            <div class="flex-1 pb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-medium">{{ $activity->description }}</h4>
                        <p class="text-sm text-gray-500">{{ $activity->details }}</p>
                    </div>
                    <span class="text-sm text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    @if($activities->hasPages())
    <div class="mt-4">
        {{ $activities->links() }}
    </div>
    @endif
</x-card>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #3b82f6;">
            <i class="fas fa-user-cog"></i>
        </div>
        <div class="stat-number">{{ $admin->roles_count }}</div>
        <div class="stat-label">Assigned Roles</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #10b981;">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="stat-number">{{ $admin->permissions_count }}</div>
        <div class="stat-label">Total Permissions</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
            <i class="fas fa-history"></i>
        </div>
        <div class="stat-number">{{ $totalActivities }}</div>
        <div class="stat-label">Total Activities</div>
    </div>
</div>
@endsection
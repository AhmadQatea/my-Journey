@extends('admin.layouts.admin')

@section('title', 'Admin Details')
@section('page-title', 'Admin Details: ' . $admin->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Admin Profile -->
    <x-card title="Profile Information">
        <div class="text-center mb-6">
            <div class="w-24 h-24 rounded-full bg-primary text-black flex items-center justify-center text-2xl font-bold mx-auto mb-3">
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
                <span class="font-medium">Role:</span>
                <span class="badge badge-info">{{ $admin->role ? $admin->role->name : 'No Role' }}</span>
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

    <!-- Role & Permissions Information -->
    @if($admin->role)
    <x-card title="معلومات الدور والصلاحيات" class="lg:col-span-2">
        <div class="space-y-4">
            <div>
                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">الدور</label>
                <p class="text-lg font-bold text-gray-900 dark:text-black mt-1">{{ $admin->role->name }}</p>
                @if($admin->role->description)
                <p class="text-gray-700 dark:text-gray-500 mt-1">{{ $admin->role->description }}</p>
                @endif
            </div>

            <!-- Role Permissions -->
            @if($admin->role->permissions && count($admin->role->permissions) > 0)
            <div>
                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">صلاحيات الدور</label>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($admin->role->permissions as $permission)
                    <span class="badge badge-primary">{{ $permission }}</span>
                    @endforeach
                </div>
            </div>
            @else
            <div>
                <p class="text-gray-500">لا توجد صلاحيات محددة لهذا الدور</p>
            </div>
            @endif
        </div>
    </x-card>
    @endif
</div>
@endsection

@extends('admin.layouts.admin')

@section('title', 'Role Details')
@section('page-title', 'Role Details: ' . $role->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Role Information -->
    <x-card title="Role Information">
        <div class="space-y-4">
            <div>
                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Role Name</label>
                <p class="text-lg font-bold text-gray-900 dark:text-black mt-1">{{ $role->name }}</p>
            </div>
            
            @if($role->description)
            <div>
                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Description</label>
                <p class="text-gray-700 dark:text-gray-500 mt-1">{{ $role->description }}</p>
            </div>
            @endif
            
            <div>
                <label class="text-sm font-semibold text-gray-600 dark:text-gray-400">Statistics</label>
                <div class="mt-2 space-y-2">
                    <div class="flex justify-between">
                        <span>Admins:</span>
                        <span class="badge badge-info">{{ $role->admins_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Users:</span>
                        <span class="badge badge-info">{{ $role->users_count }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Permissions:</span>
                        <span class="badge badge-primary">{{ count($role->permissions ?? []) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex gap-2">
            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-warning flex-1">
                <i class="fas fa-edit"></i> Edit
            </a>
            @if($role->admins_count == 0 && $role->users_count == 0)
            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger w-full" onclick="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
            @endif
        </div>
    </x-card>

    <!-- Permissions -->
    <x-card title="Permissions" class="lg:col-span-2">
        @if($role->permissions && count($role->permissions) > 0)
        <div class="flex flex-wrap gap-2">
            @foreach($role->permissions as $permission)
            <span class="badge badge-primary">{{ $permission }}</span>
            @endforeach
        </div>
        @else
        <p class="text-gray-500">لا توجد صلاحيات محددة لهذا الدور</p>
        @endif
    </x-card>
</div>
@endsection

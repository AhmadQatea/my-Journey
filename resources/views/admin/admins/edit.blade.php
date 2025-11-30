@extends('admin.layouts.admin')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Admin: ' . $admin->name)

@section('content')
<x-card title="Edit Admin Information">
    <form action="{{ route('admin.admins.update', $admin) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="space-y-4">
                <h4 class="font-semibold text-lg border-b pb-2">Personal Information</h4>
                
                <x-form.input 
                    name="name" 
                    label="Full Name" 
                    required 
                    value="{{ old('name', $admin->name) }}"
                />
                
                <x-form.input 
                    name="email" 
                    label="Email Address" 
                    type="email" 
                    required 
                    value="{{ old('email', $admin->email) }}"
                />
                
                <x-form.input 
                    name="phone" 
                    label="Phone Number" 
                    type="tel" 
                    value="{{ old('phone', $admin->phone) }}"
                />
                
                <div class="form-group">
                    <label class="form-label">Profile Image</label>
                    @if($admin->image)
                    <div class="mb-3">
                        <img src="{{ asset($admin->image) }}" alt="{{ $admin->name }}" 
                             class="w-20 h-20 rounded-full object-cover border">
                    </div>
                    @endif
                    <x-form.file-upload 
                        name="image" 
                        label="Change Profile Image"
                        accept="image/*"
                    />
                </div>
            </div>
            
            <!-- Account Settings -->
            <div class="space-y-4">
                <h4 class="font-semibold text-lg border-b pb-2">Account Settings</h4>
                
                <x-form.input 
                    name="password" 
                    label="New Password" 
                    type="password" 
                />
                
                <x-form.input 
                    name="password_confirmation" 
                    label="Confirm New Password" 
                    type="password" 
                />
                
                <x-form.select 
                    name="role_type" 
                    label="Role Type" 
                    :options="[
                        'big_boss' => 'Big Boss',
                        'site_admin' => 'Site Admin',
                        'booking_admin' => 'Booking Admin',
                        'user_admin' => 'User Admin',
                        'employee' => 'Employee'
                    ]" 
                    required 
                    :selected="old('role_type', $admin->role_type)"
                />
                
                <div class="form-group">
                    <label class="form-label">Roles *</label>
                    <select class="form-control @error('roles') is-invalid @enderror" name="roles[]" multiple required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" 
                                {{ in_array($role->id, old('roles', $admin->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $role->name }} - {{ $role->description }}
                            </option>
                        @endforeach
                    </select>
                    @error('roles')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_super_admin" value="1" 
                               {{ old('is_super_admin', $admin->is_super_admin) ? 'checked' : '' }} class="mr-2">
                        <span class="form-label mb-0">Is Super Admin</span>
                    </label>
                </div>
                
                <div class="form-group">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', $admin->is_active) ? 'checked' : '' }} class="mr-2">
                        <span class="form-label mb-0">Active Account</span>
                    </label>
                </div>
            </div>
        </div>
        
        <!-- Permissions Section -->
        <div class="mt-6">
            <h4 class="font-semibold text-lg border-b pb-2 mb-4">Permissions</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($permissions as $permission)
                <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                           {{ in_array($permission->id, old('permissions', $admin->permissions->pluck('id')->toArray())) ? 'checked' : '' }} class="mr-3">
                    <div>
                        <span class="font-medium">{{ $permission->name }}</span>
                        <p class="text-sm text-gray-500">{{ $permission->description }}</p>
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Admin
            </button>
        </div>
    </form>
</x-card>

<!-- Admin Activity Log -->
<x-card title="Recent Activity" class="mt-6">
    <div class="space-y-3">
        @foreach($admin->activities()->latest()->take(10)->get() as $activity)
        <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
            <div class="flex items-center gap-3">
                <i class="fas fa-{{ $activity->type_icon }} text-{{ $activity->type_color }}-500"></i>
                <span>{{ $activity->description }}</span>
            </div>
            <span class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
        </div>
        @endforeach
    </div>
</x-card>
@endsection
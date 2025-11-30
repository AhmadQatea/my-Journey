@extends('admin.layouts.admin')

@section('title', 'Create Admin')
@section('page-title', 'Create New Admin')

@section('content')
<x-card title="Admin Information">
    <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Personal Information -->
            <div class="space-y-4">
                <h4 class="font-semibold text-lg border-b pb-2">Personal Information</h4>
                
                <x-form.input 
                    name="name" 
                    label="Full Name" 
                    required 
                    value="{{ old('name') }}"
                />
                
                <x-form.input 
                    name="email" 
                    label="Email Address" 
                    type="email" 
                    required 
                    value="{{ old('email') }}"
                />
                
                <x-form.input 
                    name="phone" 
                    label="Phone Number" 
                    type="tel" 
                    value="{{ old('phone') }}"
                />
                
                <x-form.file-upload 
                    name="image" 
                    label="Profile Image"
                    accept="image/*"
                />
            </div>
            
            <!-- Account Settings -->
            <div class="space-y-4">
                <h4 class="font-semibold text-lg border-b pb-2">Account Settings</h4>
                
                <x-form.input 
                    name="password" 
                    label="Password" 
                    type="password" 
                    required 
                />
                
                <x-form.input 
                    name="password_confirmation" 
                    label="Confirm Password" 
                    type="password" 
                    required 
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
                    :selected="old('role_type')"
                />
                
                <div class="form-group">
                    <label class="form-label">Roles *</label>
                    <select class="form-control @error('roles') is-invalid @enderror" name="roles[]" multiple required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                {{ $role->name }} - {{ $role->description }}
                            </option>
                        @endforeach
                    </select>
                    @error('roles')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Hold Ctrl to select multiple roles</p>
                </div>
                
                <div class="form-group">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_super_admin" value="1" 
                               {{ old('is_super_admin') ? 'checked' : '' }} class="mr-2">
                        <span class="form-label mb-0">Is Super Admin</span>
                    </label>
                </div>
                
                <div class="form-group">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }} class="mr-2">
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
                           {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }} class="mr-3">
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
                <i class="fas fa-save"></i> Create Admin
            </button>
        </div>
    </form>
</x-card>
@endsection
@extends('admin.layouts.admin')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Admin: ' . $admin->name)

@section('content')
<x-card title="Edit Admin Information">
    <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST">
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
                
                <div class="form-group">
                    <label class="form-label">الدور *</label>
                    <select name="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                        <option value="">اختر الدور</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $admin->role_id) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }} @if($role->description) - {{ $role->description }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
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
        
        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Admin
            </button>
        </div>
    </form>
</x-card>
@endsection
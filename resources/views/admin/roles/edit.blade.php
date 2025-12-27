<x-admin.edit-form
    title="Edit Role"
    :page-title="'Edit Role: ' . $role->name"
    :action="route('admin.roles.update', $role)"
    :model="$role"
    :back-route="route('admin.roles.index')"
    back-text="Cancel"
    submit-text="Update Role"
    layout="card"
>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Information -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg border-b pb-2">Basic Information</h4>
            
            <x-form.input 
                name="name" 
                label="Role Name" 
                required 
                value="{{ old('name', $role->name) }}"
            />
            
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" 
                          class="form-control @error('description') is-invalid @enderror" 
                          rows="3">{{ old('description', $role->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <!-- Permissions -->
        <div class="space-y-4">
            <h4 class="font-semibold text-lg border-b pb-2">Permissions</h4>
            <p class="text-sm text-gray-500 mb-4">اختر الصلاحيات لهذا الدور</p>
            <div class="max-h-96 overflow-y-auto border rounded-lg p-4">
                <div class="grid grid-cols-1 gap-3">
                    @php
                        $rolePermissions = old('permissions', $role->permissions ?? []);
                    @endphp
                    @foreach($allPermissions as $permission)
                    <label class="flex items-center p-2 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors">
                        <input type="checkbox" name="permissions[]" value="{{ $permission }}" 
                               {{ in_array($permission, $rolePermissions) ? 'checked' : '' }} class="mr-3">
                        <span class="text-sm font-medium">{{ $permission }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-admin.edit-form>

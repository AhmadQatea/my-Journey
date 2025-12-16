<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check() && (auth('admin')->user()->isSuperAdmin() || auth('admin')->user()->hasPermission('manage_admins'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'اسم الدور مطلوب',
            'name.unique' => 'اسم الدور مستخدم بالفعل',
            'name.max' => 'اسم الدور يجب أن يكون أقل من 255 حرف',
            'description.max' => 'الوصف يجب أن يكون أقل من 500 حرف',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'trip_id' => 'required|exists:trips,id',
            'guest_count' => 'required|integer|min:1|max:100',
            'booking_date' => 'required|date|after_or_equal:today',
            'total_price' => 'required|numeric|min:0',
            'status' => 'nullable|in:معلقة,مؤكدة,مرفوضة,ملغاة',
            'special_requests' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'nullable|string|max:500|required_if:status,مرفوضة',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'يجب اختيار المستخدم.',
            'user_id.exists' => 'المستخدم المختار غير موجود.',
            'trip_id.required' => 'يجب اختيار الرحلة.',
            'trip_id.exists' => 'الرحلة المختارة غير موجودة.',
            'guest_count.required' => 'عدد الضيوف مطلوب.',
            'guest_count.integer' => 'عدد الضيوف يجب أن يكون رقماً صحيحاً.',
            'guest_count.min' => 'عدد الضيوف يجب أن يكون شخصاً واحداً على الأقل.',
            'guest_count.max' => 'عدد الضيوف يجب ألا يتجاوز 100 شخص.',
            'booking_date.required' => 'تاريخ الحجز مطلوب.',
            'booking_date.date' => 'تاريخ الحجز يجب أن يكون تاريخاً صالحاً.',
            'booking_date.after_or_equal' => 'تاريخ الحجز يجب أن يكون اليوم أو بعده.',
            'total_price.required' => 'السعر الإجمالي مطلوب.',
            'total_price.numeric' => 'السعر الإجمالي يجب أن يكون رقماً.',
            'total_price.min' => 'السعر الإجمالي يجب أن يكون 0 على الأقل.',
            'status.required' => 'حالة الحجز مطلوبة.',
            'status.in' => 'حالة الحجز غير صالحة.',
            'special_requests.max' => 'الطلبات الخاصة يجب ألا تتجاوز 1000 حرف.',
            'admin_notes.max' => 'ملاحظات المسؤول يجب ألا تتجاوز 1000 حرف.',
            'rejection_reason.required_if' => 'سبب الرفض مطلوب عند رفض الحجز.',
            'rejection_reason.max' => 'سبب الرفض يجب ألا يتجاوز 500 حرف.',
        ];
    }
}

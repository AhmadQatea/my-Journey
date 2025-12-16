<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'user_id' => 'nullable|exists:users,id',
            'trip_id' => 'nullable|exists:trips,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:100',
            'excerpt' => 'nullable|string|max:500',
            'rating' => 'nullable|integer|min:1|max:5',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:معلقة,منشورة,مرفوضة',
            'rejection_reason' => 'nullable|string|max:500|required_if:status,مرفوضة',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'المستخدم المختار غير موجود.',
            'trip_id.exists' => 'الرحلة المختارة غير موجودة.',
            'title.required' => 'عنوان المقال مطلوب.',
            'title.string' => 'عنوان المقال يجب أن يكون نصاً.',
            'title.max' => 'عنوان المقال يجب ألا يتجاوز 255 حرفاً.',
            'content.required' => 'محتوى المقال مطلوب.',
            'content.string' => 'محتوى المقال يجب أن يكون نصاً.',
            'content.min' => 'محتوى المقال يجب أن يكون 100 حرف على الأقل.',
            'excerpt.max' => 'الملخص يجب ألا يتجاوز 500 حرف.',
            'rating.integer' => 'التقييم يجب أن يكون رقماً صحيحاً.',
            'rating.min' => 'التقييم يجب أن يكون 1 على الأقل.',
            'rating.max' => 'التقييم يجب أن يكون 5 على الأكثر.',
            'images.array' => 'الصور يجب أن تكون مصفوفة.',
            'images.max' => 'يمكن رفع حتى 10 صور.',
            'images.*.image' => 'يجب أن تكون الملفات المرفوعة صوراً.',
            'images.*.mimes' => 'يجب أن تكون الصور بصيغة: jpeg, png, jpg, gif.',
            'images.*.max' => 'حجم كل صورة يجب ألا يتجاوز 2 ميجابايت.',
            'status.in' => 'حالة المقال غير صالحة.',
            'rejection_reason.required_if' => 'سبب الرفض مطلوب عند رفض المقال.',
            'rejection_reason.max' => 'سبب الرفض يجب ألا يتجاوز 500 حرف.',
        ];
    }
}

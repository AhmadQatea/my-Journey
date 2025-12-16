<?php

namespace App\Http\Requests;

use App\Models\TouristSpot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class OfferRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'trip_id' => 'required|exists:trips,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:مفعل,منتهي',
            // الحقول المخصصة (اختيارية)
            'custom_price' => 'nullable|numeric|min:0',
            'custom_included_places' => 'nullable|array',
            'custom_included_places.*' => 'nullable|exists:tourist_spots,id',
            'custom_features' => 'nullable|array',
            'custom_features.*' => 'nullable|string|max:255',
            'custom_start_time' => 'nullable|date_format:H:i',
            'custom_departure_governorate_id' => 'nullable|exists:governorates,id',
            'custom_meeting_point' => 'nullable|string|max:500',
            'custom_duration_hours' => 'nullable|integer|min:1',
            'custom_max_persons' => 'nullable|integer|min:1|max:100',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // التحقق من أن الأماكن المخصصة موجودة في محافظات الرحلة
            if ($this->filled('custom_included_places') && $this->filled('trip_id')) {
                $trip = \App\Models\Trip::find($this->trip_id);
                if ($trip) {
                    $governorateIds = [$trip->governorate_id];
                    if ($trip->passing_governorates) {
                        $governorateIds = array_merge($governorateIds, $trip->passing_governorates);
                    }

                    $touristSpots = TouristSpot::whereIn('id', $this->custom_included_places)
                        ->whereIn('governorate_id', $governorateIds)
                        ->pluck('id')
                        ->toArray();

                    $invalidSpots = array_diff($this->custom_included_places, $touristSpots);

                    if (! empty($invalidSpots)) {
                        $validator->errors()->add(
                            'custom_included_places',
                            'بعض الأماكن السياحية المختارة ليست ضمن محافظات الرحلة المحددة.'
                        );
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان العرض مطلوب',
            'description.min' => 'الوصف يجب أن يكون على الأقل 20 حرفاً',
            'trip_id.required' => 'يجب اختيار رحلة',
            'trip_id.exists' => 'الرحلة المختارة غير موجودة',
            'discount_percentage.required' => 'نسبة الخصم مطلوبة',
            'discount_percentage.max' => 'نسبة الخصم لا يمكن أن تكون أكثر من 100%',
            'start_date.after_or_equal' => 'تاريخ البدء يجب أن يكون اليوم أو بعده',
            'end_date.after' => 'تاريخ الانتهاء يجب أن يكون بعد تاريخ البدء',
            'status.required' => 'حالة العرض مطلوبة',
        ];
    }

    public function attributes(): array
    {
        return [
            'trip_id' => 'الرحلة',
            'discount_percentage' => 'نسبة الخصم',
            'start_date' => 'تاريخ البدء',
            'end_date' => 'تاريخ الانتهاء',
            'custom_price' => 'السعر المخصص',
            'custom_included_places' => 'الأماكن المضمنة المخصصة',
            'custom_features' => 'الميزات المخصصة',
        ];
    }
}

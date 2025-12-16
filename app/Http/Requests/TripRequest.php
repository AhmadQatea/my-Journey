<?php

// app/Http/Requests/TripRequest.php

namespace App\Http\Requests;

use App\Models\TouristSpot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class TripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'governorate_id' => 'required|exists:governorates,id',
            'departure_governorate_id' => 'required|exists:governorates,id',
            'trip_type' => 'required|in:داخل المحافظة,عدة محافظات',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',
            'duration_hours' => 'required|integer|min:1',
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'max_persons' => 'required|integer|min:1|max:100',
            'available_seats' => 'required|integer|min:1|lte:max_persons',
            'price' => 'required|numeric|min:0',
            'meeting_point' => 'required|string|max:500',
            'requirements' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'included_places' => 'required|array|min:1',
            'included_places.*' => 'required|exists:tourist_spots,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'passing_governorates' => 'required_if:trip_type,عدة محافظات|array|min:1',
            'passing_governorates.*' => 'exists:governorates,id',
        ];

        // قواعد خاصة بالمستخدمين VIP
        if ($this->user()->hasRole('vip_user')) {
            $rules['vip_commission'] = 'nullable|numeric|min:0|max:50';
            $rules['price'] = 'required|numeric|min:0|max:1000000';
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // التحقق من أن الأماكن المختارة ضمن المحافظات المختارة
            if ($this->filled('included_places') && $this->filled('governorate_id')) {
                $governorateIds = [$this->governorate_id];

                // إضافة المحافظات التي سنمر بها
                if ($this->filled('passing_governorates')) {
                    $governorateIds = array_merge($governorateIds, $this->passing_governorates);
                }

                $governorateIds = array_unique($governorateIds);

                // التحقق من أن كل مكان سياحي مختار موجود في إحدى المحافظات المختارة
                $touristSpots = TouristSpot::whereIn('id', $this->included_places)
                    ->whereIn('governorate_id', $governorateIds)
                    ->pluck('id')
                    ->toArray();

                $invalidSpots = array_diff($this->included_places, $touristSpots);

                if (! empty($invalidSpots)) {
                    $validator->errors()->add(
                        'included_places',
                        'بعض الأماكن السياحية المختارة ليست ضمن المحافظات المحددة للرحلة.'
                    );
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الرحلة مطلوب',
            'description.min' => 'الوصف يجب أن يكون على الأقل 50 حرفاً',
            'start_date.after_or_equal' => 'تاريخ البدء يجب أن يكون اليوم أو بعده',
            'available_seats.lte' => 'المقاعد المتاحة يجب أن تكون أقل من أو تساوي العدد الأقصى للأشخاص',
        ];
    }

    public function attributes(): array
    {
        return [
            'governorate_id' => 'المحافظة',
            'category_id' => 'القسم',
            'duration_hours' => 'عدد الساعات',
            'max_persons' => 'العدد الأقصى للأشخاص',
            'meeting_point' => 'نقطة اللقاء',
        ];
    }
}

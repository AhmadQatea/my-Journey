<?php

// app/Http/Requests/VipTripRequest.php

namespace App\Http\Requests;

class VipTripRequest extends TripRequest
{
    public function rules(): array
    {
        $rules = parent::rules();

        // قواعد إضافية لرحلات VIP
        $rules['price'] = 'required|numeric|min:0|max:500000'; // سقف أقل لرحلات VIP
        $rules['start_date'] = 'required|date|after_or_equal:+3 days'; // يجب أن يكون بعد 3 أيام على الأقل

        return $rules;
    }
}

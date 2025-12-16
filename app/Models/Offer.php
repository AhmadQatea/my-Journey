<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'trip_id',
        'discount_percentage',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'custom_price',
        'custom_included_places',
        'custom_features',
        'custom_start_time',
        'custom_departure_governorate_id',
        'custom_meeting_point',
        'custom_duration_hours',
        'custom_max_persons',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'discount_percentage' => 'decimal:2',
        'custom_price' => 'decimal:2',
        'custom_included_places' => 'array',
        'custom_features' => 'array',
        'custom_start_time' => 'datetime:H:i',
        'custom_duration_hours' => 'integer',
        'custom_max_persons' => 'integer',
    ];

    // العلاقة مع الرحلة
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // العلاقة مع منشئ العرض
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // العلاقة مع محافظة الانطلاق المخصصة
    public function customDepartureGovernorate()
    {
        return $this->belongsTo(Governorate::class, 'custom_departure_governorate_id');
    }

    // Helper method للحصول على السعر النهائي (السعر المخصص أو السعر من الرحلة مع الخصم)
    public function getFinalPrice(): float
    {
        if ($this->custom_price) {
            return (float) $this->custom_price;
        }

        $tripPrice = $this->trip->price ?? 0;
        $discount = ($tripPrice * $this->discount_percentage) / 100;

        return $tripPrice - $discount;
    }

    // Helper method للحصول على الأماكن المضمنة (المخصصة أو من الرحلة)
    public function getIncludedPlaces(): array
    {
        return $this->custom_included_places ?? $this->trip->included_places ?? [];
    }

    // Helper method للحصول على الميزات (المخصصة أو من الرحلة)
    public function getFeatures(): array
    {
        return $this->custom_features ?? $this->trip->features ?? [];
    }

    // Helper method للحصول على وقت البدء (المخصص أو من الرحلة)
    public function getStartTime(): string
    {
        return $this->custom_start_time ?? $this->trip->start_time ?? '00:00';
    }

    // Helper method للحصول على مكان الانطلاق (المخصص أو من الرحلة)
    public function getMeetingPoint(): string
    {
        return $this->custom_meeting_point ?? $this->trip->meeting_point ?? '';
    }

    // Helper method للحصول على المدة (المخصصة أو من الرحلة)
    public function getDurationHours(): int
    {
        return $this->custom_duration_hours ?? $this->trip->duration_hours ?? 0;
    }

    // Helper method للحصول على العدد الأقصى (المخصص أو من الرحلة)
    public function getMaxPersons(): int
    {
        return $this->custom_max_persons ?? $this->trip->max_persons ?? 0;
    }
}

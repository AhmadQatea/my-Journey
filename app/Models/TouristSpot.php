<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TouristSpot extends Model
{
    use HasFactory;

    protected $fillable = [
        'governorate_id',
        'name',
        'description',
        'location',
        'type',
        'images',
        'entrance_fee',
        'opening_hours'
    ];

    protected $casts = [
        'images' => 'array',
        'entrance_fee' => 'decimal:2'
    ];

    // العلاقة مع المحافظة
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }
}

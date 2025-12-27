<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'location', 'featured_image', 'latitude', 'longitude'];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // العلاقة مع الأماكن السياحية
    public function touristSpots()
    {
        return $this->hasMany(TouristSpot::class);
    }

    // العلاقة مع الرحلات
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    // العلاقة مع أفضل أوقات الزيارة
    public function bestVisitingTimes()
    {
        return $this->belongsToMany(BestVisitingTime::class, 'governorate_best_visiting_time');
    }
}

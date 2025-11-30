<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'location', 'featured_image'];

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
}

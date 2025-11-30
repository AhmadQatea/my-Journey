<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'governorate_id',
        'trip_type',
        'duration_hours',
        'start_time',
        'max_persons',
        'price',
        'included_places',
        'images',
        'created_by',
        'status',
        'is_featured'
    ];

    protected $casts = [
        'included_places' => 'array',
        'images' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'start_time' => 'datetime:H:i'
    ];

    // العلاقة مع المحافظة
    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    // العلاقة مع منشئ الرحلة (لـ VIP users)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // العلاقة مع الحجوزات
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // العلاقة مع المقالات
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    // العلاقة مع العروض
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}

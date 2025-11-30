<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'guest_count',
        'booking_date',
        'total_price',
        'status',
        'special_requests',
        'admin_notes'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'decimal:2'
    ];

    // العلاقة مع المستخدم
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة مع الرحلة
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}

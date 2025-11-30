<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'title',
        'content',
        'rating',
        'images',
        'status'
    ];

    protected $casts = [
        'images' => 'array',
        'rating' => 'integer'
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

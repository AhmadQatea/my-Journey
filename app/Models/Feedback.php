<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'rating',
        'comments',
        'likes',
    ];

    protected $casts = [
        'rating' => 'integer',
        'likes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

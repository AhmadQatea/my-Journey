<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'about_story',
        'about_story_en',
        'about_mission',
        'about_vision',
        'contact_email',
        'contact_phone',
        'contact_address',
        'working_hours',
        'terms_and_conditions',
        'privacy_policy',
        'cookie_policy',
        'social_facebook',
        'social_twitter',
        'social_instagram',
        'social_youtube',
        'social_linkedin',
        'social_whatsapp',
    ];

    protected $casts = [
        'working_hours' => 'array',
    ];

    /**
     * الحصول على إعدادات الموقع (سجل واحد فقط)
     */
    public static function getSettings(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'code', 'expires_at', 'used', 'purpose'];

    protected $dates = ['expires_at', 'created_at'];

    public $timestamps = false;

    /**
     * التحقق إذا كان الرمز صالحاً
     */
    public function isValid()
    {
        return !$this->used && $this->expires_at > now();
    }

    /**
     * تعيين الرمز كمستخدم
     */
    public function markAsUsed()
    {
        $this->used = true;
        $this->save();
    }

    /**
     * حذف الرموز المنتهية
     */
    public static function cleanupExpired()
    {
        return self::where('expires_at', '<', now())->delete();
    }
}

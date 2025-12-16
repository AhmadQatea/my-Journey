<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تعديل enum status لإضافة "قيد التفعيل"
        DB::statement("ALTER TABLE trips MODIFY COLUMN status ENUM('معلقة', 'مقبولة', 'مرفوضة', 'قيد التفعيل') DEFAULT 'معلقة'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع enum status إلى الحالة السابقة
        // تحويل "قيد التفعيل" إلى "مقبولة" قبل الحذف
        DB::statement("UPDATE trips SET status = 'مقبولة' WHERE status = 'قيد التفعيل'");
        DB::statement("ALTER TABLE trips MODIFY COLUMN status ENUM('معلقة', 'مقبولة', 'مرفوضة') DEFAULT 'معلقة'");
    }
};

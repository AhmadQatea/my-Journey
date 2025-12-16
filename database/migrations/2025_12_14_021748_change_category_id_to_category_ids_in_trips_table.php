<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // إضافة عمود جديد category_ids كـ JSON
            $table->json('category_ids')->nullable()->after('category_id');
        });

        // نسخ البيانات من category_id إلى category_ids
        DB::statement('UPDATE trips SET category_ids = JSON_ARRAY(category_id) WHERE category_id IS NOT NULL');

        // حذف foreign key constraint أولاً
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        // حذف العمود القديم
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            // إعادة إضافة category_id
            $table->foreignId('category_id')->nullable()->after('governorate_id')->constrained()->onDelete('set null');
        });

        // نسخ البيانات من category_ids إلى category_id (أول عنصر فقط)
        DB::statement("UPDATE trips SET category_id = JSON_UNQUOTE(JSON_EXTRACT(category_ids, '$[0]')) WHERE category_ids IS NOT NULL AND JSON_LENGTH(category_ids) > 0");

        // حذف category_ids
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('category_ids');
        });
    }
};

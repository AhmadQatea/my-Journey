<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // جعل user_id nullable
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE articles MODIFY user_id BIGINT UNSIGNED NULL');
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE articles DROP FOREIGN KEY articles_user_id_foreign');
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE articles ADD CONSTRAINT articles_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // إرجاع user_id إلى required (لكن هذا قد يفشل إذا كانت هناك مقالات بدون user_id)
            \Illuminate\Support\Facades\DB::statement('UPDATE articles SET user_id = 1 WHERE user_id IS NULL');
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE articles MODIFY user_id BIGINT UNSIGNED NOT NULL');
        });
    }
};

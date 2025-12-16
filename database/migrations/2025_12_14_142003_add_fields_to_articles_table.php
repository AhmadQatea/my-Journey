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
            $table->text('excerpt')->nullable()->after('content');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->integer('views_count')->default(0)->after('rejection_reason');
        });

        // Make trip_id nullable
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE articles MODIFY trip_id BIGINT UNSIGNED NULL');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE articles DROP FOREIGN KEY articles_trip_id_foreign');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE articles ADD CONSTRAINT articles_trip_id_foreign FOREIGN KEY (trip_id) REFERENCES trips(id) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'rejection_reason', 'views_count']);
        });
    }
};

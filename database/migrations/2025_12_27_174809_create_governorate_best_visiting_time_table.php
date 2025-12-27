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
        if (! Schema::hasTable('governorate_best_visiting_time')) {
            Schema::create('governorate_best_visiting_time', function (Blueprint $table) {
                $table->id();
                $table->foreignId('governorate_id')->constrained()->onDelete('cascade');
                $table->foreignId('best_visiting_time_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                $table->unique(['governorate_id', 'best_visiting_time_id'], 'gov_bvt_unique');
            });
        } else {
            // Table exists, just add the unique constraint if it doesn't exist
            $constraintExists = DB::select("
                SELECT COUNT(*) as count
                FROM information_schema.table_constraints
                WHERE constraint_schema = DATABASE()
                AND table_name = 'governorate_best_visiting_time'
                AND constraint_name = 'gov_bvt_unique'
            ");

            if (empty($constraintExists) || $constraintExists[0]->count == 0) {
                DB::statement('ALTER TABLE `governorate_best_visiting_time` ADD UNIQUE KEY `gov_bvt_unique` (`governorate_id`, `best_visiting_time_id`)');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governorate_best_visiting_time');
    }
};

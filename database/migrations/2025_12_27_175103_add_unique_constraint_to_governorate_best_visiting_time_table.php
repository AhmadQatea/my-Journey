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
        // Check if the unique constraint doesn't exist, then add it
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `governorate_best_visiting_time` DROP INDEX `gov_bvt_unique`');
    }
};

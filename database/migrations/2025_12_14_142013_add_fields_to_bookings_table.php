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
        Schema::table('bookings', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('status');
            $table->boolean('created_by_admin')->default(false)->after('rejection_reason');
            $table->foreignId('created_by_admin_id')->nullable()->after('created_by_admin')->constrained('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->dropColumn(['rejection_reason', 'created_by_admin', 'created_by_admin_id']);
        });
    }
};

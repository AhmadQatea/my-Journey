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
            $table->foreign('created_by_admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('confirmed_by_admin_id')->references('id')->on('admins')->onDelete('set null');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('created_by_admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->foreign('confirmed_by_admin_id')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->dropForeign(['confirmed_by_admin_id']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['created_by_admin_id']);
            $table->dropForeign(['confirmed_by_admin_id']);
        });
    }
};

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
            $table->boolean('created_by_admin')->default(false)->after('user_id');
            $table->foreignId('created_by_admin_id')->nullable()->after('created_by_admin')->constrained('admins')->onDelete('set null');
            $table->foreignId('confirmed_by_admin_id')->nullable()->after('created_by_admin_id')->constrained('admins')->onDelete('set null');
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
            $table->dropColumn(['created_by_admin', 'created_by_admin_id', 'confirmed_by_admin_id']);
        });
    }
};

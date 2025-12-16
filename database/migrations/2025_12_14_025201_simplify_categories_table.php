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
        Schema::table('categories', function (Blueprint $table) {
            // حذف الحقول غير الضرورية
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['type', 'description', 'parent_id', 'order', 'is_active', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->enum('type', ['بحرية', 'تراثية', 'مناظر خلابة', 'مغامرات', 'تاريخية', 'أنهار وأودية'])->after('slug');
            $table->text('description')->nullable()->after('type');
            $table->foreignId('parent_id')->nullable()->after('description')->constrained('categories')->onDelete('cascade');
            $table->integer('order')->default(0)->after('parent_id');
            $table->boolean('is_active')->default(true)->after('order');
        });
    }
};

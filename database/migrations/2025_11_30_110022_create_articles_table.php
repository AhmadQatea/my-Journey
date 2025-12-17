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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('created_by_admin')->default(false);
            $table->unsignedBigInteger('created_by_admin_id')->nullable();
            $table->unsignedBigInteger('confirmed_by_admin_id')->nullable();
            $table->foreignId('trip_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->integer('rating')->default(5);
            $table->json('images')->nullable();
            $table->enum('status', ['معلقة', 'منشورة', 'مرفوضة'])->default('معلقة');
            $table->text('rejection_reason')->nullable();
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

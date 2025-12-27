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
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->onDelete('cascade');
            $table->string('type')->comment('نوع الإشعار: identity_verification, booking, article, etc.');
            $table->string('title');
            $table->text('message');
            $table->string('icon')->nullable()->comment('أيقونة الإشعار');
            $table->string('color')->default('info')->comment('لون الإشعار: info, success, warning, danger');
            $table->string('link')->nullable()->comment('رابط الإشعار');
            $table->json('data')->nullable()->comment('بيانات إضافية');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['admin_id', 'is_read']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};

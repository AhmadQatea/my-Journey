<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// الصفحة الرئيسية للزوار
Route::get('/', [HomeController::class, 'index'])->name('home');

// مجموعة routes المصادقة (يتم التعامل معها بواسطة Fortify)
require __DIR__.'/auth.php';

// routes تحت المصادقة
Route::middleware(['auth'])->group(function () {
    // Dashboard للمستخدمين العاديين
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // routes أخرى محمية بالمصادقة...
});

// routes لوحة التحكم للمسؤولين
require __DIR__.'/admin.php';

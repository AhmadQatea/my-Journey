<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\ArticlesController;
use App\Http\Controllers\Website\ContactController;
use App\Http\Controllers\Website\LegalController;
use App\Http\Controllers\Website\ProvincesController;
use App\Http\Controllers\Website\TripsController;
use Illuminate\Support\Facades\Route;

// الصفحة الرئيسية للزوار
Route::get('/', [HomeController::class, 'index'])->name('home');

// صفحات الموقع العامة
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/trips', [TripsController::class, 'index'])->name('trips');
Route::get('/articles', [ArticlesController::class, 'index'])->name('articles');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'storeContact'])->name('contact.store');
Route::post('/feedback', [ContactController::class, 'storeFeedback'])->name('feedback.store');
Route::get('/provinces', [ProvincesController::class, 'index'])->name('provinces');
Route::get('/provinces/{governorate}', [ProvincesController::class, 'show'])->name('provinces.show');

// الصفحات القانونية
Route::get('/terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/cookies', [LegalController::class, 'cookies'])->name('legal.cookies');

// API endpoint للصفحات القانونية (للاستخدام مع JavaScript)
Route::get('/api/legal/{type}', [LegalController::class, 'getContent'])->name('api.legal.content');

// مجموعة routes المصادقة (يتم التعامل معها بواسطة Fortify)
require __DIR__.'/auth.php';

// routes تحت المصادقة
Route::middleware(['auth', 'two-factor'])->group(function () {
    // Dashboard للمستخدمين العاديين
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Bookings routes
    Route::get('/my-bookings', [\App\Http\Controllers\BookingController::class, 'index'])->name('my-bookings');
    Route::get('/bookings/create', [\App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [\App\Http\Controllers\BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'destroy'])->name('bookings.destroy');

    // Articles routes
    Route::get('/my-articles', [\App\Http\Controllers\ArticleController::class, 'index'])->name('my-articles');
    Route::get('/articles/create', [\App\Http\Controllers\ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [\App\Http\Controllers\ArticleController::class, 'store'])->name('articles.store');

    // صفحة عرض المقال العامة (مستقلة عن لوحة المستخدم)
    Route::get('/articles/{article}', [\App\Http\Controllers\Website\ArticlesController::class, 'show'])
        ->name('articles.show');

    // صفحة عرض المقال داخل ملف المستخدم (خاصة بصاحب المقال)
    Route::get('/my-articles/{article}', [\App\Http\Controllers\ArticleController::class, 'show'])
        ->name('user-articles.show');

    Route::get('/articles/{article}/edit', [\App\Http\Controllers\ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [\App\Http\Controllers\ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [\App\Http\Controllers\ArticleController::class, 'destroy'])->name('articles.destroy');
});

// routes لوحة التحكم للمسؤولين
require __DIR__.'/admin.php';

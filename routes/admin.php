<?php

use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// مسارات المصادقة (غير محمية)
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login']);
});

// مسارات لوحة التحكم (محمية)
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');

    // Trips
    Route::resource('trips', TripController::class);
    Route::post('/trips/{trip}/activate', [TripController::class, 'activate'])->name('trips.activate');
    Route::post('/trips/{trip}/deactivate', [TripController::class, 'deactivate'])->name('trips.deactivate');
    Route::post('/trips/{trip}/disable-bookings', [TripController::class, 'disableBookings'])->name('trips.disable-bookings');
    Route::post('/trips/{trip}/enable-bookings', [TripController::class, 'enableBookings'])->name('trips.enable-bookings');

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Articles
    Route::resource('articles', ArticleController::class);
    Route::post('/articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve');
    Route::post('/articles/{article}/reject', [ArticleController::class, 'reject'])->name('articles.reject');

    // Deals/Offers
    Route::resource('deals', OfferController::class);
    Route::get('/deals', [OfferController::class, 'index'])->name('deals.index');
    Route::get('/deals/create', [OfferController::class, 'create'])->name('deals.create');
    Route::post('/deals', [OfferController::class, 'store'])->name('deals.store');
    Route::get('/deals/{deal}', [OfferController::class, 'show'])->name('deals.show');
    Route::get('/deals/{deal}/edit', [OfferController::class, 'edit'])->name('deals.edit');
    Route::put('/deals/{deal}', [OfferController::class, 'update'])->name('deals.update');
    Route::delete('/deals/{deal}', [OfferController::class, 'destroy'])->name('deals.destroy');

    // Cities (سيتم إضافتها لاحقاً)
    // Route::resource('cities', CityController::class);

    // Admins (سيتم إضافتها لاحقاً)
    // Route::resource('admins', AdminController::class);

    // Reports (سيتم إضافتها لاحقاً)
    // Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

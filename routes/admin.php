<?php

use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GovernorateController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TouristSpotController;
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

    // Dashboard - مع دعم role في الـ URL
    Route::get('/dashboard', [DashboardController::class, 'redirectToRoleDashboard'])->name('dashboard.redirect');
    Route::get('/dashboard-{role}', [DashboardController::class, 'index'])->name('dashboard');

    // المحافظات
    Route::resource('governorates', GovernorateController::class);
    Route::post('/governorates/{governorate}/activate', [GovernorateController::class, 'activate'])->name('governorates.activate');
    Route::post('/governorates/{governorate}/deactivate', [GovernorateController::class, 'deactivate'])->name('governorates.deactivate');

    // الأماكن السياحية
    Route::resource('tourist-spots', TouristSpotController::class);
    Route::post('/tourist-spots/{tourist_spot}/activate', [TouristSpotController::class, 'activate'])->name('tourist-spots.activate');
    Route::post('/tourist-spots/{tourist_spot}/deactivate', [TouristSpotController::class, 'deactivate'])->name('tourist-spots.deactivate');

    // الفئات
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Users
    Route::resource('users', UserController::class)->except(['create', 'store']);
    Route::post('/users/{user}/activate', [UserController::class, 'activate'])->name('users.activate');
    Route::post('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::post('/users/{user}/verify-identity', [UserController::class, 'verifyIdentity'])->name('users.verify-identity');
    Route::post('/users/{user}/upgrade-to-vip', [UserController::class, 'upgradeToVip'])->name('users.upgrade-to-vip');

    // Trips
    Route::resource('trips', TripController::class);
    Route::get('/trips/tourist-spots/by-governorates', [TripController::class, 'getTouristSpotsByGovernorates'])->name('trips.tourist-spots.by-governorates');
    Route::post('/trips/{trip}/status', [TripController::class, 'changeStatus'])->name('trips.status');
    Route::post('/trips/bulk-action', [TripController::class, 'bulkAction'])->name('trips.bulk-action');
    Route::post('/trips/{trip}/feature', [TripController::class, 'toggleFeatured'])->name('trips.feature');
    Route::post('/trips/{trip}/activate', [TripController::class, 'activate'])->name('trips.activate');
    Route::post('/trips/{trip}/deactivate', [TripController::class, 'deactivate'])->name('trips.deactivate');
    Route::post('/trips/{trip}/disable-bookings', [TripController::class, 'disableBookings'])->name('trips.disable-bookings');
    Route::post('/trips/{trip}/enable-bookings', [TripController::class, 'enableBookings'])->name('trips.enable-bookings');

    // Bookings
    Route::resource('bookings', BookingController::class);
    Route::post('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');

    // Articles
    Route::resource('articles', ArticleController::class);
    Route::post('/articles/{article}/approve', [ArticleController::class, 'approve'])->name('articles.approve');
    Route::post('/articles/{article}/reject', [ArticleController::class, 'reject'])->name('articles.reject');

    // Deals/Offers
    Route::resource('deals', OfferController::class);
    Route::post('/deals/{deal}/change-status', [OfferController::class, 'changeStatus'])->name('deals.changeStatus');
    Route::get('/deals/get-trip-details', [OfferController::class, 'getTripDetails'])->name('deals.get-trip-details');

    // Admins
    Route::resource('admins', AdminsController::class);

    // Roles
    Route::resource('roles', RoleController::class);
});

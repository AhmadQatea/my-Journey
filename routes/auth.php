<?php

use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginEmailVerificationController;

// Authentication routes
Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    // Password change
    Route::get('/password/change', function () {
        return view('auth.change-password');
    })->name('password.change');

    // 2FA routes
    Route::prefix('two-factor')->group(function () {
        Route::get('/setup', [TwoFactorAuthController::class, 'showSetupForm'])
            ->name('two-factor.setup');

        Route::post('/setup', [TwoFactorAuthController::class, 'enable'])
            ->name('two-factor.enable');

        Route::delete('/setup', [TwoFactorAuthController::class, 'disable'])
            ->name('two-factor.disable');

        Route::get('/recovery-codes', [TwoFactorAuthController::class, 'showRecoveryCodes'])
            ->name('two-factor.recovery-codes');

        Route::post('/recovery-codes', [TwoFactorAuthController::class, 'generateNewRecoveryCodes'])
            ->name('two-factor.generate-recovery-codes');

        Route::post('/verify-for-password-change', [TwoFactorAuthController::class, 'verifyForPasswordChange'])
            ->name('two-factor.verify-for-password-change');
    });

    // Google account linking
    Route::post('/auth/google/link', [SocialiteController::class, 'linkGoogleAccount'])
        ->name('google.link');

    Route::post('/auth/google/unlink', [SocialiteController::class, 'unlinkGoogleAccount'])
        ->name('google.unlink');
});

// Google OAuth routes
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])
    ->name('login.google');

Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

// 2FA challenge during login
Route::middleware(['auth'])->group(function () {
    Route::get('/two-factor-challenge', function () {
        return view('auth.two-factor-challenge');
    })->name('two-factor.challenge');

    Route::post('/two-factor-challenge', [TwoFactorAuthController::class, 'verify'])
        ->name('two-factor.verify');

    // إرسال كود التحقق عبر الإيميل للتحقق بخطوتين
    Route::post('/two-factor/send-email-code', [TwoFactorAuthController::class, 'sendEmailCode'])
        ->name('two-factor.send-email-code');
});


// ========== Routes للتحقق من الإيميل عند تسجيل الدخول ==========
Route::middleware('guest')->group(function () {
    // إرسال كود التحقق
    Route::post('/login/send-code', [LoginEmailVerificationController::class, 'sendLoginCode'])
        ->name('login.send-code');

    // عرض نموذج إدخال كود التحقق
    Route::get('/login/verify-code', [LoginEmailVerificationController::class, 'showVerifyForm'])
        ->name('login.verify-code');

    // التحقق من كود تسجيل الدخول
    Route::post('/login/verify-code', [LoginEmailVerificationController::class, 'verifyLoginCode'])
        ->name('login.verify-code.post');

    // إعادة إرسال كود التحقق
    Route::post('/login/resend-code', [LoginEmailVerificationController::class, 'resendLoginCode'])
        ->name('login.resend-code');
});

// ========== Routes لاستعادة كلمة المرور (لغير المسجلين) ==========
Route::middleware('guest')->group(function () {
    // طلب كود التحقق
    Route::get('/forgot-password', [PasswordResetController::class, 'showCodeRequestForm'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetCode'])
        ->name('password.code.send');

    // إدخال كود التحقق
    Route::get('/password/verify-code', [PasswordResetController::class, 'showVerifyCodeForm'])
        ->name('password.code.verify');

    Route::post('/password/verify-code', [PasswordResetController::class, 'verifyResetCode'])
        ->name('password.code.verify.post');

    // إعادة إرسال الكود
    Route::get('/password/resend-code', [PasswordResetController::class, 'resendResetCode'])
        ->name('password.code.resend');

    // تعيين كلمة المرور الجديدة
    Route::get('/password/reset', [PasswordResetController::class, 'showResetForm'])
        ->name('password.reset.form');

    Route::post('/password/reset', [PasswordResetController::class, 'resetPassword'])
        ->name('password.reset');
});

// ========== Routes لتغيير كلمة المرور (للمسجلين) ==========
Route::middleware('auth')->group(function () {
    // طلب كود التحقق
    Route::get('/password/change/request', [ChangePasswordController::class, 'showRequestCodeForm'])
        ->name('password.change.request');

    Route::post('/password/change/send-code', [ChangePasswordController::class, 'sendChangeCode'])
        ->name('password.change.send');

    // إدخال كود التحقق
    Route::get('/password/change/verify', [ChangePasswordController::class, 'showVerifyChangeCodeForm'])
        ->name('password.change.verify');

    Route::post('/password/change/verify', [ChangePasswordController::class, 'verifyChangeCode'])
        ->name('password.change.verify.post');

    // تغيير كلمة المرور
    Route::get('/password/change', [ChangePasswordController::class, 'showChangeForm'])
        ->name('password.change.form');

    Route::post('/password/change', [ChangePasswordController::class, 'changePassword'])
        ->name('password.change');
});

<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\LoginEmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\TwoFactorAuthController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Password change
    Route::get('/password/change', function () {
        return view('auth.change-password');
    })->name('password.change');

    // 2FA setup page
    Route::get('/two-factor/setup', [TwoFactorAuthController::class, 'showSetupForm'])
        ->name('two-factor.setup');

    // Verify password before enabling 2FA
    Route::post('/two-factor/verify-password', [TwoFactorAuthController::class, 'verifyPassword'])
        ->name('two-factor.verify-password');

    // Enable 2FA (confirm with code) - custom route to avoid conflict with Fortify
    Route::post('/two-factor/enable', [TwoFactorAuthController::class, 'enable'])
        ->name('two-factor.enable-custom');

    // 2FA recovery codes page (custom route to avoid conflict with Fortify)
    Route::get('/two-factor/recovery-codes', [TwoFactorAuthController::class, 'showRecoveryCodes'])
        ->name('two-factor.recovery-codes.show');

    // Generate new recovery codes
    Route::post('/user/two-factor-recovery-codes', [TwoFactorAuthController::class, 'generateNewRecoveryCodes'])
        ->name('two-factor.generate-recovery-codes');

    // Keep verify-for-password-change for password change functionality
    Route::post('/two-factor/verify-for-password-change', [TwoFactorAuthController::class, 'verifyForPasswordChange'])
        ->name('two-factor.verify-for-password-change');

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

// 2FA challenge is handled by Fortify automatically via FortifyServiceProvider
// Fortify will redirect to /two-factor-challenge when needed

// إرسال كود التحقق عبر الإيميل للتحقق بخطوتين (مخصص)
Route::middleware(['auth'])->group(function () {
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

    // Email Verification routes
    Route::post('/email/send-verification', [\App\Http\Controllers\User\EmailVerificationController::class, 'sendVerificationCode'])
        ->name('email.send');
    Route::get('/email/verify', [\App\Http\Controllers\User\EmailVerificationController::class, 'showVerifyForm'])
        ->name('email.verify');
    Route::post('/email/verify', [\App\Http\Controllers\User\EmailVerificationController::class, 'verifyCode'])
        ->name('email.verify.post');
    Route::post('/email/resend', [\App\Http\Controllers\User\EmailVerificationController::class, 'resendCode'])
        ->name('email.resend');

    // Identity Verification routes
    Route::get('/identity-verification', [\App\Http\Controllers\User\IdentityVerificationController::class, 'create'])
        ->name('identity-verification.create');
    Route::post('/identity-verification', [\App\Http\Controllers\User\IdentityVerificationController::class, 'store'])
        ->name('identity-verification.store');

    // Notifications routes
    Route::get('/notifications', [\App\Http\Controllers\User\NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\User\NotificationController::class, 'markAsRead'])
        ->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\User\NotificationController::class, 'markAllAsRead'])
        ->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\User\NotificationController::class, 'destroy'])
        ->name('notifications.destroy');
});

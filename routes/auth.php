<?php

use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

// routes المصادقة الاجتماعية (اختياري)
Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect'])
    ->name('social.login.redirect');

Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback'])
    ->name('social.login.callback');

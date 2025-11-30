<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

abstract class AdminController extends Controller
{
    /**
     * Get the authenticated admin user.
     */
    protected function admin(): ?\App\Models\Admin
    {
        return Auth::guard('admin')->user();
    }

    /**
     * Get the authenticated admin user ID.
     */
    protected function adminId(): ?int
    {
        return Auth::guard('admin')->id();
    }

    /**
     * Check if an admin is authenticated.
     */
    protected function isAdminAuthenticated(): bool
    {
        return Auth::guard('admin')->check();
    }
}

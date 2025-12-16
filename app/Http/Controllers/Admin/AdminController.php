<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;

abstract class AdminController extends Controller
{
    /**
     * Get the authenticated admin user.
     */
    protected function admin(): ?Admin
    {
        return auth('admin')->user();
    }
}

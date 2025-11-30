<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Booking;
use App\Models\User;

class DashboardController extends AdminController
{
    public function index()
    {
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalArticles = Article::count();
        $totalRevenue = Booking::where('status', 'مؤكدة')->sum('total_price') ?? 0;

        $recentUsers = User::latest()->take(5)->get();
        $recentBookings = Booking::with(['user', 'trip'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalBookings',
            'totalArticles',
            'totalRevenue',
            'recentUsers',
            'recentBookings'
        ));
    }
}

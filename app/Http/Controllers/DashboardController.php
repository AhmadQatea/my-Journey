<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // جلب عدد الحجوزات النشطة (المؤكدة)
        $activeBookingsCount = Booking::where('user_id', $user->id)
            ->where('status', 'مؤكدة')
            ->count();

        // جلب عدد المقالات المنشورة
        $publishedArticlesCount = Article::where('user_id', $user->id)
            ->where('status', 'منشورة')
            ->count();

        // جلب آخر الحجوزات (5 حجوزات)
        $recentBookings = Booking::where('user_id', $user->id)
            ->with('trip')
            ->latest()
            ->take(5)
            ->get();

        // جلب آخر المقالات (5 مقالات)
        $recentArticles = Article::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // تحديد حالة الحساب
        $accountStatus = 'عادي';
        $accountStatusDescription = 'مستخدم نشط';

        if ($user->isVip()) {
            $accountStatus = 'مميز';
            $accountStatusDescription = 'مستخدم VIP';
        } elseif ($user->identity_verified) {
            $accountStatus = 'موثق';
            $accountStatusDescription = 'هوية موثقة';
        }

        return view('website.user.dashboard', compact(
            'activeBookingsCount',
            'publishedArticlesCount',
            'recentBookings',
            'recentArticles',
            'accountStatus',
            'accountStatusDescription'
        ));
    }
}

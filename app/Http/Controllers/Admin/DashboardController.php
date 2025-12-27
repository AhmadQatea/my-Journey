<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Booking;
use App\Models\Offer;
use App\Models\TouristSpot;
use App\Models\User;

class DashboardController extends AdminController
{
    /**
     * إعادة التوجيه من /dashboard إلى /dashboard-{role}
     */
    public function redirectToRoleDashboard()
    {
        $admin = $this->admin();

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        // تحميل role إذا لم يكن محملاً
        if (! $admin->relationLoaded('role')) {
            $admin->load('role');
        }

        $roleSlug = $admin->getRoleSlug();

        if ($roleSlug) {
            return redirect()->route('admin.dashboard', ['role' => $roleSlug]);
        }

        // إذا لم يكن هناك role، نعيد التوجيه إلى dashboard بدون role
        return redirect()->route('admin.dashboard', ['role' => 'admin']);
    }

    public function index(string $role)
    {
        $admin = $this->admin();

        if (! $admin) {
            return redirect()->route('admin.login');
        }

        // تحميل role إذا لم يكن محملاً
        if (! $admin->relationLoaded('role')) {
            $admin->load('role');
        }

        // التحقق من أن الـ role في الـ URL يطابق role الأدمن الحالي
        $adminRoleSlug = $admin->getRoleSlug();
        if ($adminRoleSlug && $adminRoleSlug !== $role) {
            // إذا كان الـ role غير صحيح، نعيد التوجيه إلى الـ URL الصحيح
            return redirect()->route('admin.dashboard', ['role' => $adminRoleSlug]);
        }

        $totalUsers = User::count();
        $totalVerifiedUsers = User::where('identity_verified', true)->count();

        $totalBookings = Booking::count();
        $totalArticles = Article::count();
        $totalTouristSpots = TouristSpot::count();
        $totalOffers = Offer::count();
        $recentUsers = User::latest()->take(5)->get();
        $recentBookings = Booking::with(['user', 'trip'])->latest()->take(5)->get();

        // بيانات الحجوزات حسب الشهر (آخر 6 أشهر)
        $bookingsData = $this->getBookingsByMonth(6);

        // بيانات الإيرادات حسب الشهر (آخر 6 أشهر)
        $revenueData = $this->getRevenueByMonth(6);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalVerifiedUsers',
            'totalBookings',
            'totalArticles',
            'totalTouristSpots',
            'totalOffers',
            'recentUsers',
            'recentBookings',
            'bookingsData',
            'revenueData'
        ));
    }

    /**
     * جلب بيانات الحجوزات حسب الشهر
     */
    private function getBookingsByMonth(int $months = 6): array
    {
        $data = [];
        $labels = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $count = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            $data[] = $count;
            $labels[] = $this->getArabicMonthName($date->month);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * جلب بيانات الإيرادات حسب الشهر
     */
    private function getRevenueByMonth(int $months = 6): array
    {
        $data = [];
        $labels = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            // حساب الإيرادات من الحجوزات المؤكدة فقط
            $revenue = Booking::where('status', 'مؤكدة')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('total_price');

            $data[] = (float) $revenue;
            $labels[] = $this->getArabicMonthName($date->month);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * الحصول على اسم الشهر بالعربية
     */
    private function getArabicMonthName(int $month): string
    {
        $months = [
            1 => 'يناير',
            2 => 'فبراير',
            3 => 'مارس',
            4 => 'أبريل',
            5 => 'مايو',
            6 => 'يونيو',
            7 => 'يوليو',
            8 => 'أغسطس',
            9 => 'سبتمبر',
            10 => 'أكتوبر',
            11 => 'نوفمبر',
            12 => 'ديسمبر',
        ];

        return $months[$month] ?? '';
    }
}

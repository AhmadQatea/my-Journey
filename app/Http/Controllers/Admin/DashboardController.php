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

        // بيانات الحجوزات حسب الشهر (آخر 6 أشهر) - افتراضي
        $bookingsData = $this->getBookingsByMonth(6);

        // بيانات الإيرادات حسب الشهر (آخر 6 أشهر) - افتراضي
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
     * API endpoint لجلب بيانات الحجوزات والإيرادات حسب الفترة
     */
    public function getChartData()
    {
        $period = request('period', 'months'); // days, weeks, months
        $count = (int) request('count', 6);

        $bookingsData = match ($period) {
            'days' => $this->getBookingsByDays($count),
            'weeks' => $this->getBookingsByWeeks($count),
            'months' => $this->getBookingsByMonth($count),
            default => $this->getBookingsByMonth(6),
        };

        $revenueData = match ($period) {
            'days' => $this->getRevenueByDays($count),
            'weeks' => $this->getRevenueByWeeks($count),
            'months' => $this->getRevenueByMonth($count),
            default => $this->getRevenueByMonth(6),
        };

        return response()->json([
            'bookings' => $bookingsData,
            'revenue' => $revenueData,
        ]);
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
     * جلب بيانات الحجوزات حسب الأيام
     */
    private function getBookingsByDays(int $days = 7): array
    {
        $data = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();

            $count = Booking::whereBetween('created_at', [$startOfDay, $endOfDay])->count();

            $data[] = $count;
            $labels[] = $date->format('d/m');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * جلب بيانات الحجوزات حسب الأسابيع
     */
    private function getBookingsByWeeks(int $weeks = 4): array
    {
        $data = [];
        $labels = [];

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $date = now()->subWeeks($i);
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek = $date->copy()->endOfWeek();

            $count = Booking::whereBetween('created_at', [$startOfWeek, $endOfWeek])->count();

            $data[] = $count;
            $labels[] = $startOfWeek->format('d/m').' - '.$endOfWeek->format('d/m');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * جلب بيانات الإيرادات حسب الأيام
     */
    private function getRevenueByDays(int $days = 7): array
    {
        $data = [];
        $labels = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();

            $revenue = Booking::where('status', 'مؤكدة')
                ->whereBetween('created_at', [$startOfDay, $endOfDay])
                ->sum('total_price');

            $data[] = (float) $revenue;
            $labels[] = $date->format('d/m');
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * جلب بيانات الإيرادات حسب الأسابيع
     */
    private function getRevenueByWeeks(int $weeks = 4): array
    {
        $data = [];
        $labels = [];

        for ($i = $weeks - 1; $i >= 0; $i--) {
            $date = now()->subWeeks($i);
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek = $date->copy()->endOfWeek();

            $revenue = Booking::where('status', 'مؤكدة')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('total_price');

            $data[] = (float) $revenue;
            $labels[] = $startOfWeek->format('d/m').' - '.$endOfWeek->format('d/m');
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

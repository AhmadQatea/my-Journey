<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\Booking;
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

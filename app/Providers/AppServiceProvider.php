<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Route Model Binding للـ Admin
        Route::bind('admin', function ($value) {
            return Admin::findOrFail($value);
        });

        // تعريف Gates للصلاحيات (تعمل مع guard 'admin')
        Gate::define('manage_governorates', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();

            return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_governorates'));
        });

        Gate::define('manage_tourist_spots', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();

            return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_tourist_spots'));
        });

        Gate::define('manage_trips', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();

            return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_trips'));
        });

        Gate::define('manage_deals', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();

            return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_deals'));
        });

        Gate::define('manage_bookings', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();

            return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_bookings'));
        });

        Gate::define('manage_articles', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();

            return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_articles'));
        });

        Gate::define('view_users', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();

            return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('view_users'));
        });

        Gate::define('site-manager', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();

            return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('site-manager') || $admin->hasPermission('settings.manage'));
        });

        Gate::define('manage_feedback', function ($admin = null) {
            $admin = $admin ?? Auth::guard('admin')->user();

            return $admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_feedback') || $admin->hasPermission('view_users') || $admin->hasPermission('site-manager') || $admin->hasPermission('settings.manage'));
        });

        // تمرير معلومات الأدمن إلى جميع الـ views
        View::composer('admin.*', function ($view) {
            $currentAdmin = Auth::guard('admin')->user();
            $viewData = $view->getData();

            // تحميل role إذا لم يكن محملاً
            if ($currentAdmin && ! $currentAdmin->relationLoaded('role')) {
                $currentAdmin->load('role');
            }

            // إذا كان هناك متغير admin من Controller (مثل في edit/show)، لا نستبدله
            $adminForView = $viewData['admin'] ?? $currentAdmin;

            $roleName = $currentAdmin?->role?->name ?? null;
            $roleNameArabic = null;
            $roleSlug = null;

            if ($currentAdmin) {
                $roleSlug = $currentAdmin->getRoleSlug();
            }

            if ($roleName && $currentAdmin?->role) {
                $roles = Role::getRoles();
                $roleNameArabic = $roles[$roleName] ?? $currentAdmin->role->name;
            }

            // قائمة أزرار الـ sidebar
            $sidebarMenuItems = self::getSidebarMenuItems($currentAdmin, $roleSlug);

            // فقط إذا لم يكن هناك متغير admin موجود بالفعل (من Controller)
            if (! isset($viewData['admin'])) {
                $view->with('admin', $currentAdmin);
            }

            $view->with([
                'currentAdmin' => $currentAdmin,
                'adminRole' => $roleName,
                'adminRoleName' => $roleNameArabic,
                'adminRoleSlug' => $roleSlug,
                'isSuperAdmin' => $currentAdmin?->is_super_admin ?? false,
                'adminPermissions' => $currentAdmin?->role?->permissions ?? [],
                'sidebarMenuItems' => $sidebarMenuItems,
            ]);
        });
    }

    /**
     * الحصول على قائمة أزرار الـ sidebar
     */
    private static function getSidebarMenuItems(?Admin $admin, ?string $adminRoleSlug): array
    {
        $items = [];

        // التحقق إذا كان المسؤول site manager فقط (وليس super admin)
        // site manager لديه صلاحية site-manager أو settings.manage فقط بدون صلاحيات أخرى
        $roleName = $admin?->role?->name ?? null;
        $hasSiteManagerPermission = $admin && ($admin->hasPermission('site-manager') || $admin->hasPermission('settings.manage'));
        $isSiteManagerOnly = $admin
            && ! $admin->isSuperAdmin()
            && $hasSiteManagerPermission
            && ! $admin->hasPermission('manage_governorates')
            && ! $admin->hasPermission('manage_tourist_spots')
            && ! $admin->hasPermission('manage_trips')
            && ! $admin->hasPermission('manage_deals')
            && ! $admin->hasPermission('manage_bookings')
            && ! $admin->hasPermission('manage_articles')
            && ! $admin->hasPermission('manage_admins');

        // Dashboard - متاح للجميع
        $items[] = [
            'title' => 'لوحة التحكم',
            'route' => $adminRoleSlug ? route('admin.dashboard', ['role' => $adminRoleSlug]) : route('admin.dashboard.redirect'),
            'icon' => 'fas fa-tachometer-alt',
            'routePattern' => 'admin.dashboard*',
            'permission' => null, // متاح للجميع
        ];

        // إذا كان site manager فقط، نعرض فقط الملاحظات والتقييمات وإعدادات الموقع
        if ($isSiteManagerOnly) {
            // الملاحظات والتقييمات - متاحة لـ site manager حتى بدون صلاحية view_users
            $items[] = [
                'title' => 'الملاحظات والتقييمات',
                'route' => 'admin.feedback.index',
                'icon' => 'fas fa-comment-dots',
                'routePattern' => 'admin.feedback.*',
                'permission' => null, // متاحة لـ site manager
            ];

            // إعدادات الموقع
            $items[] = [
                'title' => 'إعدادات الموقع',
                'route' => 'admin.site.index',
                'icon' => 'fas fa-cog',
                'routePattern' => 'admin.site.*',
                'permission' => null, // متاحة لـ site manager
            ];

            return $items;
        }

        // المحافظات
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_governorates'))) {
            $items[] = [
                'title' => 'المحافظات',
                'route' => 'admin.governorates.index',
                'icon' => 'fas fa-mountain',
                'routePattern' => 'admin.governorates.*',
                'permission' => 'manage_governorates',
            ];
        }

        // الأماكن السياحية
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_tourist_spots'))) {
            $items[] = [
                'title' => 'الأماكن السياحية',
                'route' => 'admin.tourist-spots.index',
                'icon' => 'fas fa-map-marker-alt',
                'routePattern' => 'admin.tourist-spots.*',
                'permission' => 'manage_tourist_spots',
            ];
        }

        // الفئات
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_categories'))) {
            $items[] = [
                'title' => 'الفئات',
                'route' => 'admin.categories.index',
                'icon' => 'fas fa-tags',
                'routePattern' => 'admin.categories.*',
                'permission' => 'manage_categories',
            ];
        }

        // الرحلات
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_trips'))) {
            $items[] = [
                'title' => 'الرحلات',
                'route' => 'admin.trips.index',
                'icon' => 'fas fa-map-marked-alt',
                'routePattern' => 'admin.trips.*',
                'permission' => 'manage_trips',
            ];
        }

        // العروض
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_deals'))) {
            $items[] = [
                'title' => 'العروض',
                'route' => 'admin.deals.index',
                'icon' => 'fas fa-tag',
                'routePattern' => 'admin.deals.*',
                'permission' => 'manage_deals',
            ];
        }

        // الحجوزات
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_bookings'))) {
            $items[] = [
                'title' => 'الحجوزات',
                'route' => 'admin.bookings.index',
                'icon' => 'fas fa-calendar-check',
                'routePattern' => 'admin.bookings.*',
                'permission' => 'manage_bookings',
            ];
        }

        // المقالات
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_articles'))) {
            $items[] = [
                'title' => 'المقالات',
                'route' => 'admin.articles.index',
                'icon' => 'fas fa-newspaper',
                'routePattern' => 'admin.articles.*',
                'permission' => 'manage_articles',
            ];
        }

        // المستخدمين
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('view_users'))) {
            $items[] = [
                'title' => 'المستخدمين',
                'route' => 'admin.users.index',
                'icon' => 'fas fa-users',
                'routePattern' => 'admin.users.*',
                'permission' => 'view_users',
            ];
        }

        // طلبات توثيق الهوية
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('view_users'))) {
            $pendingCount = \App\Models\IdentityVerification::where('status', 'pending')->count();
            $items[] = [
                'title' => 'توثيق الهوية',
                'route' => 'admin.identity-verifications.index',
                'icon' => 'fas fa-id-card',
                'routePattern' => 'admin.identity-verifications.*',
                'permission' => 'view_users',
                'badge' => $pendingCount > 0 ? $pendingCount : null,
            ];
        }

        // المسؤولين
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_admins'))) {
            $items[] = [
                'title' => 'المسؤولين',
                'route' => 'admin.admins.index',
                'icon' => 'fas fa-user-shield',
                'routePattern' => 'admin.admins.*',
                'permission' => 'manage_admins',
            ];
        }

        // الأدوار
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('manage_admins'))) {
            $items[] = [
                'title' => 'الأدوار',
                'route' => 'admin.roles.index',
                'icon' => 'fas fa-user-tag',
                'routePattern' => 'admin.roles.*',
                'permission' => 'manage_admins',
            ];
        }

        // رسائل المستخدمين
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('view_users'))) {
            $unreadMessagesCount = \App\Models\ContactMessage::where('status', '!=', 'replied')->count();
            $items[] = [
                'title' => 'رسائل المستخدمين',
                'route' => 'admin.contact-messages.index',
                'icon' => 'fas fa-envelope',
                'routePattern' => 'admin.contact-messages.*',
                'permission' => 'view_users',
                'badge' => $unreadMessagesCount > 0 ? $unreadMessagesCount : null,
            ];
        }

        // الملاحظات والتقييمات
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('view_users') || $admin->hasPermission('manage_feedback') || $admin->hasPermission('site-manager') || $admin->hasPermission('settings.manage'))) {
            $items[] = [
                'title' => 'الملاحظات والتقييمات',
                'route' => 'admin.feedback.index',
                'icon' => 'fas fa-comment-dots',
                'routePattern' => 'admin.feedback.*',
                'permission' => 'manage_feedback',
            ];
        }

        // إعدادات الموقع
        if ($admin && ($admin->isSuperAdmin() || $admin->hasPermission('site-manager') || $admin->hasPermission('settings.manage'))) {
            $items[] = [
                'title' => 'إعدادات الموقع',
                'route' => 'admin.site.index',
                'icon' => 'fas fa-cog',
                'routePattern' => 'admin.site.*',
                'permission' => 'site-manager',
            ];
        }

        return $items;
    }
}

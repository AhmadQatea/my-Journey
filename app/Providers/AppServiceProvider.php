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
            ]);
        });
    }
}

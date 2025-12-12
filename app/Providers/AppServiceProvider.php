<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
            $admin = Auth::guard('admin')->user();

            $roleName = $admin?->role?->name ?? null;
            $roleNameArabic = null;
            $roleSlug = null;

            if ($admin) {
                $roleSlug = $admin->getRoleSlug();
            }

            if ($roleName && $admin?->role) {
                $roles = Role::getRoles();
                $roleNameArabic = $roles[$roleName] ?? $admin->role->name;
            }

            $view->with([
                'admin' => $admin,
                'adminRole' => $roleName,
                'adminRoleName' => $roleNameArabic,
                'adminRoleSlug' => $roleSlug,
                'isSuperAdmin' => $admin?->is_super_admin ?? false,
                'adminPermissions' => $admin?->role?->permissions ?? [],
            ]);
        });
    }
}

<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;
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

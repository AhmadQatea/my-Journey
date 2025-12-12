<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Get all available permissions from Role model
        $allPermissions = Role::getPermissions();

        // User permissions (limited)
        $userPermissions = [
            'view dashboard',
            'manage profile',
            'manage 2fa',
            'manage social login',
        ];

        // Create admin role with all permissions
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['permissions' => $allPermissions]
        );

        // Update admin role permissions if it already exists
        if ($adminRole->wasRecentlyCreated === false) {
            $adminRole->update(['permissions' => $allPermissions]);
        }

        // Create user role with limited permissions
        $userRole = Role::firstOrCreate(
            ['name' => 'user'],
            ['permissions' => $userPermissions]
        );

        // Update user role permissions if it already exists
        if ($userRole->wasRecentlyCreated === false) {
            $userRole->update(['permissions' => $userPermissions]);
        }
    }
}

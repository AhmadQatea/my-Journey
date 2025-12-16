<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPermissions = Role::getPermissions();

        // Big Boss - جميع الصلاحيات
        $bigBossRole = Role::firstOrCreate(
            ['name' => 'big_boss'],
            [
                'description' => 'Big Boss - المسؤول الرئيسي',
                'permissions' => $allPermissions,
            ]
        );
        if (! $bigBossRole->wasRecentlyCreated) {
            $bigBossRole->update(['permissions' => $allPermissions]);
        }

        // Site Admin - صلاحيات إدارة الموقع
        $siteAdminPermissions = [
            'manage_governorates',
            'manage_tourist_spots',
            'manage_categories',
            'manage_trips',
            'manage_deals',
            'manage_articles',
        ];
        $siteAdminRole = Role::firstOrCreate(
            ['name' => 'site_admin'],
            [
                'description' => 'Site Admin - مدير الموقع',
                'permissions' => $siteAdminPermissions,
            ]
        );
        if (! $siteAdminRole->wasRecentlyCreated) {
            $siteAdminRole->update(['permissions' => $siteAdminPermissions]);
        }

        // Booking Admin - صلاحيات الحجوزات فقط
        $bookingAdminPermissions = [
            'manage_bookings',
            'bookings.create',
            'bookings.read',
            'bookings.update',
            'bookings.delete',
        ];
        $bookingAdminRole = Role::firstOrCreate(
            ['name' => 'booking_admin'],
            [
                'description' => 'Booking Admin - مدير الحجوزات',
                'permissions' => $bookingAdminPermissions,
            ]
        );
        if (! $bookingAdminRole->wasRecentlyCreated) {
            $bookingAdminRole->update(['permissions' => $bookingAdminPermissions]);
        }

        // Users Admin - صلاحيات المستخدمين فقط
        $usersAdminPermissions = [
            'view_users',
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
        ];
        $usersAdminRole = Role::firstOrCreate(
            ['name' => 'users_admin'],
            [
                'description' => 'Users Admin - مدير المستخدمين',
                'permissions' => $usersAdminPermissions,
            ]
        );
        if (! $usersAdminRole->wasRecentlyCreated) {
            $usersAdminRole->update(['permissions' => $usersAdminPermissions]);
        }

        $this->command->info('تم إنشاء الأدوار بنجاح!');
    }
}

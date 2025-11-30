<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('ahmad+9963');

        // الحصول على الأدوار
        $bigBossRole = Role::where('name', 'big_boss')->first();
        $bookingAdminRole = Role::where('name', 'booking_admin')->first();
        $userAdminRole = Role::where('name', 'users_admin')->first();
        $siteAdminRole = Role::where('name', 'site_admin')->first();

        // إنشاء Big Boss Admin
        $bigBoss = Admin::firstOrCreate(
            ['email' => 'ahmad@bigboss.com'],
            [
                'name' => 'Big Boss',
                'email' => 'ahmad@bigboss.com',
                'password' => $password,
                'role_id' => $bigBossRole?->id,
                'is_super_admin' => true,
                'is_active' => true,
            ]
        );

        // إنشاء Booking Admin
        $bookingAdmin = Admin::firstOrCreate(
            ['email' => 'ahmad@booking.com'],
            [
                'name' => 'Booking Manager',
                'email' => 'ahmad@booking.com',
                'password' => $password,
                'role_id' => $bookingAdminRole?->id,
                'is_super_admin' => false,
                'is_active' => true,
            ]
        );

        // إنشاء User Admin
        $userAdmin = Admin::firstOrCreate(
            ['email' => 'ahmad@user.com'],
            [
                'name' => 'User Manager',
                'email' => 'ahmad@user.com',
                'password' => $password,
                'role_id' => $userAdminRole?->id,
                'is_super_admin' => false,
                'is_active' => true,
            ]
        );

        // إنشاء Site Admin
        $siteAdmin = Admin::firstOrCreate(
            ['email' => 'ahmad@site.com'],
            [
                'name' => 'Site Manager',
                'email' => 'ahmad@site.com',
                'password' => $password,
                'role_id' => $siteAdminRole?->id,
                'is_super_admin' => false,
                'is_active' => true,
            ]
        );

        $this->command->info('تم إنشاء المسؤولين بنجاح!');
        $this->command->info('Big Boss: ahmad@bigboss.com');
        $this->command->info('Booking Admin: ahmad@booking.com');
        $this->command->info('User Admin: ahmad@user.com');
        $this->command->info('Site Admin: ahmad@site.com');
        $this->command->info('كلمة المرور لجميع الحسابات: ahmad+9963');
    }
}

<?php

namespace App\Console\Commands;

use App\Models\AdminNotification;
use App\Models\UserNotification;
use Illuminate\Console\Command;

class CleanOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'حذف الإشعارات القديمة (أكثر من 24 ساعة)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $adminDeleted = AdminNotification::old()->delete();
        $userDeleted = UserNotification::old()->delete();

        $total = $adminDeleted + $userDeleted;

        $this->info("تم حذف {$total} إشعار قديم ({$adminDeleted} إشعار مسؤول، {$userDeleted} إشعار مستخدم).");

        return Command::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CleanupTwoFactor extends Command
{
    protected $signature = '2fa:cleanup';

    protected $description = 'تنظيف بيانات 2FA للمستخدمين غير المفعلين';

    public function handle(): int
    {
        $count = User::whereNull('two_factor_confirmed_at')
            ->whereNotNull('two_factor_secret')
            ->where('updated_at', '<', now()->subDays(30))
            ->update([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
            ]);

        $this->info("تم تنظيف بيانات $count مستخدم");

        return Command::SUCCESS;
    }
}

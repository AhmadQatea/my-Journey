<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('2fa:cleanup')->monthly();

Schedule::command('inspire')->everyMinute();
Schedule::command('passwords:cleanup')->daily();
Schedule::command('notifications:clean')->hourly();

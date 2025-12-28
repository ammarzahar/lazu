<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SyncAdPerformanceJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new SyncAdPerformanceJob())->dailyAt('02:00');
Schedule::command('lazu:generate-daily-brief')->dailyAt('08:00');
Schedule::command('lazu:queue-reminders')->dailyAt('08:30');
Schedule::command('lazu:send-weekly-report')->weeklyOn(1, '09:00');

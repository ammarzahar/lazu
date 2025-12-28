<?php

namespace App\Jobs;

use App\Models\AdPerformanceSnapshot;
use App\Models\User;
use App\Services\Ads\AdsProvider;
use Illuminate\Support\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncAdPerformanceJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    public function handle(AdsProvider $provider): void
    {
        $date = Carbon::yesterday()->toDateString();

        User::query()->chunk(50, function ($users) use ($provider, $date) {
            foreach ($users as $user) {
                try {
                    $payload = $provider->fetchDailySummary($user, ['start' => $date, 'end' => $date]);
                } catch (\Throwable $e) {
                    continue;
                }

                foreach ($payload['daily'] ?? [] as $day) {
                    AdPerformanceSnapshot::query()->updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'provider' => $payload['provider'] ?? 'meta',
                            'date' => $day['date'] ?? $date,
                        ],
                        [
                            'raw_payload' => $payload,
                            'metrics' => $day,
                        ]
                    );
                }
            }
        });
    }
}

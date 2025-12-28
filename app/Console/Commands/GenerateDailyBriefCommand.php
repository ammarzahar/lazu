<?php

namespace App\Console\Commands;

use App\Models\CmoBrief;
use App\Models\MarketingEvent;
use App\Models\User;
use App\Services\Ai\AiService;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class GenerateDailyBriefCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazu:generate-daily-brief';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily CMO briefs for all users';

    /**
     * Execute the console command.
     */
    public function handle(AiService $aiService)
    {
        User::query()->chunk(50, function ($users) use ($aiService) {
            foreach ($users as $user) {
                $today = Carbon::now($user->timezone)->toDateString();

                $exists = CmoBrief::query()
                    ->where('user_id', $user->id)
                    ->where('date', $today)
                    ->exists();

                if ($exists || ! $user->businessProfile) {
                    continue;
                }

                $context = [
                    'business_profile' => $user->businessProfile->toArray(),
                    'offer' => $user->offers()->latest()->first()?->toArray(),
                    'performance' => $user->adPerformanceSnapshots()->latest('date')->first()?->metrics,
                    'upcoming_event' => MarketingEvent::query()
                        ->whereDate('event_date', '>=', $today)
                        ->orderBy('event_date')
                        ->first(),
                ];

                $brief = $aiService->generateDailyCmoBrief($context);

                CmoBrief::query()->create([
                    'user_id' => $user->id,
                    'date' => $today,
                    'decisions' => $brief->decisions,
                ]);
            }
        });

        $this->info('Daily briefs generated.');
    }
}

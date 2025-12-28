<?php

namespace App\Console\Commands;

use App\Models\MarketingEvent;
use App\Models\Reminder;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class QueueRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazu:queue-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queue reminder records for upcoming marketing events';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = MarketingEvent::query()
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->get();

        User::query()->chunk(50, function ($users) use ($events) {
            foreach ($users as $user) {
                foreach ($events as $event) {
                    $leadTimes = $event->default_lead_time_days ?? [14, 7, 1];
                    foreach ($leadTimes as $days) {
                        $remindAt = Carbon::parse($event->event_date, $user->timezone)
                            ->subDays((int) $days)
                            ->setTime(9, 0);

                        if ($remindAt->isPast()) {
                            continue;
                        }

                        Reminder::query()->firstOrCreate(
                            [
                                'user_id' => $user->id,
                                'marketing_event_id' => $event->id,
                                'remind_at' => $remindAt->utc(),
                            ],
                            [
                                'channel' => 'in_app',
                                'status' => 'pending',
                            ]
                        );
                    }
                }
            }
        });

        $this->info('Reminders queued.');
    }
}

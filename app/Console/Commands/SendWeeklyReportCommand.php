<?php

namespace App\Console\Commands;

use App\Mail\WeeklyReportMail;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class SendWeeklyReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazu:send-weekly-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly marketing report emails to founders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->where('role', 'founder')->chunk(50, function ($users) {
            foreach ($users as $user) {
                $start = Carbon::now($user->timezone)->subDays(7)->toDateString();
                $snapshots = $user->adPerformanceSnapshots()
                    ->whereDate('date', '>=', $start)
                    ->get();

                $avgCtr = round($snapshots->avg('metrics.ctr') ?? 0, 2);
                $avgCpa = round($snapshots->avg('metrics.cpa') ?? 0, 2);
                $totalSpend = round($snapshots->sum('metrics.spend') ?? 0, 2);

                $worked = [];
                $didnt = [];

                if ($avgCtr >= 1.5) {
                    $worked[] = "CTR stayed healthy at {$avgCtr}%";
                } elseif ($avgCtr > 0) {
                    $didnt[] = "CTR needs improvement (avg {$avgCtr}%)";
                }

                if ($avgCpa > 0 && $avgCpa <= 30) {
                    $worked[] = "CPA under control at RM {$avgCpa}";
                } elseif ($avgCpa > 0) {
                    $didnt[] = "CPA high at RM {$avgCpa}";
                }

                if ($totalSpend > 0) {
                    $worked[] = "Weekly spend RM {$totalSpend} across Meta ads";
                }

                if ($worked === []) {
                    $worked[] = 'Baseline data collected; ready for optimization.';
                }

                if ($didnt === []) {
                    $didnt[] = 'No major red flags detected.';
                }

                $report = [
                    'worked' => $worked,
                    'didnt_work' => $didnt,
                    'focus' => 'Tighten the offer messaging and test 2 new creatives.',
                    'actions' => [
                        'Refresh ad headline with a clearer benefit.',
                        'Test two new creatives with different hooks.',
                        'Review audience targeting and exclude low-performing segments.',
                    ],
                ];

                Mail::to($user->email)->send(new WeeklyReportMail($report));
            }
        });

        $this->info('Weekly reports sent.');
    }
}

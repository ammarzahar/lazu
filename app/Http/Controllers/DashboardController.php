<?php

namespace App\Http\Controllers;

use App\Models\CmoBrief;
use App\Models\MarketingEvent;
use App\Services\Ai\AiService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(AiService $aiService)
    {
        $user = auth()->user();
        $profile = $user->businessProfile;
        $today = Carbon::now($user->timezone)->toDateString();

        $offer = $user->offers()->latest()->first();
        $latestSnapshot = $user->adPerformanceSnapshots()->latest('date')->first();

        $brief = $user->cmoBriefs()->where('date', $today)->first();

        if (! $brief) {
            $context = [
                'business_profile' => $profile?->toArray(),
                'offer' => $offer?->toArray(),
                'performance' => $latestSnapshot?->metrics,
                'upcoming_event' => MarketingEvent::query()
                    ->whereDate('event_date', '>=', $today)
                    ->orderBy('event_date')
                    ->first(),
            ];

            $briefResult = $aiService->generateDailyCmoBrief($context);

            $brief = CmoBrief::query()->create([
                'user_id' => $user->id,
                'date' => $today,
                'decisions' => $briefResult->decisions,
            ]);
        }

        $upcomingEvents = MarketingEvent::query()
            ->whereBetween('event_date', [$today, Carbon::parse($today)->addDays(60)->toDateString()])
            ->orderBy('event_date')
            ->get();

        return view('dashboard', [
            'brief' => $brief,
            'offer' => $offer,
            'upcomingEvents' => $upcomingEvents,
            'latestSnapshot' => $latestSnapshot,
        ]);
    }

    public function sendWeeklyReport()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        Artisan::call('lazu:send-weekly-report');

        return redirect()->route('dashboard')->with('status', 'Weekly report queued.');
    }
}

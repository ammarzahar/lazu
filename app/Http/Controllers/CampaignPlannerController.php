<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignPlanRequest;
use App\Models\CampaignPlan;
use App\Models\MarketingEvent;
use App\Services\Ai\AiService;
use Illuminate\Support\Carbon;

class CampaignPlannerController extends Controller
{
    public function index()
    {
        $today = Carbon::now(auth()->user()->timezone)->toDateString();

        $events = MarketingEvent::query()
            ->whereDate('event_date', '>=', $today)
            ->orderBy('event_date')
            ->get();

        $plans = auth()->user()->campaignPlans()->latest()->get();

        return view('campaigns.index', [
            'events' => $events,
            'plans' => $plans,
        ]);
    }

    public function store(CampaignPlanRequest $request, AiService $aiService)
    {
        $data = $request->validated();
        $user = $request->user();
        $event = MarketingEvent::query()->findOrFail($data['marketing_event_id']);

        try {
            $planResult = $aiService->generateCampaignPlan(
                $event->toArray(),
                $user->businessProfile,
                null,
                $data['objective']
            );
        } catch (\Throwable $e) {
            return redirect()->route('campaigns.index')->with('status', 'Campaign planning failed. Please try again.');
        }

        $plan = CampaignPlan::query()->create([
            'user_id' => $user->id,
            'marketing_event_id' => $event->id,
            'objective' => $data['objective'],
            'duration_days' => $data['duration_days'],
            'offer_plan' => $planResult->offer,
            'copy_pack' => $planResult->copy,
            'status' => 'draft',
        ]);

        return redirect()->route('campaigns.show', $plan)->with('status', 'Campaign plan created.');
    }

    public function show(CampaignPlan $campaignPlan)
    {
        $this->authorize('view', $campaignPlan);

        return view('campaigns.show', [
            'plan' => $campaignPlan,
        ]);
    }
}

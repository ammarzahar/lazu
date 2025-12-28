<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferStoreRequest;
use App\Models\Offer;
use App\Services\Ai\AiService;

class OfferController extends Controller
{
    public function show()
    {
        $offer = auth()->user()->offers()->latest()->first();

        return view('offer.show', [
            'offer' => $offer,
        ]);
    }

    public function store(OfferStoreRequest $request, AiService $aiService)
    {
        $user = $request->user();
        $profile = $user->businessProfile;
        $offerText = $request->validated()['current_offer_text'];

        try {
            $analysis = $aiService->generateOfferAnalysis($profile, $offerText);
            $upgrades = $aiService->generateOfferUpgrades($profile, $offerText);
        } catch (\Throwable $e) {
            return redirect()->route('offer.show')->with('status', 'AI analysis failed. Please try again.');
        }

        $offer = Offer::query()->create([
            'user_id' => $user->id,
            'current_offer_text' => $offerText,
            'offer_score' => $analysis->score,
            'ai_findings' => $analysis->findings,
            'ai_suggestions' => [
                'analysis' => $analysis->suggestions,
                'upgrades' => $upgrades->toArray(),
            ],
        ]);

        return view('offer.show', [
            'offer' => $offer,
            'analysis' => $analysis,
            'upgrades' => $upgrades,
        ])->with('status', 'Offer analyzed.');
    }
}

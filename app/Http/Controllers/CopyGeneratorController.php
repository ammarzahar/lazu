<?php

namespace App\Http\Controllers;

use App\Http\Requests\CopyGenerateRequest;
use App\Services\Ai\AiService;

class CopyGeneratorController extends Controller
{
    public function show()
    {
        $offer = auth()->user()->offers()->latest()->first();

        return view('copy.show', [
            'offer' => $offer,
        ]);
    }

    public function generate(CopyGenerateRequest $request, AiService $aiService)
    {
        $user = $request->user();
        $profile = $user->businessProfile;
        $offer = $user->offers()->latest()->first();
        $offerText = $request->validated()['offer_text'] ?? $offer?->current_offer_text;

        if (! $offerText) {
            return redirect()->route('copy.show')->with('status', 'Please add an offer first.');
        }

        try {
            $result = $aiService->generateAdsCopy(
                $profile,
                ['text' => $offerText],
                $request->validated()['style'],
                $request->validated()['language']
            );
        } catch (\Throwable $e) {
            return redirect()->route('copy.show')->with('status', 'Copy generation failed. Please try again.');
        }

        return view('copy.show', [
            'offer' => $offer,
            'copy' => $result,
        ])->with('status', 'Copy generated.');
    }
}

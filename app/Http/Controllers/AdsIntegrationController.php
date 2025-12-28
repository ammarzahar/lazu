<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdsConnectionRequest;
use App\Models\AdAccount;
use App\Services\Ads\AdsProvider;
use Illuminate\Support\Carbon;

class AdsIntegrationController extends Controller
{
    public function edit()
    {
        $account = auth()->user()->adAccounts()->where('provider', 'meta')->latest()->first();
        $latestSnapshot = auth()->user()->adPerformanceSnapshots()->latest('date')->first();

        return view('ads.edit', [
            'account' => $account,
            'provider' => config('ads.provider'),
            'latestSnapshot' => $latestSnapshot,
        ]);
    }

    public function store(AdsConnectionRequest $request)
    {
        $data = $request->validated();

        AdAccount::query()->updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'provider' => 'meta',
            ],
            [
                'account_id' => $data['account_id'],
                'access_token' => $data['access_token'],
                'token_expires_at' => $data['token_expires_at'] ?? null,
                'is_active' => $data['is_active'] ?? true,
            ]
        );

        return redirect()->route('ads.edit')->with('status', 'Meta Ads account saved.');
    }

    public function test(AdsProvider $provider)
    {
        $range = [
            'start' => Carbon::now()->subDays(2)->toDateString(),
            'end' => Carbon::now()->toDateString(),
        ];

        try {
            $provider->fetchDailySummary(auth()->user(), $range);
        } catch (\Throwable $e) {
            return redirect()->route('ads.edit')->with('status', 'Connection failed. Check token and account ID.');
        }

        return redirect()->route('ads.edit')->with('status', 'Connection looks good.');
    }
}

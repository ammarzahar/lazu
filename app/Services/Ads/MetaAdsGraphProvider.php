<?php

namespace App\Services\Ads;

use App\Models\AdAccount;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaAdsGraphProvider implements AdsProvider
{
    public function fetchDailySummary(User $user, array $dateRange): array
    {
        $account = AdAccount::query()
            ->where('user_id', $user->id)
            ->where('provider', 'meta')
            ->where('is_active', true)
            ->latest()
            ->first();

        if (! $account) {
            throw new \RuntimeException('No active Meta ad account connected.');
        }

        $cacheKey = sprintf('meta_ads:%s:%s:%s', $user->id, $dateRange['start'], $dateRange['end']);

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($account, $dateRange) {
            $version = config('ads.meta_graph_version', 'v20.0');
            $endpoint = "https://graph.facebook.com/{$version}/act_{$account->account_id}/insights";
            $fields = implode(',', [
                'spend',
                'clicks',
                'impressions',
                'ctr',
                'cpc',
                'actions',
            ]);

            $response = Http::get($endpoint, [
                'access_token' => $account->access_token,
                'fields' => $fields,
                'time_range' => [
                    'since' => $dateRange['start'],
                    'until' => $dateRange['end'],
                ],
                'time_increment' => 1,
            ]);

            if (! $response->successful()) {
                Log::warning('Meta Graph error', ['status' => $response->status(), 'body' => $response->body()]);
                throw new \RuntimeException('Meta Graph request failed.');
            }

            $data = $response->json('data', []);
            $daily = [];

            foreach ($data as $row) {
                $actions = collect($row['actions'] ?? []);
                $leadAction = $actions->firstWhere('action_type', 'lead');
                $purchaseAction = $actions->firstWhere('action_type', 'purchase');
                $leads = (int) ($leadAction['value'] ?? 0);
                $purchases = (int) ($purchaseAction['value'] ?? 0);

                $daily[] = [
                    'date' => $row['date_start'] ?? $dateRange['start'],
                    'spend' => (float) ($row['spend'] ?? 0),
                    'cpa' => $leads > 0 ? (float) ($row['spend'] ?? 0) / $leads : 0,
                    'roas' => 0,
                    'ctr' => (float) ($row['ctr'] ?? 0),
                    'cpc' => (float) ($row['cpc'] ?? 0),
                    'impressions' => (int) ($row['impressions'] ?? 0),
                    'clicks' => (int) ($row['clicks'] ?? 0),
                    'purchases' => $purchases,
                    'leads' => $leads,
                ];
            }

            return [
                'provider' => 'meta',
                'daily' => $daily,
            ];
        });
    }
}

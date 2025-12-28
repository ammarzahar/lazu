<?php

namespace App\Services\Ads;

use App\Models\User;
use Illuminate\Support\Carbon;

class MetaAdsMockProvider implements AdsProvider
{
    public function fetchDailySummary(User $user, array $dateRange): array
    {
        $start = Carbon::parse($dateRange['start']);
        $end = Carbon::parse($dateRange['end']);

        $days = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $impressions = rand(1200, 4200);
            $clicks = rand(60, 180);
            $spend = rand(80, 220);
            $leads = rand(4, 18);
            $purchases = rand(1, 6);
            $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0;
            $cpc = $clicks > 0 ? round($spend / $clicks, 2) : 0;
            $cpa = $leads > 0 ? round($spend / $leads, 2) : 0;
            $roas = $spend > 0 ? round(($purchases * rand(40, 120)) / $spend, 2) : 0;

            $days[] = [
                'date' => $cursor->toDateString(),
                'spend' => $spend,
                'cpa' => $cpa,
                'roas' => $roas,
                'ctr' => $ctr,
                'cpc' => $cpc,
                'impressions' => $impressions,
                'clicks' => $clicks,
                'purchases' => $purchases,
                'leads' => $leads,
            ];

            $cursor->addDay();
        }

        return [
            'provider' => 'meta',
            'daily' => $days,
        ];
    }
}

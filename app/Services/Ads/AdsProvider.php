<?php

namespace App\Services\Ads;

use App\Models\User;

interface AdsProvider
{
    /**
     * @param  array{start:string,end:string}  $dateRange
     */
    public function fetchDailySummary(User $user, array $dateRange): array;
}

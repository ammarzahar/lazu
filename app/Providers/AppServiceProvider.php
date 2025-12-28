<?php

namespace App\Providers;

use App\Services\Ads\AdsProvider;
use App\Services\Ads\MetaAdsGraphProvider;
use App\Services\Ads\MetaAdsMockProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AdsProvider::class, function () {
            return config('ads.provider') === 'meta'
                ? new MetaAdsGraphProvider()
                : new MetaAdsMockProvider();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('ai', function (Request $request) {
            return Limit::perMinute(20)->by($request->user()?->id ?: $request->ip());
        });
    }
}

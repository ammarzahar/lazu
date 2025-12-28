<?php

use App\Http\Controllers\AdsIntegrationController;
use App\Http\Controllers\BusinessProfileController;
use App\Http\Controllers\CampaignPlannerController;
use App\Http\Controllers\CopyGeneratorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/app', [DashboardController::class, 'index'])
    ->middleware(['auth', 'profile.complete'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/app/business-profile', [BusinessProfileController::class, 'edit'])->name('business-profile.edit');
    Route::post('/app/business-profile', [BusinessProfileController::class, 'store'])->name('business-profile.store');

    Route::middleware('profile.complete')->group(function () {
        Route::get('/app/offer', [OfferController::class, 'show'])->name('offer.show');
        Route::post('/app/offer', [OfferController::class, 'store'])->middleware('throttle:ai')->name('offer.store');

        Route::get('/app/copy-generator', [CopyGeneratorController::class, 'show'])->name('copy.show');
        Route::post('/app/copy-generator', [CopyGeneratorController::class, 'generate'])->middleware('throttle:ai')->name('copy.generate');

        Route::get('/app/ads/connect', [AdsIntegrationController::class, 'edit'])->name('ads.edit');
        Route::post('/app/ads/connect', [AdsIntegrationController::class, 'store'])->name('ads.store');
        Route::post('/app/ads/test', [AdsIntegrationController::class, 'test'])->name('ads.test');

        Route::get('/app/campaigns', [CampaignPlannerController::class, 'index'])->name('campaigns.index');
        Route::post('/app/campaigns', [CampaignPlannerController::class, 'store'])->middleware('throttle:ai')->name('campaigns.store');
        Route::get('/app/campaigns/{campaignPlan}', [CampaignPlannerController::class, 'show'])->name('campaigns.show');

        Route::post('/app/reports/weekly/send', [DashboardController::class, 'sendWeeklyReport'])->name('reports.weekly.send');
    });
});

require __DIR__.'/auth.php';

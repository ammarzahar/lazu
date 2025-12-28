<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingEvent extends Model
{
    protected $fillable = [
        'name',
        'event_date',
        'region',
        'category',
        'default_lead_time_days',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'default_lead_time_days' => 'array',
        ];
    }

    public function campaignPlans()
    {
        return $this->hasMany(CampaignPlan::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }
}

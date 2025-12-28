<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignPlan extends Model
{
    protected $fillable = [
        'user_id',
        'marketing_event_id',
        'objective',
        'duration_days',
        'offer_plan',
        'copy_pack',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'duration_days' => 'integer',
            'offer_plan' => 'array',
            'copy_pack' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marketingEvent()
    {
        return $this->belongsTo(MarketingEvent::class);
    }
}

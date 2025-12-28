<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'user_id',
        'marketing_event_id',
        'remind_at',
        'channel',
        'status',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'remind_at' => 'datetime',
            'payload' => 'array',
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

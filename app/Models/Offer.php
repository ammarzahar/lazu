<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'user_id',
        'current_offer_text',
        'offer_score',
        'ai_findings',
        'ai_suggestions',
    ];

    protected function casts(): array
    {
        return [
            'offer_score' => 'integer',
            'ai_findings' => 'array',
            'ai_suggestions' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

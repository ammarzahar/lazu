<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiCall extends Model
{
    protected $fillable = [
        'user_id',
        'model',
        'endpoint',
        'tokens_est',
        'cost_est',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'tokens_est' => 'integer',
            'cost_est' => 'decimal:4',
            'payload' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

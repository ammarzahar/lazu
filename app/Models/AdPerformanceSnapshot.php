<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdPerformanceSnapshot extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'date',
        'raw_payload',
        'metrics',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'raw_payload' => 'array',
            'metrics' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmoBrief extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'decisions',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'decisions' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

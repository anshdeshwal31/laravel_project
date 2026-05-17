<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class InvestorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'investor_type',
        'investment_min',
        'investment_max',
        'preferred_industries',
        'location_preference',
    ];

    protected function casts(): array
    {
        return [
            'investment_min' => 'decimal:2',
            'investment_max' => 'decimal:2',
            'preferred_industries' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

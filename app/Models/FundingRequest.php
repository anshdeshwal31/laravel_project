<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class FundingRequest extends Model
{
    protected $fillable = [
        'startup_user_id',
        'investor_user_id',
        'funding_opportunity_id',
        'requested_amount',
        'message',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'requested_amount' => 'decimal:2',
        ];
    }

    public function startup(): BelongsTo
    {
        return $this->belongsTo(User::class, 'startup_user_id');
    }

    public function investor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'investor_user_id');
    }

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(FundingOpportunity::class, 'funding_opportunity_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}

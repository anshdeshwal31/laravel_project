<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundingRequestReview extends Model
{
    protected $fillable = [
        'funding_request_id',
        'status',
        'overall_score',
        'summary',
        'strengths',
        'weaknesses',
        'risks',
        'verdict',
        'error_message',
        'raw_analysis',
    ];

    protected function casts(): array
    {
        return [
            'strengths' => 'array',
            'weaknesses' => 'array',
            'risks' => 'array',
            'overall_score' => 'integer',
        ];
    }

    /**
     * Get the funding request associated with this AI review.
     */
    public function fundingRequest(): BelongsTo
    {
        return $this->belongsTo(FundingRequest::class);
    }
}

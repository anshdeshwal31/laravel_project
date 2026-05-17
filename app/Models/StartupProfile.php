<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class StartupProfile extends Model
{
    protected $fillable = [
        'user_id',
        'startup_name',
        'domain',
        'industry',
        'stage',
        'funding_requirement',
        'location',
        'pitch_description',
        'document_paths',
    ];

    protected function casts(): array
    {
        return [
            'funding_requirement' => 'decimal:2',
            'document_paths' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

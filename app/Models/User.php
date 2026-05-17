<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    public const ROLE_STARTUP = 'startup';

    public const ROLE_INVESTOR = 'investor';

    public const ROLE_ADMIN = 'admin';

    public function startupProfile(): HasOne
    {
        return $this->hasOne(StartupProfile::class);
    }

    public function investorProfile(): HasOne
    {
        return $this->hasOne(InvestorProfile::class);
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(FundingOpportunity::class);
    }

    public function sentFundingRequests(): HasMany
    {
        return $this->hasMany(FundingRequest::class, 'startup_user_id');
    }

    public function receivedFundingRequests(): HasMany
    {
        return $this->hasMany(FundingRequest::class, 'investor_user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}

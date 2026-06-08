<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'has_lifetime_access',
    'trial_ends_at',
])]
final class Account extends Model
{
    /** @use HasFactory<\Database\Factories\AccountFactory> */
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'has_lifetime_access' => 'boolean',
        'trial_ends_at' => 'datetime',
    ];

    /**
     * Get the users associated with the account.
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the vaults associated with the account.
     *
     * @return HasMany<Vault, $this>
     */
    public function vaults(): HasMany
    {
        return $this->hasMany(Vault::class);
    }

    /**
     * Get activity logs associated with the account.
     *
     * @return HasMany<UserActivityLog, $this>
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(UserActivityLog::class);
    }

    /**
     * All contacts across all vaults in the account.
     *
     * @return HasManyThrough<Contact, Vault>
     */
    public function contacts(): HasManyThrough
    {
        return $this->hasManyThrough(Contact::class, Vault::class);
    }
}

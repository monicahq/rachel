<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'account_id',
    'user_id',
    'actor_user_id',
    'loggable_type',
    'loggable_id',
    'category',
    'action',
    'metadata',
])]
final class UserActivityLog extends Model
{
    /** @use HasFactory<\Database\Factories\UserActivityLogFactory> */
    use HasFactory;

    public const CATEGORY_PROFILE = 'profile';

    public const CATEGORY_SECURITY = 'security';

    public const CATEGORY_ACCOUNT = 'account';

    public const CATEGORY_VAULT = 'vault';

    public const CATEGORY_CONTACT = 'contact';

    public const ACTION_PROFILE_NAME_UPDATED = 'profile.name.updated';

    public const ACTION_PROFILE_EMAIL_UPDATED = 'profile.email.updated';

    public const ACTION_PROFILE_NAME_AND_EMAIL_UPDATED = 'profile.name_email.updated';

    public const ACTION_SECURITY_PASSWORD_UPDATED = 'security.password.updated';

    public const ACTION_SECURITY_TWO_FACTOR_ENABLED = 'security.two_factor.enabled';

    public const ACTION_SECURITY_TWO_FACTOR_CONFIRMED = 'security.two_factor.confirmed';

    public const ACTION_SECURITY_TWO_FACTOR_DISABLED = 'security.two_factor.disabled';

    public const ACTION_SECURITY_TWO_FACTOR_RECOVERY_CODES_REGENERATED = 'security.two_factor.recovery_codes.regenerated';

    public const ACTION_SECURITY_WEBAUTHN_KEY_REGISTERED = 'security.webauthn.key.registered';

    public const ACTION_SECURITY_WEBAUTHN_KEY_UPDATED = 'security.webauthn.key.updated';

    public const ACTION_SECURITY_WEBAUTHN_KEY_DELETED = 'security.webauthn.key.deleted';

    public const ACTION_SECURITY_WEBAUTHN_KEY_UPGRADED = 'security.webauthn.key.upgraded';

    public const ACTION_ACCOUNT_DELETED = 'account.deleted';

    public const ACTION_VAULT_CREATED = 'vault.created';

    public const ACTION_CONTACT_CREATED = 'contact.created';

    public const ACTION_CONTACT_PARAMETER_CREATED = 'contact.parameter.created';

    public const ACTION_CONTACT_EMAIL_ADDED = 'contact.email.added';

    public const ACTION_CONTACT_EMAIL_UPDATED = 'contact.email.updated';

    public const ACTION_CONTACT_EMAIL_DELETED = 'contact.email.deleted';

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['loggable'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Get the account that owns the activity log.
     *
     * @return BelongsTo<Account, $this>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the user targeted by the activity.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the actor who triggered the activity.
     *
     * @return BelongsTo<User, $this>
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    /**
     * Get the loggable model related to this activity.
     *
     * @return MorphTo<Model, $this>
     */
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}

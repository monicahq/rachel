<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Activity\TwoFactorDisabled;
use App\Events\Activity\TwoFactorEnabled;
use App\Events\Activity\TwoFactorRecoveryCodesRegenerated;
use App\Models\User;

/**
 * Manage two-factor authentication for a user.
 */
final readonly class ManageTwoFactorAuthentication
{
    private const ACTION_ENABLE = 'enable';

    private const ACTION_DISABLE = 'disable';

    private const ACTION_REGENERATE_RECOVERY = 'regenerate_recovery';

    public function __construct(
        public User $user,
        public string $action,
    ) {}

    public function execute(): void
    {
        match ($this->action) {
            self::ACTION_ENABLE => $this->enable(),
            self::ACTION_DISABLE => $this->disable(),
            self::ACTION_REGENERATE_RECOVERY => $this->regenerateRecoveryCodes(),
            default => null,
        };
    }

    private function enable(): void
    {
        $this->user->update([
            'two_factor_secret' => encrypt(random_bytes(16)),
            'two_factor_recovery_codes' => json_encode([]),
            'two_factor_confirmed_at' => null,
        ]);

        event(new TwoFactorEnabled(
            user: $this->user,
            actor: $this->user,
        ));
    }

    private function disable(): void
    {
        $this->user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        event(new TwoFactorDisabled(
            user: $this->user,
            actor: $this->user,
        ));
    }

    private function regenerateRecoveryCodes(): void
    {
        $this->user->update([
            'two_factor_recovery_codes' => json_encode([]),
        ]);

        event(new TwoFactorRecoveryCodesRegenerated(
            user: $this->user,
            actor: $this->user,
        ));
    }
}

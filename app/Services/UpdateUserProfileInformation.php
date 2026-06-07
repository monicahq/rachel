<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Activity\ProfileEmailUpdated;
use App\Events\Activity\ProfileNameAndEmailUpdated;
use App\Events\Activity\ProfileNameUpdated;
use App\Models\User;

/**
 * Update a user's profile information (name and/or email).
 */
final readonly class UpdateUserProfileInformation
{
    public function __construct(
        public User $user,
        public string $name,
        public ?string $email = null,
    ) {}

    public function execute(): void
    {
        $nameChanged = $this->user->name !== $this->name;
        $emailChanged = $this->email && $this->user->email !== $this->email;

        $this->user->fill([
            'name' => $this->name,
        ]);

        if ($this->email) {
            $this->user->fill([
                'email' => $this->email,
            ]);
        }

        $this->user->save();

        if ($nameChanged || $emailChanged) {
            $this->logActivity($nameChanged, $emailChanged);
        }
    }

    private function logActivity(bool $nameChanged, bool $emailChanged): void
    {
        match (true) {
            $nameChanged && $emailChanged => event(new ProfileNameAndEmailUpdated(
                user: $this->user,
                actor: $this->user,
            )),
            $nameChanged => event(new ProfileNameUpdated(
                user: $this->user,
                actor: $this->user,
            )),
            $emailChanged => event(new ProfileEmailUpdated(
                user: $this->user,
                actor: $this->user,
            )),
            default => null,
        };
    }
}

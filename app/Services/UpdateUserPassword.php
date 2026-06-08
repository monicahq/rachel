<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Activity\SecurityPasswordUpdated;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Update a user's password.
 */
final readonly class UpdateUserPassword
{
    public function __construct(
        public User $user,
        public string $password,
    ) {}

    public function execute(): void
    {
        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->logActivity();
    }

    private function logActivity(): void
    {
        event(new SecurityPasswordUpdated(
            user: $this->user,
            actor: $this->user,
        ));
    }
}

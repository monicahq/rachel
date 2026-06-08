<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

/**
 * Delete a user account.
 */
final readonly class DeleteUserAccount
{
    public function __construct(
        public User $user,
    ) {}

    public function execute(): void
    {
        $this->user->delete();
    }
}

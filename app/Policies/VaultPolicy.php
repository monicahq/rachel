<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Vault;

final class VaultPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vault $vault): bool
    {
        return $user->account->id === $vault->account_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vault $vault): bool
    {
        return $user->account->id === $vault->account_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vault $vault): bool
    {
        return $user->account->id === $vault->account_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vault $vault): bool
    {
        return $user->account->id === $vault->account_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vault $vault): bool
    {
        return $user->account->id === $vault->account_id;
    }
}

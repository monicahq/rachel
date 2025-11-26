<?php

declare(strict_types=1);

namespace App\Policies\Concerns;

use App\Models\Contact;
use App\Models\User;
use App\Models\Vault;
use Illuminate\Support\Facades\Route;

trait ManagePolicy
{
    /**
     * Determine whether the user and vault belong to the same account.
     */
    protected function sameAccount(User $user, ?Vault $vault): bool
    {
        return $user->account_id === $vault?->account_id;
    }

    /**
     * Determine whether the contact and vault belong to the same vault.
     */
    protected function sameVault(Contact $contact, ?Vault $vault): bool
    {
        return $contact->vault_id === $vault?->id;
    }

    /**
     * Retrieve the vault from the current route.
     */
    protected function getVault(): ?Vault
    {
        return Route::current()->parameter('vault');
    }
}

<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use App\Models\Vault;
use App\Policies\Concerns\ManagePolicy;

final class ContactPolicy
{
    use ManagePolicy;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, ?Vault $vault = null): bool
    {
        $vault ??= $this->getVault();

        return $this->sameAccount($user, $vault);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contact $contact, ?Vault $vault = null): bool
    {
        $vault ??= $this->getVault();

        return $this->sameAccount($user, $vault)
            && $this->sameVault($contact, $vault);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?Vault $vault = null): bool
    {
        return $this->viewAny($user, $vault);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contact $contact, ?Vault $vault = null): bool
    {
        return $this->view($user, $contact, $vault);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contact $contact, ?Vault $vault = null): bool
    {
        return $this->view($user, $contact, $vault);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contact $contact, ?Vault $vault = null): bool
    {
        return $this->view($user, $contact, $vault);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Contact $contact, ?Vault $vault = null): bool
    {
        return $this->view($user, $contact, $vault);
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\SlugHelper;
use App\Models\User;
use App\Models\Vault;

/**
 * Create a vault for a user.
 */
final class CreateVault
{
    private Vault $vault;

    public function __construct(
        public readonly User $user,
        public readonly string $name,
        public readonly ?string $description = null,
    ) {}

    public function execute(): Vault
    {
        $this->create();

        return $this->vault;
    }

    private function create(): void
    {
        $slug = SlugHelper::generateUniqueSlug(
            collection: $this->user->account->vaults,
            name: $this->name,
            locale: $this->user->locale,
        );

        $this->vault = $this->user->account->vaults()->create([
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
        ]);
    }
}

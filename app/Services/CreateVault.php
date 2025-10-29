<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Vault;
use Illuminate\Support\Str;

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
        $this->vault = $this->user->account->vaults()->create([
            'name' => $this->name,
            'slug' => Str::slug($this->name, language: $this->user->locale),
            'description' => $this->description,
        ]);
    }
}

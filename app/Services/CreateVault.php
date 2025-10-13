<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Account;
use App\Models\Vault;
use Illuminate\Support\Str;

/**
 * Create a vault for a user.
 */
final class CreateVault
{
    private Vault $vault;

    public function __construct(
        private Account $account,
        private string $name,
        private ?string $description = null,
    ) {}

    public function execute(): Vault
    {
        $this->create();

        return $this->vault;
    }

    private function create(): void
    {
        $this->vault = $this->account->vaults()->create([
            'name' => $this->name,
            'slug' => Str::slug($this->name /* , language: Auth::user()->locale */),
            'description' => $this->description,
        ]);
    }
}

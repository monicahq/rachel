<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Vault;
use Illuminate\Support\Str;

/**
 * Update a vault for a user.
 */
final readonly class UpdateVault
{
    public function __construct(
        public Vault $vault,
        public User $user,
        public string $name,
        public ?string $description = null,
    ) {}

    public function execute(): Vault
    {
        $this->update();

        return $this->vault;
    }

    private function update(): void
    {
        $this->vault->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name, language: $this->user->locale),
            'description' => $this->description,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Vault;
use Illuminate\Support\Str;

/**
 * Update a vault for a user.
 */
final readonly class UpdateVault
{
    public function __construct(
        private Vault $vault,
        private string $name,
        private ?string $description = null,
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
            'slug' => Str::slug($this->name /* , language: Auth::user()->locale */),
            'description' => $this->description,
        ]);
    }
}

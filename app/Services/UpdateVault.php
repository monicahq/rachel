<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\SlugHelper;
use App\Models\User;
use App\Models\Vault;

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
        $this->vault->fill([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        if ($this->vault->isDirty('name')) {
            $slug = SlugHelper::generateUniqueSlug(
                collection: $this->user->account->vaults,
                name: $this->name,
                locale: $this->user->locale,
            );

            $this->vault->fill([
                'slug' => $slug,
            ]);
        }

        $this->vault->save();
    }
}

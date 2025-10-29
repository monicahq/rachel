<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Vault;

/**
 * Destroy a vault for a user.
 */
final readonly class DestroyVault
{
    public function __construct(
        public Vault $vault,
    ) {}

    public function execute(): void
    {
        $this->vault->delete();
    }
}

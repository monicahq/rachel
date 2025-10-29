<?php

declare(strict_types=1);

use App\Models\Vault;
use App\Services\DestroyVault;

it('can destroy a vault', function (): void {
    $vault = Vault::factory()->create();

    (new DestroyVault(
        vault: $vault,
    ))->execute();

    $this->assertDatabaseMissing('vaults', [
        'id' => $vault->id,
    ]);
});

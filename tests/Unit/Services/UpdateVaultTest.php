<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use App\Services\UpdateVault;

it('can update a vault', function (): void {
    $user = User::factory()->create();
    $vault = Vault::factory()->create([
        'account_id' => $user->account->id,
    ]);

    $vault = (new UpdateVault(
        vault: $vault,
        user: $user,
        name: 'New Vault',
        description: 'Default one',
    ))->execute();

    expect($vault)->toBeInstanceOf(Vault::class);

    $this->assertDatabaseHas('vaults', [
        'id' => $vault->id,
        'name' => 'New Vault',
        'description' => 'Default one',
    ]);
});

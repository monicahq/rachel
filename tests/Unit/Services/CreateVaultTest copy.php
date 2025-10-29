<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use App\Services\CreateVault;

it('can create a vault', function (): void {
    $user = User::factory()->create();

    $vault = (new CreateVault(
        user: $user,
        name: 'New Vault',
    ))->execute();

    expect($vault)->toBeInstanceOf(Vault::class);

    $this->assertDatabaseHas('vaults', [
        'id' => $vault->id,
        'name' => 'New Vault',
        'account_id' => $user->account->id,
    ]);
});

it('can create a vault with description', function (): void {
    $user = User::factory()->create();

    $vault = (new CreateVault(
        user: $user,
        name: 'New Vault',
        description: 'Default one',
    ))->execute();

    expect($vault)->toBeInstanceOf(Vault::class);

    $this->assertDatabaseHas('vaults', [
        'id' => $vault->id,
        'name' => 'New Vault',
        'slug' => 'new-vault',
        'account_id' => $user->account->id,
        'description' => 'Default one',
    ]);
});

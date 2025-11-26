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
        'account_id' => $user->account_id,
        'name' => 'New Vault',
        'slug' => 'new-vault',
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
        'account_id' => $user->account_id,
        'name' => 'New Vault',
        'slug' => 'new-vault',
        'description' => 'Default one',
    ]);
});

it('can create same vault name twice', function (): void {
    $user = User::factory()->create();
    Vault::factory()->create([
        'account_id' => $user->account_id,
        'slug' => 'new-vault',
    ]);

    $vault = (new CreateVault(
        user: $user,
        name: 'New Vault',
    ))->execute();

    expect($vault)->toBeInstanceOf(Vault::class);

    $this->assertDatabaseHas('vaults', [
        'id' => $vault->id,
        'account_id' => $user->account_id,
        'name' => 'New Vault',
        'slug' => 'new-vault-1',
    ]);
});

<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use App\Services\UpdateVault;

it('can update a vault', function (): void {
    $user = User::factory()->create();
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
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
        'account_id' => $user->account_id,
        'name' => 'New Vault',
        'slug' => 'new-vault',
        'description' => 'Default one',
    ]);
});

it('update vault name also updates the slug', function (): void {
    $user = User::factory()->create();
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
        'slug' => 'old-vault',
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
        'account_id' => $user->account_id,
        'name' => 'New Vault',
        'slug' => 'new-vault',
        'description' => 'Default one',
    ]);
});

it('update slug without collision', function (): void {
    $user = User::factory()->create();
    Vault::factory()->create([
        'account_id' => $user->account_id,
        'slug' => 'new-vault',
    ]);
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
        'slug' => 'old-vault',
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
        'account_id' => $user->account_id,
        'name' => 'New Vault',
        'slug' => 'new-vault-1',
        'description' => 'Default one',
    ]);
});

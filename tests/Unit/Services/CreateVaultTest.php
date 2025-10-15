<?php

declare(strict_types=1);

use App\Models\Account;
use App\Models\Vault;
use App\Services\CreateVault;

it('can create a vault', function (): void {
    $account = Account::factory()->create();

    $vault = (new CreateVault(
        account: $account,
        name: 'New Vault',
    ))->execute();

    expect($vault)->toBeInstanceOf(Vault::class);

    $this->assertDatabaseHas('vaults', [
        'id' => $vault->id,
        'name' => 'New Vault',
        'account_id' => $account->id,
    ]);
});

it('can create a vault with description', function (): void {
    $account = Account::factory()->create();

    $vault = (new CreateVault(
        account: $account,
        name: 'New Vault',
        description: 'Default one',
    ))->execute();

    expect($vault)->toBeInstanceOf(Vault::class);

    $this->assertDatabaseHas('vaults', [
        'id' => $vault->id,
        'name' => 'New Vault',
        'slug' => 'new-vault',
        'account_id' => $account->id,
        'description' => 'Default one',
    ]);
});

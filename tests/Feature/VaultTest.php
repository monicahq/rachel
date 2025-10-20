<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use Livewire\Volt\Volt;

test('users can list vaults', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);

    $response = $this->get(route('vaults.index'));
    $response->assertStatus(200);
    $response->assertSee($vault->name);
});

test('users can show a vault', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);

    $response = $this->get(route('vaults.show', [$vault]));
    $response->assertStatus(200);
    $response->assertSee($vault->name);
});

test('user can create a vault', function (): void {
    $this->actingAs($user = User::factory()->create());
    $response = Volt::test('vaults.index')
        ->set('name', 'Test Vault')
        ->call('create');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('vaults.show', ['vault' => 'test-vault']));
});

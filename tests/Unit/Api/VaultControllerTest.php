<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use Laravel\Sanctum\Sanctum;

covers(App\Http\Controllers\Api\VaultController::class);

describe('/api/vaults', function (): void {
    test('api get empty vaults', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );

        $response = $this->get('/api/vaults')
            ->assertOk();

        expect($response->json('data'))
            ->toBe([]);
    });

    test('api get all vaults', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->get('/api/vaults')
            ->assertOk();

        expect($response->json('data.*.id'))
            ->toBe([$vault->id]);
    });

    test('api get a vault', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->get('/api/vaults/'.$vault->slug)
            ->assertOk();

        expect($response->json('data.id'))
            ->toBe($vault->id);
    });

    test('api get a vault by id', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->get('/api/vaults/'.$vault->id)
            ->assertOk();

        expect($response->json('data.id'))
            ->toBe($vault->id);
    });

    test('can\'t read other account vault', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create();

        $this->get('/api/vaults/'.$vault->slug)
            ->assertNotFound();
    });

    test('api create a vault', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['write']
        );

        $response = $this->post('/api/vaults', [
            'name' => 'my vault',
            'description' => null,
        ])
            ->assertCreated();

        expect($response->json('data.name'))
            ->toBe('my vault');

        $this->assertDatabaseHas('vaults', [
            'id' => $response->json('data.id'),
        ]);
    });

    test('api can\'t create a vault', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );

        $response = $this->post('/api/vaults', [
            'name' => 'my vault',
            'description' => null,
        ])
            ->assertForbidden();
    });

    test('api update a vault', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['write']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->put('/api/vaults/'.$vault->slug, [
            'name' => 'my vault',
            'description' => 'youpi',
        ])
            ->assertOk();

        expect($response->json('data.name'))
            ->toBe('my vault');

        $this->assertDatabaseHas('vaults', [
            'id' => $vault->id,
            'name' => 'my vault',
            'description' => 'youpi',
        ]);
    });

    test('api delete a vault', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['write']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->delete('/api/vaults/'.$vault->slug)
            ->assertOk();

        $this->assertDatabaseMissing('vaults', [
            'id' => $response->json('data.id'),
        ]);
    });
});

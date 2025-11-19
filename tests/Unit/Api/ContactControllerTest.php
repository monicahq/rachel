<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Models\User;
use App\Models\Vault;
use Laravel\Sanctum\Sanctum;

describe('api-contacts', function (): void {
    test('api get empty contacts', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->get("/api/vaults/{$vault->id}/contacts")
            ->assertOk();

        expect($response->json('data'))
            ->toBe([]);
    });

    test('api get all contacts', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);
        $contact = Contact::factory()->create([
            'vault_id' => $vault->id,
        ]);

        $response = $this->get("/api/vaults/{$vault->id}/contacts")
            ->assertOk();

        expect($response->json('data.*.id'))
            ->toBe([$contact->id]);
    });

    test('api get a contact', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);
        $contact = Contact::factory()->create([
            'vault_id' => $vault->id,
        ]);

        $response = $this->get("/api/vaults/{$vault->id}/contacts/".$contact->slug)
            ->assertOk();

        expect($response->json('data.id'))
            ->toBe($contact->id);
    });

    test('api get a contact by id', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);
        $contact = Contact::factory()->create([
            'vault_id' => $vault->id,
        ]);

        $response = $this->get("/api/vaults/{$vault->id}/contacts/".$contact->id)
            ->assertOk();

        expect($response->json('data.id'))
            ->toBe($contact->id);
    });

    test('can\'t read other vaults contacts', function (): void {
        Sanctum::actingAs(
            User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create();
        $contact = Contact::factory()->create();

        $this->get("/api/vaults/{$vault->id}/contacts/".$contact->slug)
            ->assertNotFound();
    });

    test('api create a contact', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['write']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);

        $response = $this->post("/api/vaults/{$vault->id}/contacts", [
            'name' => 'my vault',
        ])
            ->assertCreated();

        expect($response->json('data.name'))
            ->toBe('my vault');

        $this->assertDatabaseHas('contacts', [
            'id' => $response->json('data.id'),
        ]);
    });

    test('api can\'t create a contact', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['read']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);

        $this->post("/api/vaults/{$vault->id}/contacts/", [
            'name' => 'my contact',
        ])
            ->assertForbidden();
    });

    test('api update a contact', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['write']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);
        $contact = Contact::factory()->create([
            'vault_id' => $vault->id,
        ]);

        $response = $this->put("/api/vaults/{$vault->id}/contacts/".$contact->slug, [
            'name' => 'Jean-Claude Duss',
        ])
            ->assertOk();

        expect($response->json('data.name'))
            ->toBe('Jean-Claude Duss');

        $this->assertDatabaseHas('contacts', [
            'id' => $contact->id,
            'name' => 'Jean-Claude Duss',
        ]);
    });

    test('api delete a contact', function (): void {
        Sanctum::actingAs(
            $user = User::factory()->create(),
            ['write']
        );
        $vault = Vault::factory()->create([
            'account_id' => $user->account_id,
        ]);
        $contact = Contact::factory()->create([
            'vault_id' => $vault->id,
        ]);

        $this->delete("/api/vaults/{$vault->id}/contacts/".$contact->slug)
            ->assertNoContent();

        $this->assertDatabaseMissing('contacts', [
            'id' => $contact->id,
        ]);
    });
});

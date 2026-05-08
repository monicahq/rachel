<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Models\ContactParameter;
use App\Models\User;
use App\Models\Vault;
use Laravel\Sanctum\Sanctum;

describe('api-contact-parameters', function (): void {
    test('api get empty contact parameters', function (): void {
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

        $response = $this->getJson("/api/v1/vaults/{$vault->id}/contacts/{$contact->slug}/parameters")
            ->assertOk();

        expect($response->json('data'))
            ->toBe([]);
    });

    test('api get all contact parameters', function (): void {
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
        $parameter = ContactParameter::factory()->create([
            'vault_id' => $vault->id,
            'contact_id' => $contact->id,
        ]);

        $response = $this->getJson("/api/v1/vaults/{$vault->id}/contacts/{$contact->slug}/parameters")
            ->assertOk();

        expect($response->json('data.*.id'))
            ->toBe([$parameter->id]);
    });

    test('api create a contact parameter', function (): void {
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

        $this->postJson("/api/v1/vaults/{$vault->id}/contacts/{$contact->slug}/parameters", [
            'key' => 'nickname',
            'label' => 'Nickname',
            'type' => 'string',
            'data' => 'Jojo',
        ])->assertCreated();

        $this->assertDatabaseHas('contact_parameters', [
            'vault_id' => $vault->id,
            'contact_id' => $contact->id,
            'key' => 'nickname',
            'label' => 'Nickname',
            'type' => 'string',
            'data' => 'Jojo',
        ]);
    });

    test('api update a contact parameter', function (): void {
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
        $parameter = ContactParameter::factory()->create([
            'vault_id' => $vault->id,
            'contact_id' => $contact->id,
        ]);

        $this->putJson("/api/v1/vaults/{$vault->id}/contacts/{$contact->slug}/parameters/{$parameter->id}", [
            'key' => 'birthday',
            'label' => 'Birthday',
            'type' => 'date',
            'data' => '1990-01-01',
        ])->assertOk();

        $this->assertDatabaseHas('contact_parameters', [
            'id' => $parameter->id,
            'key' => 'birthday',
            'label' => 'Birthday',
            'type' => 'date',
            'data' => '1990-01-01',
        ]);
    });

    test('api delete a contact parameter', function (): void {
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
        $parameter = ContactParameter::factory()->create([
            'vault_id' => $vault->id,
            'contact_id' => $contact->id,
        ]);

        $this->deleteJson("/api/v1/vaults/{$vault->id}/contacts/{$contact->slug}/parameters/{$parameter->id}")
            ->assertNoContent();

        $this->assertDatabaseMissing('contact_parameters', [
            'id' => $parameter->id,
        ]);
    });
});

<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Models\User;
use App\Models\Vault;
use Livewire\Volt\Volt;

test('users can list contacts', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);

    $response = $this->get(route('contacts.index', [$vault]));
    $response->assertStatus(200);
    $response->assertSee($contact->name);
});

test('users can show a contact', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);

    $response = $this->get(route('contacts.show', [$vault, $contact]));
    $response->assertStatus(200);
    $response->assertSee($contact->name);
});

test('user can create a contact', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);

    $response = Volt::test('contacts.index', ['vault' => $vault])
        ->set('name', 'Test Contact')
        ->call('create');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('contacts.show', [$vault, 'contact' => 'test-contact']));
});

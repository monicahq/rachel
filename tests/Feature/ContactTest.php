<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Models\ContactParameter;
use App\Models\User;
use App\Models\Vault;
use Livewire\Livewire;

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

test('user can visit the create contact page', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);

    $response = $this->get(route('contacts.create', [$vault]));
    $response->assertStatus(200);
});

test('user can create a contact', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);

    $response = Livewire::test('pages::contacts.create', ['vault' => $vault])
        ->set('name', 'Test Contact')
        ->call('create');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('contacts.show', [$vault, 'contact' => 'test-contact']));
});

test('user can create a contact parameter from contact page', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);

    Livewire::test('pages::contacts.show', ['vault' => $vault, 'contact' => $contact])
        ->set('parameterKey', 'nickname')
        ->set('parameterLabel', 'Nickname')
        ->set('parameterType', 'string')
        ->set('parameterData', 'Jojo')
        ->call('createParameter')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('contact_parameters', [
        'contact_id' => $contact->id,
        'vault_id' => $vault->id,
        'key' => 'nickname',
        'label' => 'Nickname',
        'type' => 'string',
        'data' => 'Jojo',
    ]);

    expect(ContactParameter::query()->where('contact_id', $contact->id)->count())->toBe(1);
});

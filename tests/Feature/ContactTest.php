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

test('user can add an email parameter from contact page', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);

    Livewire::test('pages::contacts.show', ['vault' => $vault, 'contact' => $contact])
        ->set('emailAddress', 'john@example.com')
        ->call('addEmail')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('contact_parameters', [
        'contact_id' => $contact->id,
        'vault_id' => $vault->id,
        'key' => 'email',
        'label' => 'Email',
        'type' => 'string',
        'data' => 'john@example.com',
    ]);
});

test('user can delete an email parameter from contact page', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);
    $emailParameter = ContactParameter::factory()->create([
        'contact_id' => $contact->id,
        'vault_id' => $vault->id,
        'key' => 'email',
        'label' => 'Email',
        'type' => 'string',
        'data' => 'john@example.com',
    ]);

    Livewire::test('pages::contacts.show', ['vault' => $vault, 'contact' => $contact])
        ->call('deleteEmailParameter', $emailParameter->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('contact_parameters', [
        'id' => $emailParameter->id,
    ]);
});

test('user cannot delete a non-email parameter using deleteEmailParameter', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);
    $parameter = ContactParameter::factory()->create([
        'contact_id' => $contact->id,
        'vault_id' => $vault->id,
        'key' => 'nickname',
        'label' => 'Nickname',
        'type' => 'string',
        'data' => 'Jojo',
    ]);

    Livewire::test('pages::contacts.show', ['vault' => $vault, 'contact' => $contact])
        ->call('deleteEmailParameter', $parameter->id)
        ->assertHasErrors();

    $this->assertDatabaseHas('contact_parameters', [
        'id' => $parameter->id,
    ]);
});

test('user can open add email modal', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);

    Livewire::test('pages::contacts.show', ['vault' => $vault, 'contact' => $contact])
        ->call('openAddEmailModal')
        ->assertSet('showEmailModal', true)
        ->assertSet('editingEmail', false)
        ->assertSet('editingEmailId', null)
        ->assertSet('emailAddress', '');
});

test('user can open edit email modal', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);
    $emailParameter = ContactParameter::factory()->create([
        'contact_id' => $contact->id,
        'vault_id' => $vault->id,
        'key' => 'email',
        'label' => 'Email',
        'type' => 'string',
        'data' => 'john@example.com',
    ]);

    Livewire::test('pages::contacts.show', ['vault' => $vault, 'contact' => $contact])
        ->call('openEditEmailModal', $emailParameter->id)
        ->assertSet('showEmailModal', true)
        ->assertSet('editingEmail', true)
        ->assertSet('editingEmailId', $emailParameter->id)
        ->assertSet('emailAddress', 'john@example.com');
});

test('user can update an email parameter', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create([
        'account_id' => $user->account_id,
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);
    $emailParameter = ContactParameter::factory()->create([
        'contact_id' => $contact->id,
        'vault_id' => $vault->id,
        'key' => 'email',
        'label' => 'Email',
        'type' => 'string',
        'data' => 'john@example.com',
    ]);

    Livewire::test('pages::contacts.show', ['vault' => $vault, 'contact' => $contact])
        ->set('emailAddress', 'jane@example.com')
        ->call('updateEmail')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('contact_parameters', [
        'id' => $emailParameter->id,
        'data' => 'jane@example.com',
    ]);
});

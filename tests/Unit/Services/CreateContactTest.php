<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Services\CreateContact;

it('can create a contact', function (): void {
    $vault = vault();

    $contact = (new CreateContact(
        vault: $vault,
        name: 'New Contact',
    ))->execute();

    expect($contact)->toBeInstanceOf(Contact::class);

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'vault_id' => $vault->id,
        'name' => 'New Contact',
        'slug' => 'new-contact',
    ]);
});

it('can create a contact with description', function (): void {
    $vault = vault();

    $contact = (new CreateContact(
        vault: $vault,
        name: 'New Contact',
    ))->execute();

    expect($contact)->toBeInstanceOf(Contact::class);

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'vault_id' => $vault->id,
        'name' => 'New Contact',
        'slug' => 'new-contact',
    ]);
});

it('can create same contact name twice', function (): void {
    $vault = vault();
    Contact::factory()->create([
        'vault_id' => $vault->id,
        'slug' => 'new-contact',
    ]);

    $contact = (new CreateContact(
        vault: $vault,
        name: 'New Contact',
    ))->execute();

    expect($contact)->toBeInstanceOf(Contact::class);

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'vault_id' => $vault->id,
        'name' => 'New Contact',
        'slug' => 'new-contact-1',
    ]);
});

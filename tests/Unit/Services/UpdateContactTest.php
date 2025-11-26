<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Services\UpdateContact;

it('can update a contact', function (): void {
    $vault = vault();
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
    ]);

    $contact = (new UpdateContact(
        contact: $contact,
        vault: $vault,
        name: 'New Contact',
    ))->execute();

    expect($contact)->toBeInstanceOf(Contact::class);

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'vault_id' => $vault->id,
        'name' => 'New Contact',
    ]);
});

it('update contact name also updates the slug', function (): void {
    $vault = vault();
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
        'slug' => 'old-contact',
    ]);

    $contact = (new UpdateContact(
        contact: $contact,
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

it('update slug without collision', function (): void {
    $vault = vault();
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
        'slug' => 'new-contact',
    ]);
    $contact = Contact::factory()->create([
        'vault_id' => $vault->id,
        'slug' => 'old-contact',
    ]);

    $contact = (new UpdateContact(
        contact: $contact,
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

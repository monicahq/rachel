<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Services\DestroyContact;

it('can destroy a contact', function (): void {
    Illuminate\Support\Facades\Date::setTestNow(now());

    $contact = Contact::factory()->create();

    (new DestroyContact(
        contact: $contact,
    ))->execute();

    $this->assertDatabaseHas('contacts', [
        'id' => $contact->id,
        'deleted_at' => now(), // Soft delete check
    ]);
});

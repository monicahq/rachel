<?php

declare(strict_types=1);

use App\Models\Contact;
use App\Models\ContactParameter;
use App\Services\CreateParameter;

it('can create a parameter', function (): void {
    $contact = Contact::factory()->create();

    $parameter = (new CreateParameter(
        contact: $contact,
        key: 'birthday',
        label: 'Birthday',
        type: 'date',
        data: '1990-01-01',
    ))->execute();

    expect($parameter)->toBeInstanceOf(ContactParameter::class);

    $this->assertDatabaseHas('contact_parameters', [
        'id' => $parameter->id,
        'contact_id' => $contact->id,
        'key' => 'birthday',
        'type' => 'date',
        'data' => '1990-01-01',
    ]);
});

it('can create a parameter without value', function (): void {
    $contact = Contact::factory()->create();

    $parameter = (new CreateParameter(
        contact: $contact,
        key: 'nickname',
        label: 'Nickname',
        type: 'string',
    ))->execute();

    expect($parameter)->toBeInstanceOf(ContactParameter::class);

    $this->assertDatabaseHas('contact_parameters', [
        'id' => $parameter->id,
        'contact_id' => $contact->id,
        'key' => 'nickname',
        'type' => 'string',
        'data' => null,
    ]);
});

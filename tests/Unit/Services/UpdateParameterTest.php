<?php

declare(strict_types=1);

use App\Models\ContactParameter;
use App\Services\UpdateParameter;

it('can update a parameter', function (): void {
    $parameter = ContactParameter::factory()->create([
        'key' => 'old-key',
        'type' => 'string',
        'data' => 'old-data',
    ]);

    $parameter = (new UpdateParameter(
        parameter: $parameter,
        key: 'new-key',
        label: 'New Name',
        type: 'date',
        data: '2024-01-01',
    ))->execute();

    expect($parameter)->toBeInstanceOf(ContactParameter::class);

    $this->assertDatabaseHas('contact_parameters', [
        'id' => $parameter->id,
        'key' => 'new-key',
        'type' => 'date',
        'data' => '2024-01-01',
    ]);
});

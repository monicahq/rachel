<?php

declare(strict_types=1);

use App\Models\ContactParameter;
use App\Services\DestroyParameter;

it('can destroy a parameter', function (): void {
    $parameter = ContactParameter::factory()->create();

    (new DestroyParameter(
        parameter: $parameter,
    ))->execute();

    $this->assertDatabaseMissing('contact_parameters', [
        'id' => $parameter->id,
    ]);
});

<?php

declare(strict_types=1);

use App\Models\WebauthnKey;

it('belongs to a user', function (): void {
    $webauthnKey = WebauthnKey::factory()->create();

    expect($webauthnKey->user()->exists())->toBeTrue();
});

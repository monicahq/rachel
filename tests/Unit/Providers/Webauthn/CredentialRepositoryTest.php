<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\WebauthnKey;
use App\Providers\Webauthn\PasskeyCredentialRepository;
use App\Providers\Webauthn\SecurityKeyCredentialRepository;
use LaravelWebauthn\Services\Webauthn\CredentialRepository;

it('get the right registered keys', function (string $className, int $result): void {
    $user = User::factory()->create();
    WebauthnKey::factory()->create([
        'user_id' => $user->id,
        'kind' => 'passkey',
    ]);
    WebauthnKey::factory()->create([
        'user_id' => $user->id,
        'kind' => 'security',
    ]);

    $registeredKeys = app($className)->getRegisteredKeys($user);
    expect($registeredKeys)->toHaveCount($result);
})->with([
    [PasskeyCredentialRepository::class, 1],
    [SecurityKeyCredentialRepository::class, 1],
    [CredentialRepository::class, 2],
]);

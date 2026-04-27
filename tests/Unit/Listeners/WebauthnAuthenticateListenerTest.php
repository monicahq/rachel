<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\WebauthnKey;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\Event\AuthenticatorAssertionResponseValidationSucceededEvent;
use Webauthn\PublicKeyCredentialRequestOptions;

it('adds the last used at date', function (): void {
    Illuminate\Support\Facades\Date::setTestNow(Illuminate\Support\Facades\Date::parse('2020-12-31'));

    $user = User::factory()->create();
    $webauthnKey = WebauthnKey::factory()->create([
        'user_id' => $user->id,
    ]);

    event(new AuthenticatorAssertionResponseValidationSucceededEvent(
        credentialId: $webauthnKey->credentialId,
        authenticatorAssertionResponse: $this->mock(AuthenticatorAssertionResponse::class),
        publicKeyCredentialRequestOptions: new PublicKeyCredentialRequestOptions('challenger'),
        host: 'localhost',
        userHandle: (string) $user->id,
        publicKeyCredentialSource: $webauthnKey->publicKeyCredentialSource)
    );

    $webauthnKey->refresh();

    expect($webauthnKey->used_at)->toEqual(Illuminate\Support\Facades\Date::parse('2020-12-31'));
});

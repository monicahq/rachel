<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\WebauthnKey;
use Carbon\Carbon;
use Webauthn\AuthenticatorAssertionResponse;
use Webauthn\Event\AuthenticatorAssertionResponseValidationSucceededEvent;
use Webauthn\PublicKeyCredentialRequestOptions;

it('adds the last used at date', function (): void {
    \Illuminate\Support\Facades\Date::setTestNow(\Illuminate\Support\Facades\Date::parse('2020-12-31'));

    $user = User::factory()->create();
    $webauthnKey = WebauthnKey::factory()->create([
        'user_id' => $user->id,
    ]);

    event(new AuthenticatorAssertionResponseValidationSucceededEvent(
        $this->mock(AuthenticatorAssertionResponse::class),
        new PublicKeyCredentialRequestOptions('challenger'),
        'localhost',
        (string) $user->id,
        $webauthnKey->publicKeyCredentialSource)
    );

    $webauthnKey->refresh();

    expect($webauthnKey->used_at)->toEqual(\Illuminate\Support\Facades\Date::parse('2020-12-31'));
});

<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\WebauthnKey;
use ParagonIE\ConstantTime\Base64UrlSafe;
use Webauthn\Event\AuthenticatorAssertionResponseValidationSucceededEvent;

final class WebauthnAuthenticateListener
{
    /**
     * Handle webauthn used key event.
     */
    public function handle(AuthenticatorAssertionResponseValidationSucceededEvent $event): void
    {
        $webauthnKey = WebauthnKey::where('user_id', $event->userHandle)
            ->where('credentialId', Base64UrlSafe::encode($event->publicKeyCredentialSource->publicKeyCredentialId))
            ->first();

        if ($webauthnKey !== null) {
            $webauthnKey->used_at = now();
            $webauthnKey->save();
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

final class LoginListener
{
    /**
     * Handle Login webhooks.
     */
    public function handle(Login $event): void
    {
        if (! $event->remember) {
            session()->put('auth.password_confirmed_at', now()->unix());
        }
    }
}

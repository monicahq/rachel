<?php

declare(strict_types=1);

namespace App\Events\Activity;

use App\Models\UserActivityLog;

final readonly class WebauthnKeyRegistered extends UserActivityEvent
{
    public const CATEGORY = UserActivityLog::CATEGORY_SECURITY;

    public const ACTION = UserActivityLog::ACTION_SECURITY_WEBAUTHN_KEY_REGISTERED;
}

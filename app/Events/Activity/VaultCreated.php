<?php

declare(strict_types=1);

namespace App\Events\Activity;

use App\Models\UserActivityLog;

final readonly class VaultCreated extends UserActivityEvent
{
    public const CATEGORY = UserActivityLog::CATEGORY_VAULT;

    public const ACTION = UserActivityLog::ACTION_VAULT_CREATED;
}

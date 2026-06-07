<?php

declare(strict_types=1);

namespace App\Events\Activity;

use App\Models\UserActivityLog;

final readonly class AccountDeleted extends UserActivityEvent
{
    public const CATEGORY = UserActivityLog::CATEGORY_ACCOUNT;

    public const ACTION = UserActivityLog::ACTION_ACCOUNT_DELETED;
}

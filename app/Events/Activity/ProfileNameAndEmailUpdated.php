<?php

declare(strict_types=1);

namespace App\Events\Activity;

use App\Models\UserActivityLog;

final readonly class ProfileNameAndEmailUpdated extends UserActivityEvent
{
    public const CATEGORY = UserActivityLog::CATEGORY_PROFILE;

    public const ACTION = UserActivityLog::ACTION_PROFILE_NAME_AND_EMAIL_UPDATED;
}

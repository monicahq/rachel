<?php

declare(strict_types=1);

namespace App\Events\Activity;

use App\Models\UserActivityLog;

final readonly class ContactParameterCreated extends UserActivityEvent
{
    public const CATEGORY = UserActivityLog::CATEGORY_CONTACT;

    public const ACTION = UserActivityLog::ACTION_CONTACT_PARAMETER_CREATED;
}

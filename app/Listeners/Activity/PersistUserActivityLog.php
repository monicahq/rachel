<?php

declare(strict_types=1);

namespace App\Listeners\Activity;

use App\Events\Activity\UserActivityEvent;
use App\Models\UserActivityLog;

final class PersistUserActivityLog
{
    public function handle(UserActivityEvent $event): void
    {
        UserActivityLog::create([
            'account_id' => $event->user->account_id,
            'user_id' => $event->user->id,
            'actor_user_id' => $event->actor?->id,
            'loggable_type' => $event->loggable?->getMorphClass(),
            'loggable_id' => $event->loggable instanceof \Illuminate\Database\Eloquent\Model ? (string) $event->loggable->getKey() : null,
            'category' => $event::CATEGORY,
            'action' => $event::ACTION,
            'metadata' => $event->metadata,
        ]);
    }
}

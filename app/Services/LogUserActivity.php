<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Account;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Database\Eloquent\Model;

final readonly class LogUserActivity
{
    /**
     * Persist a user activity log event.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function execute(
        User $user,
        string $category,
        string $action,
        ?User $actor = null,
        array $metadata = [],
        ?Model $loggable = null,
    ): UserActivityLog {
        return UserActivityLog::create([
            'account_id' => $user->account_id,
            'user_id' => $user->id,
            'actor_user_id' => $actor?->id,
            'loggable_type' => $loggable?->getMorphClass(),
            'loggable_id' => $loggable?->getKey(),
            'category' => $category,
            'action' => $action,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Persist an account scoped activity log event.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function executeForAccount(
        Account $account,
        ?User $user,
        string $category,
        string $action,
        ?User $actor = null,
        array $metadata = [],
        ?Model $loggable = null,
    ): UserActivityLog {
        return UserActivityLog::create([
            'account_id' => $account->id,
            'user_id' => $user?->id,
            'actor_user_id' => $actor?->id,
            'loggable_type' => $loggable?->getMorphClass(),
            'loggable_id' => $loggable?->getKey(),
            'category' => $category,
            'action' => $action,
            'metadata' => $metadata,
        ]);
    }
}

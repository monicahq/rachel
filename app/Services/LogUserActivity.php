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
        ?string $loggableName = null,
    ): UserActivityLog {
        return UserActivityLog::create([
            'account_id' => $user->account_id,
            'user_id' => $user->id,
            'actor_user_id' => $actor?->id,
            'loggable_type' => $loggable?->getMorphClass(),
            'loggable_id' => $loggable instanceof \Illuminate\Database\Eloquent\Model ? (string) $loggable->getKey() : null,
            'loggable_name' => $this->resolveLoggableName($loggable, $loggableName),
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
        ?string $loggableName = null,
    ): UserActivityLog {
        return UserActivityLog::create([
            'account_id' => $account->id,
            'user_id' => $user?->id,
            'actor_user_id' => $actor?->id,
            'loggable_type' => $loggable?->getMorphClass(),
            'loggable_id' => $loggable instanceof \Illuminate\Database\Eloquent\Model ? (string) $loggable->getKey() : null,
            'loggable_name' => $this->resolveLoggableName($loggable, $loggableName),
            'category' => $category,
            'action' => $action,
            'metadata' => $metadata,
        ]);
    }

    private function resolveLoggableName(?Model $loggable, ?string $loggableName): ?string
    {
        if ($loggableName !== null && $loggableName !== '') {
            return $loggableName;
        }

        if (!$loggable instanceof \Illuminate\Database\Eloquent\Model) {
            return null;
        }

        foreach (['name', 'label', 'title'] as $attribute) {
            $value = $loggable->getAttribute($attribute);

            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}

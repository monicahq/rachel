<?php

declare(strict_types=1);

namespace App\Events\Activity;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract readonly class UserActivityEvent
{
    public const CATEGORY = '';

    public const ACTION = '';

    /**
     * @param  array<string, mixed>  $metadata
     */
    public function __construct(
        public User $user,
        public ?User $actor = null,
        public array $metadata = [],
        public ?Model $loggable = null,
    ) {}
}

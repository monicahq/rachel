<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\Activity\ContactCreated;
use App\Events\Activity\ContactEmailAdded;
use App\Events\Activity\ContactEmailDeleted;
use App\Events\Activity\ContactEmailUpdated;
use App\Events\Activity\ContactParameterCreated;
use App\Events\Activity\ProfileEmailUpdated;
use App\Events\Activity\ProfileNameAndEmailUpdated;
use App\Events\Activity\ProfileNameUpdated;
use App\Events\Activity\SecurityPasswordUpdated;
use App\Events\Activity\TwoFactorConfirmed;
use App\Events\Activity\TwoFactorDisabled;
use App\Events\Activity\TwoFactorEnabled;
use App\Events\Activity\TwoFactorRecoveryCodesRegenerated;
use App\Events\Activity\VaultCreated;
use App\Events\Activity\WebauthnKeyDeleted;
use App\Events\Activity\WebauthnKeyRegistered;
use App\Events\Activity\WebauthnKeyUpdated;
use App\Events\Activity\WebauthnKeyUpgraded;
use App\Listeners\Activity\PersistUserActivityLog;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string>
     */
    private array $listen_logs = [
        ProfileNameUpdated::class,
        ProfileEmailUpdated::class,
        ProfileNameAndEmailUpdated::class,
        SecurityPasswordUpdated::class,
        TwoFactorEnabled::class,
        TwoFactorConfirmed::class,
        TwoFactorDisabled::class,
        TwoFactorRecoveryCodesRegenerated::class,
        WebauthnKeyRegistered::class,
        WebauthnKeyUpdated::class,
        WebauthnKeyDeleted::class,
        WebauthnKeyUpgraded::class,
        VaultCreated::class,
        ContactCreated::class,
        ContactParameterCreated::class,
        ContactEmailAdded::class,
        ContactEmailUpdated::class,
        ContactEmailDeleted::class,
    ];

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        $listen_logs = collect($this->listen_logs)->mapWithKeys(fn ($event): array => [
            $event => [PersistUserActivityLog::class],
        ])->all();

        return array_merge(parent::listens(), $listen_logs);
    }
}

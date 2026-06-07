<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Activity\ContactEmailDeleted;
use App\Models\ContactParameter;
use App\Models\User;

/**
 * Destroy a parameter.
 */
final readonly class DestroyParameter
{
    public function __construct(
        public ContactParameter $parameter,
        public ?User $actor = null,
    ) {}

    public function execute(): void
    {
        $this->logActivity();
        $this->parameter->delete();
    }

    private function logActivity(): void
    {
        if (! $this->actor || $this->parameter->key !== 'email') {
            return;
        }

        $contact = $this->parameter->contact;

        if (! $contact) {
            return;
        }

        event(new ContactEmailDeleted(
            user: $this->actor,
            actor: $this->actor,
            loggable: $contact,
            loggableName: $contact->name,
        ));
    }
}

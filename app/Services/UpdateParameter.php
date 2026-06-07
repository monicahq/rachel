<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Activity\ContactEmailUpdated;
use App\Models\ContactParameter;
use App\Models\User;

/**
 * Update a parameter.
 */
final readonly class UpdateParameter
{
    public function __construct(
        public ContactParameter $parameter,
        public string $key,
        public string $label,
        public string $type,
        public ?string $data = null,
        public ?User $actor = null,
    ) {}

    public function execute(): ContactParameter
    {
        $this->parameter->fill([
            'key' => $this->key,
            'label' => $this->label,
            'type' => $this->type,
            'data' => $this->data,
        ]);

        $this->parameter->save();
        $this->logActivity();

        return $this->parameter;
    }

    private function logActivity(): void
    {
        if (! $this->actor || $this->key !== 'email') {
            return;
        }

        $contact = $this->parameter->contact;

        if (! $contact) {
            return;
        }

        event(new ContactEmailUpdated(
            user: $this->actor,
            actor: $this->actor,
            loggable: $contact,
            loggableName: $contact->name,
        ));
    }
}

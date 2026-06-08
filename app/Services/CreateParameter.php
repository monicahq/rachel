<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Activity\ContactEmailAdded;
use App\Events\Activity\ContactParameterCreated;
use App\Models\Contact;
use App\Models\ContactParameter;
use App\Models\User;

/**
 * Create a parameter.
 */
final readonly class CreateParameter
{
    public function __construct(
        public Contact $contact,
        public string $key,
        public string $type,
        public ?string $label = null,
        public ?string $data = null,
        public ?User $actor = null,
    ) {}

    public function execute(): ContactParameter
    {
        $parameter = $this->contact->parameters()->create([
            'vault_id' => $this->contact->vault_id,
            'key' => $this->key,
            'type' => $this->type,
            'label' => $this->label,
            'data' => $this->data,
        ]);

        $this->logActivity();

        return $parameter;
    }

    private function logActivity(): void
    {
        if (! $this->actor instanceof User) {
            return;
        }

        if ($this->key === 'email') {
            event(new ContactEmailAdded(
                user: $this->actor,
                actor: $this->actor,
                metadata: [
                    'contact_name' => $this->contact->name,
                ],
                loggable: $this->contact,
            ));

            return;
        }

        event(new ContactParameterCreated(
            user: $this->actor,
            actor: $this->actor,
            metadata: [
                'parameter_key' => $this->key,
                'contact_name' => $this->contact->name,
            ],
            loggable: $this->contact,
        ));
    }
}

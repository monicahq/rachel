<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\Activity\ContactCreated;
use App\Helpers\SlugHelper;
use App\Models\Contact;
use App\Models\User;
use App\Models\Vault;

/**
 * Create a contact.
 */
final class CreateContact
{
    private Contact $contact;

    public function __construct(
        public readonly Vault $vault,
        public readonly string $name,
        private readonly ?User $actor = null,
    ) {}

    public function execute(): Contact
    {
        $this->create();
        $this->logActivity();

        return $this->contact;
    }

    private function create(): void
    {
        $slug = SlugHelper::generateUniqueSlug(
            collection: $this->vault->contacts,
            name: $this->name,
            locale: $this->vault->account->users->first()->locale,
        );

        $this->contact = $this->vault->contacts()->create([
            'name' => $this->name,
            'slug' => $slug,
        ]);
    }

    private function logActivity(): void
    {
        $actor = $this->actor ?? $this->vault->account->users->first();

        event(new ContactCreated(
            user: $actor,
            actor: $actor,
            metadata: [
                'contact_name' => $this->contact->name,
            ],
            loggable: $this->contact,
            loggableName: $this->contact->name,
        ));
    }
}

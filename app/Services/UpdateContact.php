<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\SlugHelper;
use App\Models\Contact;
use App\Models\Vault;

/**
 * Update a contact.
 */
final readonly class UpdateContact
{
    public function __construct(
        public Contact $contact,
        public Vault $vault,
        public string $name,
    ) {}

    public function execute(): Contact
    {
        $this->update();

        return $this->contact;
    }

    private function update(): void
    {
        $this->contact->fill([
            'name' => $this->name,
        ]);

        if ($this->contact->isDirty('name')) {
            $slug = SlugHelper::generateUniqueSlug(
                collection: $this->vault->contacts,
                name: $this->name,
                locale: $this->vault->account->users->first()->locale,
            );

            $this->contact->fill([
                'slug' => $slug,
            ]);
        }

        $this->contact->save();
    }
}

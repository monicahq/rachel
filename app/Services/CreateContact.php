<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\SlugHelper;
use App\Models\Contact;
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
    ) {}

    public function execute(): Contact
    {
        $this->create();

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
}

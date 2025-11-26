<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contact;

/**
 * Destroy a contact.
 */
final readonly class DestroyContact
{
    public function __construct(
        public Contact $contact,
    ) {}

    public function execute(): void
    {
        $this->contact->delete();
    }
}

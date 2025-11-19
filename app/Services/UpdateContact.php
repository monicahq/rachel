<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contact;
use App\Models\Vault;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        $this->contact->update([
            'name' => $this->name,
            'slug' => Str::slug($this->name, language: Auth::user()->locale),
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contact;
use App\Models\Vault;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
        $this->contact = $this->vault->contacts()->create([
            'name' => $this->name,
            'slug' => Str::slug($this->name, language: Auth::user()->locale),
        ]);
    }
}

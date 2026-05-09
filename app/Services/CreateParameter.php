<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Contact;
use App\Models\ContactParameter;

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
    ) {}

    public function execute(): ContactParameter
    {
        return $this->contact->parameters()->create([
            'vault_id' => $this->contact->vault_id,
            'key' => $this->key,
            'type' => $this->type,
            'label' => $this->label,
            'data' => $this->data,
        ]);
    }
}

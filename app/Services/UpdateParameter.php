<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ContactParameter;

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

        return $this->parameter;
    }
}

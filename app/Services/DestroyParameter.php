<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ContactParameter;

/**
 * Destroy a parameter.
 */
final readonly class DestroyParameter
{
    public function __construct(
        public ContactParameter $parameter,
    ) {}

    public function execute(): void
    {
        $this->parameter->delete();
    }
}

# Services

Services live in `app/Services/` and encapsulate one business operation. Pages and API controllers always delegate writes to a service.

Canonical examples: `app/Services/CreateVault.php`, `app/Services/CreateContact.php`, `app/Services/UpdateVault.php`, `app/Services/DestroyVault.php`.

## Create

```bash
php artisan make:class Services/CreateSomething --no-interaction
```

Then convert the generated class to the conventions below.

## Naming

One verb + one resource per class:

- `CreateVault`, `UpdateVault`, `DestroyVault`
- `CreateContact`, `UpdateContact`, `DestroyContact`
- `CreateAccount`

## Structure

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\SlugHelper;
use App\Models\User;
use App\Models\Vault;

/**
 * Create a vault for a user.
 */
final class CreateVault
{
    private Vault $vault;

    public function __construct(
        public readonly User $user,
        public readonly string $name,
        public readonly ?string $description = null,
    ) {}

    public function execute(): Vault
    {
        $this->create();

        return $this->vault;
    }

    private function create(): void
    {
        $slug = SlugHelper::generateUniqueSlug(
            collection: $this->user->account->vaults,
            name: $this->name,
            locale: $this->user->locale,
        );

        $this->vault = $this->user->account->vaults()->create([
            'name' => $this->name,
            'slug' => $slug,
            'description' => $this->description,
        ]);
    }
}
```

## Conventions

- `final class`, `declare(strict_types=1);`.
- One short PHPDoc at the class level describing the operation.
- Constructor uses property promotion with `public readonly` for every input.
- Single public entry point: `execute(): ReturnType`.
- Decompose work into private methods (`create()`, `update()`, `attach()`, ...).
- Return the affected model from `execute()`. Use `void` for destroy services.
- For destroy services, accept the model in the constructor and call `delete()` inside `execute()`.
- For unique slugs, use `App\Helpers\SlugHelper::generateUniqueSlug(collection: ..., name: ..., locale: ...)`.
- Do **not** add validation here — the page or API controller already validated using `Model::rules()`.
- Do **not** authorize here — authorization happens at the page/controller layer.
- Avoid optional dependencies, repository abstractions, or DI containers; instantiate the service directly with `new`.

## Calling a Service

```php
$vault = (new CreateVault(
    user: $request->user(),
    name: $validated['name'],
    description: $validated['description'] ?? null,
))->execute();
```

Always use named arguments for clarity.

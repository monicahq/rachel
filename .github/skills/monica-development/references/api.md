# API Endpoints

API controllers live in `app/Http/Controllers/Api/` and extend `ApiController`. Each public method is documented with Scribe attributes so the API reference (under `.scribe/` and the rendered docs in `public/vendor/`) stays in sync.

Canonical examples: `app/Http/Controllers/Api/VaultController.php`, `app/Http/Controllers/Api/ContactController.php`.

## Create

```bash
php artisan make:controller Api/SomethingController --api --no-interaction
```

Then adapt the file to extend `ApiController` and follow the structure below.

## Controller Structure

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Vault;
use App\Services\CreateVault;
use App\Services\DestroyVault;
use App\Services\UpdateVault;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

/**
 * @group Vault management
 *
 * @subgroup Vaults
 */
final class VaultController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(Vault::class);
        $this->middleware('abilities:read')->only(['index', 'show']);
        $this->middleware('abilities:write')->only(['store', 'update', 'delete']);
    }

    /**
     * List all vaults.
     */
    #[ResponseFromApiResource(JsonResource::class, Vault::class, collection: true)]
    public function index(Request $request): JsonResource
    {
        return JsonResource::collection($request->user()->account->vaults);
    }

    /**
     * Retrieve a vault.
     */
    #[ResponseFromApiResource(JsonResource::class, Vault::class)]
    public function show(Vault $vault): JsonResource
    {
        return new JsonResource($vault);
    }

    /**
     * Create a vault.
     */
    #[ResponseFromApiResource(JsonResource::class, Vault::class, status: 201)]
    #[BodyParam('name', description: 'The name of the vault. Max 255 characters.')]
    #[BodyParam('description', description: 'The description of the vault. Max 65535 characters.', required: false)]
    public function store(Request $request): JsonResource
    {
        $validated = $this->validate($request, Vault::rules());

        $vault = (new CreateVault(
            user: $request->user(),
            name: $validated['name'],
            description: $validated['description'] ?? null,
        ))->execute();

        return new JsonResource($vault);
    }

    /**
     * Destroy a vault.
     */
    #[Response(status: 204)]
    public function destroy(Vault $vault): JsonResponse
    {
        (new DestroyVault(vault: $vault))->execute();

        return new JsonResponse(status: 204);
    }
}
```

## Conventions

- `final class`, extends `ApiController` (which logs API requests via middleware).
- Class-level PHPDoc with `@group` (top-level group in Scribe) and `@subgroup` (nested group).
- Constructor must:
  1. Call `parent::__construct()`.
  2. Call `$this->authorizeResource(Model::class)` to wire policies to controller methods automatically.
  3. Restrict abilities via Sanctum: `abilities:read` on `index`/`show`, `abilities:write` on `store`/`update`/`delete`.
- Each action method has a one-line PHPDoc — Scribe uses it as the endpoint title.
- Validation: `$validated = $this->validate($request, Model::rules());`.
- Delegate writes to a service from `app/Services/`.
- Use route model binding (`Vault $vault`, `Contact $contact`). For nested resources, accept both: `(Vault $vault, Contact $contact)`.
- Returns:
  - Single item → `new JsonResource($model)`.
  - Collection → `JsonResource::collection($models)`.
  - Destroy → `new JsonResponse(status: 204)`.

## Scribe Attributes

Apply to every endpoint method:

| Attribute | When to use |
| --------- | ----------- |
| `#[ResponseFromApiResource(JsonResource::class, Model::class)]` | `show` returning a single resource. |
| `#[ResponseFromApiResource(JsonResource::class, Model::class, collection: true)]` | `index` returning a collection. |
| `#[ResponseFromApiResource(JsonResource::class, Model::class, status: 201)]` | `store` returning the created resource. |
| `#[BodyParam('field', description: '...')]` | Each input field. Add `required: false` for optional fields. |
| `#[Response(status: 204)]` | `destroy` returning no content. |

Add one `#[BodyParam(...)]` per documented input. Mirror the wording used in `VaultController` / `ContactController` (e.g. "Max 255 characters.").

## Route Registration

Add the route in `routes/api.php`, inside the `auth:sanctum` group with the `api.` name prefix, using `Route::apiResource()`:

```php
Route::middleware('auth:sanctum')->name('api.')->group(function (): void {
    Route::apiResource('vaults', VaultController::class);
    Route::apiResource('vaults/{vault}/contacts', ContactController::class);
});
```

Restrict verbs with `->only([...])` when the controller does not implement them all (see `UserController` which only exposes `index` and `show`).

The API is mounted at `/api/v1/...` (configured at the application level — do not prefix routes manually).

## Regenerating Documentation

After adding or modifying endpoints, regenerate the Scribe docs:

```bash
php artisan scribe:generate
```

Generated artifacts:

- `.scribe/endpoints/*.yaml` — endpoint metadata (committed).
- `public/vendor/scribe/` — rendered HTML/Postman/OpenAPI (built output).
- `resources/views/scribe/` — Blade view of the docs.

Review the diff in `.scribe/` to confirm your endpoint appears with the expected group, parameters, and example responses.

# Tests

Rachel uses Pest. Each new feature ships with three test files: a Feature test for the Livewire page, a Unit test for the service, and a Unit test for the API controller.

Canonical examples:

- `tests/Feature/VaultTest.php` (Livewire page)
- `tests/Unit/Services/CreateVaultTest.php` (service)
- `tests/Unit/Api/VaultControllerTest.php` (API)

## Create

```bash
# Feature test (Livewire page)
php artisan make:test --pest VaultTest --no-interaction

# Unit test (service or API controller)
php artisan make:test --pest --unit Services/CreateVaultTest --no-interaction
php artisan make:test --pest --unit Api/VaultControllerTest --no-interaction
```

## Conventions

- Every test file starts with `<?php` then `declare(strict_types=1);`.
- Use `test('description', ...)` or `it('description', ...)`.
- Use model factories — never insert rows manually.
- Always associate created models to the authenticated user's account: `Vault::factory()->create(['account_id' => $user->account_id])`.
- Add at least one negative test (unauthorized user, wrong account, missing model).

## Feature: Livewire Page

Mirror `tests/Feature/VaultTest.php`. Cover: page renders, route returns 200, key data is visible, Livewire actions succeed and redirect correctly.

```php
<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use Livewire\Livewire;

test('users can list vaults', function (): void {
    $this->actingAs($user = User::factory()->create());
    $vault = Vault::factory()->create(['account_id' => $user->account_id]);

    $this->get(route('vaults.index'))
        ->assertStatus(200)
        ->assertSee($vault->name);
});

test('user can create a vault', function (): void {
    $this->actingAs(User::factory()->create());

    Livewire::test('pages::vaults.index')
        ->set('name', 'Test Vault')
        ->call('create')
        ->assertHasNoErrors()
        ->assertRedirect(route('vaults.show', ['vault' => 'test-vault']));
});
```

Use `Livewire::test('pages::group.name')` with the same string passed to `Route::livewire()` in `routes/web.php`.

## Unit: Service

Mirror `tests/Unit/Services/CreateVaultTest.php`. Instantiate the service directly with named arguments, call `execute()`, then assert with `expect(...)` and `assertDatabaseHas(...)`.

```php
<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use App\Services\CreateVault;

it('can create a vault', function (): void {
    $user = User::factory()->create();

    $vault = (new CreateVault(user: $user, name: 'New Vault'))->execute();

    expect($vault)->toBeInstanceOf(Vault::class);

    $this->assertDatabaseHas('vaults', [
        'id' => $vault->id,
        'account_id' => $user->account_id,
        'name' => 'New Vault',
        'slug' => 'new-vault',
    ]);
});
```

Cover edge cases: optional fields, slug collision (creating the same name twice → `slug-1`), behaviour with related models.

## Unit: API Controller

Mirror `tests/Unit/Api/VaultControllerTest.php`. Wrap related tests in `describe(...)`. Authenticate with Sanctum and the appropriate ability.

```php
<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Vault;
use Laravel\Sanctum\Sanctum;

describe('api-vaults', function (): void {
    test('api get all vaults', function (): void {
        Sanctum::actingAs($user = User::factory()->create(), ['read']);
        $vault = Vault::factory()->create(['account_id' => $user->account_id]);

        $response = $this->getJson('/api/v1/vaults')->assertOk();

        expect($response->json('data.*.id'))->toBe([$vault->id]);
    });

    test('api create a vault', function (): void {
        Sanctum::actingAs(User::factory()->create(), ['write']);

        $response = $this->postJson('/api/v1/vaults', [
            'name' => 'my vault',
            'description' => null,
        ])->assertCreated();

        expect($response->json('data.name'))->toBe('my vault');
        $this->assertDatabaseHas('vaults', ['id' => $response->json('data.id')]);
    });

    test("can't read other account vault", function (): void {
        Sanctum::actingAs(User::factory()->create(), ['read']);
        $vault = Vault::factory()->create();

        $this->getJson('/api/v1/vaults/'.$vault->slug)->assertNotFound();
    });
});
```

Required coverage per endpoint:

- `index`: empty list, populated list, isolation between accounts.
- `show`: by slug, by id, 404 for another account's resource.
- `store`: created (201), validation errors, ability scope (`['read']` should fail with 403).
- `update`: success, 404 across accounts.
- `destroy`: 204, model removed from DB, 404 across accounts.

Use the right JSON helper and assertion:

| Verb | Helper | Assertion |
| ---- | ------ | --------- |
| GET | `getJson(...)` | `assertOk()` |
| POST | `postJson(...)` | `assertCreated()` |
| PUT/PATCH | `putJson(...)` | `assertOk()` |
| DELETE | `deleteJson(...)` | `assertNoContent()` |

## Running Tests

```bash
# Run a single file
php artisan test --compact tests/Feature/VaultTest.php

# Filter by name
php artisan test --compact --filter='users can list vaults'

# Run the whole suite (avoid unless validating broad changes)
php artisan test --compact
```

After editing PHP, also run `vendor/bin/pint --dirty --format agent` before considering the change done.

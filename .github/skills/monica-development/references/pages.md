# Livewire Pages

Pages in Rachel are Livewire 4 single-file components (SFC) located under `resources/views/pages/`. Files are prefixed with `⚡` (lightning bolt). The class is anonymous and lives at the top of the Blade file.

Canonical example: `resources/views/pages/vaults/⚡index.blade.php`.

## Create

```bash
php artisan make:livewire pages::vaults.create-something --no-interaction
```

This creates `resources/views/pages/vaults/⚡create-something.blade.php`.

For a nested resource: `pages::contacts.index` → `resources/views/pages/contacts/⚡index.blade.php`.

## File Structure

```php
<?php

use App\Models\Vault;
use App\Services\CreateVault;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;

new class extends Livewire\Component
{
    #[Locked]
    public Collection $vaults;

    public string $name = '';

    public ?string $description = null;

    public function render()
    {
        return $this->view()->title(__('List of vaults'));
    }

    public function mount(): void
    {
        $this->authorize('viewAny', Vault::class);

        $this->vaults = Auth::user()->account->vaults->map(
            fn (Vault $vault): array => [
                'id' => $vault->id,
                'name' => $vault->name,
                'route' => route('vaults.show', $vault),
            ],
        );
    }

    public function create(): void
    {
        $this->authorize('create', Vault::class);

        $validated = $this->validate(Vault::rules());

        $vault = (new CreateVault(
            user: Auth::user(),
            name: $validated['name'],
            description: $validated['description'] ?? null,
        ))->execute();

        $this->reset('name', 'description');
        $this->dispatch('vault-created');
        $this->redirect(route('vaults.show', $vault));
    }
}; ?>

<div>
  {{-- Blade markup using Flux components and Tailwind --}}
</div>
```

## Conventions

- Use `#[Locked]` for any property that must not be mutated by the client (collections derived from the DB, IDs, etc.).
- Public properties used in forms are typed scalars (`string`, `?string`, `int`).
- `mount()` always begins with `$this->authorize(...)`. Load data into `#[Locked]` properties here.
- `render()` returns `$this->view()->title(__('...'));` so the page sets a translated title.
- Action methods (`create`, `update`, `destroy`):
  1. `$this->authorize(...)` against the appropriate policy ability.
  2. `$validated = $this->validate(Model::rules());`
  3. Delegate the work to a service: `(new ServiceClass(...))->execute()`.
  4. `$this->reset(...)` form fields when keeping the user on the page.
  5. `$this->dispatch('event-name')` to notify other components.
  6. `$this->redirect(route(...))` when navigating away.
- Never put business logic in the page — always call a service from `app/Services/`.
- Never write SQL or call Eloquent write methods directly from the component; use a service.

## Markup

- Use Flux components (`<x-...>`) and the project's own Blade components.
- All user-visible strings go through `__('...')`.
- Tailwind utility classes only (no inline styles besides minor animation values like `style="animation-duration: 4s"`).
- Keep state on the server — use `wire:model`, `wire:click`, etc. instead of Alpine when possible.

## Breadcrumbs

- Add the breadcrumb in the top of the page's Blade file, inside the root `<div>`:
```
  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('dashboard')],
    ['label' => __('New page')]
  ]" />
```

## Route Registration

Add the route in `routes/web.php` inside the `['auth', 'verified']` group using `Route::livewire()`:

```php
Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::livewire('vaults', 'pages::vaults.index')->name('vaults.index');
    Route::livewire('vaults/{vault}', 'pages::vaults.show')
        ->name('vaults.show')
        ->missing(fn () => to_route('vaults.index'));
});
```

- Use `->missing(...)` for show/edit routes to gracefully redirect on a missing model.
- Always set `->name(...)` and use `route('name', ...)` in code and Blade — never hardcode URLs.

## Authorization

If you introduce a new resource, also add a policy in `app/Policies/` registered through Laravel's auto-discovery, and use `$this->authorize('ability', Model::class)` (class for `create`/`viewAny`, instance otherwise) inside `mount()` and every action method.

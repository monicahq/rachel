<?php

use App\Models\Contact;
use App\Models\Vault;
use App\Services\CreateContact;
use Livewire\Attributes\Locked;

new class extends Livewire\Component {
  #[Locked]
  public $vault;

  #[Locked]
  public $routes;

  public string $name = '';

  public function render()
  {
    return $this->view()->title(__('Add a contact in vault :vault', ['vault' => $this->vault['name']]));
  }

  public function mount(Vault $vault): void
  {
    $this->authorize('create', [Contact::class, $vault]);

    $this->vault = [
      'id' => $vault->id,
      'name' => $vault->name,
    ];
    $this->routes = [
      'vaults' => [
        'index' => route('vaults.index'),
        'show' => route('vaults.show', $vault),
      ],
      'contacts' => [
        'index' => route('contacts.index', $vault),
      ],
    ];
  }

  public function create(): void
  {
    $vault = Vault::find($this->vault['id']);

    $this->authorize('create', [Contact::class, $vault]);

    $validated = $this->validate(Contact::rules());

    $contact = (new CreateContact(vault: $vault, name: $validated['name']))->execute();

    $this->redirect(route('contacts.show', [$vault, $contact]));
  }
}; ?>

<div>
  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('dashboard')],
    ['label' => __('Vaults'), 'route' => Arr::get($routes, 'vaults.index')],
    ['label' => $vault['name'], 'route' => Arr::get($routes, 'vaults.show')],
    ['label' => __('Contacts'), 'route' => Arr::get($routes, 'contacts.index')],
  ]" />

  <div class="mx-auto max-w-lg px-2 py-2 sm:px-6 sm:py-6 lg:px-8">
    <section class="flex w-full flex-col gap-6">
      <h1 class="text-xl font-semibold dark:text-white">
        {{ __('Add a contact') }}
      </h1>

      <x-box>
        <form method="POST" wire:submit="create" class="space-y-6">
          <x-input wire:model="name" id="name" :label="__('Contact name')" type="text" required />

          <div class="flex items-center justify-between">
            <x-link :href="Arr::get($routes, 'vaults.show')">
              <flux:button variant="filled">
                {{ __('Cancel') }}
              </flux:button>
            </x-link>

            <flux:button variant="primary" type="submit">
              {{ __('Create') }}
            </flux:button>
          </div>
        </form>
      </x-box>
    </section>
  </div>
</div>

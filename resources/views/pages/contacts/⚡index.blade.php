<?php

use App\Models\Contact;
use App\Models\Vault;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;

new class extends Livewire\Component {
  #[Locked]
  public $vault;

  #[Locked]
  public $routes;

  #[Locked]
  public Collection $contacts;

  public function render()
  {
    return $this->view()->title(__('Contacts in vault :vault', ['vault' => $this->vault['name']]));
  }

  public function mount(Vault $vault): void
  {
    $this->authorize('viewAny', [Contact::class, $vault]);

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
        'create' => route('contacts.create', $vault),
        'index' => route('contacts.index', $vault),
      ],
    ];
    $this->contacts = $vault->contacts
      ->map(
        fn (Contact $contact): array => [
          'id' => $contact->id,
          'name' => $contact->name,
          'route' => route('contacts.show', [$vault, $contact]),
        ],
      )
      ->sortByCollator('name');
  }
}; ?>

<div>
  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('dashboard')],
    ['label' => __('Vaults'), 'route' => Arr::get($routes, 'vaults.index')],
    ['label' => $vault['name'], 'route' => Arr::get($routes, 'vaults.show')],
    ['label' => __('Contacts')],
  ]" />

  <div class="mx-auto max-w-lg px-2 py-2 sm:px-6 sm:py-6 lg:px-8">
    <section class="flex w-full flex-col gap-10">
      <!-- contacts list -->
      <div class="grid grid-cols-1 gap-10">
        @foreach ($contacts as $contact)
          <x-link wire:key="{{ $contact['id'] }}" :href="$contact['route']" class="dark:text-white">
            {{ $contact['name'] }}
          </x-link>
        @endforeach
      </div>

      <div class="flex justify-center">
        <x-link :href="Arr::get($routes, 'contacts.create')">
          <flux:button icon="plus-circle">{{ __('Add a contact') }}</flux:button>
        </x-link>
      </div>
    </section>
  </div>
</div>

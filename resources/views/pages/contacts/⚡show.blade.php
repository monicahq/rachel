<?php

use App\Models\Contact;
use App\Models\Vault;
use Livewire\Attributes\Locked;

new class extends Livewire\Component {
  #[Locked]
  public $contact;

  #[Locked]
  public $vault;

  #[Locked]
  public $routes;

  public function render()
  {
    return $this->view()->title($this->contact['name']);
  }

  public function mount(Vault $vault, Contact $contact): void
  {
    $this->authorize('view', [$contact, $vault]);

    $this->vault = [
      'id' => $vault->id,
      'name' => $vault->name,
    ];
    $this->contact = [
      'id' => $contact->id,
      'name' => $contact->name,
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
}; ?>

<div class="dark:text-white">
  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('dashboard')],
    ['label' => __('Vaults'), 'route' => Arr::get($routes, 'vaults.index')],
    ['label' => $vault['name'], 'route' => Arr::get($routes, 'vaults.show')],
    ['label' => __('Contacts'), 'route' => Arr::get($routes, 'contacts.index')],
    ['label' => $contact['name']],
  ]" />

  {{ $contact['name'] }}
</div>

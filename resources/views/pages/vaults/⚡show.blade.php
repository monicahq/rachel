<?php

use App\Models\Vault;
use Livewire\Attributes\Locked;

new class extends Livewire\Component {
  #[Locked]
  public $vault;

  #[Locked]
  public $routes;

  public function render()
  {
    return $this->view()->title(__('Vault :vault', ['vault' => $this->vault['name']]));
  }

  public function mount(Vault $vault): void
  {
    $this->authorize('view', $vault);

    $this->vault = [
      'name' => $vault->name,
    ];
    $this->routes = [
      'vaults' => [
        'index' => route('vaults.index'),
      ],
      'contacts' => [
        'index' => route('contacts.index', $vault),
      ],
    ];
  }
}; ?>

<div>
  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('dashboard')],
    ['label' => __('Vaults'), 'route' => Arr::get($routes, 'vaults.index')],
    ['label' => $vault['name']],
  ]" />

  <div class="dark:text-white">
    {{ $vault['name'] }}
  </div>

  <x-link :href="Arr::get($routes, 'contacts.index')" class="dark:text-white">
    {{ __('Contacts') }}
  </x-link>
</div>

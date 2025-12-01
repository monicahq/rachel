<?php

use App\Models\Vault;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;
use function Livewire\Volt\title;

title(fn (): string|array|null => __('Vault :vault', ['vault' => $this->vault['name']]));

state(['vault'])->locked();

mount(function (Vault $vault): void {
    $this->authorize('view', $vault);

    $this->vault = [
        'name' => $vault->name,
        'routes' => [
            'contacts' => route('contacts.index', $vault),
        ],
    ];
});

?>

<div>
  <div class="dark:text-white">
    {{ $vault['name'] }}
  </div>

  <x-link :href="$vault['routes']['contacts']" class="dark:text-white">
    {{ __('Contacts') }}
  </x-link>
</div>

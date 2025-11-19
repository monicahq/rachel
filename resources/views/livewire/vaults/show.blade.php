<?php

use App\Models\Vault;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['vault'])->locked();

mount(function (Vault $vault): void {
    $this->authorize('view', $vault);

    $this->vault = $vault;
});

?>

<div>
  <div class="dark:text-white">
    {{ $vault->name }}
  </div>

  <x-link :href="route('contacts.index', $vault)" class="dark:text-white">Contacts</x-link>
</div>

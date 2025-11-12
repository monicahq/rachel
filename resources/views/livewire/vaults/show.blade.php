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
  {{ $vault->name }}
</div>

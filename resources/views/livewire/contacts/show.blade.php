<?php

use App\Models\Contact;
use App\Models\Vault;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;
use function Livewire\Volt\title;

title(fn () => $this->contact['name']);

state(['contact'])->locked();

mount(function (Vault $vault, Contact $contact): void {
    $this->authorize('view', [$contact, $vault]);

    $this->contact = [
        'name' => $contact->name,
    ];
});
?>

<div class="dark:text-white">
  {{ $contact['name'] }}
</div>

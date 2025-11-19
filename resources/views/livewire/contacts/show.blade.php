<?php

use App\Models\Contact;
use App\Models\Vault;

use function Livewire\Volt\mount;
use function Livewire\Volt\state;

state(['contact'])->locked();

mount(function (Vault $vault, Contact $contact): void {
    $this->authorize('view', $contact);

    $this->contact = $contact;
});

?>

<div class="dark:text-white">
  {{ $contact->name }}
</div>

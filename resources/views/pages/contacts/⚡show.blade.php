<?php

use App\Models\Contact;
use App\Models\Vault;
use Livewire\Attributes\Locked;

new class extends Livewire\Component {
  #[Locked]
  public $contact;

  public function render()
  {
    return $this->view()->title($this->contact['name']);
  }

  public function mount(Vault $vault, Contact $contact): void
  {
    $this->authorize('view', [$contact, $vault]);

    $this->contact = [
      'name' => $contact->name,
    ];
  }
}; ?>

<div class="dark:text-white">
  {{ $contact['name'] }}
</div>

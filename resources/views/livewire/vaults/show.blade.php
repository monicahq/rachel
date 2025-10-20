<?php

use App\Models\Vault;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new class extends Component
{
    #[Locked]
    public Vault $vault;

    public function mount(Vault $vault): void
    {
        $this->authorize('view', $vault);

        $this->vault = $vault;
    }
}; ?>

<div>
  {{ $vault->name }}
</div>

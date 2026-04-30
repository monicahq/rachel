<?php

use App\Models\Vault;
use Livewire\Attributes\Locked;

new class extends Livewire\Component
{
    #[Locked]
    public $vault;

    public function render()
    {
        return $this->view()->title(__('Vault :vault', ['vault' => $this->vault['name']]));
    }

    public function mount(Vault $vault): void
    {
        $this->authorize('view', $vault);

        $this->vault = [
            'name' => $vault->name,
            'routes' => [
                'contacts' => route('contacts.index', $vault),
            ],
        ];
    }
}; ?>

<div>
  <div class="dark:text-white">
    {{ $vault['name'] }}
  </div>

  <x-link :href="$vault['routes']['contacts']" class="dark:text-white">
    {{ __('Contacts') }}
  </x-link>
</div>

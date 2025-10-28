<?php

use App\Models\Vault;
use App\Services\CreateVault;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new class extends Component
{
    #[Locked]
    public Collection $vaults;

    #[Validate(['required', 'string', 'max:255'])]
    public string $name = '';

    #[Validate(['nullable', 'string', 'max:255'])]
    public string $description = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Vault::class);

        $this->vaults = Auth::user()->account->vaults;
    }

    public function create(): void
    {
        $validated = $this->validate();

        $vault = (new CreateVault(account: Auth::user()->account, name: $validated['name'], description: $validated['description'] ?? null))->execute();

        $this->reset('name', 'description');

        $this->dispatch('vault-created');

        $this->redirect(route('vaults.show', $vault));
    }
}; ?>

<section class="w-full">
  @foreach ($vaults as $vault)
    <div wire:key="vault-{{ $vault->id }}">
      <x-link :href="route('vaults.show', $vault)" wire:navigate>{{ $vault->name }}</x-link>
    </div>
  @endforeach

  <form method="POST" wire:submit="create" class="mt-6 space-y-6">
    <x-input wire:model="name" id="name" :label="__('Vault Name')" type="text" required />
    <x-input wire:model="description" id="description" :label="__('Vault Description')" type="text" :optional="true" />

    <div class="flex items-center gap-4">
      <div class="flex items-center justify-end">
        <flux:button variant="primary" type="submit" class="w-full">
          {{ __('Create') }}
        </flux:button>
      </div>

      <x-action-message class="me-3" on="vault-created">
        {{ __('Created') }}
      </x-action-message>
    </div>
  </form>
</section>

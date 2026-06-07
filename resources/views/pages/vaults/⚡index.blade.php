<?php

use App\Models\Vault;
use App\Services\CreateVault;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;

new class extends Livewire\Component
{
    #[Locked]
    public Collection $vaults;

    public string $name;

    public string $description;

    public function render()
    {
        return $this->view()->title(__('List of vaults'));
    }

    public function mount(Vault $vault): void
    {
        $this->authorize('viewAny', Vault::class);

        $this->vaults = Auth::user()->account->vaults->map(
            fn (Vault $vault): array => [
                'id' => $vault->id,
                'name' => $vault->name,
                'route' => route('vaults.show', $vault),
            ],
        );
    }

    public function create(): void
    {
        $this->authorize('create', Vault::class);

        $validated = $this->validate(Vault::rules());

        $vault = (new CreateVault(user: Auth::user(), name: $validated['name'], description: $validated['description'] ?? null, actor: Auth::user()))->execute();

        $this->reset('name', 'description');

        $this->dispatch('vault-created');

        $this->redirect(route('vaults.show', $vault));
    }
}; ?>

<div>
  <div class="mx-auto max-w-lg px-2 py-2 sm:px-6 sm:py-6 lg:px-8">
    <section class="flex w-full flex-col gap-10">
      <!-- vaults list -->
      <div class="grid grid-cols-1 gap-10">
        @foreach ($vaults as $vault)
          <x-pages::vaults.partials.vault-box :name="$vault['name']" :route="$vault['route']" />
        @endforeach
      </div>

      <x-box
        x-data="{
        showForm: false,
        toggle: function() { this.showForm = !this.showForm }
      }">
        <div class="flex justify-center" x-show="!showForm" x-transition:enter.duration.200ms>
          <flux:button icon="plus-circle" @click.prevent="toggle()">{{ __('Add a vault') }}</flux:button>
        </div>

        <!-- create vault form -->
        <form method="POST" wire:submit="create" class="space-y-6" wire:cloak x-show="showForm" x-transition:enter.duration.200ms>
          <x-input wire:model="name" id="name" :label="__('Vault name')" type="text" required />
          <x-input wire:model="description" id="description" :label="__('Vault description')" type="text" :optional="true" />

          <div class="flex items-center justify-between">
            <flux:button variant="filled" @click.prevent="toggle()">
              {{ __('Cancel') }}
            </flux:button>

            <div class="flex items-center gap-4">
              <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">
                  {{ __('Create') }}
                </flux:button>
              </div>

              <x-action-message class="me-3" on="vault-created">
                {{ __('Created.') }}
              </x-action-message>
            </div>
          </div>
        </form>
      </x-box>
    </section>
  </div>
</div>

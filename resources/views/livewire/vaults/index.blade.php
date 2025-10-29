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

        $vault = (new CreateVault(
            user: Auth::user(),
            name: $validated['name'],
            description: $validated['description'] ?? null)
        )->execute();

        $this->reset('name', 'description');

        $this->dispatch('vault-created');

        $this->redirect(route('vaults.show', $vault));
    }
}; ?>

<div x-data="{ showForm: false }">
  <div class="mx-auto max-w-lg px-2 py-2 sm:px-6 sm:py-6 lg:px-8">
    <section class="flex w-full flex-col gap-10">
      <!-- vaults list -->
      <div class="grid grid-cols-1 gap-10">
        @foreach ($vaults as $vault)
          <x-link :href="route('vaults.show', $vault)">
            <x-box class="group h-40 hover:bg-[#E4EEF3] hover:ring-1 hover:ring-gray-200 hover:dark:bg-[#202830] hover:dark:ring-gray-700" padding="p-0">
              <div class="relative h-full w-full overflow-hidden">
                <!-- Vault name -->
                <div class="absolute inset-0 z-10 flex items-center justify-center text-center">
                  <span class="text-lg font-semibold">{{ $vault->name }}</span>
                </div>

                <!-- Orbit circles -->
                <div class="absolute inset-0 flex items-center justify-center">
                  <div class="absolute h-64 w-64 rounded-full border border-gray-300"></div>
                  <div class="absolute h-96 w-96 rounded-full border border-gray-300"></div>
                </div>

                <!-- Avatars on orbits -->
                <div class="absolute inset-0">
                  <!-- Inner orbit avatars (h-64 w-64) -->
                  <div class="absolute top-1/2 left-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2">
                    <!-- Avatar 1 - Right -->
                    <div class="absolute top-1/2 -right-3 h-8 w-8 -translate-y-1/2 group-hover:animate-spin" style="animation-duration: 4s">
                      <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 text-xs font-bold text-white">A</div>
                    </div>
                    <!-- Avatar 2 - Left -->
                    <div class="absolute top-[40%] -left-3 h-8 w-8 -translate-y-1/2 group-hover:animate-spin" style="animation-duration: 4s; animation-delay: -2s">
                      <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-xs font-bold text-white">B</div>
                    </div>
                  </div>

                  <!-- Outer orbit avatars (h-96 w-96) -->
                  <div class="absolute top-1/2 left-1/2 h-96 w-96 -translate-x-1/2 -translate-y-1/2">
                    <!-- Avatar 1 - Top right -->
                    <div class="absolute top-[30%] right-[18%] h-6 w-6 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -1s">
                      <div class="flex h-6 w-6 items-center justify-center rounded-full bg-pink-500 text-xs font-bold text-white">F</div>
                    </div>
                    <!-- Avatar 2 - Right -->
                    <div class="absolute top-[59%] -right-[2%] h-6 w-6 -translate-y-1/2 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -2s">
                      <div class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-500 text-xs font-bold text-white">G</div>
                    </div>
                    <!-- Avatar 3 - Bottom right -->
                    <div class="absolute right-1/4 bottom-1/4 h-6 w-6 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -3s">
                      <div class="flex h-6 w-6 items-center justify-center rounded-full bg-orange-500 text-xs font-bold text-white">H</div>
                    </div>
                    <!-- Avatar 4 - Bottom left -->
                    <div class="absolute bottom-1/4 left-1/4 h-6 w-6 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -5s">
                      <div class="flex h-6 w-6 items-center justify-center rounded-full bg-cyan-500 text-xs font-bold text-white">J</div>
                    </div>
                    <!-- Avatar 5 - Left -->
                    <div class="absolute top-[55%] -left-[3%] h-6 w-6 -translate-y-1/2 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -2.5s">
                      <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">K</div>
                    </div>
                    <!-- Avatar 6 - Top left -->
                    <div class="absolute top-1/4 left-1/4 h-6 w-6 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -1.5s">
                      <div class="flex h-6 w-6 items-center justify-center rounded-full bg-violet-500 text-xs font-bold text-white">L</div>
                    </div>
                  </div>
                </div>
              </div>
            </x-box>
          </x-link>
        @endforeach
      </div>

      <x-box>
        <div class="flex justify-center" x-show="!showForm">
          <flux:button icon="plus-circle" @click="showForm = !showForm">{{ __('Add a vault') }}</flux:button>
        </div>

        <!-- create vault form -->
        <form method="POST" wire:submit="create" class="space-y-6" wire:cloak x-show="showForm" x-transition>
          <x-input wire:model="name" id="name" :label="__('Vault name')" type="text" required />
          <x-input wire:model="description" id="description" :label="__('Vault description')" type="text" :optional="true" />

          <div class="flex items-center justify-between">
            <flux:button @click="showForm = !showForm">{{ __('Cancel') }}</flux:button>

            <flux:button variant="primary" type="submit">
              {{ __('Create') }}
            </flux:button>

            <x-action-message class="me-3" on="vault-created">
              {{ __('Created') }}
            </x-action-message>
          </div>
        </form>
      </x-box>
    </section>
  </div>
</div>

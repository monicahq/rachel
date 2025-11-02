<?php

use App\Models\WebauthnKey;
use Illuminate\Support\Facades\Auth;
use LaravelWebauthn\Facades\Webauthn;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    public string $publicKey;

    /**
     * Indicates if the user is currently managing a WebauthnKey.
     */
    public bool $managingWebauthnKey = false;

    /**
     * The key that is currently having its permissions managed.
     */
    public ?WebauthnKey $managingWebauthnKeyFor = null;

    public ?string $updateKeyName = null;

    /**
     * Indicates if the application is confirming if a WebauthnKey should be deleted.
     */
    public bool $confirmingWebauthnKeyDeletion = false;

    /**
     * The ID of the WebauthnKey being deleted.
     */
    public ?int $webauthnKeyIdBeingDeleted = null;

    public function mount(): void
    {
        $this->publicKey = (string) Webauthn::prepareAttestation(Auth::user());
    }

    #[Computed]
    public function webauthnKeys()
    {
        return Auth::user()->webauthnKeys;
    }

    #[On('key-created')]
    public function updateKeys(): void
    {
        unset($this->webauthnKeys);
    }

    /**
     * Allow the given token's permissions to be managed.
     */
    public function manageWebauthnKey(int $id): void
    {
        $this->managingWebauthnKey = true;

        $this->managingWebauthnKeyFor = Auth::user()
            ->webauthnKeys()
            ->where('id', $id)
            ->firstOrFail();

        $this->updateKeyName = $this->managingWebauthnKeyFor->name;
    }

    /**
     * Update the API token's permissions.
     */
    public function updateWebauthnKey(): void
    {
        $validated = $this->validate([
            'updateKeyName' => ['required', 'string', 'max:255'],
        ]);

        $this->managingWebauthnKeyFor
            ->forceFill([
                'name' => $validated['updateKeyName'],
            ])
            ->save();

        $this->updateKeyName = null;

        $this->managingWebauthnKey = false;
    }

    /**
     * Confirm that the given API token should be deleted.
     */
    public function confirmWebauthnKeyDeletion(int $id): void
    {
        $this->confirmingWebauthnKeyDeletion = true;

        $this->webauthnKeyIdBeingDeleted = $id;
    }

    /**
     * Delete the API token.
     */
    public function deleteWebauthnKey(): void
    {
        Auth::user()
            ->webauthnKeys()
            ->where('id', $this->webauthnKeyIdBeingDeleted)
            ->first()
            ->delete();

        $this->updateKeys();

        $this->confirmingWebauthnKeyDeletion = false;

        $this->webauthnKeyIdBeingDeleted = null;
    }
}; ?>

<div>
  <h3 class="font-semi-bold mb-1 text-lg dark:text-white">
    {{ __('Register a new key') }}
  </h3>

  <livewire:auth.webauthn.registerkey :$publicKey :action="__('Register a new key')" :autoload="false" />

  @if ($this->webauthnKeys->count() > 0)
    <div class="mt-4 dark:text-white">
      <h3 class="font-semi-bold mb-1 text-lg dark:text-white">
        {{ __('Your keys:') }}
      </h3>

      <!-- API Token Permissions Modal -->
      <x-dialog-modal wire:model.live="managingWebauthnKey">
        <x-slot name="title">
          {{ __('Update key') }}
        </x-slot>

        <x-slot name="content">
          <div class="mt-2 mb-4 flex gap-4 *:gap-x-2">
            <x-input type="text" wire:model="updateKeyName" id="name" :label="__('Key name')" />
          </div>
        </x-slot>

        <x-slot name="footer">
          <flux:button variant="filled" wire:click="$set('managingWebauthnKey', false)" wire:loading.attr="disabled">
            {{ __('Cancel') }}
          </flux:button>

          <flux:button variant="primary" class="ms-3" wire:click="updateWebauthnKey" wire:loading.attr="disabled">
            {{ __('Save') }}
          </flux:button>
        </x-slot>
      </x-dialog-modal>

      <!-- Delete Token Confirmation Modal -->
      <x-confirmation-modal wire:model.live="confirmingWebauthnKeyDeletion">
        <x-slot name="title">
          {{ __('Delete key') }}
        </x-slot>

        <x-slot name="content">
          {{ __('Are you sure you would like to delete this key?') }}
        </x-slot>

        <x-slot name="footer">
          <flux:button variant="filled" wire:click="$toggle('confirmingWebauthnKeyDeletion')" wire:loading.attr="disabled">
            {{ __('Cancel') }}
          </flux:button>

          <flux:button variant="danger" class="ms-3" wire:click="deleteWebauthnKey" wire:loading.attr="disabled">
            {{ __('Delete') }}
          </flux:button>
        </x-slot>
      </x-confirmation-modal>

      <div class="md:grid md:grid-cols-2 md:gap-6">
        <div class="mt-5 md:col-span-2 md:mt-0">
          <!-- Webauthn key list -->
          @foreach ($this->webauthnKeys as $webauthnKey)
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-4 last:border-b-0 dark:border-gray-700" wire:key="{{ $webauthnKey->id }}">
              <div class="break-all dark:text-white">
                {{ $webauthnKey->name }}
              </div>

              @if ($webauthnKey->used_at)
                <div class="me-2 text-sm text-gray-400">{{ __('Last used') }} {{ $webauthnKey->used_at->diffForHumans() }}</div>
              @endif

              <div class="ms-2 flex items-center">
                <flux:button size="xs" variant="ghost" class="me-2" wire:click="manageWebauthnKey({{ $webauthnKey->id }})">
                  {{ __('Edit') }}
                </flux:button>

                <flux:button size="xs" variant="ghost" class="text-red-900" wire:click="confirmWebauthnKeyDeletion({{ $webauthnKey->id }})">
                  {{ __('Delete') }}
                </flux:button>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  @endif
</div>

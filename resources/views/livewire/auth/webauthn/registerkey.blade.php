<?php

use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    public string $publicKey;

    public string $name = '';

    public bool $processing = false;

    public string $errorMessage = '';

    public string $action = '';

    public bool $autoload = false;

    public function mount(string $publicKey, string $action, bool $autoload = false, string $name = ''): void
    {
        $this->publicKey = $publicKey;
        $this->action = $action;
        $this->autoload = $autoload;
        $this->name = $name;
    }

    #[On('start-registration')]
    public function registerKey(): void
    {
        $this->errorMessage = '';
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $this->js('start');
    }

    #[On('force-registration')]
    public function registerName(string $name): void
    {
        $validated = Illuminate\Support\Facades\Validator::validate(
            ['name' => $name],
            [
                'name' => ['required', 'string', 'max:255'],
            ],
        );
        $this->name = $validated['name'];

        $this->js('start');
    }
}; ?>

<div>
  <form method="POST" wire:submit="registerKey" class="space-y-6">
    <x-input type="text" wire:model="name" id="name" :label="__('Key name')" />

    <div class="flex items-center gap-4">
      <div class="flex items-center justify-end">
        <flux:button variant="primary" type="submit" class="w-full">
          {{ $action }}
        </flux:button>
      </div>

      <x-action-message class="me-3" on="key-created">
        {{ __('Created.') }}
      </x-action-message>
    </div>

    <div wire:show="processing" class="mt-4 mb-4 flex rounded-lg border border-gray-300 bg-gray-500/30 px-4 py-4 shadow-md dark:border-gray-700">
      <div class="me-2 text-teal-800 dark:text-teal-200">
        <flux:icon.loading />
      </div>
      <p class="font-bold text-green-100">
        {{ __('Waiting for input from browser interaction…') }}
      </p>
    </div>

    <div wire:show="errorMessage">
      <div class="relative mt-4 mb-4 rounded-sm border border-red-400/30 bg-red-100/10 px-4 py-3 dark:border-red-600/30 dark:bg-red-900/10">
        <span class="flex font-bold text-red-700/80 dark:text-red-300/80" wire:text="errorMessage"></span>
      </div>
      <flux:button icon="arrow-path" wire:show="!processing" @click="$dispatch('start-registration');" variant="primary" color="sky">
        {{ __('Retry') }}
      </flux:button>
    </div>
  </form>
</div>

@assets
  @vite(['resources/js/webauthn.js'])
@endassets

@script
  <script>
    const formatErrorMessage = (name, message) => {
      switch (name) {
        case 'InvalidStateError':
          return '{{ __('This key is already registered. It’s not necessary to register it again.') }}';
        case 'NotAllowedError':
          return '{{ __('The operation either timed out or was not allowed.') }}';
        case 'SecurityError':
          return '{{ __('The operation is insecure.') }}';
        case 'not_supported':
          return !window.isSecureContext && window.location.hostname !== 'localhost' && window.location.hostname !== '127.0.0.1' ? '{{ __('WebAuthn only supports secure connections. Please load this page with https scheme.') }}' : '{{ __('Your browser doesn’t currently support WebAuthn.') }}';
        default:
          return message;
      }
    };

    $js('start', () => {
      if (!Webauthn.browserSupportsWebAuthn()) {
        $wire.$set('errorMessage', formatErrorMessage('not_supported'));
        return;
      }

      $wire.processing = true;
      Webauthn.startRegistration({ optionsJSON: JSON.parse($wire.publicKey) })
        .then((data) => {
          webauthnRegisterCallback(data);
        })
        .catch((error) => {
          $wire.processing = false;
          $wire.$set('errorMessage', formatErrorMessage(error.name, error.message));
        });
    });

    const webauthnRegisterCallback = (data) => {
      axios
        .post('{{ route('webauthn.store') }}', {
          name: $wire.name,
          ...data,
        })
        .then((response) => {
          $wire.processing = false;
          $wire.name = '';
          Livewire.dispatch('key-created', response.result);
        })
        .catch((error) => {
          $wire.processing = false;
          $wire.$set('errorMessage', error.message);
        });
    };
  </script>
@endscript

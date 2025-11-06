<?php

use App\Providers\Webauthn\PasskeyCredentialRepository;
use App\Providers\Webauthn\SecurityKeyCredentialRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;
use LaravelWebauthn\Facades\Webauthn;
use LaravelWebauthn\Services\Webauthn\CredentialRepository;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component
{
    public string $action;

    public string $publicKey;

    public bool $processing = false;

    public bool $success = false;

    public string $errorMessage = '';

    public bool $autofill;

    public string $keyKind;

    public function mount(string $action, bool $autofill = false, string $keyKind = 'passkey'): void
    {
        $this->action = $action;
        $this->autofill = $autofill;
        $this->keyKind = $keyKind;
    }

    #[On('start-authenticate')]
    public function start(): void
    {
        $this->bind();

        if (($user = Auth::user()) === null) {
            $request = TwoFactorLoginRequest::createFrom(request());
            if ($request->hasChallengedUser()) {
                $user = $request->challengedUser();
            }
        }

        $this->publicKey = Webauthn::prepareAssertion($user);
        $this->errorMessage = '';
        $this->js('start()');
    }

    #[On('webauthn-stop')]
    public function stop(string $message): void
    {
        if ($this->processing) {
            $this->processing = false;
            $this->errorMessage = $message;
        }
    }

    public function callback(array $data): void
    {
        $this->dispatch('webauthn-authenticate', $data);
    }

    #[On('login-error')]
    public function error(string $message): void
    {
        $this->processing = false;
        $this->errorMessage = $message;
    }

    public function bind(): void
    {
        if ($this->keyKind === 'security') {
            App::bind(CredentialRepository::class, SecurityKeyCredentialRepository::class);
        } elseif ($this->keyKind === 'passkey') {
            App::bind(CredentialRepository::class, PasskeyCredentialRepository::class);
        }
    }
}; ?>

<div wire:cloak x-data="{ run: false }">
  <flux:button icon="key" x-show="!run" @click="run = true; $wire.start()" variant="primary" color="sky" class="w-full">
    {{ $action }}
  </flux:button>

  <div x-show="(run || $wire.processing) && ! $wire.errorMessage" class="mt-4 mb-4 flex rounded-lg border border-gray-300 bg-gray-500/30 px-4 py-8 shadow-md dark:border-gray-700">
    <div class="me-2 text-teal-800 dark:text-teal-200">
      <flux:icon.loading />
    </div>
    <p class="font-bold text-green-100">
      {{ __('Waiting for input from browser interaction…') }}
    </p>
  </div>
  <div wire:show="success" class="mt-4 mb-4 flex rounded-lg border border-green-300 bg-green-500/30 px-4 py-8 shadow-md dark:border-green-700" x-transition:leave.opacity.duration.1500ms style="display: none">
    <p class="font-bold">
      {{ __('Authenticated.') }}
    </p>
  </div>

  <div wire:show="errorMessage">
    <div class="relative mt-4 mb-4 rounded-sm border border-red-400/30 bg-red-100/10 px-4 py-3 dark:border-red-600/30 dark:bg-red-900/10">
      <span class="flex font-bold text-red-700/80 dark:text-red-300/80" wire:text="errorMessage"></span>
    </div>
    <flux:button icon="arrow-path" wire:show="!processing" @click="$wire.start()" variant="primary" color="sky">
      {{ __('Retry') }}
    </flux:button>
  </div>
  @error('email')
    <div class="relative mt-4 mb-4 rounded-sm border border-red-400/30 bg-red-100/10 px-4 py-3 dark:border-red-600/30 dark:bg-red-900/10">
      <span class="flex font-bold text-red-700/80 dark:text-red-300/80">
        {{ $message }}
      </span>
    </div>
    <flux:button icon="arrow-path" wire:show="!processing" @click="$wire.start()" variant="primary" color="sky">
      {{ __('Retry') }}
    </flux:button>
  @enderror
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

    const loginWaitForKey = async (publicKey) => {
      await Webauthn.browserSupportsWebAuthnAutofill()
        .then((available) => {
          return Webauthn.startAuthentication({
            optionsJSON: publicKey,
            useBrowserAutofill: $wire.autofill && available,
          });
        })
        .then((data) => $wire.callback(data))
        .catch((error) => {
          stop();
          $wire.errorMessage = formatErrorMessage(error.name, error.message);
        });
    };

    $js('start', async () => {
      if (!Webauthn.browserSupportsWebAuthn()) {
        stop();
        $wire.errorMessage = formatErrorMessage('not_supported');
      } else {
        $wire.processing = true;
        await loginWaitForKey(JSON.parse($wire.publicKey));
      }
    });

    const stop = () => {
      $wire.processing = false;
    };
  </script>
@endscript

<?php

use App\Models\User;
use App\Services\CreateAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use LaravelWebauthn\Facades\Webauthn;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $passwordless = true;

    public string $publicKey = '';

    public string $errorMessage = '';

    public bool $processing = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        if ($this->passwordless) {
            $this->js('check');
        } else {
            $this->continue();
        }
    }

    #[On('continue-register')]
    public function continue(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'disposable_email'],
            'password' => [new Rules\RequiredIf(! $this->passwordless), 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = new CreateAccount(
            email: $validated['email'],
            password: $validated['password'],
            name: $validated['name'],
        )->execute();

        event(new Registered($user));

        Auth::login($user);

        if ($this->passwordless) {
            $this->publicKey = (string) Webauthn::prepareAttestation($user);

            $this->js('start');
        } else {
            Session::regenerate();

            $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
        }
    }

    public function restart(): void
    {
        $this->errorMessage = '';
        $this->publicKey = (string) Webauthn::prepareAttestation(Auth::user());

        $this->js('start');
    }

    #[On('key-created')]
    public function endRegister(): void
    {
        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="grid min-h-screen w-screen grid-cols-1 lg:grid-cols-2">
  <!-- Left side - Register form -->
  <div class="mx-auto flex w-full max-w-2xl flex-1 flex-col justify-center gap-y-10 px-5 py-10 sm:px-30">
    <p class="group flex items-center gap-x-1 text-sm text-gray-600">
      <x-phosphor-arrow-left class="h-4 w-4 transition-transform duration-150 group-hover:-translate-x-1 dark:text-gray-100" />
      <x-link href="" class="group-hover:underline dark:text-gray-100">{{ __('Back to the marketing website') }}</x-link>
    </p>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <!-- Title -->
    <div class="flex items-center gap-x-2">
      <a href="" class="group flex items-center gap-x-2 transition-transform ease-in-out">
        <div class="flex h-7 w-7 items-center justify-center transition-all duration-400 group-hover:-translate-y-0.5 group-hover:-rotate-3">
          <x-app-logo-icon />
        </div>
      </a>
      <h1 class="text-2xl font-semibold text-gray-900 dark:text-neutral-200">
        {{ __('Sign up for an account') }}
      </h1>
    </div>

    <!-- Register form -->
    <x-box>
      <form method="POST" wire:submit="register" class="flex flex-col gap-4">
        <!-- Name -->
        <x-input wire:model="name" id="name" :label="__('Name')" type="text" required autofocus autocomplete="username webauthn" :placeholder="__('Full name')" />

        <!-- Email Address -->
        <x-input wire:model="email" id="email" :label="__('Email address')" type="email" required autocomplete="email" placeholder="email@example.com" />

        <div wire:show="!passwordless" x-transition.duration.100ms>
          <!-- Password -->
          <x-input wire:model="password" id="password" :label="__('Password')" type="password" autocomplete="new-password" :placeholder="__('Password')" />

          <!-- Confirm Password -->
          <x-input wire:model="password_confirmation" id="password_confirmation" :label="__('Confirm password')" type="password" autocomplete="new-password" :placeholder="__('Confirm password')" />
        </div>

        <div>
          {{ __('A passkey is a faster and safer way to sign in than a password.') }}
          {{ __('Your account is created with a passkey unless you choose to create a password.') }}
        </div>

        <x-link class="cursor-pointer" wire:click="$toggle('passwordless')">
          <span wire:show="passwordless">
            {{ __('Signin with a passkey') }}
          </span>
          <span wire:show="!passwordless">
            {{ __('Signin with a password') }}
          </span>
        </x-link>

        <div class="flex items-center justify-between">
          <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
            {{ __('Create account') }}
          </flux:button>
        </div>
      </form>

      <div wire:show="errorMessage" x-transition.duration.100ms>
        <div class="relative mt-4 mb-4 rounded-sm border border-red-400/30 bg-red-100/10 px-4 py-3 dark:border-red-600/30 dark:bg-red-900/10">
          <span class="flex font-bold text-red-700/80 dark:text-red-300/80" wire:text="errorMessage"></span>
        </div>
        <form method="POST" wire:submit="restart">
          <flux:button icon="arrow-path" wire:show="passwordless" variant="primary" type="submit" color="sky">
            {{ __('Retry') }}
          </flux:button>
        </form>
      </div>
    </x-box>

    <!-- Register link -->
    <x-box class="text-center text-sm">
      {{ __('Already have an account?') }}
      <x-link :href="route('login')">{{ __('Log in') }}</x-link>
    </x-box>

    <ul class="text-xs text-gray-600">
      <li>&copy; {{ config('app.name') }} {{ now()->format('Y') }}</li>
    </ul>
  </div>

  <!-- Right side - Image -->
  <div class="relative hidden bg-gray-400 lg:block dark:bg-gray-600">
    <!-- Quote Box -->
    <div class="absolute inset-0 flex items-center justify-center">bla</div>
  </div>
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

    $js('check', () => {
      if (!Webauthn.browserSupportsWebAuthn()) {
        $wire.errorMessage = formatErrorMessage('not_supported');
        $wire.passwordless = false;
      } else {
        Livewire.dispatch('continue-register');
      }
    });

    $js('start', () => {
      if (!Webauthn.browserSupportsWebAuthn()) {
        $wire.errorMessage = formatErrorMessage('not_supported');
        return;
      }

      $wire.processing = true;
      Webauthn.startRegistration({ optionsJSON: JSON.parse($wire.publicKey) })
        .then((data) => {
          webauthnRegisterCallback(data);
        })
        .catch((error) => {
          $wire.processing = false;
          $wire.errorMessage = formatErrorMessage(error.name, error.message);
        });
    });

    const webauthnRegisterCallback = (data) => {
      axios
        .post('{{ route('webauthn.store') }}', {
          name: $wire.name,
          ...data,
        })
        .then((response) => {
          location.assign('/dashboard');
          /*Livewire.dispatch('key-created');*/
        })
        .catch((error) => {
          $wire.processing = false;
          $wire.errorMessage = error.message;
        });
    };
  </script>
@endscript

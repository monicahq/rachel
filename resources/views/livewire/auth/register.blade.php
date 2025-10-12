<?php

use App\Models\User;
use App\Services\CreateAccount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
  public string $name = '';

  public string $email = '';

  public string $password = '';

  public string $password_confirmation = '';

  /**
   * Handle an incoming registration request.
   */
  public function register(): void
  {
    $validated = $this->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class, 'disposable_email'],
      'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = new CreateAccount(
      email: $validated['email'],
      password: $validated['password'],
      name: $validated['name'],
    )->execute();

    event(new Registered($user));

    Auth::login($user);

    Session::regenerate();

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
          <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
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
        <x-input wire:model="name" id="name" :label="__('Name')" type="text" required autofocus autocomplete="name" :placeholder="__('Full name')" />

        <!-- Email Address -->
        <x-input wire:model="email" id="email" :label="__('Email address')" type="email" required autocomplete="email" placeholder="email@example.com" />

        <!-- Password -->
        <x-input wire:model="password" id="password" :label="__('Password')" type="password" required autocomplete="new-password" :placeholder="__('Password')" />

        <!-- Confirm Password -->
        <x-input wire:model="password_confirmation" id="password_confirmation" :label="__('Confirm password')" type="password" required autocomplete="new-password" :placeholder="__('Confirm password')" />

        <div class="flex items-center justify-between">
          <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
            {{ __('Create account') }}
          </flux:button>
        </div>
      </form>
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
  <div class="relative hidden bg-gray-400 lg:block">
    <!-- Quote Box -->
    <div class="absolute inset-0 flex items-center justify-center">bla</div>
  </div>
</div>

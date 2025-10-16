<?php

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component {
  #[Validate('required|string|email')]
  public string $email = '';

  #[Validate('required|string')]
  public string $password = '';

  public bool $remember = false;

  /**
   * Handle an incoming authentication request.
   */
  public function login(): void
  {
    $this->validate();

    $this->ensureIsNotRateLimited();

    $user = $this->validateCredentials();

    if (Features::canManageTwoFactorAuthentication() && $user->hasEnabledTwoFactorAuthentication()) {
      Session::put([
        'login.id' => $user->getKey(),
        'login.remember' => $this->remember,
      ]);

      $this->redirect(route('two-factor.login'), navigate: true);

      return;
    }

    Auth::login($user, $this->remember);

    RateLimiter::clear($this->throttleKey());
    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
  }

  /**
   * Validate the user's credentials.
   */
  protected function validateCredentials(): User
  {
    $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email, 'password' => $this->password]);

    if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
      RateLimiter::hit($this->throttleKey());

      throw ValidationException::withMessages([
        'email' => __('auth.failed'),
      ]);
    }

    return $user;
  }

  /**
   * Ensure the authentication request is not rate limited.
   */
  protected function ensureIsNotRateLimited(): void
  {
    if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
      return;
    }

    event(new Lockout(request()));

    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
      'email' => __('auth.throttle', [
        'seconds' => $seconds,
        'minutes' => ceil($seconds / 60),
      ]),
    ]);
  }

  /**
   * Get the authentication rate limiting throttle key.
   */
  protected function throttleKey(): string
  {
    return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
  }
}; ?>

<div class="grid min-h-screen w-screen grid-cols-1 lg:grid-cols-2">
  <!-- Left side - Login form -->
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
        {{ __('Welcome back') }}
      </h1>
    </div>

    <!-- login form -->
    <x-box>
      <form method="POST" wire:submit="login" class="flex flex-col gap-4">
        <!-- Email address -->
        <x-input wire:model="email" id="email" :label="__('Email address')" type="email" required autofocus autocomplete="email" placeholder="email@example.com" />

        <!-- Password -->
        <x-input wire:model="password" id="password" :label="__('Password')" type="password" required autocomplete="current-password" :placeholder="__('Password')" />

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

        <div class="flex items-center justify-between">
          <x-link :href="route('password.request')" class="text-sm">
            {{ __('Forgot your password?') }}
          </x-link>

          <flux:button variant="primary" type="submit" data-test="login-button">
            {{ __('Log in') }}
          </flux:button>
        </div>
      </form>
    </x-box>

    <!-- Register link -->
    <x-box class="text-center text-sm">
      {{ __('New to :name?', ['name' => config('app.name')]) }}
      <x-link :href="route('register')">{{ __('Sign up') }}</x-link>
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

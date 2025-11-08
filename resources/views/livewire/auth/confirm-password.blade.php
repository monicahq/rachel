<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component {
  #[On('webauthn-authenticate')]
  public function confirm(?array $data = null): void
  {
    if (Auth::getProvider()->validateCredentials(Auth::user(), $data)) {
      session(['auth.password_confirmed_at' => Date::now()->unix()]);
      $this->redirectIntended(navigate: true);
    } else {
      $this->dispatch('webauthn-stop', __('Not authorized'));
    }
  }
}; ?>

<div class="flex flex-col gap-6">
  <x-auth-header :title="__('Confirm password')" :description="__('This is a secure area of the application. Please confirm your password before continuing.')" />

  <x-auth-session-status class="text-center" :status="session('status')" />

  <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
    @csrf

    <x-input name="password" id="password" :label="__('Password')" type="password" required autocomplete="current-password" :placeholder="__('Password')" />

    <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
      {{ __('Confirm') }}
    </flux:button>
  </form>

  <fieldset class="mt-6 border-t border-gray-300 dark:border-gray-700">
    <legend class="mx-auto px-4 text-sm dark:text-white">
      {{ __('Or') }}
    </legend>
  </fieldset>

  <div class="mt-4">
    <livewire:auth.webauthn.authenticate :action="__('Use passkey or security key')" />
  </div>
</div>

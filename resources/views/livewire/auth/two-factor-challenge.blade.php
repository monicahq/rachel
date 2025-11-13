<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Fortify\Events\ValidTwoFactorAuthenticationCodeProvided;
use Laravel\Fortify\Http\Requests\TwoFactorLoginRequest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component {
  #[On('webauthn-authenticate')]
  public function confirm(?array $data = null): void
  {
    $request = TwoFactorLoginRequest::createFrom(request());
    $user = $request->challengedUser();

    if ($user instanceof User && Auth::getProvider()->validateCredentials($user, $data)) {
      event(new ValidTwoFactorAuthenticationCodeProvided($user));
      Auth::login($user, true);
      Session::regenerate();

      $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    } else {
      $this->dispatch('webauthn-stop', __('Not authorized'));
    }
  }
}; ?>

<div class="flex flex-col gap-6">
  <div class="relative h-auto w-full" x-cloak x-data="{
    showRecoveryInput: @js($errors->has('recovery_code')),
    code: '',
    recovery_code: '',
    toggleInput() {
      this.showRecoveryInput = ! this.showRecoveryInput

      this.code = ''
      this.recovery_code = ''

      $dispatch('clear-2fa-auth-code')

      $nextTick(() => {
        this.showRecoveryInput
          ? this.$refs.recovery_code?.focus()
          : $dispatch('focus-2fa-auth-code')
      })
    },
  }">
    <div x-show="!showRecoveryInput">
      <x-auth-header :title="__('Authentication Code')" :description="__('Enter the authentication code provided by your authenticator application.')" />
    </div>

    <div x-show="showRecoveryInput">
      <x-auth-header :title="__('Recovery Code')" :description="__('Please confirm access to your account by entering one of your emergency recovery codes.')" />
    </div>

    <form method="POST" action="{{ route('two-factor.login.store') }}">
      @csrf

      <div class="space-y-5 text-center">
        <div x-show="!showRecoveryInput">
          <div class="my-5 flex items-center justify-center">
            <x-input-otp name="code" digits="6" autocomplete="one-time-code" x-model="code" />
          </div>

          @error('code')
            <flux:text color="red">
              {{ $message }}
            </flux:text>
          @enderror
        </div>

        <div x-show="showRecoveryInput">
          <div class="my-5">
            <x-input type="text" id="recovery_code" name="recovery_code" x-ref="recovery_code" x-bind:required="showRecoveryInput" autocomplete="one-time-code" x-model="recovery_code" />
          </div>
        </div>

        <flux:button variant="primary" type="submit" class="w-full">
          {{ __('Continue') }}
        </flux:button>
      </div>

      <div class="mt-5 space-x-0.5 text-center text-sm leading-5">
        <span class="opacity-50">{{ __('or you can') }}</span>
        <div class="inline cursor-pointer font-medium underline opacity-80">
          <span x-show="!showRecoveryInput" @click="toggleInput()">{{ __('login using a recovery code') }}</span>
          <span x-show="showRecoveryInput" @click="toggleInput()">{{ __('login using an authentication code') }}</span>
        </div>
      </div>
    </form>
  </div>

  <fieldset class="border-t border-gray-300 dark:border-gray-700">
    <legend class="mx-auto px-4 text-sm dark:text-white">
      {{ __('Or') }}
    </legend>
  </fieldset>

  <div class="mt-4">
    <livewire:auth.webauthn.authenticate :action="__('Use passkey or security key')" />
  </div>
</div>

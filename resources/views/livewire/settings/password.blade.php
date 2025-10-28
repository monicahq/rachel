<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app.settings')] class extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
  <x-box>
    <x-slot:title>
      {{ __('Password management') }}
    </x-slot>

    <x-slot:description>
      {{ __('Update your password to secure your account.') }}
    </x-slot>
    <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
      <x-input wire:model="current_password" id="current_password" :label="__('Current password')" type="password" required autocomplete="current-password" />
      <x-input wire:model="password" id="password" :label="__('New password')" type="password" required autocomplete="new-password" />
      <x-input wire:model="password_confirmation" id="password_confirmation" :label="__('Confirm Password')" type="password" required autocomplete="new-password" />

      <div class="flex items-center gap-4">
        <div class="flex items-center justify-end">
          <flux:button variant="primary" type="submit" class="w-full" data-test="update-password-button">
            {{ __('Save') }}
          </flux:button>
        </div>

        <x-action-message class="me-3" on="password-updated">
          {{ __('Saved.') }}
        </x-action-message>
      </div>
    </form>
  </x-box>
</section>

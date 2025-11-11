<?php

use App\Models\Account;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Illuminate\Support\Carbon;

use function Livewire\Volt\layout;
use function Livewire\Volt\mount;
use function Livewire\Volt\state;
use function Livewire\Volt\title;

layout('components.layouts.instance');
title(fn (): string => __('Instance management'));

state(['user', 'account', 'password', 'activities']);

mount(function (Account $account): void {
  $this->account = $account;
  $this->user = $account->users()->first();
  $this->activities = collect([
    [
      'action' => 'Account upgraded to Pro plan',
      'status' => 'Upgrade',
      'icon' => 'arrow-up',
      'description' => 'Upgraded from Basic to Pro subscription with additional features',
      'actor' => 'System',
      'created_at' => \Illuminate\Support\Facades\Date::parse('2025-01-15 14:30:00'),
      'color' => 'green',
    ],

    [
      'action' => 'Profile information updated',
      'icon' => 'pencil',
      'description' => 'Updated email address and phone number',
      'actor' => 'John Doe',
      'created_at' => \Illuminate\Support\Facades\Date::parse('2025-01-14 16:15:00'),
      'color' => 'blue',
    ],

    [
      'action' => 'Two-factor authentication enabled',
      'status' => 'Security',
      'icon' => 'shield-check',
      'description' => 'Enhanced account security with 2FA',
      'actor' => 'John Doe',
      'created_at' => \Illuminate\Support\Facades\Date::parse('2025-01-12 10:45:00'),
      'color' => 'purple',
    ],

    [
      'action' => 'Payment method updated',
      //'icon' => 'credit-card',
      'description' => 'Added new credit card ending in 4242',
      'actor' => 'John Doe',
      'created_at' => \Illuminate\Support\Facades\Date::parse('2025-01-08 15:20:00'),
      'color' => 'orange',
    ],

    [
      'action' => 'Account created',
      'status' => 'Created',
      'icon' => 'user-plus',
      'description' => 'New account registered with basic plan',
      'actor' => 'John Doe',
      'created_at' => \Illuminate\Support\Facades\Date::parse('2025-01-01 09:00:00'),
      'color' => 'gray',
      'last' => true,
    ],
  ]);
});

$freeAccount = function (): void {
  $this->validate([
    'password' => ['required', 'string', 'current_password'],
  ]);

  // TODO

  $this->redirect(route('instances.accounts.show', $this->account), navigate: true);
};

$reset2fa = function (DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void {
  $this->validate([
    'password' => ['required', 'string', 'current_password'],
  ]);

  $disableTwoFactorAuthentication($this->user);

  $this->redirect(route('instances.accounts.show', $this->account), navigate: true);
};

$deleteAccount = function (): void {
  abort_if($this->user->id === Illuminate\Support\Facades\Auth::user()->id, 403);

  $this->validate([
    'password' => ['required', 'string', 'current_password'],
  ]);

  $this->account->delete();

  $this->redirect(route('instances.index'), navigate: true);
};
?>

<div>
  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('dashboard')],
    ['label' => __('Instance management'), 'route' => route('instances.index')],
    ['label' => __('Account')]
  ]" />

  <div class="mx-auto max-w-5xl space-y-6 px-2 py-2 sm:px-0 sm:py-10">
    @include('livewire.instances.accounts.partials.account-header', ['user' => $user])

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[250px_1fr]">
      <div class="space-y-6">
        <!-- sidebar -->
        @include('livewire.instances.accounts.partials.account-sidebar')

        <!-- actions -->
        <div class="space-y-2">
          <flux:modal.trigger name="free-account">
            <flux:button icon="gift" variant="primary" class="mb-2 w-full">
              {{ __('Give free account') }}
            </flux:button>
          </flux:modal.trigger>

          <flux:modal.trigger name="reset-account-2fa">
            <flux:button icon="key" variant="outline" class="mb-2 w-full">
              {{ __('Reset 2FA') }}
            </flux:button>
          </flux:modal.trigger>

          <flux:modal.trigger name="confirm-account-deletion">
            <flux:button icon="trash" variant="outline" class="w-full">
              {{ __('Delete account') }}
            </flux:button>
          </flux:modal.trigger>
        </div>
      </div>

      <!-- main content -->
      <div class="space-y-6">
        <x-box title="Activity Timeline">
          @foreach ($activities as $activity)
            @include('livewire.instances.accounts.partials.activity', $activity)
          @endforeach
        </x-box>
      </div>

      <flux:modal name="free-account" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="freeAccount" class="space-y-6">
          <div>
            <flux:heading size="lg">{{ __('Are you sure you want to upgrade this account to a free account?') }}</flux:heading>

            <flux:subheading>
              {{ __('Please enter your password to confirm you would like to upgrade the account.') }}
            </flux:subheading>
          </div>

          <x-input wire:model="password" :label="__('Password')" type="password" />

          <div class="flex justify-end space-x-2 rtl:space-x-reverse">
            <flux:modal.close>
              <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="danger" type="submit" data-test="confirm-reset-account-2fa">
              {{ __('Upgrade account') }}
            </flux:button>
          </div>
        </form>
      </flux:modal>

      <flux:modal name="reset-account-2fa" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="reset2fa" class="space-y-6">
          <div>
            <flux:heading size="lg">{{ __('Are you sure you want to disable this account\'s 2fa?') }}</flux:heading>

            <flux:subheading>
              {{ __('When 2fa is disable, the user will be able to login with their password only, and the account will be less secure.') }}
              {{ __('Please enter your password to confirm you would like to deactivate the account\'s 2fa.') }}
            </flux:subheading>
          </div>

          <x-input wire:model="password" :label="__('Password')" type="password" />

          <div class="flex justify-end space-x-2 rtl:space-x-reverse">
            <flux:modal.close>
              <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="danger" type="submit" data-test="confirm-reset-account-2fa">
              {{ __('Reset 2fa') }}
            </flux:button>
          </div>
        </form>
      </flux:modal>

      <flux:modal name="confirm-account-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="deleteAccount" class="space-y-6">
          <div>
            <flux:heading size="lg">{{ __('Are you sure you want to delete this account?') }}</flux:heading>

            <flux:subheading>
              {{ __('Once the account is deleted, all of its resources and data will be permanently deleted.') }}
              {{ __('Please enter your password to confirm you would like to permanently delete this account.') }}
            </flux:subheading>
          </div>

          <x-input wire:model="password" :label="__('Password')" type="password" />

          <div class="flex justify-end space-x-2 rtl:space-x-reverse">
            <flux:modal.close>
              <flux:button variant="filled">{{ __('Cancel') }}</flux:button>
            </flux:modal.close>

            <flux:button variant="danger" type="submit" data-test="confirm-delete-account-button">
              {{ __('Delete account') }}
            </flux:button>
          </div>
        </form>
      </flux:modal>
    </div>
  </div>
</div>

<?php

use App\Models\Account;

use function Livewire\Volt\layout;
use function Livewire\Volt\title;
use function Livewire\Volt\with;

layout('components.layouts.instance');
title(fn (): string => __('Instance management'));

with(
  fn (): array => [
    'total_accounts' => fn () => Account::count(),
    'accounts_last_30' => fn () => Account::where('created_at', '>=', now()->addDays(-30))->count(),
    'last_accounts' => fn () => Account::orderByDesc('created_at')
      ->take(20)
      ->get(),
  ],
);
?>

<div>
  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('dashboard')],
    ['label' => __('Instance management')]
  ]" />

  <div class="mx-auto max-w-5xl space-y-6 px-2 py-2 sm:px-0 sm:py-10">
    <!-- stats -->
    <div class="grid grid-cols-1 gap-2 sm:gap-6 lg:grid-cols-3">
      <x-box>
        <p class="mb-1 text-sm text-gray-500">Total accounts</p>
        <p class="text-2xl font-semibold">{{ $total_accounts }}</p>
      </x-box>

      <x-box>
        <p class="mb-1 text-sm text-gray-500">New accounts last 30 days</p>
        <p class="text-2xl font-semibold">{{ $accounts_last_30 }}</p>
      </x-box>

      <x-box>
        <p class="mb-1 text-sm text-gray-500">Active accounts last 30 days</p>
        <p class="text-2xl font-semibold">1000</p>
      </x-box>
    </div>

    <x-box title="Latest accounts" description="These are the latest accounts created on the instance." padding="p-0">
      <x-table>
        <x-table.rows>
          @foreach ($last_accounts as $account)
            <x-table.row wire:key="{{ $account->id }}">
              <x-table.cell>
                <x-keyboard>{{ $account->id }}</x-keyboard>
              </x-table.cell>

              <x-table.cell>
                <span class="block truncate text-ellipsis">
                  <x-link href="{{ route('instances.accounts.show', $account) }}">
                    {{ $account->users()->first()->name }}
                    ({{ $account->users()->first()->email }})
                  </x-link>
                </span>
              </x-table.cell>

              <x-table.cell class="hidden sm:inline-block">
                {{ $account->created_at }}
              </x-table.cell>

              <x-table.cell class="hidden sm:inline-block">
                <x-badge color="green">Paid</x-badge>
              </x-table.cell>
            </x-table.row>
          @endforeach
        </x-table.rows>
      </x-table>
    </x-box>
  </div>
</div>

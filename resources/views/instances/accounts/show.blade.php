<x-layouts.instance :title="__('Instance management')">
  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('dashboard')],
    ['label' => __('Instance management'), 'route' => route('instances.index')],
    ['label' => __('Account')]
  ]" />

  <div class="mx-auto w-5xl space-y-6 py-10">
    @include('instances.accounts.partials.account-header')

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[250px_1fr]">
      <div class="space-y-6">
        <!-- sidebar -->
        @include('instances.accounts.partials.account-sidebar')

        <!-- actions -->
        <div class="space-y-2">
          <flux:button icon="gift" type="submit" variant="primary" class="w-full">
            {{ __('Give free account') }}
          </flux:button>
          <flux:button icon="key" type="submit" variant="outline" class="w-full">
            {{ __('Reset 2FA') }}
          </flux:button>
          <flux:button icon="trash" type="submit" variant="outline" class="w-full">
            {{ __('Delete account') }}
          </flux:button>
        </div>
      </div>

      <!-- main content -->
      <div class="space-y-6">
        <x-box title="Activity Timeline">
          <!-- Event 1: Account upgraded -->
          <div class="relative flex gap-x-3">
            <div class="absolute top-8 left-5 h-full w-0.5 bg-gray-200 dark:bg-neutral-700"></div>
            <div class="relative flex h-10 w-10 items-center justify-center">
              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30">
                <x-phosphor-arrow-up class="size-4 text-green-600 dark:text-green-400" />
              </div>
            </div>
            <div class="min-w-0 flex-1 pb-8">
              <div class="flex items-center gap-x-2">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Account upgraded to Pro plan</p>
                <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-400">Upgrade</span>
              </div>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upgraded from Basic to Pro subscription with additional features</p>
              <div class="mt-2 flex items-center gap-x-2 text-xs text-gray-500 dark:text-gray-500">
                <span class="font-medium">System</span>
                <span>•</span>
                <x-tooltip text="January 15, 2025 at 2:30 PM" class="cursor-help">2 hours ago</x-tooltip>
              </div>
            </div>
          </div>

          <!-- Event 2: Profile updated -->
          <div class="relative flex gap-x-3">
            <div class="absolute top-8 left-5 h-full w-0.5 bg-gray-200 dark:bg-neutral-700"></div>
            <div class="relative flex h-10 w-10 items-center justify-center">
              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                <x-phosphor-pencil class="size-4 text-blue-600 dark:text-blue-400" />
              </div>
            </div>
            <div class="min-w-0 flex-1 pb-8">
              <div class="flex items-center gap-x-2">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Profile information updated</p>
              </div>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Updated email address and phone number</p>
              <div class="mt-2 flex items-center gap-x-2 text-xs text-gray-500 dark:text-gray-500">
                <span class="font-medium">John Doe</span>
                <span>•</span>
                <x-tooltip text="January 14, 2025 at 4:15 PM" class="cursor-help">1 day ago</x-tooltip>
              </div>
            </div>
          </div>

          <!-- Event 3: 2FA enabled -->
          <div class="relative flex gap-x-3">
            <div class="absolute top-8 left-5 h-full w-0.5 bg-gray-200 dark:bg-neutral-700"></div>
            <div class="relative flex h-10 w-10 items-center justify-center">
              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30">
                <x-phosphor-shield-check class="size-4 text-purple-600 dark:text-purple-400" />
              </div>
            </div>
            <div class="min-w-0 flex-1 pb-8">
              <div class="flex items-center gap-x-2">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Two-factor authentication enabled</p>
                <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">Security</span>
              </div>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Enhanced account security with 2FA</p>
              <div class="mt-2 flex items-center gap-x-2 text-xs text-gray-500 dark:text-gray-500">
                <span class="font-medium">John Doe</span>
                <span>•</span>
                <x-tooltip text="January 12, 2025 at 10:45 AM" class="cursor-help">3 days ago</x-tooltip>
              </div>
            </div>
          </div>

          <!-- Event 4: Payment updated -->
          <div class="relative flex gap-x-3">
            <div class="absolute top-8 left-5 h-full w-0.5 bg-gray-200 dark:bg-neutral-700"></div>
            <div class="relative flex h-10 w-10 items-center justify-center">
              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900/30">
                <x-phosphor-credit-card class="size-4 text-orange-600 dark:text-orange-400" />
              </div>
            </div>
            <div class="min-w-0 flex-1 pb-8">
              <div class="flex items-center gap-x-2">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Payment method updated</p>
              </div>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Added new credit card ending in 4242</p>
              <div class="mt-2 flex items-center gap-x-2 text-xs text-gray-500 dark:text-gray-500">
                <span class="font-medium">John Doe</span>
                <span>•</span>
                <x-tooltip text="January 8, 2025 at 3:20 PM" class="cursor-help">1 week ago</x-tooltip>
              </div>
            </div>
          </div>

          <!-- Event 5: Account created -->
          <div class="relative flex gap-x-3">
            <div class="relative flex h-10 w-10 items-center justify-center">
              <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-900/30">
                <x-phosphor-user-plus class="size-4 text-gray-600 dark:text-gray-400" />
              </div>
            </div>
            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-x-2">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Account created</p>
                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">Created</span>
              </div>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">New account registered with basic plan</p>
              <div class="mt-2 flex items-center gap-x-2 text-xs text-gray-500 dark:text-gray-500">
                <span class="font-medium">John Doe</span>
                <span>•</span>
                <x-tooltip text="January 1, 2025 at 9:00 AM" class="cursor-help">2 weeks ago</x-tooltip>
              </div>
            </div>
          </div>
        </x-box>
      </div>
    </div>
  </div>
</x-layouts.instance>

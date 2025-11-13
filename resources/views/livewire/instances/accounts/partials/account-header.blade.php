@props([
  'user',
])

<x-box>
  <div class="flex items-center justify-between">
    <div class="space-y-1 sm:space-y-1">
      <h1 class="text-xl font-normal">
        {{ $user->name }}
      </h1>
      <div class="flex flex-col space-y-1 space-x-6 sm:flex-row sm:items-center sm:space-y-0">
        <div class="flex items-center gap-x-2">
          <x-phosphor-mailbox class="size-4 text-gray-500" />
          <p class="text-gray-500">
            {{ $user->email }}
          </p>
        </div>
        <div class="flex items-center gap-x-2">
          <x-phosphor-clock-clockwise class="size-4 text-gray-500" />
          <x-tooltip text="12/12/2025 12:32pm" class="text-gray-500">
            {{ $user->created_at->diffForHumans() }}
          </x-tooltip>
        </div>
        <div class="flex items-center gap-x-2">
          <x-phosphor-user class="size-4 text-gray-500" />
          <p class="text-gray-500">213 contacts</p>
        </div>
      </div>
    </div>

    <!-- account status -->
    <div>
      <!-- case: paid account -->
      <div class="flex flex-col items-center gap-y-1 rounded-md border border-gray-200 px-3 py-2 text-sm dark:border-neutral-700">
        <x-phosphor-shooting-star class="size-4 text-green-500" />
        <p class="text-sm text-green-800">Paid</p>
      </div>
    </div>
  </div>
</x-box>

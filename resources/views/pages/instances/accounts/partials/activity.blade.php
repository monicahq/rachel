@props([
  'action',
  'status',
  'icon' => 'user-plus',
  'description',
  'actor',
  'created_at',
  'color',
  'last' => false,
])

<div class="relative flex gap-x-3">
  @if (! $last)
    <div class="absolute top-8 left-5 h-full w-0.5 bg-gray-200 dark:bg-neutral-700"></div>
  @endif

  <div class="relative flex h-10 w-10 items-center justify-center">
    <div class="bg-{{ $color }}-100 dark:bg-{{ $color }}-900/30 flex h-8 w-8 items-center justify-center rounded-full">
      @isset($icon)
        <x-dynamic-component :component="'phosphor-' . $icon" class="size-4 text-{{ $color }}-600 dark:text-{{ $color }}-400" />
      @endisset
    </div>
  </div>
  <div class="min-w-0 flex-1 pb-8">
    <div class="flex items-center gap-x-2">
      <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
        {{ $action }}
      </p>
      @isset($status)
        <span class="bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900/30 dark:text-{{ $color }}-400 inline-flex items-center rounded-full px-2 py-1 text-xs font-medium">
          {{ $status }}
        </span>
      @endisset
    </div>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
      {{ $description }}
    </p>
    <div class="mt-2 flex items-center gap-x-2 text-xs text-gray-500 dark:text-gray-500">
      <span class="font-medium">{{ $actor }}</span>
      <span>•</span>
      <x-tooltip text="{{ $created_at->format('Y') }}" class="cursor-help">
        {{ $created_at->diffForHumans() }}
      </x-tooltip>
    </div>
  </div>
</div>

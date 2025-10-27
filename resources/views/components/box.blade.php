@props([
  'padding' => 'p-4',
  'description' => null,
])

<div class="flex flex-col gap-2">
  @isset($title)
    <h2 class="font-semi-bold mb-1 text-lg dark:text-white">{{ $title }}</h2>
  @endisset

  @isset($description)
    <div class="mb-2 flex flex-col gap-y-2 text-sm text-gray-500">
      {{ $description }}
    </div>
  @endisset

  <div {{ $attributes->merge(['class' => 'rounded-lg border border-gray-200 bg-white dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200 ' . $padding]) }}>
    {{ $slot }}
  </div>
</div>

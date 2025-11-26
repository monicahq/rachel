@props([
  'icon' => '',
  'navigate' => true,
])

<a {{
    $attributes
      ->class([
        'text-zinc-600 hover:bg-zinc-950/5 hover:text-zinc-800 dark:text-zinc-400',
        'flex h-8 items-center justify-between gap-3 rounded-lg',
        'px-2 text-sm leading-5 dark:hover:bg-white/5 dark:hover:text-white',
      ])
      ->merge(['noUnderline' => true])
  }} @if ($navigate) wire:navigate wire:current="!text-green-600 !hover:bg-green-600/5 !dark:text-green-500 !dark:hover:bg-green-500/5" @endif>
  <div class="flex items-center gap-2">
    <x-dynamic-component :component="'phosphor-' . $icon" class="size-4 min-w-3" />
    <span>
      {{ $slot }}
    </span>
  </div>
</a>

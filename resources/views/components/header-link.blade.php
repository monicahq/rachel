@props([
  'icon' => '',
  'selected' => false,
])

<a {{
  $attributes
    ->class([
      'group flex items-center gap-2 rounded-md border border-transparent px-2 py-1 font-medium dark:text-white hover:bg-[#E4EEF3] hover:dark:bg-[#202830]',
      'dark:bg-[#202830]' => $selected,
    ])
    ->merge(['wire:navigate' => true])
}}>
  <x-dynamic-component :component="'phosphor-' . $icon" class="size-4 text-gray-300 transition-transform duration-250 group-hover:text-[#0779CF] dark:text-gray-500" />
  {{ $slot }}
</a>

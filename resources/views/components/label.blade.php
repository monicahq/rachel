@props([
  'for' => null,
  'value' => null,
  'optional' => false,
])

<label for="{{ $for }}" {!! $attributes->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300']) !!}>
  {{ $value }}

  @if ($optional)
    <span class="ml-1 inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-gray-500/10 ring-inset dark:bg-gray-950">{{ __('optional') }}</span>
  @endif
</label>

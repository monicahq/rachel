@props([
  'submit',
])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
  <x-section-title>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="description">{{ $description }}</x-slot>
  </x-section-title>

  <div class="mt-5 md:col-span-2 md:mt-0">
    <form wire:submit="{{ $submit }}" class="my-6 w-full space-y-6">
      {{ $form }}

      @if (isset($actions))
        <div class="flex items-center gap-4">
          <div class="flex items-center justify-end">
            {{ $actions }}
          </div>
        </div>
      @endif
    </form>
  </div>
</div>

@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
  <div class="pb-4">
    <div class="text-lg font-medium">
      {{ $title }}
    </div>

    <div class="mt-4 text-sm">
      {{ $content }}
    </div>
  </div>

  <div class="flex flex-row justify-end text-end">
    {{ $footer }}
  </div>
</x-modal>

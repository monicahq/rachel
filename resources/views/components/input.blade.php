@props([
  'id' => null,
  'avoidAutofill' => true,
  'type' => 'text',
  'label' => null,
  'optional' => false,
])

<div class="group/input relative block w-full space-y-2" data-input>
  @if ($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
      {{ $label }}

      @if ($optional)
        <span class="ml-1 inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-gray-500/10 ring-inset dark:bg-gray-950">{{ __('optional') }}</span>
      @endif
    </label>
  @endif

  <div class="relative">
    <input
      @if($avoidAutofill) data-1p-ignore @endif
      type="{{ $type }}"
      id="{{ $id }}"
      {!! $attributes->merge(['class' => 'w-full appearance-none pr-3 pl-3 bg-white dark:bg-white/10 dark:disabled:bg-white/[7%] text-gray-700 placeholder-gray-400 disabled:text-gray-500 disabled:placeholder-gray-400/70 dark:text-gray-300 dark:placeholder-gray-400 dark:disabled:text-gray-400 dark:disabled:placeholder-gray-500 rounded-lg border border-gray-200 border-b-gray-300/80 disabled:border-b-gray-200 dark:border-white/10 dark:disabled:border-white/5 shadow-xs disabled:shadow-none dark:shadow-none h-10 py-2 text-base leading-[1.375rem] sm:text-sm dark:focus-visible:outline-2']) !!} />

    @if ($type === 'password')
      <div class="absolute end-0 top-0 bottom-0 flex items-center gap-x-1.5 pe-3 text-xs" x-data="{ shown: false }" x-on:click="
        shown = ! shown
        $el
          .closest('[data-input]')
          .querySelector('input')
          .setAttribute('type', shown ? 'text' : 'password')
      " x-bind:data-viewable="shown" x-init="
        let input = $el.closest('[data-input]')?.querySelector('input')

        if (! input) return

        let observer = new MutationObserver(() => {
          let type = shown ? 'text' : 'password'
          if (input.getAttribute('type') === type) return
          input.setAttribute('type', type)
        })

        observer.observe(input, { attributes: true, attributeFilter: ['type'] })
      ">
        <x-phosphor-eye-slash class="block h-5 w-5 dark:text-gray-300 [[data-viewable]>&]:hidden" />
        <x-phosphor-eye class="hidden h-5 w-5 dark:text-gray-300 [[data-viewable]>&]:block" />
      </div>
    @endif
  </div>
</div>

@props([
  'id' => null,
  'avoidAutofill' => true,
  'type' => 'text',
  'label' => null,
  'value' => null,
  'optional' => false,
  'handleErrors' => true,
])

<div class="group/input relative block w-full space-y-2" data-input>
  @if ($label)
    <x-label :for="$id" :value="$label" :optional="$optional" />
  @endif

  <div class="relative">
    <input
      @if($avoidAutofill) data-1p-ignore @endif
      type="{{ $type }}"
      id="{{ $id }}"
      value="{{ $value }}"
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

    @if ($handleErrors)
      @error($id)
        <flux:text color="red">
          {{ $message }}
        </flux:text>
      @enderror
    @endif
  </div>
</div>

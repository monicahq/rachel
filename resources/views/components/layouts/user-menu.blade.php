<div class="flex items-center gap-1">
  <a href="" class="flex items-center gap-2 rounded-md border border-transparent px-2 py-1 font-medium hover:border-gray-200 hover:bg-gray-100 dark:border-gray-700 dark:text-white hover:dark:border-gray-500 hover:dark:bg-[#202830]">
    <x-phosphor-lifebuoy class="size-4 text-gray-600 transition-transform duration-150" />
    {{ __('Docs') }}
  </a>

  <div x-data="{ menuOpen: false }" @click.away="menuOpen = false" class="relative">
    <button @click="menuOpen = !menuOpen" :class="{ 'bg-gray-100' : menuOpen }" class="flex cursor-pointer items-center gap-2 rounded-md border border-transparent px-2 py-1 font-medium hover:border-gray-200 hover:bg-gray-100 dark:border-gray-700 dark:text-white hover:dark:border-gray-500 hover:dark:bg-[#202830]">
      {{ __('Menu') }}
      <x-phosphor-caret-down class="size-4 text-gray-600 transition-transform duration-150" x-bind:class="{ 'rotate-180' : menuOpen }" />
    </button>

    <div x-cloak x-show="menuOpen" x-transition:enter="transition duration-50 ease-linear" x-transition:enter-start="-translate-y-1 opacity-90" x-transition:enter-end="translate-y-0 opacity-100" class="absolute top-0 right-0 z-50 mt-10 w-52 min-w-[8rem] rounded-md border border-gray-200/70 bg-white p-1 text-sm text-gray-800 shadow-md dark:border-gray-700 dark:bg-[#202830] dark:text-white" x-cloak>
      <a @click="menuOpen = false" href="{{ route('instances.index') }}" class="relative flex w-full cursor-default items-center rounded px-2 py-1.5 outline-none select-none hover:bg-gray-100 hover:text-gray-900 hover:dark:bg-gray-600" wire:navigate.hover>
        <x-phosphor-user class="mr-2 size-4 text-gray-600" />
        {{ __('Instance management') }}
      </a>

      <a @click="menuOpen = false" href="{{ route('settings.index') }}" class="relative flex w-full cursor-default items-center rounded px-2 py-1.5 outline-none select-none hover:bg-gray-100 hover:text-gray-900 hover:dark:bg-gray-600" wire:navigate.hover>
        <x-phosphor-user class="mr-2 size-4 text-gray-600" />
        {{ __('Settings') }}
      </a>

      <div class="-mx-1 my-1 h-px bg-gray-200 dark:bg-gray-700"></div>

      <form method="POST" action="{{ route('logout') }}" class="w-full">
        @csrf
        <button @click="menuOpen = false" type="submit" class="relative flex w-full cursor-default items-center rounded px-2 py-1.5 outline-none select-none hover:bg-gray-100 hover:text-gray-900 hover:dark:bg-gray-600">
          <x-phosphor-sign-out class="mr-2 size-4 text-gray-600" />
          {{ __('Logout') }}
        </button>
      </form>
    </div>
  </div>
</div>

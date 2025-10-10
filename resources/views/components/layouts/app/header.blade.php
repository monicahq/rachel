<header {{ $attributes->class(['flex w-full max-w-[1920px] items-center px-2 sm:pr-4 sm:pl-9 dark:bg-[#151B23]']) }}>
  <!-- normal desktop header -->
  <nav class="hidden flex-1 items-center gap-3 pt-3 pb-3 sm:flex">
    <a href="" class="flex h-7 w-7 items-center">
      <x-app-logo-icon />
    </a>

    <!-- menu -->
    <div class="-ml-4 flex-1">
      <div class="flex items-center justify-center">
        <div class="flex space-x-1 rounded-lg border border-gray-200 p-0.5 dark:border-0 dark:ring-1 dark:ring-gray-700">
          <!-- dashboard -->
          <x-header-link icon="house-line-fill" href="/" selected>{{ __('Dashboard') }}</x-header-link>

          <!-- search -->
          <x-header-link icon="magnifying-glass-fill" href="/">{{ __('Search') }}</x-header-link>

          <!-- contacts -->
          <x-header-link icon="users-three-fill" href="/">{{ __('Contacts') }}</x-header-link>

          <!-- me -->
          <x-header-link icon="binoculars-fill" href="/">{{ __('Me') }}</x-header-link>
        </div>
      </div>
    </div>

    <!-- user menu -->
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

        <div x-cloak x-show="menuOpen" x-transition:enter="transition duration-50 ease-linear" x-transition:enter-start="-translate-y-1 opacity-90" x-transition:enter-end="translate-y-0 opacity-100" class="absolute top-0 right-0 z-50 mt-10 w-48 min-w-[8rem] rounded-md border border-gray-200/70 bg-white p-1 text-sm text-gray-800 shadow-md dark:border-gray-700 dark:bg-[#202830] dark:text-white" x-cloak>
          <a @click="menuOpen = false" href="" class="relative flex w-full cursor-default items-center rounded px-2 py-1.5 outline-none select-none hover:bg-gray-100 hover:text-gray-900 hover:dark:bg-gray-600">
            <x-phosphor-user class="mr-2 size-4 text-gray-600" />
            {{ __('Profile') }}
          </a>

          <div class="-mx-1 my-1 h-px bg-gray-200"></div>

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
  </nav>

  <!-- mobile header -->
  <nav class="flex w-full items-center justify-between gap-3 pt-2 pb-2 sm:hidden" x-data="{ mobileMenuOpen: false }" aria-label="Global">
    <a href="/" class="flex h-7 w-7">
      <x-app-logo-icon size="7" />
    </a>

    <button @click="mobileMenuOpen = true" class="flex items-center gap-2 rounded-md border border-transparent py-1 font-medium hover:border-gray-200 hover:bg-gray-100">
      <x-phosphor-list class="size-5 text-gray-600 transition-transform duration-150" />
    </button>

    <!-- Mobile menu overlay -->
    <div x-cloak x-show="mobileMenuOpen" x-transition:enter="transition duration-50 ease-out" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition duration-50 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 bg-white dark:bg-gray-900">
      <div class="flex h-full flex-col">
        <!-- Mobile menu header -->
        <div class="flex items-center justify-between border-b border-gray-200 px-2 py-1 dark:border-gray-700">
          <div class="flex h-7 w-7">
            <x-app-logo-icon size="7" />
          </div>

          <button @click="mobileMenuOpen = false" class="flex items-center gap-2 rounded-md border border-transparent py-2 font-medium hover:border-gray-200 hover:bg-gray-100 dark:hover:border-gray-600 dark:hover:bg-gray-800">
            <x-phosphor-x class="size-5 text-gray-600 dark:text-gray-400" />
          </button>
        </div>

        <!-- Mobile menu content -->
        <div class="flex-1 space-y-4 p-4">
          <a @click="mobileMenuOpen = false" href="/" class="flex items-center gap-3 rounded-md p-3 text-lg font-medium text-gray-800 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
            {{ __('Dashboard') }}
          </a>

          <a @click="mobileMenuOpen = false" href="/" class="flex items-center gap-3 rounded-md p-3 text-lg font-medium text-gray-800 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
            <x-phosphor-magnifying-glass class="size-5 text-gray-600 dark:text-gray-400" />
            {{ __('Search') }}
          </a>

          <a @click="mobileMenuOpen = false" href="/" class="flex items-center gap-3 rounded-md p-3 text-lg font-medium text-gray-800 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
            <x-phosphor-lifebuoy class="size-5 text-gray-600 dark:text-gray-400" />
            {{ __('Docs') }}
          </a>

          <a @click="mobileMenuOpen = false" href="" class="flex items-center gap-3 rounded-md p-3 text-lg font-medium text-gray-800 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
            <x-phosphor-user class="size-5 text-gray-600 dark:text-gray-400" />
            {{ __('Profile') }}
          </a>
        </div>

        <!-- Mobile menu footer -->
        <div class="border-t border-gray-200 p-4 dark:border-gray-700">
          <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button @click="mobileMenuOpen = false" type="submit" class="flex w-full items-center gap-3 rounded-md p-3 text-lg font-medium text-gray-800 transition-colors hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
              <x-phosphor-sign-out class="size-5 text-gray-600 dark:text-gray-400" />
              {{ __('Logout') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </nav>
</header>

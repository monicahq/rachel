<header {{ $attributes->class(['flex w-full max-w-[1920px] items-center px-2 sm:pr-4 sm:pl-9 dark:bg-[#151B23]']) }}>
  <!-- normal desktop header -->
  <nav class="hidden flex-1 items-center gap-3 pt-3 pb-3 sm:flex" aria-label="Main navigation">
    <div class="flex items-center gap-4">
      <a href="{{ route('dashboard') }}" class="group h-7 w-7 gap-x-2 transition-transform ease-in-out">
        <div class="flex h-7 w-7 items-center justify-center transition-all duration-400 group-hover:-translate-y-0.5 group-hover:-rotate-3">
          <x-app-logo-icon />
        </div>
      </a>

      <div class="flex items-center gap-2">
        <span class="font-medium dark:border-gray-700 dark:text-white">Administration of the instance</span>
      </div>
    </div>

    <!-- separator -->
    <div class="-ml-4 flex-1"></div>

    <!-- user menu -->
    @include('components.layouts.user-menu')
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

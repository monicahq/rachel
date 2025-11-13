<x-layouts.app :title="__('Settings')">
  <div class="grid h-[calc(100vh-48px)] grid-cols-1 lg:grid-cols-[240px_1fr]">
    <!-- sidebar -->
    <livewire:settings.partials.settings-sidebar />

    <!-- main content -->
    <div class="relative bg-gray-50 px-6 pt-8 lg:px-12 dark:bg-[#151B23]">
      <div class="mx-auto max-w-2xl px-2 py-2 sm:px-0">
        {{ $slot }}
      </div>
    </div>
  </div>
</x-layouts.app>

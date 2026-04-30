<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @include('layouts::app.meta')
  </head>
  <body class="flex min-h-screen flex-col font-sans text-sm text-gray-900 antialiased">
    <x-layouts::app.header />

    <main class="flex flex-1 flex-col bg-gray-50 px-2 py-px dark:bg-[#151B23]">
      <div class="mx-auto flex w-full grow flex-col items-stretch rounded-lg bg-[#F9FBFC] shadow-xs ring-1 ring-[#E6E7E9] dark:bg-[#202830] dark:ring-gray-700">
        <div class="grid h-[calc(100vh-48px)] grid-cols-1 lg:grid-cols-[240px_1fr]">
          <!-- sidebar -->
          <x-pages::settings.partials.sidebar />

          <!-- main content -->
          <div class="relative bg-gray-50 px-6 pt-8 lg:px-12 dark:bg-[#151B23]">
            <div class="mx-auto max-w-2xl px-2 py-2 sm:px-0">
              {{ $slot }}
            </div>
          </div>
        </div>
      </div>
    </main>

    <x-layouts::app.footer />

    @fluxScripts
    @livewireScriptConfig
  </body>
</html>

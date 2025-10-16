<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @include('components.layouts.meta')
  </head>
  <body class="flex min-h-screen flex-col border-5 border-yellow-500 font-sans text-sm text-gray-900 antialiased">
    <x-layouts.instance.header />

    <main class="flex flex-1 flex-col bg-white px-2 py-px dark:bg-[#151B23]">
      <div class="mx-auto flex w-full grow flex-col items-stretch rounded-lg bg-[#F9FBFC] shadow-xs ring-1 ring-[#E6E7E9] dark:bg-[#202830] dark:ring-gray-700">
        {{ $slot }}
      </div>
    </main>

    <x-layouts.app.footer />

    @fluxScripts
    @livewireScriptConfig
  </body>
</html>

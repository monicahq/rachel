<footer {{ $attributes->class(['flex w-full max-w-[1920px] items-center pr-4 pl-9 dark:bg-[#151B23]']) }}>
  <div class="flex py-3 text-sm text-gray-600">
    <div class="flex">&copy; {{ config('app.name') }} &middot; {{ now()->format('Y') }}</div>
  </div>
</footer>

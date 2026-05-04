@props([
  'name',
  'route',
])

<x-link :href="$route">
  <x-box class="group h-40 hover:bg-[#E4EEF3] hover:ring-1 hover:ring-gray-200 hover:dark:bg-[#202830] hover:dark:ring-gray-700" padding="p-0">
    <div class="relative h-full w-full overflow-hidden">
      <!-- Vault name -->
      <div class="absolute inset-0 z-10 flex items-center justify-center text-center">
        <span class="text-lg font-semibold">{{ $name }}</span>
      </div>

      <!-- Orbit circles -->
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="absolute h-64 w-64 rounded-full border border-gray-300"></div>
        <div class="absolute h-96 w-96 rounded-full border border-gray-300"></div>
      </div>

      <!-- Avatars on orbits -->
      <div class="absolute inset-0">
        <!-- Inner orbit avatars (h-64 w-64) -->
        <div class="absolute top-1/2 left-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2">
          <!-- Avatar 1 - Right -->
          <div class="absolute top-1/2 -right-3 h-8 w-8 -translate-y-1/2 group-hover:animate-spin" style="animation-duration: 4s">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-500 text-xs font-bold text-white">A</div>
          </div>
          <!-- Avatar 2 - Left -->
          <div class="absolute top-[40%] -left-3 h-8 w-8 -translate-y-1/2 group-hover:animate-spin" style="animation-duration: 4s; animation-delay: -2s">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-500 text-xs font-bold text-white">B</div>
          </div>
        </div>

        <!-- Outer orbit avatars (h-96 w-96) -->
        <div class="absolute top-1/2 left-1/2 h-96 w-96 -translate-x-1/2 -translate-y-1/2">
          <!-- Avatar 1 - Top right -->
          <div class="absolute top-[30%] right-[18%] h-6 w-6 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -1s">
            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-pink-500 text-xs font-bold text-white">F</div>
          </div>
          <!-- Avatar 2 - Right -->
          <div class="absolute top-[59%] -right-[2%] h-6 w-6 -translate-y-1/2 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -2s">
            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-500 text-xs font-bold text-white">G</div>
          </div>
          <!-- Avatar 3 - Bottom right -->
          <div class="absolute right-1/4 bottom-1/4 h-6 w-6 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -3s">
            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-orange-500 text-xs font-bold text-white">H</div>
          </div>
          <!-- Avatar 4 - Bottom left -->
          <div class="absolute bottom-1/4 left-1/4 h-6 w-6 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -5s">
            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-cyan-500 text-xs font-bold text-white">J</div>
          </div>
          <!-- Avatar 5 - Left -->
          <div class="absolute top-[55%] -left-[3%] h-6 w-6 -translate-y-1/2 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -2.5s">
            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-xs font-bold text-white">K</div>
          </div>
          <!-- Avatar 6 - Top left -->
          <div class="absolute top-1/4 left-1/4 h-6 w-6 group-hover:animate-spin" style="animation-duration: 6s; animation-delay: -1.5s">
            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-violet-500 text-xs font-bold text-white">L</div>
          </div>
        </div>
      </div>
    </div>
  </x-box>
</x-link>

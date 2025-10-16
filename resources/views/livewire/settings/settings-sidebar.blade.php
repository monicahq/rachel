<section class="rounded-tl-lg rounded-bl-lg border-r border-gray-300">
  <div class="flex flex-col px-6 pt-8">
    <!-- back to dashboard -->
    <x-link :href="route('dashboard')" class="mb-4">
      <div class="flex h-8 items-center justify-between gap-3 rounded-lg px-2 text-sm leading-5 text-zinc-600 hover:bg-zinc-950/5 hover:text-zinc-800 dark:text-zinc-400 dark:hover:bg-white/5 dark:hover:text-white">
        <div class="flex items-center gap-2">
          <x-phosphor-caret-left class="size-3 min-w-3" />
          <span>
            {{ __('Back to dashboard') }}
          </span>
        </div>
      </div>
    </x-link>

    <div class="flex flex-col gap-4">
      <!-- your account -->
      <div class="flex flex-col gap-0.5">
        <span class="tpx-2 py-1.5 text-xs font-semibold text-zinc-950/40 dark:text-white/40">
          {{ __('Your account') }}
        </span>
        <x-link :href="route('profile.edit')">
          <div class="{{ request()->routeIs('profile.edit') ? 'text-green-600 hover:bg-green-600/5 dark:text-green-500 dark:hover:bg-green-500/5' : 'text-zinc-600 hover:bg-zinc-950/5 hover:text-zinc-800 dark:text-zinc-400' }} flex h-8 items-center justify-between gap-3 rounded-lg px-2 text-sm leading-5 dark:hover:bg-white/5 dark:hover:text-white">
            <div class="flex items-center gap-2">
              <x-phosphor-user class="size-4 min-w-3" />
              <span>
                {{ __('Profile') }}
              </span>
            </div>
          </div>
        </x-link>

        <x-link :href="route('password.edit')">
          <div class="{{ request()->routeIs('password.edit') ? 'text-green-600 hover:bg-green-600/5 dark:text-green-500 dark:hover:bg-green-500/5' : 'text-zinc-600 hover:bg-zinc-950/5 hover:text-zinc-800 dark:text-zinc-400 dark:hover:bg-white/5 dark:hover:text-white' }} flex h-8 items-center justify-between gap-3 rounded-lg px-2 text-sm leading-5">
            <div class="flex items-center gap-2">
              <x-phosphor-shield-chevron class="size-4 min-w-3" />
              <span>
                {{ __('Security & password') }}
              </span>
            </div>
          </div>
        </x-link>

        <x-link :href="route('two-factor.show')">
          <div class="{{ request()->routeIs('two-factor.show') ? 'text-green-600 hover:bg-green-600/5 dark:text-green-500 dark:hover:bg-green-500/5' : 'text-zinc-600 hover:bg-zinc-950/5 hover:text-zinc-800 dark:text-zinc-400 dark:hover:bg-white/5 dark:hover:text-white' }} flex h-8 items-center justify-between gap-3 rounded-lg px-2 text-sm leading-5">
            <div class="flex items-center gap-2">
              <x-phosphor-gear class="size-4 min-w-3" />
              <span>
                {{ __('Two factor authentication') }}
              </span>
            </div>
          </div>
        </x-link>
      </div>

      <!-- administration -->
      <div class="flex flex-col gap-0.5">
        <span class="tpx-2 py-1.5 text-xs font-semibold text-zinc-950/40 dark:text-white/40">
          {{ __('Appearance') }}
        </span>

        <x-link :href="route('appearance.edit')">
          <div class="{{ request()->routeIs('appearance.edit') ? 'text-green-600 hover:bg-green-600/5 dark:text-green-500 dark:hover:bg-green-500/5' : 'text-zinc-600 hover:bg-zinc-950/5 hover:text-zinc-800 dark:text-zinc-400 dark:hover:bg-white/5 dark:hover:text-white' }} flex h-8 items-center justify-between gap-3 rounded-lg px-2 text-sm leading-5">
            <div class="flex items-center gap-2">
              <x-phosphor-puzzle-piece class="size-4 min-w-3" />
              <span>
                {{ __('Personalization') }}
              </span>
            </div>
          </div>
        </x-link>
      </div>
    </div>
  </div>
</section>

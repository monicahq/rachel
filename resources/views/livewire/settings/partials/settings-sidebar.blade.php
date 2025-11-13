<section class="rounded-tl-lg rounded-bl-lg border-r border-gray-300">
  <div class="flex flex-col px-6 pt-8">
    <!-- back to dashboard -->
    <x-setting-link :href="route('dashboard')" icon="caret-left">
      {{ __('Back to dashboard') }}
    </x-setting-link>

    <div class="mt-4 flex flex-col gap-4">
      <!-- your account -->
      <div class="flex flex-col gap-0.5">
        <span class="tpx-2 py-1.5 text-xs font-semibold text-zinc-950/40 dark:text-white/40">
          {{ __('Your account') }}
        </span>
        <x-setting-link :href="route('profile.edit')" icon="user">
          {{ __('Profile') }}
        </x-setting-link>
        <x-setting-link :href="route('password.edit')" icon="shield-chevron">
          {{ __('Security & password') }}
        </x-setting-link>
        <x-setting-link :href="route('two-factor.show')" icon="gear">
          {{ __('2FA') }}
        </x-setting-link>
        <x-setting-link :href="route('settings.api-token-manager')" icon="gear">
          {{ __('API Tokens') }}
        </x-setting-link>
      </div>

      <!-- administration -->
      <div class="flex flex-col gap-0.5">
        <span class="tpx-2 py-1.5 text-xs font-semibold text-zinc-950/40 dark:text-white/40">
          {{ __('Appearance') }}
        </span>
        <x-setting-link :href="route('appearance.edit')" icon="puzzle-piece">
          {{ __('Personalization') }}
        </x-setting-link>
      </div>
    </div>
  </div>
</section>

<?php

use App\Models\UserActivityLog;
use App\Services\PresentUserActivity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

new #[Layout('layouts::settings')] class extends Livewire\Component
{
    public Collection $activities;

    public function mount(): void
    {
        $presenter = resolve(PresentUserActivity::class);
        $user = Auth::user();

        $items = UserActivityLog::query()
            ->where('user_id', $user->id)
            ->with('actor')
            ->latest()
            ->limit(50)
            ->get();

        $this->activities = $items->values()->map(function (UserActivityLog $log, int $index) use ($items, $presenter, $user): array {
            $entry = $presenter->execute($log);
            $entry['actor'] = $presenter->actorLabel($user, $log);
            $entry['last'] = $index === $items->count() - 1;

            return $entry;
        });
    }

    public function render()
    {
        return $this->view()->title(__('Activity log'));
    }
}; ?>

<section class="w-full">
  <x-box>
    <x-slot:title>
      {{ __('Activity log') }}
    </x-slot>

    <x-slot:description>
      {{ __('Recent actions made on your account.') }}
    </x-slot>

    <div class="space-y-1">
      @forelse ($activities as $activity)
        @include('pages::instances.accounts.partials.activity', $activity)
      @empty
        <flux:text variant="subtle">
          {{ __('No activity yet.') }}
        </flux:text>
      @endforelse
    </div>
  </x-box>
</section>

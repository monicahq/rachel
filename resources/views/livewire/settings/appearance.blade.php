<?php

use Livewire\Volt\Component;

new class extends Component
{
    //
}; ?>

<section class="w-full">
  <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
    <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
    <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
    <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
  </flux:radio.group>
</section>

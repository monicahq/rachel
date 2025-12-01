<?php

use App\Models\Contact;
use App\Models\Vault;
use App\Services\CreateContact;

use function Livewire\Volt\mount;
use function Livewire\Volt\rules;
use function Livewire\Volt\state;
use function Livewire\Volt\title;

title(fn (): string|array|null => __('Contacts in vault :vault', ['vault' => $this->vault['name']]));

state(['vault', 'contacts'])->locked();
state(['name']);

rules(fn (): array => Contact::rules());

mount(function (Vault $vault): void {
    $this->authorize('viewAny', [Contact::class, $vault]);

    $this->vault = [
        'id' => $vault->id,
        'name' => $vault->name,
    ];
    $this->contacts = $vault->contacts
        ->map(
            fn (Contact $contact): array => [
                'id' => $contact->id,
                'name' => $contact->name,
                'route' => route('contacts.show', [$vault, $contact]),
            ],
        )
        ->sortByCollator('name');
});

$create = function (): void {
    $vault = Vault::where('account_id', \Illuminate\Support\Facades\Auth::user()->account_id)->find($this->vault['id']);

    $this->authorize('create', [Contact::class, $vault]);

    $validated = $this->validate();

    $contact = (new CreateContact(vault: $vault, name: $validated['name']))->execute();

    $this->reset('name');

    $this->dispatch('contact-created');

    $this->redirect(route('contacts.show', [$vault, $contact]));
};
?>

<div>
  <div class="mx-auto max-w-lg px-2 py-2 sm:px-6 sm:py-6 lg:px-8">
    <section class="flex w-full flex-col gap-10">
      <!-- contacts list -->
      <div class="grid grid-cols-1 gap-10">
        @foreach ($contacts as $contact)
          <x-link wire:key="{{ $contact['id'] }}" :href="$contact['route']" class="dark:text-white">
            {{ $contact['name'] }}
          </x-link>
        @endforeach
      </div>

      <x-box
        x-data="{
        showForm: false,
        toggle: function() { this.showForm = !this.showForm }
      }">
        <div class="flex justify-center" x-show="!showForm" x-transition:enter.duration.200ms>
          <flux:button icon="plus-circle" @click.prevent="toggle()">{{ __('Add a contact') }}</flux:button>
        </div>

        <!-- create contact form -->
        <form method="POST" wire:submit="create" class="space-y-6" wire:cloak x-show="showForm" x-transition:enter.duration.200ms>
          <x-input wire:model="name" id="name" :label="__('Contact name')" type="text" required />

          <div class="flex items-center justify-between">
            <flux:button variant="filled" @click.prevent="toggle()">
              {{ __('Cancel') }}
            </flux:button>

            <div class="flex items-center gap-4">
              <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">
                  {{ __('Create') }}
                </flux:button>
              </div>

              <x-action-message class="me-3" on="contact-created">
                {{ __('Created.') }}
              </x-action-message>
            </div>
          </div>
        </form>
      </x-box>
    </section>
  </div>
</div>

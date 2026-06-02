<?php

use App\Models\Contact;
use App\Models\ContactParameter;
use App\Models\Vault;
use App\Services\CreateParameter;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;

new class extends Livewire\Component {
  #[Locked]
  public $contact;

  #[Locked]
  public $vault;

  #[Locked]
  public $routes;

  public string $parameterKey = '';

  public string $parameterLabel = '';

  public string $parameterType = 'string';

  public ?string $parameterData = null;

  public string $emailAddress = '';

  public bool $showEmailModal = false;

  public bool $editingEmail = false;

  public ?int $editingEmailId = null;

  public function render()
  {
    return $this->view()->title($this->contact['name']);
  }

  public function mount(Vault $vault, Contact $contact): void
  {
    $this->authorize('view', [$contact, $vault]);

    $this->vault = [
      'id' => $vault->id,
      'name' => $vault->name,
    ];
    $this->contact = [
      'id' => $contact->id,
      'name' => $contact->name,
      'parameters' => $this->parametersMap($contact->parameters),
    ];
    $this->routes = [
      'vaults' => [
        'index' => route('vaults.index'),
        'show' => route('vaults.show', $vault),
      ],
      'contacts' => [
        'index' => route('contacts.index', $vault),
      ],
    ];
  }

  public function createParameter(): void
  {
    $vault = Vault::find($this->vault['id']);
    $contact = Contact::find($this->contact['id']);

    $this->authorize('update', [$contact, $vault]);

    $validated = $this->validate([
      'parameterKey' => ContactParameter::rules()['key'],
      'parameterLabel' => ContactParameter::rules()['label'],
      'parameterType' => ContactParameter::rules()['type'],
      'parameterData' => ContactParameter::rules()['data'],
    ]);

    (new CreateParameter(contact: $contact, key: $validated['parameterKey'], type: $validated['parameterType'], label: $validated['parameterLabel'], data: $validated['parameterData'] ?? null))->execute();

    $this->reset('parameterKey', 'parameterLabel', 'parameterType', 'parameterData');
    $this->parameterType = 'string';

    $contact->load('parameters');

    $this->contact['parameters'] = $this->parametersMap($contact->parameters);

    $this->dispatch('parameter-created');
  }

  public function addEmail(): void
  {
    $vault = Vault::find($this->vault['id']);
    $contact = Contact::find($this->contact['id']);

    $this->authorize('update', [$contact, $vault]);

    $validated = $this->validate([
      'emailAddress' => 'required|email|max:255',
    ]);

    (new CreateParameter(contact: $contact, key: 'email', type: 'string', label: 'Email', data: $validated['emailAddress']))->execute();

    $this->reset('emailAddress');

    $contact->load('parameters');

    $this->contact['parameters'] = $this->parametersMap($contact->parameters);

    $this->dispatch('email-added');

    $this->closeEmailModal();
  }

  public function openAddEmailModal(): void
  {
    $this->editingEmail = false;
    $this->editingEmailId = null;
    $this->emailAddress = '';
    $this->showEmailModal = true;
  }

  public function openEditEmailModal(int $emailId): void
  {
    $emails = $this->contact['parameters']['email'] ?? [];
    $email = collect($emails)->firstWhere('id', $emailId);

    if ($email) {
      $this->editingEmail = true;
      $this->editingEmailId = $emailId;
      $this->emailAddress = $email['data'];
      $this->showEmailModal = true;
    }
  }

  public function closeEmailModal(): void
  {
    $this->showEmailModal = false;
    $this->editingEmail = false;
    $this->editingEmailId = null;
    $this->reset('emailAddress');
    $this->resetValidation();
  }

  public function updateEmail(): void
  {
    $vault = Vault::find($this->vault['id']);
    $contact = Contact::find($this->contact['id']);

    $this->authorize('update', [$contact, $vault]);

    $validated = $this->validate([
      'emailAddress' => 'required|email|max:255',
    ]);

    $parameter = ContactParameter::findOrFail($this->editingEmailId);

    if ($parameter->key !== 'email') {
      $this->addError('emailAddress', __('Invalid parameter'));

      return;
    }

    $parameter->update(['data' => $validated['emailAddress']]);

    $this->reset('emailAddress');

    $contact->load('parameters');

    $this->contact['parameters'] = $this->parametersMap($contact->parameters);

    $this->dispatch('email-updated');

    $this->closeEmailModal();
  }

  public function deleteEmailParameter(int $parameterId): void
  {
    $vault = Vault::find($this->vault['id']);
    $contact = Contact::find($this->contact['id']);

    $this->authorize('update', [$contact, $vault]);

    $parameter = ContactParameter::findOrFail($parameterId);

    if ($parameter->key !== 'email') {
      $this->addError('emailAddress', __('Invalid parameter'));

      return;
    }

    $parameter->delete();

    $contact->load('parameters');

    $this->contact['parameters'] = $this->parametersMap($contact->parameters);

    $this->dispatch('email-deleted');
  }

  private function parametersMap(Collection $parameters): array
  {
    return $parameters
      ->groupBy('key')
      ->map(
        fn (Collection $group): array => $group
          ->map(
            fn (ContactParameter $parameter): array => [
              'id' => $parameter->id,
              'key' => $parameter->key,
              'label' => $parameter->label,
              'type' => $parameter->type,
              'data' => $parameter->data,
            ],
          )
          ->values()
          ->all(),
      )
      ->all();
  }
}; ?>

<div class="dark:text-white">
  <x-breadcrumb :items="[
    ['label' => __('Dashboard'), 'route' => route('dashboard')],
    ['label' => __('Vaults'), 'route' => Arr::get($routes, 'vaults.index')],
    ['label' => $vault['name'], 'route' => Arr::get($routes, 'vaults.show')],
    ['label' => __('Contacts'), 'route' => Arr::get($routes, 'contacts.index')],
    ['label' => $contact['name']],
  ]" />

  <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
    {{ $contact['name'] }}
  </h1>

  <!-- Emails Section -->
  <div class="mt-8">
    <h2 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Email Addresses') }}</h2>

    @php
      $emails = $contact['parameters']['email'] ?? [];
    @endphp

    @if (count($emails) > 0)
      <dl class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
        @foreach ($emails as $email)
          <div class="flex flex-col items-center justify-between gap-4 py-4 sm:flex-row">
            <dd class="text-sm break-all text-gray-900 dark:text-white">{{ $email['data'] }}</dd>
            <div class="flex gap-2">
              <flux:button variant="ghost" size="sm" wire:click="openEditEmailModal({{ $email['id'] }})">
                {{ __('Edit') }}
              </flux:button>
              <flux:button variant="danger" size="sm" wire:click="deleteEmailParameter({{ $email['id'] }})">
                {{ __('Delete') }}
              </flux:button>
            </div>
          </div>
        @endforeach
      </dl>
    @else
      <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ __('No email addresses added yet.') }}</p>
    @endif
  </div>

  <!-- Add Email Form -->
  <div class="mt-6">
    <flux:button variant="primary" wire:click="openAddEmailModal">
      {{ __('Add Email Address') }}
    </flux:button>
    <!-- Parameters Section -->
    <div class="mt-8">
      <h2 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Other Parameters') }}</h2>

      <div class="mt-6">
        <x-box>
          <form method="POST" wire:submit="createParameter" class="space-y-6">
            <x-input wire:model="parameterKey" id="parameter_key" :label="__('Parameter key')" type="text" required />
            <x-input wire:model="parameterLabel" id="parameter_label" :label="__('Parameter label')" type="text" :optional="true" />
            <x-input wire:model="parameterType" id="parameter_type" :label="__('Parameter type')" type="text" required />
            <x-input wire:model="parameterData" id="parameter_data" :label="__('Parameter value')" type="text" :optional="true" />

            <div class="flex items-center justify-end gap-4">
              <x-action-message class="me-3" on="parameter-created">
                {{ __('Created.') }}
              </x-action-message>

              <flux:button variant="primary" type="submit">
                {{ __('Add parameter') }}
              </flux:button>
            </div>
          </form>
        </x-box>
      </div>

      <dl class="mt-4 divide-y divide-gray-200 dark:divide-gray-700">
        @foreach ($contact['parameters'] as $key => $parameters)
          @if ($key !== 'email')
            @foreach ($parameters as $parameter)
              <div class="flex flex-col py-4 sm:flex-row">
                <dt class="w-full text-sm font-medium text-gray-500 sm:w-1/3 dark:text-gray-400">{{ $parameter['label'] }}</dt>
                <dd class="mt-1 w-full text-sm break-all text-gray-900 sm:w-2/3 dark:text-white">{{ $parameter['data'] }}</dd>
              </div>
            @endforeach
          @endif
        @endforeach
      </dl>
    </div>
  </div>

  <!-- Email Modal -->
  <x-dialog-modal wire:model.live="showEmailModal">
    <x-slot name="title">
      {{ $editingEmail ? __('Edit Email Address') : __('Add Email Address') }}
    </x-slot>

    <x-slot name="content">
      <form wire:submit="{{ $editingEmail ? 'updateEmail' : 'addEmail' }}" class="space-y-6">
        <x-input wire:model="emailAddress" id="email_address" :label="__('Email Address')" type="email" required />
      </form>
    </x-slot>

    <x-slot name="footer">
      <div class="flex items-center justify-end gap-4">
        <x-action-message class="me-3" on="email-added,email-updated">
          {{ $editingEmail ? __('Email updated.') : __('Email added.') }}
        </x-action-message>

        <flux:button variant="ghost" wire:click="closeEmailModal">
          {{ __('Cancel') }}
        </flux:button>

        <flux:button variant="primary" wire:click="{{ $editingEmail ? 'updateEmail' : 'addEmail' }}">
          {{ $editingEmail ? __('Update Email') : __('Add Email') }}
        </flux:button>
      </div>
    </x-slot>
  </x-dialog-modal>
</div>

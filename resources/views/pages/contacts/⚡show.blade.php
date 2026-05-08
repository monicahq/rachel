<?php

use App\Models\Contact;
use App\Models\ContactParameter;
use App\Models\Vault;
use App\Services\CreateParameter;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;

new class extends Livewire\Component
{
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

        (new CreateParameter(contact: $contact, key: $validated['parameterKey'], label: $validated['parameterLabel'], type: $validated['parameterType'], data: $validated['parameterData'] ?? null))->execute();

        $this->reset('parameterKey', 'parameterLabel', 'parameterType', 'parameterData');
        $this->parameterType = 'string';

        $contact->load('parameters');

        $this->contact['parameters'] = $this->parametersMap($contact->parameters);

        $this->dispatch('parameter-created');
    }

    private function parametersMap(Collection $parameters): array
    {
        return $parameters
            ->map(
                fn (ContactParameter $parameter): array => [
                    'id' => $parameter->id,
                    'key' => $parameter->key,
                    'label' => $parameter->label,
                    'type' => $parameter->type,
                    'data' => $parameter->data,
                ],
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

  Name: {{ $contact['name'] }}

  <div class="mt-6">
    <x-box>
      <form method="POST" wire:submit="createParameter" class="space-y-6">
        <x-input wire:model="parameterKey" id="parameter_key" :label="__('Parameter key')" type="text" required />
        <x-input wire:model="parameterLabel" id="parameter_label" :label="__('Parameter label')" type="text" required />
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

  <div class="mt-4">
    <h2 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('Parameters') }}</h2>
    <dl class="mt-2 divide-y divide-gray-200 dark:divide-gray-700">
      @foreach ($contact['parameters'] as $parameter)
        <div class="flex flex-col py-4 sm:flex-row">
          <dt class="w-full text-sm font-medium text-gray-500 sm:w-1/3 dark:text-gray-400">{{ $parameter['label'] }}</dt>
          <dd class="mt-1 w-full text-sm break-all text-gray-900 sm:w-2/3 dark:text-white">{{ $parameter['data'] }}</dd>
        </div>
      @endforeach
    </dl>
  </div>
</div>

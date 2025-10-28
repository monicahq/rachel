<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app.settings')] class extends Component {
  /**
   * The available permissions.
   */
  public array $permissions;

  /**
   * The current user.
   */
  #[Locked]
  public User $user;

  /**
   * The create API token form state.
   */
  public array $createApiTokenForm = [
    'name' => '',
    'permissions' => [],
  ];

  /**
   * Indicates if the plain text token is being displayed to the user.
   */
  public bool $displayingToken = false;

  /**
   * The plain text token value.
   */
  public string $plainTextToken = '';

  /**
   * Indicates if the user is currently managing an API token's permissions.
   */
  public bool $managingApiTokenPermissions = false;

  /**
   * The token that is currently having its permissions managed.
   */
  public ?PersonalAccessToken $managingPermissionsFor = null;

  /**
   * The update API token form state.
   */
  public array $updateApiTokenForm = [
    'permissions' => [],
  ];

  /**
   * Indicates if the application is confirming if an API token should be deleted.
   */
  public bool $confirmingApiTokenDeletion = false;

  /**
   * The ID of the API token being deleted.
   */
  public ?int $apiTokenIdBeingDeleted = null;

  /**
   * The default permissions.
   */
  private static array $DEFAULT_PERMISSION = ['read'];

  /**
   * Mount the component.
   */
  public function mount(): void
  {
    $this->user = Auth::user();
    $this->permissions = [
      'read' => __('Read'),
      'write' => __('Write'),
    ];
    $this->resetForm();
  }

  /**
   * Create a new API token.
   */
  public function createApiToken(): void
  {
    $this->resetErrorBag();

    $this->validate([
      'createApiTokenForm.name' => ['required', 'string', 'max:255'],
    ]);

    $this->displayTokenValue($this->user->createToken($this->createApiTokenForm['name'], $this->validPermissions($this->createApiTokenForm['permissions'])));

    $this->resetForm();

    $this->dispatch('created');
  }

  /**
   * Allow the given token's permissions to be managed.
   */
  public function manageApiTokenPermissions(int $tokenId): void
  {
    $this->managingApiTokenPermissions = true;

    $this->managingPermissionsFor = $this->user
      ->tokens()
      ->where('id', $tokenId)
      ->firstOrFail();

    $this->updateApiTokenForm['permissions'] = $this->managingPermissionsFor->abilities;
  }

  /**
   * Update the API token's permissions.
   */
  public function updateApiToken(): void
  {
    $this->managingPermissionsFor
      ->forceFill([
        'abilities' => $this->validPermissions($this->updateApiTokenForm['permissions']),
      ])
      ->save();

    $this->resetForm();

    $this->managingApiTokenPermissions = false;
  }

  /**
   * Confirm that the given API token should be deleted.
   */
  public function confirmApiTokenDeletion(int $tokenId): void
  {
    $this->confirmingApiTokenDeletion = true;

    $this->apiTokenIdBeingDeleted = $tokenId;
  }

  /**
   * Delete the API token.
   */
  public function deleteApiToken(): void
  {
    $this->user
      ->tokens()
      ->where('id', $this->apiTokenIdBeingDeleted)
      ->first()
      ->delete();

    $this->user->load('tokens');

    $this->confirmingApiTokenDeletion = false;

    $this->managingPermissionsFor = null;
  }

  protected function resetForm(): void
  {
    $this->createApiTokenForm['name'] = '';
    $this->createApiTokenForm['permissions'] = self::$DEFAULT_PERMISSION;
  }

  /**
   * Display the token value to the user.
   */
  protected function displayTokenValue(NewAccessToken $token): void
  {
    $this->displayingToken = true;

    $this->plainTextToken = explode('|', $token->plainTextToken, 2)[1];

    $this->dispatch('showing-token-modal');
  }

  private function validPermissions($permissions)
  {
    return array_values(array_intersect($permissions, array_keys($this->permissions)));
  }
}; ?>

<section class="w-full space-y-10">
  <x-box>
    <x-slot:title>
      {{ __('API token manager') }}
    </x-slot>

    <!-- Generate API Token -->
    <x-form-section submit="createApiToken">
      <x-slot name="title">
        {{ __('Create API Token') }}
      </x-slot>

      <x-slot name="description">
        {{ __('API tokens allow third-party services to authenticate with our application on your behalf.') }}
      </x-slot>

      <x-slot name="form">
        <!-- Token Name -->
        <div class="col-span-6 sm:col-span-4">
          <x-input id="name" type="text" :label="__('Token Name')" class="mt-1 block w-full" wire:model="createApiTokenForm.name" autofocus />
        </div>

        <!-- Token Permissions -->
        <div class="col-span-6">
          <x-label :value="__('Permissions')" />

          <div class="mt-2 flex gap-4 *:gap-x-2">
            @foreach ($permissions as $permission => $label)
              <flux:checkbox wire:model="createApiTokenForm.permissions" :value="$permission" :label="$label" />
            @endforeach
          </div>
        </div>
      </x-slot>

      <x-slot name="actions">
        <div class="flex items-center justify-end">
          <flux:button type="submit" variant="primary" class="w-full">
            {{ __('Create') }}
          </flux:button>

          <x-action-message class="me-3" on="created">
            {{ __('Created') }}
          </x-action-message>
        </div>
      </x-slot>
    </x-form-section>

    <!-- Token Value Modal -->
    <x-dialog-modal wire:model.live="displayingToken">
      <x-slot name="title">
        {{ __('API Token') }}
      </x-slot>

      <x-slot name="content">
        <div>
          {{ __('Please copy your new API token. For your security, it won\'t be shown again.') }}
        </div>

        <div class="flex items-center space-x-2" x-data="{
          copied: false,
          async copy() {
            try {
              await navigator.clipboard.writeText(this.$refs.plaintextToken.value)
              this.copied = true
              setTimeout(() => (this.copied = false), 1500)
            } catch (e) {
              console.warn('Could not copy to clipboard')
            }
          },
        }">
          <x-input x-ref="plaintextToken" type="text" readonly :value="$plainTextToken" class="mt-4 w-full rounded bg-gray-100 px-4 py-2 font-mono text-sm break-all text-gray-500" autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" @showing-token-modal.window="setTimeout(() => $refs.plaintextToken.select(), 250)" />
          <button @click="copy()" class="mt-4 cursor-pointer border-l border-stone-200 px-3 transition-colors dark:border-stone-600" title="{{ __('Copy to clipboard') }}">
            <flux:icon.document-duplicate x-show="!copied" variant="outline"></flux:icon.document-duplicate>
            <flux:icon.check x-show="copied" variant="solid" class="text-green-500"></flux:icon.check>
          </button>
        </div>
      </x-slot>

      <x-slot name="footer">
        <flux:button variant="filled" wire:click="$set('displayingToken', false)" wire:loading.attr="disabled">
          {{ __('Close') }}
        </flux:button>
      </x-slot>
    </x-dialog-modal>

    <!-- API Token Permissions Modal -->
    <x-dialog-modal wire:model.live="managingApiTokenPermissions">
      <x-slot name="title">
        {{ __('API Token Permissions') }}
      </x-slot>

      <x-slot name="content">
        <div class="mt-2 mb-4 flex gap-4 *:gap-x-2">
          @foreach ($permissions as $permission => $label)
            <flux:checkbox wire:model="createApiTokenForm.permissions" :value="$permission" :label="$label" />
          @endforeach
        </div>
      </x-slot>

      <x-slot name="footer">
        <flux:button variant="filled" wire:click="$set('managingApiTokenPermissions', false)" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </flux:button>

        <flux:button variant="primary" class="ms-3" wire:click="updateApiToken" wire:loading.attr="disabled">
          {{ __('Save') }}
        </flux:button>
      </x-slot>
    </x-dialog-modal>

    <!-- Delete Token Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingApiTokenDeletion">
      <x-slot name="title">
        {{ __('Delete API Token') }}
      </x-slot>

      <x-slot name="content">
        {{ __('Are you sure you would like to delete this API token?') }}
      </x-slot>

      <x-slot name="footer">
        <flux:button variant="filled" wire:click="$toggle('confirmingApiTokenDeletion')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </flux:button>

        <flux:button variant="danger" class="ms-3" wire:click="deleteApiToken" wire:loading.attr="disabled">
          {{ __('Delete') }}
        </flux:button>
      </x-slot>
    </x-confirmation-modal>
  </x-box>

  @if ($user->tokens->isNotEmpty())
    <x-box class="mb-10" padding="p-0">
      <x-slot:title>
        {{ __('Manage API Tokens') }}
      </x-slot>

      <x-slot:description>
        {{ __('You may delete any of your existing tokens if they are no longer needed.') }}
      </x-slot>

      <!-- Manage API Tokens -->
      <div class="md:grid md:grid-cols-2 md:gap-6">
        <div class="mt-5 md:col-span-2 md:mt-0">
          <!-- API Token List -->
          <div class="">
            @foreach ($user->tokens->sortBy('name') as $token)
              <div class="flex items-center justify-between border-b border-gray-200 px-4 py-4 last:border-b-0 dark:border-gray-700">
                <div class="break-all dark:text-white">
                  {{ $token->name }}
                </div>

                <div class="ms-2 flex items-center">
                  @if ($token->last_used_at)
                    <div class="text-sm text-gray-400">{{ __('Last used') }} {{ $token->last_used_at->diffForHumans() }}</div>
                  @endif

                  <flux:button size="sm" variant="ghost" class="me-2" wire:click="manageApiTokenPermissions({{ $token->id }})">
                    {{ __('Permissions') }}
                  </flux:button>

                  <flux:button size="sm" variant="danger" wire:click="confirmApiTokenDeletion({{ $token->id }})">
                    {{ __('Delete') }}
                  </flux:button>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </x-box>
  @endif
</section>

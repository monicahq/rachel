<?php

use App\Models\AuthCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component {
  #[Url(nullable: false)]
  public string $redirect_uri = '';

  #[Url(nullable: false)]
  public string $state = '';

  #[Url(nullable: false)]
  public string $code_challenge = '';

  public string $target_url = '';

  public function exception($e, $stopPropagation)
  {
    if ($e instanceof ValidationException) {
      $stopPropagation();

      return to_route('dashboard');
    }

    return null;
  }

  public function store(Request $request): void
  {
    $token = Str::random(128);

    AuthCode::create([
      'user_id' => $request->user()->id,
      'code' => $token,
      'code_challenge' => $this->code_challenge,
      'expires_at' => now()->addMinutes(5),
    ]);

    $query = http_build_query([
      'state' => $this->state,
      'code' => $token,
    ]);

    Log::info('redirecting to: ' . $this->redirect_uri . '?' . $query);

    $this->target_url = $this->redirect_uri . '?' . $query;

    $this->dispatch('redirect');
  }

  #[On('redirect')]
  public function redirectToTarget()
  {
    return redirect()->away($this->target_url, true);
  }
}; ?>

<div class="flex flex-col gap-6">
  <form method="POST" wire:submit="store" class="flex flex-col gap-6">
    @csrf

    <flux:button variant="primary" type="submit" class="w-full">
      {{ __('Authorize connection') }}
    </flux:button>

    @if ($target_url != '')
      <a href="{{ $target_url }}" target="_blank" class="dark:text-white">
        {{ __('If the redirect does not happen automatically, click here to open') }}
      </a>
    @endif
  </form>
</div>

<?php

use App\Models\AuthCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component
{
    #[Url(nullable: false)]
    public string $redirect_uri = '';

    #[Url(nullable: false)]
    public string $state = '';

    #[Url(nullable: false)]
    public string $code_challenge = '';

    public function exception($e, $stopPropagation)
    {
        if ($e instanceof ValidationException) {
            $stopPropagation();

            return to_route('dashboard');
        }

        return null;
    }

    public function store(Request $request)
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

        return redirect()->away($this->redirect_uri.'?'.$query, true);
    }
}; ?>

<div class="flex flex-col gap-6">
  <form method="POST" wire:submit="store" class="flex flex-col gap-6">
    @csrf

    <flux:button variant="primary" type="submit" class="w-full">
      {{ __('Authorize connection') }}
    </flux:button>
  </form>
</div>

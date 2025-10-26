<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.guest')] class extends Component {
  public string $redirect_uri;

  public string $state;

  public function mount(Request $request): void
  {
    $validated = Validator::make($request->all(), [
      'redirect_uri' => ['required', 'string', 'url'],
      'state' => ['required', 'string'],
      'code_challenge' => ['required', 'string'],
      'code_challenge_method' => ['required', 'string'],
    ])->validate();

    $this->state = $validated['state'];

    session()->put('redirect_uri', $validated['redirect_uri']);
    session()->put('state', $validated['state']);
    session()->put('code_challenge', $validated['code_challenge']);
    session()->put('code_challenge_method', $validated['code_challenge_method']);
  }

  public function exception($e, $stopPropagation)
  {
    if ($e instanceof ValidationException) {
      return to_route('dashboard');
    }

    return null;
  }

  public function store(Request $request)
  {
    // $token = $request->user()
    //     ->createToken($request->user()->name, ['*'], now()->addYears(1))
    //     ->plainTextToken;

    // return redirect(to: session('redirect_uri'))
    //     ->with('token', $token);

    $query = http_build_query([
      'state' => $this->state,
      'code' => '',
    ]);

    return $this->redirect($this->redirect_uri . '?' . $query, true);
  }
}; ?>

<div class="flex flex-col gap-6">
  <form method="POST" action="{{ route('user.token') }}" class="flex flex-col gap-6">
    @csrf

    <flux:button variant="primary" type="submit" class="w-full">
      {{ __('Connect device') }}
    </flux:button>
  </form>
</div>

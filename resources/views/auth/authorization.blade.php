<x-layouts.guest>
  <div class="flex flex-col gap-6">
    <form method="POST" action="{{
      route('passport.authorizations.approve', [
        'state' => $request->state,
        'client_id' => $client->id,
        'auth_token' => $authToken,
      ])
    }}" class="flex flex-col gap-6">
      @csrf

      <div class="space-y-5 text-center">
        <flux:button variant="primary" type="submit" class="w-full">
          {{ __('Authorize Device') }}
        </flux:button>
      </div>
    </form>
  </div>
</x-layouts.guest>

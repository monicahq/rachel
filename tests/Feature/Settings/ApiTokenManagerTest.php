<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Volt\Volt;

test('api token can be created', function (): void {
    $this->actingAs($user = User::factory()->create());

    $response = Volt::test('settings.api-token-manager')
        ->set('createApiTokenForm.name', 'token-name')
        ->call('createApiToken');

    $response->assertHasNoErrors();

    $this->assertDatabaseHas('personal_access_tokens', [
        'name' => 'token-name',
        'tokenable_id' => $user->id,
        'abilities' => '["read"]',
    ]);
});

<?php

declare(strict_types=1);

use App\Models\User;
use Livewire\Volt\Volt;

test('authorize page requires password confirmation when enabled', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/auth/authorize')
        ->assertRedirect(route('password.confirm'));
});

test('authorize page redirects to the correct URI', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()]);

    Volt::withQueryParams([
        'redirect_uri' => 'https://example.com/callback',
        'state' => 'test-state',
        'code_challenge' => 'test-code_challenge',
    ])
        ->test('auth.authorize')
        ->set('redirect_uri', 'https://example.com/callback')
        ->set('state', 'test-state')
        ->set('code_challenge', 'test-code_challenge')
        ->call('store')
        ->assertDispatched('redirect')
        ->dispatch('redirect')
        ->assertRedirectContains('https://example.com/callback')
        ->assertOk();

    $this->assertDatabaseHas('auth_codes', [
        'user_id' => $user->id,
        'code_challenge' => 'test-code_challenge',
    ]);
});

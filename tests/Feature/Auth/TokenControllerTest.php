<?php

declare(strict_types=1);

use App\Models\User;

test('sanctum token page requires password confirmation when enabled', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/sanctum/token', [
            'device_name' => 'test-device',
        ])
        ->assertRedirect(route('password.confirm'));
});

test('sanctum token is created', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->post('/sanctum/token', [
            'device_name' => 'test-device',
        ])
        ->assertCreated();

    expect($response->json())->toBeString();

    $this->assertDatabaseHas('personal_access_tokens', [
        'name' => 'test-device',
        'tokenable_id' => $user->id,
    ]);
});

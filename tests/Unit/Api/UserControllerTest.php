<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('api current user get', function (): void {
    Sanctum::actingAs(
        $user = User::factory()->create(),
        ['read']
    );

    $response = $this->getJson('/api/v1/user')
        ->assertOk();

    expect($response->json('data.id'))
        ->toBe($user->id);
});

test('api user get', function (): void {
    Sanctum::actingAs(
        $user = User::factory()->create(),
        ['read']
    );

    $response = $this->getJson('/api/v1/users/'.$user->id)
        ->assertOk();

    expect($response->json('data.id'))
        ->toBe($user->id);
});

<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Passport\Passport;

test('api current user get', function (): void {
    Passport::actingAs(
        $user = User::factory()->create(),
        ['read']
    );

    $response = $this->get('/api/user')
        ->assertOk();

    expect($response->json('data.id'))
        ->toBe($user->id);
});

test('api user get', function (): void {
    Passport::actingAs(
        $user = User::factory()->create(),
        ['read']
    );

    $response = $this->get('/api/users/'.$user->id)
        ->assertOk();

    expect($response->json('data.id'))
        ->toBe($user->id);
});

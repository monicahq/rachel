<?php

declare(strict_types=1);

use App\Models\AuthCode;
use App\Models\User;

it('generates token for mobile devices', function (): void {
    $user = User::factory()->create();

    $codeVerifier = 'challenge';
    $codeChallenge = strtr(mb_rtrim(
        base64_encode(hash('sha256', $codeVerifier, true)), '='), '+/', '-_');

    AuthCode::factory()->create([
        'user_id' => $user->id,
        'code_challenge' => $codeChallenge,
    ]);

    $response = $this->withHeaders([
        'User-Agent' => 'UnitTest/1.0',
    ])
        ->post('/auth/token', [
            'code' => 'token',
            'code_verifier' => $codeVerifier,
        ])
        ->assertCreated();

    $response->assertJsonStructure([
        'token_type',
        'access_token',
        'expires_in',
    ]);

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'name' => 'UnitTest/1.0',
    ]);
    $this->assertDatabaseMissing('auth_codes', [
        'user_id' => $user->id,
    ]);
});

it('fails if code is invalid', function (): void {
    $user = User::factory()->create();

    AuthCode::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->post('/auth/token', [
        'code' => 'wrong',
        'code_verifier' => 'codeVerifier',
    ])
        ->assertNotFound();

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);
    $this->assertDatabaseHas('auth_codes', [
        'user_id' => $user->id,
    ]);
});

it('fails if challenge is invalid', function (): void {
    $user = User::factory()->create();

    AuthCode::factory()->create([
        'user_id' => $user->id,
    ]);

    $this->post('/auth/token', [
        'code' => 'token',
        'code_verifier' => 'wrong',
    ])
        ->assertForbidden();

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
    ]);
    $this->assertDatabaseMissing('auth_codes', [
        'user_id' => $user->id,
    ]);
});

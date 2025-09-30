<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Str;

test('api token permissions can be updated', function () {
    $this->actingAs($user = User::factory()->create());

    $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['delete', 'read'],
    ]);

    expect($user->fresh()->tokens->first())
        ->can('delete')->toBeTrue()
        ->can('create')->toBeFalse()
        ->can('missing-permission')->toBeFalse();
});

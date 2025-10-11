<?php

declare(strict_types=1);

use App\Models\User;

it('belongs to an account', function (): void {
    $user = User::factory()->create();

    expect($user->account()->exists())->toBeTrue();
});

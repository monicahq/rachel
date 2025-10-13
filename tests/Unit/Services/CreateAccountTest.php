<?php

declare(strict_types=1);

use App\Models\User;
use App\Services\CreateAccount;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;

it('creates an account', function (): void {
    \Illuminate\Support\Facades\Date::setTestNow(\Illuminate\Support\Facades\Date::create(2018, 1, 1));

    $user = (new CreateAccount(
        email: 'monica.geller@friends.com',
        password: 'password',
        name: 'Monica Geller',
    ))->execute();

    expect($user)->toBeInstanceOf(User::class);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'email' => 'monica.geller@friends.com',
        'name' => 'Monica Geller',
    ]);
});

it('cant create an account with the same email', function (): void {
    User::factory()->create([
        'email' => 'monica.geller@friends.com',
    ]);

    expect(fn (): User => (new CreateAccount(
        email: 'monica.geller@friends.com',
        password: 'password',
        name: 'Monica Geller',
    ))->execute())->toThrow(UniqueConstraintViolationException::class);
});

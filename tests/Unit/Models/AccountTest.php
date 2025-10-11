<?php

declare(strict_types=1);

use App\Models\Account;
use App\Models\User;

it('has many users', function (): void {
    $account = Account::factory()->create();
    User::factory()->count(2)->create([
        'account_id' => $account->id,
    ]);

    expect($account->users)->toHaveCount(2);
});

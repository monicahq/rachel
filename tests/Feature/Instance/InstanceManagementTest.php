<?php

declare(strict_types=1);

use App\Models\User;

test('it can access the instance management page', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('instances.index'))->assertOk();
});

test('it can access the account page', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('instances.accounts.show', $user->account->id))->assertOk();
});

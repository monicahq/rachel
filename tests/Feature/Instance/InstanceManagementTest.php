<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\UserActivityLog;

test('it can access the instance management page', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('instances.index'))->assertOk();
});

test('it can access the account page', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('instances.accounts.show', $user->account->id))->assertOk();
});

test('it displays persisted account activities', function (): void {
    $this->actingAs($user = User::factory()->create());

    UserActivityLog::query()->create([
        'account_id' => $user->account_id,
        'user_id' => $user->id,
        'actor_user_id' => $user->id,
        'category' => UserActivityLog::CATEGORY_PROFILE,
        'action' => UserActivityLog::ACTION_PROFILE_NAME_UPDATED,
        'metadata' => [],
    ]);

    $this->get(route('instances.accounts.show', $user->account->id))
        ->assertOk()
        ->assertSee('Profile information updated');
});

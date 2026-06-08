<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\UserActivityLog;

test('activity settings page is displayed', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('settings.activity'))->assertOk();
});

test('activity settings page only shows current user logs', function (): void {
    $user = User::factory()->create();
    $other = User::factory()->create();

    UserActivityLog::factory()->create([
        'account_id' => $user->account_id,
        'user_id' => $user->id,
        'actor_user_id' => $user->id,
        'category' => UserActivityLog::CATEGORY_PROFILE,
        'action' => UserActivityLog::ACTION_PROFILE_NAME_UPDATED,
        'metadata' => [],
    ]);

    UserActivityLog::factory()->create([
        'account_id' => $other->account_id,
        'user_id' => $other->id,
        'actor_user_id' => $other->id,
        'category' => UserActivityLog::CATEGORY_PROFILE,
        'action' => UserActivityLog::ACTION_PROFILE_NAME_UPDATED,
        'metadata' => [],
    ]);

    $this->actingAs($user)
        ->get(route('settings.activity'))
        ->assertSee('Profile information updated')
        ->assertDontSee('No activity yet.');
});

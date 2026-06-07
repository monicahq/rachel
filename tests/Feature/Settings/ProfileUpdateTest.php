<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\UserActivityLog;
use Livewire\Livewire;

test('profile page is displayed', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('profile.edit'))->assertOk();
});

test('profile information can be updated', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->email)->toEqual('test@example.com');
    expect($user->email_verified_at)->toBeNull();

    $this->assertDatabaseHas('user_activity_logs', [
        'user_id' => $user->id,
        'account_id' => $user->account_id,
        'action' => UserActivityLog::ACTION_PROFILE_NAME_AND_EMAIL_UPDATED,
    ]);
});

test('email verification status is unchanged when email address is unchanged', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.profile')
        ->set('name', 'Test User')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();

    $this->assertDatabaseHas('user_activity_logs', [
        'user_id' => $user->id,
        'account_id' => $user->account_id,
        'action' => UserActivityLog::ACTION_PROFILE_NAME_UPDATED,
    ]);
});

test('user can delete their account', function (): void {
    $user = User::factory()->create();
    $account = $user->account;
    $userId = $user->id;
    $accountId = $account->id;

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertDatabaseHas('user_activity_logs', [
        'user_id' => $userId,
        'account_id' => $accountId,
        'action' => UserActivityLog::ACTION_ACCOUNT_DELETED,
    ]);
});

test('correct password must be provided to delete account', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('pages::settings.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});

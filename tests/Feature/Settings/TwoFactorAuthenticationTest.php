<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\UserActivityLog;
use Livewire\Livewire;

test('two factor settings page can be rendered', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('two-factor.show'))
        ->assertOk()
        ->assertSee('Disabled');
});

test('two factor settings page requires password confirmation when enabled', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('two-factor.show'));

    $response->assertRedirect(route('password.confirm'));
});

test('two factor authentication disabled when confirmation abandoned between requests', function (): void {
    $user = User::factory()->create();

    $user->forceFill([
        'two_factor_secret' => encrypt('test-secret'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
        'two_factor_confirmed_at' => null,
    ])->save();

    $this->actingAs($user);

    $component = Livewire::test('pages::settings.two-factor');

    $component->assertSet('twoFactorEnabled', false);

    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'two_factor_secret' => null,
        'two_factor_recovery_codes' => null,
    ]);
});

test('two factor disable action is logged', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('pages::settings.two-factor')
        ->call('disable')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('user_activity_logs', [
        'user_id' => $user->id,
        'account_id' => $user->account_id,
        'action' => UserActivityLog::ACTION_SECURITY_TWO_FACTOR_DISABLED,
    ]);
});

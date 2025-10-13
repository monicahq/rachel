<?php

declare(strict_types=1);

use App\Models\User;

test('settings page is displayed', function (): void {
    $this->actingAs($user = User::factory()->create());

    $this->get(route('settings.index'))->assertOk();
});

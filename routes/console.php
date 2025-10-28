<?php

declare(strict_types=1);

use App\Models\AuthCode;
use Illuminate\Support\Facades\Schedule;

Schedule::command('sanctum:prune-expired --hours=24')->dailyAt('01:00');
Schedule::call(function (): void {
    AuthCode::where('expires_at', '<', now())->delete();
})->dailyAt('02:00');

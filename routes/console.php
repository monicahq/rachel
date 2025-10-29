<?php

declare(strict_types=1);

use App\Models\AuthCode;
use Illuminate\Support\Facades\Schedule;

Schedule::command('sanctum:prune-expired --hours=24')->dailyAt('01:00');
Schedule::command('model:prune')->dailyAt('01:05');
Schedule::command('queue:prune-batches')->dailyAt('01:10');
Schedule::command('queue:prune-failed')->dailyAt('01:15');
Schedule::call(function (): void {
    AuthCode::where('expires_at', '<', now())->delete();
})->dailyAt('02:00');

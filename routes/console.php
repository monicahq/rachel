<?php

declare(strict_types=1);

use App\Models\AuthCode;
use Illuminate\Support\Facades\Schedule;

Schedule::command('sanctum:prune-expired --hours=24')->daily();
Schedule::call(function (): void {
    AuthCode::where('expires_at', '<', now())->delete();
})->daily();

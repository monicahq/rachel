<?php

declare(strict_types=1);

use App\Http\Controllers\ApiUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('user', [ApiUserController::class, 'user']);
    Route::apiResource('users', ApiUserController::class)->only(['index', 'show']);
});

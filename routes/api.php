<?php

declare(strict_types=1);

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VaultController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->name('api.')->group(function (): void {
    Route::get('user', [UserController::class, 'user']);
    Route::apiResource('users', UserController::class)->only(['index', 'show']);
    Route::apiResource('vaults', VaultController::class);
});

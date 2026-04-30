<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\TokenController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::livewire('login', 'pages::auth.login')
        ->name('login');

    Route::livewire('register', 'pages::auth.register')
        ->name('register');

    Route::livewire('forgot-password', 'pages::auth.forgot-password')
        ->name('password.request');

    Route::livewire('reset-password/{token}', 'pages::auth.reset-password')
        ->name('password.reset');

    Route::post('auth/token', TokenController::class);
});

Route::middleware('auth')->group(function (): void {
    Route::livewire('verify-email', 'pages::auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::livewire('auth/authorize', 'pages::auth.authorize')
        ->middleware(['password.confirm']);
});

Route::post('logout', App\Livewire\Actions\Logout::class)
    ->name('logout');

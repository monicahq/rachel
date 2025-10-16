<?php

declare(strict_types=1);

use App\Http\Controllers\Settings;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', fn (): View => view('welcome'))->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/settings', [Settings\SettingsController::class, 'index'])->name('settings.index');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(['password.confirm'])
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';

<?php

declare(strict_types=1);

use App\Http\Controllers\Instances;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', fn (): View => view('welcome'))->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function (): void {

    Volt::route('vaults', 'vaults.index')->name('vaults.index');
    Volt::route('vaults/{vault}', 'vaults.show')
        ->name('vaults.show')
        ->missing(fn () => to_route('vaults.index'));

    Route::redirect('settings', 'settings/profile')->name('settings.index');
    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(['password.confirm'])
        ->name('two-factor.show');

    Route::get('/instance', [Instances\InstancesController::class, 'index'])->name('instances.index');
    Route::get('/instance/accounts/1', [Instances\InstancesAccountsController::class, 'show'])->name('instances.accounts.show');
});

require __DIR__.'/auth.php';

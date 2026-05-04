<?php

declare(strict_types=1);

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): View => view('welcome'))->name('home');

Route::livewire('dashboard', 'pages::dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function (): void {

    Route::livewire('vaults', 'pages::vaults.index')->name('vaults.index');
    Route::livewire('vaults/{vault}', 'pages::vaults.show')
        ->name('vaults.show')
        ->missing(fn () => to_route('vaults.index'));
    Route::livewire('vaults/{vault}/contacts', 'pages::contacts.index')->name('contacts.index');
    Route::livewire('vaults/{vault}/contacts/create', 'pages::contacts.create')->name('contacts.create');
    Route::livewire('vaults/{vault}/contacts/{contact}', 'pages::contacts.show')->name('contacts.show');

    Route::redirect('settings', 'settings/profile')->name('settings.index');
    Route::livewire('settings/api-token-manager', 'pages::settings.api-token-manager')->name('settings.api-token-manager');
    Route::livewire('settings/profile', 'pages::settings.profile')->name('profile.edit');
    Route::livewire('settings/password', 'pages::settings.password')->name('password.edit');
    Route::livewire('settings/appearance', 'pages::settings.appearance')->name('appearance.edit');

    Route::livewire('settings/two-factor', 'pages::settings.two-factor')
        ->middleware(['password.confirm'])
        ->name('two-factor.show');

    Route::livewire('instance', 'pages::instances.index')->name('instances.index');
    Route::livewire('instance/accounts/{account}', 'pages::instances.accounts.show')->name('instances.accounts.show');
});

require __DIR__.'/auth.php';

<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::middleware(['auth', 'verified'])->group(function (){
    Volt::route('/products', 'products.index')->name('products.index');
    Volt::route('/products/create', 'products.create')->name('products.create');
    Volt::route('/products/edit', 'products.edit')->name('products.edit');
    Volt::route('/categories', 'categories.index')->name('categories.index');
    Volt::route('/users', 'users.index')->name('users.index');
    Volt::route('/suppliers', 'suppliers.index')->name('suppliers.index');
});

require __DIR__.'/auth.php';

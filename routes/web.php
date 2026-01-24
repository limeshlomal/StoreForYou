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
    Volt::route('/products/bulk-upload', 'products.bulk-upload')->name('products.bulk-upload');
    Volt::route('/products/{product}/edit', 'products.edit')->name('products.edit');
    Volt::route('/categories', 'categories.index')->name('categories.index');
    Volt::route('/users', 'users.index')->name('users.index');
    Volt::route('/users/roles-index', 'users.roles-index')->name('users.roles.index');
    Volt::route('/users/create-roles', 'users.create-roles')->name('users.roles.create');
    Volt::route('/users/permissions-index', 'users.permission-index')->name('users.permissions.index');
    Volt::route('/users/create-permissions', 'users.create-permissions')->name('users.permissions.create');
    Volt::route('/suppliers', 'suppliers.index')->name('suppliers.index');
    Volt::route('/barcode', 'barcodes.index')->name('barcodes.index');
    Volt::route('/barcode/print', 'barcodes.print')->name('barcodes.print');
    Volt::route('/purchasings', 'purchasings.index')->name('purchasings.index');
    Volt::route('/purchasings/create', 'purchasings.create')->name('purchasings.create');
    Volt::route('/purchasings/{purchasing}/edit', 'purchasings.edit')->name('purchasings.edit');
    Volt::route('/invoices/pos', 'invoices.pos')->name('invoices.pos');
    Volt::route('/invoices', 'invoices.index')->name('invoices.index');
    Volt::route('/invoices/hold', 'invoices.hold')->name('invoices.hold');
    Volt::route('/invoices/return', 'invoices.return')->name('invoices.return');
    Volt::route('/invoices/due', 'invoices.due')->name('invoices.due');
});


require __DIR__.'/auth.php';

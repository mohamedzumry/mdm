<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\DashboardComponent;
use App\Livewire\MasterBrandComponent;
use App\Livewire\MasterCategoryComponent;
use App\Livewire\MasterItemComponent;
use App\Livewire\UserComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get('/dashboard', DashboardComponent::class)->name('dashboard');

    // Brands
    Route::get('/users', UserComponent::class)->name('users');

    // Brands
    Route::get('/brands', MasterBrandComponent::class)->name('brands');

    // Categories
    Route::get('/categories', MasterCategoryComponent::class)->name('categories');

    // Items
    Route::get('/items', MasterItemComponent::class)->name('items');
});

require __DIR__.'/auth.php';

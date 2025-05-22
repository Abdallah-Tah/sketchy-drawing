<?php

use App\Livewire\SketchBoard;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/sketch', SketchBoard::class)->name('sketch');

Route::get('/math-kids', \App\Livewire\MathKidsGame::class)
    ->middleware(['auth', 'verified'])
    ->name('math-kids');

require __DIR__ . '/auth.php';

<?php

use App\Livewire\SketchBoard;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\HomePage;

Route::get('/', HomePage::class)->name('home');

Route::view('dashboard', 'dashboard')
    ->name('dashboard');

Route::get('/sketch', SketchBoard::class)->name('sketch');

Route::get('/math-kids', \App\Livewire\MathKidsGame::class)
    ->name('math-kids');

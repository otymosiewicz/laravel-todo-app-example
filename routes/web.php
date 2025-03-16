<?php

use App\Http\Controllers\SharedTaskController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/task/shared/{hash}', [SharedTaskController::class, 'show'])
    ->name('shared_task');

require __DIR__.'/auth.php';

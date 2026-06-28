<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Admin dashboard
Route::middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])
            ->name('dashboard.admin');
    });

// Apprenant dashboard
Route::middleware(['auth', 'verified', 'role:apprenant'])
    ->group(function () {
        Route::get('/dashboard/apprenant', [DashboardController::class, 'apprenant'])
            ->name('dashboard.apprenant');
    });

// Profile (admin only for now)
Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
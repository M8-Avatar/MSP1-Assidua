<?php

use App\Http\Controllers\AlerteController;
use App\Http\Controllers\ApprenantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

require __DIR__.'/auth.php';

// Route generique : redirige vers le dashboard selon le role (appelee par les controllers Breeze)
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('dashboard.admin');
    }
    return redirect()->route('dashboard.apprenant');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::resource('apprenants', ApprenantController::class);
    Route::resource('formations', FormationController::class);
    Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
    Route::post('/presences', [PresenceController::class, 'store'])->name('presences.store');
    Route::get('/presences/{formation_id}/{date}/pdf', [PdfController::class, 'generateFeuillePresence'])
        ->name('presences.pdf')
        ->where('date', '\d{4}-\d{2}-\d{2}');
    Route::get('/alertes', [AlerteController::class, 'index'])->name('alertes.index');
    Route::post('/alertes/{alerte}/vue', [AlerteController::class, 'markAsRead'])->name('alertes.mark-read');
});

// Apprenant
Route::middleware(['auth', 'verified', 'role:apprenant'])->group(function () {
    Route::get('/dashboard/apprenant', [DashboardController::class, 'apprenant'])->name('dashboard.apprenant');
});

// Profil (tous les utilisateurs connectes)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
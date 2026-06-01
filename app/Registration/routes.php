<?php

use Illuminate\Support\Facades\Route;
use App\Registration\Controllers\PublicRegistrationController;
use App\Registration\Controllers\AdminRegistrationController;

// Public Registration Routes
Route::prefix('tournament/registration')->name('registration.')->middleware(['web'])->group(function () {
    Route::get('/', [PublicRegistrationController::class, 'instructions'])->name('instructions');
    
    // Phase 1: Participation Form & Payment
    Route::get('/phase-1', [PublicRegistrationController::class, 'showPhase1'])->name('phase1');
    Route::post('/phase-1', [PublicRegistrationController::class, 'submitPhase1'])->name('phase1.submit');
    
    // Phase 2: Full Roster Form & Payment
    Route::get('/phase-2', [PublicRegistrationController::class, 'showPhase2Access'])->name('phase2.access');
    Route::post('/phase-2/verify', [PublicRegistrationController::class, 'verifyPhase2Access'])->name('phase2.verify');
    Route::get('/phase-2/{code}', [PublicRegistrationController::class, 'showPhase2'])->name('phase2.form');
    Route::post('/phase-2/{code}', [PublicRegistrationController::class, 'submitPhase2'])->name('phase2.submit');
    
    // Paystack Callback
    Route::get('/callback', [PublicRegistrationController::class, 'paymentCallback'])->name('callback');
});

// Admin Registration Routes
Route::prefix('admin/registrations')->name('admin.registrations.')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/', [AdminRegistrationController::class, 'index'])->name('index');
    Route::get('/settings', [AdminRegistrationController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminRegistrationController::class, 'updateSettings'])->name('settings.update');
    Route::get('/{id}', [AdminRegistrationController::class, 'show'])->name('show');
    Route::delete('/{id}', [AdminRegistrationController::class, 'destroy'])->name('destroy');
});

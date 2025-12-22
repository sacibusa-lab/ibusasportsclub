<?php

use App\Http\Controllers\TournamentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminSponsorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TournamentController::class, 'index'])->name('home');
Route::get('/fixtures', [TournamentController::class, 'fixtures'])->name('fixtures');
Route::get('/results', [TournamentController::class, 'results'])->name('results');
Route::get('/table', [TournamentController::class, 'table'])->name('table');
Route::get('/teams', [TournamentController::class, 'teams'])->name('teams');
Route::get('/teams/{id}', [TournamentController::class, 'team'])->name('team.details');
Route::get('/players/{id}', [TournamentController::class, 'player'])->name('player.details');
Route::get('/knockout', [TournamentController::class, 'knockout'])->name('knockout');
Route::get('/stats', [TournamentController::class, 'stats'])->name('stats');
Route::get('/match/{id}', [TournamentController::class, 'matchDetails'])->name('match.details');

// News Routes
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

// Interview Routes
Route::get('/interviews/{id}', [\App\Http\Controllers\InterviewController::class, 'show'])->name('interviews.show');

// Auth Routes
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/teams', [AdminController::class, 'teams'])->name('teams');
    Route::post('/teams', [AdminController::class, 'storeTeam'])->name('teams.store');
    Route::put('/teams/{id}', [AdminController::class, 'updateTeam'])->name('teams.update');
    Route::delete('/teams/{id}', [AdminController::class, 'destroyTeam'])->name('teams.destroy');
    Route::get('/fixtures', [AdminController::class, 'fixtures'])->name('fixtures');
    Route::post('/fixtures', [AdminController::class, 'storeFixture'])->name('fixtures.store');
    Route::get('/fixtures/{id}/edit', [AdminController::class, 'editFixture'])->name('fixtures.edit');
    Route::put('/fixtures/{id}', [AdminController::class, 'updateFixture'])->name('fixtures.update');
    Route::delete('/fixtures/{id}', [AdminController::class, 'destroyFixture'])->name('fixtures.destroy');
    Route::post('/fixtures/{id}/results', [AdminController::class, 'updateResult'])->name('results.update');

    // Match Events
    Route::post('/matches/{id}/events', [AdminController::class, 'storeEvent'])->name('matches.events.store');
    Route::delete('/events/{id}', [AdminController::class, 'destroyEvent'])->name('events.destroy');

    // Admin News Routes
    Route::prefix('news')->name('news.')->group(function() {
        Route::get('/', [AdminNewsController::class, 'index'])->name('index');
        Route::get('/create', [AdminNewsController::class, 'create'])->name('create');
        Route::post('/', [AdminNewsController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminNewsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminNewsController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminNewsController::class, 'destroy'])->name('destroy');

        // Categories & Tags
        Route::get('/categories', [AdminNewsController::class, 'categories'])->name('categories');
        Route::post('/categories', [AdminNewsController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{id}', [AdminNewsController::class, 'destroyCategory'])->name('categories.destroy');

        Route::get('/tags', [AdminNewsController::class, 'tags'])->name('tags');
        Route::post('/tags', [AdminNewsController::class, 'storeTag'])->name('tags.store');
        Route::delete('/tags/{id}', [AdminNewsController::class, 'destroyTag'])->name('tags.destroy');

        Route::post('/upload-image', [AdminNewsController::class, 'uploadImage'])->name('upload-image');
    });

    // Admin Sponsors Routes
    Route::prefix('sponsors')->name('sponsors.')->group(function() {
        Route::get('/', [AdminSponsorController::class, 'index'])->name('index');
        Route::post('/', [AdminSponsorController::class, 'store'])->name('store');
        Route::put('/{id}', [AdminSponsorController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminSponsorController::class, 'destroy'])->name('destroy');
    });

    // Admin Players Routes
    Route::prefix('players')->name('players.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\AdminPlayerController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\AdminPlayerController::class, 'store'])->name('store');
        Route::put('/{id}', [\App\Http\Controllers\Admin\AdminPlayerController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminPlayerController::class, 'destroy'])->name('destroy');
    });

    // Admin Interviews Routes
    Route::prefix('interviews')->name('interviews.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\AdminInterviewController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdminInterviewController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\AdminInterviewController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\AdminInterviewController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\AdminInterviewController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminInterviewController::class, 'destroy'])->name('destroy');
    });

    // Admin Stories Routes
    Route::prefix('stories')->name('stories.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\AdminStoryController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\AdminStoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\AdminStoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\AdminStoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminStoryController::class, 'destroy'])->name('destroy');
        Route::delete('/items/{id}', [\App\Http\Controllers\Admin\AdminStoryController::class, 'destroyItem'])->name('items.destroy');
    });

    // Admin Settings Routes
    Route::get('/settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])->name('settings.update');
    Route::post('/fix-storage', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'fixStorage'])->name('fix-storage');
    Route::post('/sync-storage', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'syncStorage'])->name('sync-storage');

    // Admin Analytics Routes
    Route::get('/analytics', [\App\Http\Controllers\Admin\AdminAnalyticsController::class, 'index'])->name('analytics.index');
});

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
Route::get('/gallery', [TournamentController::class, 'gallery'])->name('gallery');
Route::get('/match/{id}', [TournamentController::class, 'matchDetails'])->name('match.details');
Route::get('/match/{id}/feed', [TournamentController::class, 'matchFeed'])->name('match.feed');

// News Routes
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::post('/news/{post}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('news.comments.store');

// Interview Routes
Route::get('/interviews/{id}', [\App\Http\Controllers\InterviewController::class, 'show'])->name('interviews.show');

// Auth Routes
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.post');
Route::get('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Admin Auth Routes
Route::get('/admin/login', [\App\Http\Controllers\Auth\AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\Auth\AdminLoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [\App\Http\Controllers\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');

// Predictor League Routes
Route::get('/predictor', [\App\Http\Controllers\PredictorController::class, 'index'])->name('predictor.index');
Route::post('/predictor/predict', [\App\Http\Controllers\PredictorController::class, 'predict'])->name('predictor.predict')->middleware('auth');
Route::get('/dashboard', [\App\Http\Controllers\FanDashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::post('/push-subscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'subscribe']);
    Route::post('/push-unsubscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'unsubscribe']);
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/teams', [AdminController::class, 'teams'])->name('teams');
    Route::post('/teams', [AdminController::class, 'storeTeam'])->name('teams.store');
    Route::put('/teams/{id}', [AdminController::class, 'updateTeam'])->name('teams.update');
    Route::delete('/teams/{id}', [AdminController::class, 'destroyTeam'])->name('teams.destroy');
    Route::get('/fixtures', [AdminController::class, 'fixtures'])->name('fixtures');
    Route::post('/fixtures', [AdminController::class, 'storeFixture'])->name('fixtures.store');
    Route::get('/fixtures/{id}/edit', [AdminController::class, 'editFixture'])->name('fixtures.edit');
    Route::match(['put', 'post'], '/fixtures/{id}', [AdminController::class, 'updateFixture'])->name('fixtures.update');
    Route::post('/matches/{id}/start', [AdminController::class, 'startMatch'])->name('matches.start');
    Route::delete('/fixtures/{id}', [AdminController::class, 'destroyFixture'])->name('fixtures.destroy');
    Route::post('/fixtures/{id}/results', [AdminController::class, 'updateResult'])->name('results.update');

    // Match Events
    Route::post('/matches/{match}/events', [AdminController::class, 'storeEvent'])->name('matches.events.store');
    Route::delete('/events/{id}', [AdminController::class, 'destroyEvent'])->name('matches.events.destroy');

    // Match Gallery
    Route::post('/matches/{id}/gallery', [AdminController::class, 'uploadGalleryImages'])->name('matches.gallery.upload');
    Route::delete('/matches/{matchId}/gallery/{imageId}', [AdminController::class, 'deleteGalleryImage'])->name('matches.gallery.delete');

    // Gallery Management
    Route::post('/matches/{match}/gallery', [AdminController::class, 'storeMatchImage'])->name('matches.gallery.store');
    Route::delete('/gallery/{id}', [AdminController::class, 'destroyMatchImage'])->name('matches.gallery.destroy');

    // Competitions Management
    Route::resource('competitions', \App\Http\Controllers\Admin\AdminCompetitionController::class);
    Route::resource('groups', \App\Http\Controllers\Admin\AdminGroupController::class);

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

    // Admin Referees Routes
    Route::prefix('referees')->name('referees.')->group(function() {
        Route::get('/', [\App\Http\Controllers\Admin\AdminRefereeController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\AdminRefereeController::class, 'store'])->name('store');
        Route::put('/{id}', [\App\Http\Controllers\Admin\AdminRefereeController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminRefereeController::class, 'destroy'])->name('destroy');
    });

    // Admin Settings Routes
    Route::get('/settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])->name('settings.update');
    Route::post('/fix-storage', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'fixStorage'])->name('fix-storage');
    Route::post('/sync-storage', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'syncStorage'])->name('sync-storage');

    // Admin Analytics Routes
    Route::get('/analytics', [\App\Http\Controllers\Admin\AdminAnalyticsController::class, 'index'])->name('analytics.index');

    // Admin Stats Routes
    Route::get('/stats', [\App\Http\Controllers\Admin\AdminStatsController::class, 'index'])->name('stats.index');

    // Admin Predictor Routes
    Route::get('/predictor', [\App\Http\Controllers\Admin\AdminPredictorController::class, 'index'])->name('predictor.index');
    Route::get('/predictor/user/{user}', [\App\Http\Controllers\Admin\AdminPredictorController::class, 'show'])->name('predictor.show');

    // Admin Comment Routes
    Route::get('/comments', [\App\Http\Controllers\Admin\AdminCommentController::class, 'index'])->name('comments.index');
    Route::post('/comments/{id}/toggle', [\App\Http\Controllers\Admin\AdminCommentController::class, 'toggleApproval'])->name('comments.toggle');
    Route::delete('/comments/{id}', [\App\Http\Controllers\Admin\AdminCommentController::class, 'destroy'])->name('comments.destroy');
});

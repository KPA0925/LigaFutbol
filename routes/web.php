<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\PresidentController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\GoalController;
use App\Http\Controllers\Admin\FootballMatchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;

use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\UserTeamController;
use App\Http\Controllers\User\UserPlayerController;
use App\Http\Controllers\User\UserPresidentController;
use App\Http\Controllers\User\UserMatchController;
use App\Http\Controllers\User\UserGoalController;
use App\Http\Controllers\User\UserCommentController;
use App\Http\Controllers\User\UserReportController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified', 'role:user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    Route::get('/equipos', [UserTeamController::class, 'index'])->name('teams.index');
    Route::get('/jugadores', [UserPlayerController::class, 'index'])->name('players.index');
    Route::get('/presidentes', [UserPresidentController::class, 'index'])->name('presidents.index');
    Route::get('/partidos', [UserMatchController::class, 'index'])->name('matches.index');
    Route::get('/goles', [UserGoalController::class, 'index'])->name('goals.index');
    Route::get('/comentarios', [UserCommentController::class, 'index'])->name('comments.index');
    Route::post('/comentarios', [UserCommentController::class, 'store'])->name('comments.store');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('teams', TeamController::class)->names('teams');
        Route::resource('players', PlayerController::class)->names('players');
        Route::resource('presidents', PresidentController::class)->names('presidents');
        Route::resource('goals', GoalController::class)->names('goals');
        Route::resource('matches', FootballMatchController::class)->names('matches');
        Route::resource('comments', CommentController::class)->names('comments');

        Route::get('/export', [ExportController::class, 'export'])->name('export.excel');
    });

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

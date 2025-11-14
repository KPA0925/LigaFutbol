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
use App\Http\Controllers\Admin\ReportController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('user/Dashboard'))->name('dashboard');
    Route::get('/equipos', fn() => Inertia::render('user/Equipo'))->name('equipos');
    Route::get('/jugadores', fn() => Inertia::render('user/Jugador'))->name('jugadores');
    Route::get('/presidentes', fn() => Inertia::render('user/Presidente'))->name('presidentes');
    Route::get('/comentarios', fn() => Inertia::render('user/Comentario'))->name('comentarios');
    Route::get('/estadisticas', fn() => Inertia::render('user/Estadistica'))->name('estadisticas');
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

        // Informes
        Route::get('reports/players', [ReportController::class, 'players'])->name('reports.players');
        Route::get('reports/matches', [ReportController::class, 'matches'])->name('reports.matches');
        Route::get('reports/goals', [ReportController::class, 'goals'])->name('reports.goals');
    });

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

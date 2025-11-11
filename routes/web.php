<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\MatchController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\PlayerController;
use App\Http\Controllers\Admin\PresidentController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Admin\CommentController;

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
    ->as('admin.')
    ->group(function () {
        Route::get('/', fn() => Inertia::render('admin/Admin'))->name('dashboard');
        Route::get('/partidos', fn() => Inertia::render('admin/Partidos/Index'))->name('partidos');
        Route::get('/equipos', fn() => Inertia::render('admin/Equipos/Index'))->name('equipos');
        Route::get('/jugadores', fn() => Inertia::render('admin/Jugadores/Index'))->name('jugadores');
        Route::get('/presidentes', fn() => Inertia::render('admin/Presidentes/Index'))->name('presidentes');
        Route::get('/comentarios', fn() => Inertia::render('admin/Comentarios/Index'))->name('comentarios');
        Route::get('/estadisticas', fn() => Inertia::render('admin/Estadisticas/Index'))->name('estadisticas');
    });

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

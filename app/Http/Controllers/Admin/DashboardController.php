<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use App\Models\Goal;
use App\Models\Comment;
use App\Models\FootballMatch;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | 1. Contadores globales
        |--------------------------------------------------------------------------
        */
        $stats = [
            'players'  => Player::count(),
            'teams'    => Team::count(),
            'goals'    => Goal::count(),
            'comments' => Comment::count(),
            'matches'  => FootballMatch::count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | 2. Máximos goleadores (Top 5)
        |--------------------------------------------------------------------------
        */
        $topScorers = Player::withCount('goals')
            ->orderByDesc('goals_count')
            ->take(5)
            ->get(['id', 'fullname', 'goals_count']);

        /*
        |--------------------------------------------------------------------------
        | 3. Goles por equipo (Top 5)
        |--------------------------------------------------------------------------
        |
        | Se toma cada equipo, se suman los goles de sus jugadores.
        |
        */
        $teamGoals = Team::with(['players.goals'])
            ->get()
            ->map(function ($team) {
                return [
                    'team'  => $team->name,
                    'goals' => $team->players->sum(fn($p) => $p->goals->count()),
                ];
            })
            ->sortByDesc('goals')
            ->take(5)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | 4. Estadios y partidos jugados allí
        |--------------------------------------------------------------------------
        |
        | Como no existe un campo "stadium" en la tabla matches:
        | Se considera que el partido se juega en el estadio del equipo local.
        |
        */
        $stadiumMatches = FootballMatch::with('homeTeam')
            ->get()
            ->groupBy(fn($m) => $m->homeTeam?->stadium ?? 'Estadio desconocido')
            ->map(fn($group, $stadium) => [
                'stadium' => $stadium,
                'matches' => $group->count(),
            ])
            ->values();

        /*
        |--------------------------------------------------------------------------
        | 5. Retornar dashboard
        |--------------------------------------------------------------------------
        */
        return Inertia::render('admin/dashboard/Index', [
            'stats'          => $stats,
            'topScorers'     => $topScorers,
            'teamGoals'      => $teamGoals,
            'stadiumMatches' => $stadiumMatches,
        ]);
    }
}

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
        // Contadores globales
        $totalPlayers = Player::count();
        $totalTeams = Team::count();
        $totalGoals = Goal::count();
        $totalComments = Comment::count();
        $totalMatches = FootballMatch::count();

        // Goles por jugador (para gráfico)
        $topScorers = Player::withCount('goals')
            ->orderByDesc('goals_count')
            ->take(5)
            ->get(['id', 'fullname', 'goals_count']);

        // Goles por equipo
        $teamGoals = Team::with(['players.goals'])
            ->get()
            ->map(function ($team) {
                return [
                    'team' => $team->name,
                    'goals' => $team->players->sum(fn($p) => $p->goals->count()),
                ];
            })
            ->sortByDesc('goals')
            ->take(5)
            ->values();

        return Inertia::render('admin/dashboard/Index', [
            'stats' => [
                'players' => $totalPlayers,
                'teams' => $totalTeams,
                'goals' => $totalGoals,
                'comments' => $totalComments,
                'matches' => $totalMatches,
            ],
            'topScorers' => $topScorers,
            'teamGoals' => $teamGoals,
        ]);
    }
}

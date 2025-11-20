<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Player;
use App\Models\Goal;
use App\Models\FootballMatch;
use App\Models\Comment;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'players'  => Player::count(),
            'teams'    => Team::count(),
            'goals'    => Goal::count(),
            'comments' => Comment::count(),
            'matches'  => FootballMatch::count(),
        ];

        $topScorers = Player::withCount('goals')
            ->orderByDesc('goals_count')
            ->take(5)
            ->get(['id', 'fullname', 'goals_count']);

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

        $stadiumMatches = FootballMatch::with('homeTeam')
            ->get()
            ->groupBy(fn($m) => $m->homeTeam?->stadium ?? 'Estadio desconocido')
            ->map(fn($group, $stadium) => [
                'stadium' => $stadium,
                'matches' => $group->count(),
            ])
            ->values();

        return Inertia::render('user/Dashboard', [
            'stats'          => $stats,
            'topScorers'     => $topScorers,
            'teamGoals'      => $teamGoals,
            'stadiumMatches' => $stadiumMatches,
        ]);
    }
}

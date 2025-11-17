<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Player;
use App\Models\Goal;
use App\Models\FootballMatch;
use Inertia\Inertia;

class UserDashboardController extends Controller
{
    public function index()
    {
        $totalTeams = Team::count();
        $totalPlayers = Player::count();
        $totalGoals = Goal::count();
        $totalMatches = FootballMatch::count();

        return Inertia::render('user/Dashboard', [
            'totalTeams'   => $totalTeams,
            'totalPlayers' => $totalPlayers,
            'totalMatches' => $totalMatches,
            'totalGoals'   => $totalGoals,
        ]);
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use Inertia\Inertia;

class UserGoalController extends Controller
{
    public function index()
    {
        $goals = Goal::with(['player.team', 'match.homeTeam', 'match.awayTeam'])
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('user/Goal', [
            'goals' => $goals,
        ]);
    }
}

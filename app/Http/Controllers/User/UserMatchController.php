<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Inertia\Inertia;

class UserMatchController extends Controller
{
    public function index()
    {
        $matches = FootballMatch::with([
            'teamData',
            'homeTeam',
            'awayTeam',
            'goals',
        ])
            ->get()
            ->map(function ($match) {
                return [
                    'id' => $match->id,
                    'season' => $match->season,
                    'match_date_time' => $match->match_date_time,
                    'home_team' => $match->homeTeam,
                    'away_team' => $match->awayTeam,
                    'goal_home' => $match->homeGoals()->count(),
                    'goal_away' => $match->awayGoals()->count(),
                ];
            });

        return Inertia::render('user/Match', [
            'matches' => $matches,
        ]);
    }
}
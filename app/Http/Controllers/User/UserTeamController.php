<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Inertia\Inertia;

class UserTeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('players')
            ->with('presidents')
            ->orderBy('name')
            ->get();

        return Inertia::render('user/Team', [
            'teams' => $teams,
        ]);
    }
}

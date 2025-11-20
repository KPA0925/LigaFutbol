<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\FootballMatch;
use App\Models\FootballMatchTeam;
use App\Models\Goal;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('players')->latest()->get();

        return Inertia::render('admin/Teams/Index', [
            'teams' => $teams,
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/Teams/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'founded_date' => 'required|date',
            'city' => 'required|string|max:255',
            'stadium' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1000',
        ]);

        Team::create($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Equipo creado correctamente.');
    }

    public function edit(Team $team)
    {
        return Inertia::render('admin/Teams/Edit', [
            'team' => $team,
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'founded_date' => 'required|date',
            'city' => 'required|string|max:255',
            'stadium' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1000',
        ]);

        $team->update($validated);

        return redirect()->route('admin.teams.index')->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Team $team)
    {
        $matchIds = FootballMatchTeam::where('id_home_team', $team->id)
            ->orWhere('id_away_team', $team->id)
            ->pluck('id_match');

        if ($matchIds->isNotEmpty()) {

            Goal::whereIn('id_match', $matchIds)->delete();

            FootballMatchTeam::whereIn('id_match', $matchIds)->delete();

            FootballMatch::whereIn('id', $matchIds)->delete();
        }

        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Equipo y todos sus partidos asociados fueron eliminados correctamente.');
    }
}

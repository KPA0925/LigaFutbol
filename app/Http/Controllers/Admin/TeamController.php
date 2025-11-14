<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $team->delete();

        return redirect()->route('admin.teams.index')->with('success', 'Equipo eliminado correctamente.');
    }
}

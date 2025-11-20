<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Goal;
use App\Models\Player;
use App\Models\FootballMatch;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::with(['player.team', 'match.homeTeam', 'match.awayTeam'])
            ->latest()
            ->get();

        return Inertia::render('admin/Goals/Index', [
            'goals' => $goals,
        ]);
    }

    public function create()
    {
        $matches = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->select('id', 'match_date_time', 'season')
            ->get();

        $players = Player::with('team:id,name')
            ->select('id', 'fullname', 'id_team')
            ->get();

        return Inertia::render('admin/Goals/Create', [
            'matches' => $matches,
            'players'  => $players,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'minute' => 'required|integer|min:1|max:120',
            'description' => 'nullable|string|max:255',
            'id_player' => 'required|exists:players,id',
            'id_match' => 'required|exists:matches,id',
        ]);

        $match = FootballMatch::findOrFail($request->id_match);

        if (now()->lt($match->match_date_time)) {
            return back()
                ->withErrors(['id_match' => 'No puedes registrar goles en un partido que aún no ha comenzado.'])
                ->withInput();
        }

        Goal::create($validated);

        return redirect()->route('admin.goals.index')
            ->with('success', 'Gol registrado correctamente.');
    }


    public function edit(Goal $goal)
    {
        $goal->load(['player.team', 'match.homeTeam', 'match.awayTeam']);

        $players = Player::with('team')
            ->get(['id', 'fullname', 'id_team']);

        $matches = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->select('id', 'match_date_time', 'season')
            ->get();

        return Inertia::render('admin/Goals/Edit', [
            'goal'     => $goal,
            'players'  => $players,
            'matches'  => $matches,
        ]);
    }

    public function update(Request $request, Goal $goal)
    {
        $validated = $request->validate([
            'minute' => 'required|integer|min:1|max:120',
            'description' => 'nullable|string|max:255',
            'id_player' => 'required|exists:players,id',
            'id_match' => 'required|exists:matches,id',
        ]);

        $match = FootballMatch::findOrFail($request->id_match);

        if ($match->match_date_time > now()) {
            return back()->withErrors([
                'id_match' => 'Solo puedes registrar goles en partidos que ya hayan comenzado.',
            ]);
        }

        $goal->update($validated);

        return redirect()->route('admin.goals.index')
            ->with('success', 'Gol actualizado correctamente.');
    }

    public function destroy(Goal $goal)
    {
        $goal->delete();

        return redirect()->route('admin.goals.index')
            ->with('success', 'Gol eliminado correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\FootballMatchTeam;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FootballMatchController extends Controller
{
    public function index()
    {
        $matches = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->orderBy('match_date_time', 'desc')
            ->get();

        return Inertia::render('admin/Matches/Index', [
            'matches' => $matches
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/Matches/Create', [
            'teams' => Team::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'match_date_time' => 'required|date',
            'season' => 'required|string',
            'id_home_team' => 'required|exists:teams,id',
            'id_away_team' => 'required|exists:teams,id|different:id_home_team',
        ]);

        $match = FootballMatch::create([
            'match_date_time' => $request->match_date_time,
            'season' => $request->season,
            'goal_home' => 0,
            'goal_away' => 0,
        ]);

        FootballMatchTeam::create([
            'id_match' => $match->id,
            'id_home_team' => $request->id_home_team,
            'id_away_team' => $request->id_away_team,
        ]);

        return redirect()->route('admin.matches.index')->with('success', 'Partido creado correctamente');
    }

    public function edit($id)
    {
        $match = FootballMatch::with(['homeTeam', 'awayTeam'])->findOrFail($id);

        $pivot = FootballMatchTeam::where('id_match', $id)->first();

        $match->match_date_time_formatted = date('Y-m-d\TH:i', strtotime($match->match_date_time));

        return Inertia::render('admin/Matches/Edit', [
            'match' => $match,
            'teams' => Team::select('id', 'name')->orderBy('name')->get(),
            'id_home_team' => $pivot->id_home_team ?? null,
            'id_away_team' => $pivot->id_away_team ?? null,
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'match_date_time' => 'required|date',
            'season' => 'required|string',
            'id_home_team' => 'required|exists:teams,id',
            'id_away_team' => 'required|exists:teams,id|different:id_home_team',
        ]);

        $match = FootballMatch::findOrFail($id);

        $match->update([
            'match_date_time' => $request->match_date_time,
            'season' => $request->season,
        ]);

        $pivot = FootballMatchTeam::where('id_match', $match->id)->first();
        $pivot->update([
            'id_home_team' => $request->id_home_team,
            'id_away_team' => $request->id_away_team,
        ]);

        return redirect()->route('admin.matches.index')->with('success', 'Partido actualizado correctamente');
    }

    public function destroy($id)
    {
        $match = FootballMatch::findOrFail($id);
        $match->delete();

        return redirect()->route('admin.matches.index')->with('success', 'Partido eliminado correctamente');
    }
}

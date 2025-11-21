<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use App\Models\FootballMatchTeam;
use App\Models\Team;
use App\Models\President;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class FootballMatchController extends Controller
{
    public function index()
    {
        $matches = FootballMatch::with([
            'teamData',
            'homeTeam',
            'awayTeam',
            'goals',
        ])->orderBy('created_at', 'desc')->get()
            ->map(function ($match) {
                return [
                    'id' => $match->id,
                    'season' => $match->season,
                    'match_date_time' => $match->match_date_time,
                    // 'created_at' => $match->created_at,
                    'home_team' => $match->homeTeam,
                    'away_team' => $match->awayTeam,
                    'goal_home' => $match->homeGoals()->count(),
                    'goal_away' => $match->awayGoals()->count(),
                ];
            });

        return Inertia::render('admin/Matches/Index', [
            'matches' => $matches,
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

        $matchDateTime = \Carbon\Carbon::parse($request->match_date_time);

        if ($matchDateTime->isBefore(today())) {
            throw ValidationException::withMessages([
                'match_date_time' => 'No se pueden programar partidos en fechas anteriores a hoy.',
            ]);
        }

        $matchDate = $matchDateTime->toDateString();

        $sameDayMatch = FootballMatchTeam::where(function ($q) use ($request) {
            $q->where(function ($sub) use ($request) {
                $sub->where('id_home_team', $request->id_home_team)
                    ->where('id_away_team', $request->id_away_team);
            })
                ->orWhere(function ($sub) use ($request) {
                    $sub->where('id_home_team', $request->id_away_team)
                        ->where('id_away_team', $request->id_home_team);
                });
        })
            ->whereHas('match', function ($q) use ($matchDate) {
                $q->whereDate('match_date_time', $matchDate);
            })
            ->exists();

        if ($sameDayMatch) {
            throw ValidationException::withMessages([
                'match_date_time' => 'No se puede programar el mismo partido en la misma fecha (sin importar la hora).',
            ]);
        }

        $exists = FootballMatchTeam::where(function ($q) use ($request) {
            $q->where('id_home_team', $request->id_home_team)
                ->orWhere('id_away_team', $request->id_home_team);
        })
            ->whereHas('match', function ($q) use ($request) {
                $q->where('match_date_time', $request->match_date_time);
            })
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'match_date_time' => 'Este equipo ya tiene un partido programado en esta fecha y hora.',
            ]);
        }

        $homeHasPresident = President::where('id_team', $request->id_home_team)->exists();
        $awayHasPresident = President::where('id_team', $request->id_away_team)->exists();

        if (!$homeHasPresident || !$awayHasPresident) {
            throw ValidationException::withMessages([
                'id_home_team' => !$homeHasPresident ? 'El equipo local no tiene presidente asignado.' : null,
                'id_away_team' => !$awayHasPresident ? 'El equipo visitante no tiene presidente asignado.' : null,
            ]);
        }

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

        return redirect()->route('admin.matches.index')
            ->with('success', 'Partido creado correctamente');
    }


    public function edit($id)
    {
        $match = FootballMatch::findOrFail($id);
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

        return redirect()->route('admin.matches.index')
            ->with('success', 'Partido actualizado correctamente');
    }

    public function destroy($id)
    {
        $match = FootballMatch::findOrFail($id);
        $match->delete();

        return redirect()->route('admin.matches.index')
            ->with('success', 'Partido eliminado correctamente');
    }
}

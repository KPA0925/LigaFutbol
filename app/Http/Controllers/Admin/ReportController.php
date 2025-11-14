<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Goal;
use App\Models\FootballMatch;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GenericExport;

class ReportController extends Controller
{
    public function players()
    {
        $players = Player::with(['team.presidents', 'goals'])->get()->map(function ($player) {
            return [
                'Jugador' => $player->fullname,
                'Equipo' => $player->team->name ?? 'Sin equipo',
                'Presidente' => optional($player->team->presidents)->name ?? 'N/A',
                'Goles' => $player->goals->count(),
                'Posición' => $player->position,
            ];
        });

        return Inertia::render('admin/Reports/Players', [
            'players' => $players,
        ]);
    }

    public function matches()
    {
        $matches = FootballMatch::with('teams')->get();

        return Inertia::render('admin/Reports/Matches', [
            'matches' => $matches,
        ]);
    }

    public function goals()
    {
        $goals = Goal::with(['player', 'match'])->get();

        $chartData = $goals->groupBy('player.fullname')->map->count();

        return Inertia::render('admin/Reports/Goals', [
            'goals' => $goals,
            'chartData' => $chartData,
        ]);
    }

    public function exportPlayers()
    {
        $data = Player::with(['team.presidents', 'goals'])->get()->map(function ($p) {
            return [
                'Jugador' => $p->fullname,
                'Equipo' => $p->team->name ?? '',
                'Goles' => $p->goals->count(),
                'Presidente' => optional($p->team->presidents)->name ?? '',
            ];
        });

        return Excel::download(new GenericExport($data), 'reporte_jugadores.xlsx');
    }
}

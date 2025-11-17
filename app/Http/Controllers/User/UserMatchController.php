<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FootballMatch;
use Inertia\Inertia;

class UserMatchController extends Controller
{
    public function index()
    {
        $matches = FootballMatch::with(['homeTeam', 'awayTeam'])
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'equipoA' => $m->homeTeam?->name ?? 'N/A',
                    'equipoB' => $m->awayTeam?->name ?? 'N/A',
                    'marcador' => "{$m->goal_home} - {$m->goal_away}",
                    'estado' => $this->status($m),
                    'fecha' => $m->match_date_time,
                ];
            });

        return Inertia::render('user/Match', [
            'matches' => $matches,
        ]);
    }

    private function status($m)
    {
        if (!is_null($m->goal_home) && !is_null($m->goal_away)) {
            return 'Finalizado';
        }

        return now()->lt($m->match_date_time)
            ? 'En espera'
            : 'Jugando';
    }
}

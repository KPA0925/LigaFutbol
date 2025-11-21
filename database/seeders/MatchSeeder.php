<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FootballMatch;
use App\Models\FootballMatchTeam;
use App\Models\Team;

class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $matchDates = [
            '2025-02-01 18:00',
            '2025-02-03 20:00',
            '2025-02-06 16:30',
            '2025-02-10 19:45',
            '2025-02-12 15:00',
            '2025-02-15 20:00',
            '2025-02-18 18:10',
            '2025-02-20 19:00',
            '2025-02-25 17:30',
            '2025-02-28 20:30',
        ];

        $teams = Team::pluck('id')->toArray();

        foreach ($matchDates as $date) {

            // seleccionar dos equipos distintos
            $home = $teams[array_rand($teams)];

            // excluir el equipo home
            $awayCandidates = array_diff($teams, [$home]);

            // seleccionar away
            $away = $awayCandidates[array_rand($awayCandidates)];

            // crear partido
            $match = FootballMatch::create([
                'match_date_time' => $date,
                'goal_home' => 0,
                'goal_away' => 0,
                'season' => '2025',
            ]);

            // crear relación
            FootballMatchTeam::create([
                'id_match'   => $match->id,
                'id_home_team' => $home,
                'id_away_team' => $away,
            ]);
        }
    }
}

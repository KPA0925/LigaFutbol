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
        $teams = Team::pluck('id')->toArray();

        for ($i = 1; $i <= 15; $i++) {

            $home = fake()->randomElement($teams);
            $away = fake()->randomElement(array_diff($teams, [$home]));

            $match = FootballMatch::create([
                'match_date_time' => fake()->dateTimeBetween('-10 days', '+10 days'),
                'goal_home' => 0,
                'goal_away' => 0,
                'season' => '2025',
            ]);

            FootballMatchTeam::create([
                'id_match' => $match->id,
                'id_home_team' => $home,
                'id_away_team' => $away,
            ]);
        }
    }
}

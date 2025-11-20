<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Goal;
use App\Models\FootballMatch;
use App\Models\Player;

class GoalSeeder extends Seeder
{
    public function run(): void
    {
        $matches = FootballMatch::all();

        foreach ($matches as $match) {

            // entre 0 y 5 goles por partido
            $numGoals = rand(0, 5);

            for ($i = 0; $i < $numGoals; $i++) {

                $player = Player::inRandomOrder()->first();

                Goal::create([
                    'minute' => rand(1, 90),
                    'description' => 'Gol marcado en jugada ofensiva',
                    'id_player' => $player->id,
                    'id_match' => $match->id,
                ]);
            }
        }
    }
}

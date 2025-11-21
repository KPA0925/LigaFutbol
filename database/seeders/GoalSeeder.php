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
        $goals = [
            ['minute' => 12],
            ['minute' => 44],
            ['minute' => 67],
            ['minute' => 30],
            ['minute' => 5],
            ['minute' => 81],
            ['minute' => 55],
            ['minute' => 22],
            ['minute' => 74],
            ['minute' => 88],
        ];

        $matches = FootballMatch::all();
        $players = Player::pluck('id')->toArray();

        foreach ($matches as $index => $match) {
            Goal::create([
                'minute' => $goals[$index]['minute'],
                'description' => 'Gol en jugada colectiva',
                'id_player' => $players[array_rand($players)],
                'id_match' => $match->id,
            ]);
        }
    }
}

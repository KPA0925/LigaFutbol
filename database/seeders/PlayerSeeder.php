<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\Team;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $positions = ['Portero', 'Defensa', 'Mediocampista', 'Delantero'];

        $teams = Team::all();

        foreach ($teams as $team) {
            for ($i = 1; $i <= 15; $i++) {
                Player::create([
                    'fullname' => fake()->name(),
                    'birth_date' => fake()->date(),
                    'position' => $positions[array_rand($positions)],
                    'id_team' => $team->id,
                ]);
            }
        }
    }
}

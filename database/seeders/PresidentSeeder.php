<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\President;
use App\Models\Team;

class PresidentSeeder extends Seeder
{
    public function run(): void
    {
        $teams = Team::all();

        foreach ($teams as $team) {
            President::create([
                'dni' => fake()->numerify('########'),
                'name' => fake()->firstName(),
                'lastname' => fake()->lastName(),
                'birth_date' => fake()->date(),
                'elected_date' => fake()->date(),
                'id_team' => $team->id,
            ]);
        }
    }
}

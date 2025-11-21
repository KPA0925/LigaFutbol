<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\Team;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $players = [
            ['fullname' => 'David Ospina', 'birth_date' => '1988-08-31', 'position' => 'Portero'],
            ['fullname' => 'Andrés Murillo', 'birth_date' => '1996-03-15', 'position' => 'Defensa'],
            ['fullname' => 'Jhon Lucumí', 'birth_date' => '1998-06-02', 'position' => 'Defensa'],
            ['fullname' => 'Yairo Moreno', 'birth_date' => '1995-04-20', 'position' => 'Mediocampista'],
            ['fullname' => 'Daniel Ruiz', 'birth_date' => '2001-07-30', 'position' => 'Mediocampista'],
            ['fullname' => 'Kevin Castaño', 'birth_date' => '2000-10-24', 'position' => 'Mediocampista'],
            ['fullname' => 'Luis Díaz', 'birth_date' => '1997-01-13', 'position' => 'Delantero'],
            ['fullname' => 'Miguel Borja', 'birth_date' => '1993-01-26', 'position' => 'Delantero'],
            ['fullname' => 'Harold Preciado', 'birth_date' => '1994-06-01', 'position' => 'Delantero'],
            ['fullname' => 'Radamel Falcao García', 'birth_date' => '1986-02-10', 'position' => 'Delantero'],
        ];

        $teams = Team::all();

        foreach ($teams as $team) {
            foreach ($players as $p) {
                Player::create([
                    'fullname' => $p['fullname'],
                    'birth_date' => $p['birth_date'],
                    'position' => $p['position'],
                    'id_team' => $team->id,
                ]);
            }
        }
    }
}

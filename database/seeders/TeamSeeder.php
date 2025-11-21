<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $teams = [
            [
                'name' => 'Millonarios FC',
                'founded_date' => '1946-06-18',
                'city' => 'Bogota',
                'stadium' => 'Estadio Nemesio Camacho El Campin',
                'capacity' => 36000,
            ],
            [
                'name' => 'Atletico Nacional',
                'founded_date' => '1947-04-07',
                'city' => 'Medellin',
                'stadium' => 'Estadio Atanasio Girardot',
                'capacity' => 40500,
            ],
            [
                'name' => 'Deportivo Cali',
                'founded_date' => '1912-11-23',
                'city' => 'Cali',
                'stadium' => 'Estadio Deportivo Cali',
                'capacity' => 52000,
            ],
            [
                'name' => 'Junior FC',
                'founded_date' => '1924-08-07',
                'city' => 'Barranquilla',
                'stadium' => 'Estadio Metropolitano Roberto Melendez',
                'capacity' => 46500,
            ],
            [
                'name' => 'Independiente Santa Fe',
                'founded_date' => '1941-02-28',
                'city' => 'Bogota',
                'stadium' => 'Estadio Nemesio Camacho El Campin',
                'capacity' => 36000,
            ],
            [
                'name' => 'Once Caldas',
                'founded_date' => '1961-03-17',
                'city' => 'Manizales',
                'stadium' => 'Estadio Palogrande',
                'capacity' => 32000,
            ],
            [
                'name' => 'Deportes Tolima',
                'founded_date' => '1954-12-18',
                'city' => 'Ibague',
                'stadium' => 'Estadio Manuel Murillo Toro',
                'capacity' => 28000,
            ],
            [
                'name' => 'Atletico Bucaramanga',
                'founded_date' => '1949-03-11',
                'city' => 'Bucaramanga',
                'stadium' => 'Estadio Alfonso LLopez',
                'capacity' => 25000,
            ],
            [
                'name' => 'Union Magdalena',
                'founded_date' => '1950-04-20',
                'city' => 'Santa Marta',
                'stadium' => 'Estadio Sierra Nevada',
                'capacity' => 12000,
            ],
            [
                'name' => 'Pereira FC',
                'founded_date' => '1944-02-12',
                'city' => 'Pereira',
                'stadium' => 'Estadio Hernan Ramirez Villegas',
                'capacity' => 30000,
            ],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            'Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena',
            'Cúcuta', 'Bucaramanga', 'Santa Marta', 'Pereira', 'Manizales',
            'Neiva', 'Ibagué', 'Montería', 'Tunja', 'Villavicencio'
        ];

        for ($i = 1; $i <= 15; $i++) {
            Team::create([
                'name' => "Equipo $i",
                'founded_date' => fake()->date(),
                'city' => $cities[$i - 1],
                'stadium' => "Estadio $i",
                'capacity' => rand(15000, 60000),
            ]);
        }
    }
}

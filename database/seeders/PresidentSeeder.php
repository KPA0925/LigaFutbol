<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\President;
use App\Models\Team;

class PresidentSeeder extends Seeder
{
    public function run(): void
    {
        $presidents = [
            ['dni' => '1025487965', 'name' => 'Enrique', 'lastname' => 'Camacho', 'birth_date' => '1960-03-14', 'elected_date' => '2015-01-20'],
            ['dni' => '1002547896', 'name' => 'Mauricio', 'lastname' => 'Navarro', 'birth_date' => '1970-11-02', 'elected_date' => '2022-03-11'],
            ['dni' => '1036985471', 'name' => 'Luis Fernando', 'lastname' => 'Alvarez', 'birth_date' => '1965-05-22', 'elected_date' => '2019-08-03'],
            ['dni' => '1014578923', 'name' => 'Alejandro', 'lastname' => 'Arteta', 'birth_date' => '1980-09-16', 'elected_date' => '2021-02-10'],
            ['dni' => '1045896321', 'name' => 'Eduardo', 'lastname' => 'Mendez', 'birth_date' => '1962-12-01', 'elected_date' => '2019-07-17'],
            ['dni' => '1006987412', 'name' => 'Tulio', 'lastname' => 'Castro', 'birth_date' => '1958-04-11', 'elected_date' => '2014-03-01'],
            ['dni' => '1032569874', 'name' => 'Cesar', 'lastname' => 'Camargo', 'birth_date' => '1974-10-19', 'elected_date' => '2020-06-22'],
            ['dni' => '1023658741', 'name' => 'Carlos', 'lastname' => 'Perez', 'birth_date' => '1972-07-25', 'elected_date' => '2023-01-05'],
            ['dni' => '1047852369', 'name' => 'Rafael', 'lastname' => 'Martinez', 'birth_date' => '1968-02-08', 'elected_date' => '2018-09-14'],
            ['dni' => '1052369874', 'name' => 'Javier', 'lastname' => 'Ospina', 'birth_date' => '1961-06-03', 'elected_date' => '2017-10-29'],
        ];

        $teams = Team::all();

        foreach ($teams as $index => $team) {
            President::create([
                'dni' => $presidents[$index]['dni'],
                'name' => $presidents[$index]['name'],
                'lastname' => $presidents[$index]['lastname'],
                'birth_date' => $presidents[$index]['birth_date'],
                'elected_date' => $presidents[$index]['elected_date'],
                'id_team' => $team->id,
            ]);
        }
    }
}

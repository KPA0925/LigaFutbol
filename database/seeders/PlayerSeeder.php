<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\Team;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $playersByTeam = [

            // ------------------------------------------------------------
            // 1. Millonarios
            // ------------------------------------------------------------
            [
                ['fullname' => 'Alvaro Montero', 'birth_date' => '1995-03-29', 'position' => 'Portero'],
                ['fullname' => 'Andres Llinas', 'birth_date' => '1997-05-25', 'position' => 'Defensa'],
                ['fullname' => 'Jorge Arias', 'birth_date' => '1992-07-13', 'position' => 'Defensa'],
                ['fullname' => 'Juan Carlos Pereira', 'birth_date' => '1999-02-19', 'position' => 'Mediocampista'],
                ['fullname' => 'Daniel Ruiz', 'birth_date' => '2001-07-30', 'position' => 'Mediocampista'],
                ['fullname' => 'Larry Vasquez', 'birth_date' => '1992-09-11', 'position' => 'Mediocampista'],
                ['fullname' => 'Beckham Castro', 'birth_date' => '2004-04-10', 'position' => 'Delantero'],
                ['fullname' => 'Leonardo Castro', 'birth_date' => '1992-06-14', 'position' => 'Delantero'],
                ['fullname' => 'Santiago Giordana', 'birth_date' => '1995-08-12', 'position' => 'Delantero'],
                ['fullname' => 'Omar Bertel', 'birth_date' => '1996-09-18', 'position' => 'Defensa'],
            ],

            // ------------------------------------------------------------
            // 2. Atlético Nacional
            // ------------------------------------------------------------
            [
                ['fullname' => 'Kevin Mier', 'birth_date' => '2000-05-18', 'position' => 'Portero'],
                ['fullname' => 'Daniel Mantilla', 'birth_date' => '1996-03-22', 'position' => 'Mediocampista'],
                ['fullname' => 'Cristian Zapata', 'birth_date' => '1986-09-30', 'position' => 'Defensa'],
                ['fullname' => 'Jhon Solis', 'birth_date' => '2004-01-12', 'position' => 'Mediocampista'],
                ['fullname' => 'Jefferson Duque', 'birth_date' => '1987-03-17', 'position' => 'Delantero'],
                ['fullname' => 'Dorlan Pabon', 'birth_date' => '1988-01-24', 'position' => 'Delantero'],
                ['fullname' => 'Andres Roman', 'birth_date' => '1995-10-22', 'position' => 'Defensa'],
                ['fullname' => 'Eric Ramirez', 'birth_date' => '1998-11-20', 'position' => 'Delantero'],
                ['fullname' => 'Nelson Deossa', 'birth_date' => '2000-01-22', 'position' => 'Mediocampista'],
                ['fullname' => 'Gonzalo Castellani', 'birth_date' => '1987-09-10', 'position' => 'Mediocampista'],
            ],

            // ------------------------------------------------------------
            // 3. Deportivo Cali
            // ------------------------------------------------------------
            [
                ['fullname' => 'Humberto Acevedo', 'birth_date' => '1997-05-31', 'position' => 'Portero'],
                ['fullname' => 'Juan Castilla', 'birth_date' => '2004-02-08', 'position' => 'Mediocampista'],
                ['fullname' => 'Kevin Salazar', 'birth_date' => '1996-04-15', 'position' => 'Mediocampista'],
                ['fullname' => 'Luis Haquin', 'birth_date' => '1997-11-15', 'position' => 'Defensa'],
                ['fullname' => 'Michael Ortega', 'birth_date' => '1991-04-06', 'position' => 'Mediocampista'],
                ['fullname' => 'Teofilo Gutierrez', 'birth_date' => '1985-05-17', 'position' => 'Delantero'],
                ['fullname' => 'Kevin Velasco', 'birth_date' => '1996-04-14', 'position' => 'Delantero'],
                ['fullname' => 'German Mera', 'birth_date' => '1990-03-14', 'position' => 'Defensa'],
                ['fullname' => 'Jhon Vasquez', 'birth_date' => '1995-07-15', 'position' => 'Delantero'],
                ['fullname' => 'Daniel Mantilla', 'birth_date' => '1996-03-22', 'position' => 'Mediocampista'],
            ],

            // ------------------------------------------------------------
            // 4. Junior
            // ------------------------------------------------------------
            [
                ['fullname' => 'Santiago Mele', 'birth_date' => '1997-09-06', 'position' => 'Portero'],
                ['fullname' => 'Walmer Pacheco', 'birth_date' => '1994-11-28', 'position' => 'Defensa'],
                ['fullname' => 'Ivan Rossi', 'birth_date' => '1993-11-01', 'position' => 'Mediocampista'],
                ['fullname' => 'Victor Cantillo', 'birth_date' => '1994-10-15', 'position' => 'Mediocampista'],
                ['fullname' => 'Deiber Caicedo', 'birth_date' => '2000-03-22', 'position' => 'Delantero'],
                ['fullname' => 'Carlos Bacca', 'birth_date' => '1986-09-08', 'position' => 'Delantero'],
                ['fullname' => 'Steven Rodriguez', 'birth_date' => '1992-01-10', 'position' => 'Delantero'],
                ['fullname' => 'Jose Enamorado', 'birth_date' => '2001-05-29', 'position' => 'Delantero'],
                ['fullname' => 'Andres Colorado', 'birth_date' => '1998-12-10', 'position' => 'Mediocampista'],
                ['fullname' => 'Cesar Haydar', 'birth_date' => '2001-03-22', 'position' => 'Defensa'],
            ],

            // ------------------------------------------------------------
            // 5. Santa Fe
            // ------------------------------------------------------------
            [
                ['fullname' => 'Antony Silva', 'birth_date' => '1984-02-27', 'position' => 'Portero'],
                ['fullname' => 'Marlos Torres', 'birth_date' => '1998-07-11', 'position' => 'Defensa'],
                ['fullname' => 'Kevin Londoño', 'birth_date' => '1993-03-23', 'position' => 'Mediocampista'],
                ['fullname' => 'Fabian Sambueza', 'birth_date' => '1988-08-01', 'position' => 'Mediocampista'],
                ['fullname' => 'Hugo Rodallega', 'birth_date' => '1985-07-25', 'position' => 'Delantero'],
                ['fullname' => 'Cristian Marrugo', 'birth_date' => '1985-07-23', 'position' => 'Mediocampista'],
                ['fullname' => 'Kelvin Osorio', 'birth_date' => '1993-10-05', 'position' => 'Mediocampista'],
                ['fullname' => 'Nicolas Hernandez', 'birth_date' => '1998-02-08', 'position' => 'Defensa'],
                ['fullname' => 'Jersson Gonzalez', 'birth_date' => '2002-09-14', 'position' => 'Delantero'],
                ['fullname' => 'Christian Marrugo', 'birth_date' => '1985-07-23', 'position' => 'Mediocampista'],
            ],

            // ------------------------------------------------------------
            // 6. Once Caldas
            // ------------------------------------------------------------
            [
                ['fullname' => 'Eder Chaux', 'birth_date' => '1991-05-20', 'position' => 'Portero'],
                ['fullname' => 'David Gomez', 'birth_date' => '1995-10-10', 'position' => 'Defensa'],
                ['fullname' => 'Bryan Cordoba', 'birth_date' => '1998-01-25', 'position' => 'Defensa'],
                ['fullname' => 'Dayro Moreno', 'birth_date' => '1985-09-16', 'position' => 'Delantero'],
                ['fullname' => 'Alejandro Garcia', 'birth_date' => '2003-06-22', 'position' => 'Mediocampista'],
                ['fullname' => 'Billy Arce', 'birth_date' => '1998-07-12', 'position' => 'Delantero'],
                ['fullname' => 'Sebastian Guzman', 'birth_date' => '1996-02-28', 'position' => 'Mediocampista'],
                ['fullname' => 'Luis Payares', 'birth_date' => '1990-12-10', 'position' => 'Defensa'],
                ['fullname' => 'Jacobo Escobar', 'birth_date' => '2004-03-14', 'position' => 'Delantero'],
                ['fullname' => 'Nelson Quiñones', 'birth_date' => '2002-10-17', 'position' => 'Delantero'],
            ],

            // ------------------------------------------------------------
            // 7. Deportes Tolima
            // ------------------------------------------------------------
            [
                ['fullname' => 'William Cuesta', 'birth_date' => '1992-02-19', 'position' => 'Portero'],
                ['fullname' => 'Julian Quiñones', 'birth_date' => '1989-03-14', 'position' => 'Defensa'],
                ['fullname' => 'Juan David Rios', 'birth_date' => '1997-02-25', 'position' => 'Mediocampista'],
                ['fullname' => 'Cristian Trujillo', 'birth_date' => '2000-07-18', 'position' => 'Mediocampista'],
                ['fullname' => 'Anderson Plata', 'birth_date' => '1990-11-14', 'position' => 'Delantero'],
                ['fullname' => 'Jeison Guzman', 'birth_date' => '1997-10-19', 'position' => 'Mediocampista'],
                ['fullname' => 'Diego Herazo', 'birth_date' => '1996-04-02', 'position' => 'Delantero'],
                ['fullname' => 'Alex Castro', 'birth_date' => '1994-03-08', 'position' => 'Mediocampista'],
                ['fullname' => 'Kevin Perez', 'birth_date' => '2002-11-02', 'position' => 'Delantero'],
                ['fullname' => 'Christian Mafla', 'birth_date' => '1993-02-06', 'position' => 'Defensa'],
            ],

            // ------------------------------------------------------------
            // 8. Atlético Bucaramanga
            // ------------------------------------------------------------
            [
                ['fullname' => 'Aldair Quintana', 'birth_date' => '1994-07-11', 'position' => 'Portero'],
                ['fullname' => 'Francisco Rodriguez', 'birth_date' => '1993-09-02', 'position' => 'Delantero'],
                ['fullname' => 'Carlos Henao', 'birth_date' => '1989-03-20', 'position' => 'Defensa'],
                ['fullname' => 'Daniel Cataño', 'birth_date' => '1992-01-08', 'position' => 'Mediocampista'],
                ['fullname' => 'Sherman Cardenas', 'birth_date' => '1989-08-02', 'position' => 'Mediocampista'],
                ['fullname' => 'Jhon Cordoba', 'birth_date' => '1990-05-15', 'position' => 'Delantero'],
                ['fullname' => 'Jorge Palacios', 'birth_date' => '1997-10-09', 'position' => 'Defensa'],
                ['fullname' => 'Cristian Blanco', 'birth_date' => '1992-04-22', 'position' => 'Defensa'],
                ['fullname' => 'Diego Caavez', 'birth_date' => '1999-12-15', 'position' => 'Mediocampista'],
                ['fullname' => 'Mateo Garavito', 'birth_date' => '2003-02-28', 'position' => 'Defensa'],
            ],

            // ------------------------------------------------------------
            // 9. Unión Magdalena
            // ------------------------------------------------------------
            [
                ['fullname' => 'Ramiro Sanchez', 'birth_date' => '1984-02-24', 'position' => 'Portero'],
                ['fullname' => 'Brayan Palacios', 'birth_date' => '1998-11-10', 'position' => 'Delantero'],
                ['fullname' => 'Juan Carlos Jaramillo', 'birth_date' => '1995-03-05', 'position' => 'Mediocampista'],
                ['fullname' => 'Johan Valencia', 'birth_date' => '2000-04-21', 'position' => 'Defensa'],
                ['fullname' => 'Ricardo Marquez', 'birth_date' => '1997-11-09', 'position' => 'Delantero'],
                ['fullname' => 'Javier Calle', 'birth_date' => '1991-04-16', 'position' => 'Mediocampista'],
                ['fullname' => 'Edwin Valencia', 'birth_date' => '2003-02-12', 'position' => 'Defensa'],
                ['fullname' => 'Jose Cuenu', 'birth_date' => '2001-03-03', 'position' => 'Defensa'],
                ['fullname' => 'Yeison Mejia', 'birth_date' => '1998-06-14', 'position' => 'Delantero'],
                ['fullname' => 'Pedro Arturo Valdes', 'birth_date' => '1990-02-02', 'position' => 'Mediocampista'],
            ],

            // ------------------------------------------------------------
            // 10. Deportivo Pereira
            // ------------------------------------------------------------
            [
                ['fullname' => 'Aldair Gutierrez', 'birth_date' => '1997-04-30', 'position' => 'Defensa'],
                ['fullname' => 'Yesus Cabrera', 'birth_date' => '1990-09-22', 'position' => 'Mediocampista'],
                ['fullname' => 'Jhonny Vasquez', 'birth_date' => '1987-01-23', 'position' => 'Mediocampista'],
                ['fullname' => 'Ángelo Rodriguez', 'birth_date' => '1989-04-18', 'position' => 'Delantero'],
                ['fullname' => 'Jimer Fory', 'birth_date' => '2002-09-19', 'position' => 'Defensa'],
                ['fullname' => 'Adrian Balboa', 'birth_date' => '1994-01-19', 'position' => 'Delantero'],
                ['fullname' => 'Samuel Velasquez', 'birth_date' => '2003-07-05', 'position' => 'Delantero'],
                ['fullname' => 'Carlos Ramirez', 'birth_date' => '1996-11-02', 'position' => 'Defensa'],
                ['fullname' => 'Jhoan Arenas', 'birth_date' => '1999-08-24', 'position' => 'Mediocampista'],
                ['fullname' => 'Maicol Medina', 'birth_date' => '2001-06-10', 'position' => 'Mediocampista'],
            ],
        ];

        $teams = Team::all();

        foreach ($teams as $index => $team) {
            foreach ($playersByTeam[$index] as $p) {
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

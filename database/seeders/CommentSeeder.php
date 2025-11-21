<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $comments = [
            'Excelente partido, muy buen nivel.',
            'El arbitraje estuvo correcto.',
            'Gran actuación del delantero.',
            'El equipo necesita mejorar la defensa.',
            'Qué golazo, increíble jugada.',
            'Partido muy cerrado, faltó definición.',
            'Buen debut de los nuevos jugadores.',
            'La afición apoyó durante todo el partido.',
            'El clima afectó un poco el rendimiento.',
            'Muy buena estrategia del director técnico.',
        ];

        $users = User::pluck('id')->toArray();

        foreach ($comments as $c) {
            Comment::create([
                'description' => $c,
                'id_user' => $users[array_rand($users)],
            ]);
        }
    }
}

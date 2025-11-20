<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->toArray();

        for ($i = 1; $i <= 15; $i++) {
            Comment::create([
                'description' => fake()->sentence(),
                'id_user' => fake()->randomElement($users),
            ]);
        }
    }
}

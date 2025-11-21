<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            // TeamSeeder::class,
            // PresidentSeeder::class,
            // PlayerSeeder::class,
            // MatchSeeder::class,
            // GoalSeeder::class,
            // CommentSeeder::class,
        ]);
    }
}

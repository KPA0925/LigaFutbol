<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        $admin = User::Create(
            [
                'name' => 'Administrador',
                'email' => 'administrador@gmail.com',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole($adminRole);
    }
}

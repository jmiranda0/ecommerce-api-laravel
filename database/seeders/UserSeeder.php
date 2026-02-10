<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear usuario Admin (si no existe)
        User::firstOrCreate([
            'email' => 'admin@admin.com',
        ], [
            'name' => 'Admin',
            'password' => Hash::make('password'),
            'role' => UserRole::ADMIN,
        ]);

        // 2. Crear 10 clientes de prueba
        User::factory(10)->create([
            'role' => UserRole::CUSTOMER,
        ]);
    }
}

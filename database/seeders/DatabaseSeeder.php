<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden de dependencias
        $this->call([
            UserSeeder::class,      // Primero: usuarios (no depende de nada)
            CategorySeeder::class,  // Segundo: categorías (no depende de nada)
            ProductSeeder::class,   // Tercero: productos (depende de categorías)
            OrderSeeder::class,     // Cuarto: órdenes (depende de usuarios y productos)
        ]);
    }
}
